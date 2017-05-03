<?php

class XMLArray
{
	/**
	 * 将简单XML字符串转换成复合数组
	 * <root>
	 *   <k1>v1</k1>
	 *   <k2>v2</k3>
	 *   <k3>
	 *     <aaa>bbb</aaa>
	 *   </k3>
	 * </root>
	 * @param string $xmlStr
	 * 去掉所有的属性字段
	 * 空节点`<x />`或`<x></x>` 或者content为空白字符的节点`<x>(&#x20;|&#x9;|&#xD;|&#xA;)+</x>`, 往上提一层
	 * !!不支持comment
	 *
	 * @return array ('k1'=>'v1', 'k2'=>'v2', 'k3'=>array('aaa'=>'bbb'))
	 */
	public static function SimpleXMLToKVArray($xmlStr)
	{
		//return @json_decode(json_encode((array) simplexml_load_string($xmlStr,
		//		"SimpleXMLElement", (($hasCDATA)? LIBXML_NOCDATA : 0))), 1);

		$simpleXml = simplexml_load_string($xmlStr, null, LIBXML_COMPACT|LIBXML_NOCDATA);
		if (!$simpleXml) return array();
		$jsonStr = json_encode($simpleXml);
		$jsonStr = preg_replace('/"@attributes":{[^}]+},?/', '', $jsonStr);//去掉属性
		$jsonStr = str_replace('{}', '""', $jsonStr); //特殊处理空节点: 转为空字符串
		$jsonStr = preg_replace('/{"0":("\s+")}/', '$1', $jsonStr); //特殊处理{0=>xx}节点: 往上提一层
		return json_decode($jsonStr, true); //true表示转换成array
	}

	public static function XMLToKVArray($simpleXml, $hasAttr = false)
	{
		if (is_string($simpleXml))
		{
			$simpleXml = simplexml_load_string($simpleXml, null, LIBXML_COMPACT|LIBXML_NOCDATA);
		}
		if (!$simpleXml) return array();

		$result = array();

		if ($hasAttr)
		{
			/* @var $v SimpleXMLElement */
			foreach ($simpleXml->attributes() as $k => $v)
			{
				$result['@attributes'][$k] = (string)$v;
			}
		}

		/* @var $child_node SimpleXMLElement */
		foreach ($simpleXml->children() as $child_name => $child_node)
		{
			//如果无attributes无children, 省掉简单节点的最后一次递归
			if ($child_node->attributes() || $child_node->children()) //wrapp with `count()`
			{
				$child_result = self::XMLToKVArray($child_node, $hasAttr);
			}
			else
			{
				$child_result = (string)$child_node;
			}

			//将$child_result存到$k下面, 如果有已存在, 则向下扩展成数组 //$child_node->getName()
			if (!isset($result[$child_name]))
			{
				$result[$child_name] = $child_result;
			}
			else
			{
				if (!is_array($result[$child_name])
				 || isset($result[$child_name]['@attributes'])) //如果目前只有一个元素, 则先转数组结构
				{
					$result[$child_name] = array(0 => $result[$child_name]);
				}

				$result[$child_name][] = $child_result; //追加一个结果
			}
		}

		if (empty($result)) //无attributes无children的节点, 直接转成string形式
		{
			$result = (string)$simpleXml;
		}
		else if (count($result) == 1 && isset($result['@attributes'])) //有attributes无children的节点, $result[0]存放string
		{
			$result[0] = (string)$simpleXml;
		}
		//else 有children, 返回$result

		return $result;
	}

	/**
	 * 将一维数组转换成简单XML
	 * @return `<root><k1>v1</k1><k2>v2</k2></root>`
	 */
	public static function KVArrayToXML($sRootName, $kvArray, $formatOutput = false, $encoding = 'UTF-8')
	{
		$simpleXml = new SimpleXMLElement("<$sRootName />");

		self::addArrayToSimpleXML($simpleXml, $kvArray);

		if (!$formatOutput)
		{
			return $simpleXml->asXML();
		}
		else
		{
			$dom = dom_import_simplexml($simpleXml)->ownerDocument;
			$dom->formatOutput = true;
			$dom->encoding = $encoding;
			return $dom->saveXML();
		}
	}

	protected static function isValidTagName($tag)
	{
		$pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
		return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
	}

	protected static function addArrayToSimpleXML(&$simpleXml, $kvArray)
	{
		foreach ($kvArray as $k => $v)
		{
			if (!self::isValidTagName($k))
			{
				continue;
			}

			self::addNode($simpleXml, $k, $v);
		}
	}

    /**
     * @param SimpleXMLElement $simpleXml
     * @param string $k
     * @param array $v
     */
    protected static function addNode(&$simpleXml, $k, $v)
	{
		if (is_array($v))
		{
			$indexedArr = array();
			$associativeArr = array();
			foreach ($v as $k1 => $v1)
			{
				if (is_int($k1)) //!isValidTagName()
				{
					$indexedArr[] = $v1;
				}
				else
				{
					$associativeArr[$k1] = $v1;
				}
			}

			//indexed array => 平级并列的key
			if (!empty($indexedArr))
			{
				//`<$k>proc($indexedArr[0])</$k>
				// <$k>proc($indexedArr[1])</$k>`
				foreach ($indexedArr as $v1)
				{
					self::addNode($simpleXml, $k, $v1);
				}
			}

			//associative array => array的内容挂在key子节点内
			if (!empty($associativeArr))
			{
				//`<$k>
				//     <$k1>proc($v1)</$k1>
				//     <$k2>proc($v2)</$k2>
				// </$k>`
				$childNode = $simpleXml->addChild($k);
				self::addArrayToSimpleXML($childNode, $associativeArr);
			}
		}
		else //$v是string, 增加`<$k>$v</$k>`
		{
			$simpleXml->addChild($k, ($v)); //htmlspecialchars
		}
	}

}
