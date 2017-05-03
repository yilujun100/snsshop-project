#!/usr/local/php/bin/php
<?php
error_reporting(-1);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

function SimpleXMLToArray($xmlStr)
{
	$simpleXml = simplexml_load_string($xmlStr, null, LIBXML_COMPACT|LIBXML_NOCDATA);
	$jsonStr = json_encode($simpleXml);
	$jsonStr = preg_replace('/"@attributes":{[^}]+},?/', '', $jsonStr);//去掉属性
	$jsonStr = str_replace('{}', '""', $jsonStr); //特殊处理空节点: 转为空字符串
	$jsonStr = preg_replace('/{"0":("\s+")}/', '$1', $jsonStr); //特殊处理{0=>xx}节点: 往上提一层
	return json_decode($jsonStr, true); //true表示转换成array
}

/**
 * @param SimpleXMLElement $simpleXml
 */
function SimpleXMLToArray_objvars($simpleXml)
{
	if (is_string($simpleXml))
	{
		$simpleXml = simplexml_load_string($simpleXml, null, LIBXML_COMPACT|LIBXML_NOCDATA);
	}

	$result = array();

	foreach ($simpleXml->attributes() as $k => $v)
	{
		$result['@attributes'][$k] = $v;
	}

	foreach (get_object_vars($simpleXml) as $child_name => $child_node) //$simpleXml->children()
	{
		//$child_node有3种情况"string"|xmlObject|array( ("string"|xmlObject)+ )
		//将其统一成 `array( ("string"|xmlObject)+ )` 形式
		$arrElements = (is_array($child_node)? $child_node : array(0 => $child_node));

		$arrResults = array();
		foreach ($arrElements as $k => $element)
		{
			if ($element instanceof SimpleXMLElement)
			{
				$arrResults[$k] = SimpleXMLToArray_objvars($element);
			}
			else
			{
				$arrResults[$k] = (string)$element;
			}
		}

		//将result结构还原: `array(0 => $result)` => $result
		if (count($arrResults) == 1 && isset($arrResults[0])) //当且仅当 只有[0]元素时
		{
			$child_result = $arrResults[0];
		}
		else
		{
			$child_result = $arrResults;
		}

		$result[$child_name] = $child_result; //$child_node->getName()
	}

	if (empty($result))
	{
		$result = (string)$simpleXml; //无attributes无children的节点 直接转成string形式
	}
	else if (count($result) == 1 && isset($result['@attributes']))
	{
		$result[0] = (string)$simpleXml; //其他情况将 [0]存放string形式
	}

	return $result;
}

function SimpleXMLToArray_formal($simpleXml)
{
	if (is_string($simpleXml))
	{
		$simpleXml = simplexml_load_string($simpleXml, null, LIBXML_COMPACT|LIBXML_NOCDATA);
	}

	$result = array();

	/* @var $v SimpleXMLElement */
	foreach ($simpleXml->attributes() as $k => $v)
	{
		$result['@attributes'][$k] = (string)$v;
	}

	/* @var $child_node SimpleXMLElement */
	foreach ($simpleXml->children() as $child_name => $child_node)
	{
		//如果无attributes无children, 省掉简单节点的最后一次递归
		if ($child_node->attributes() || $child_node->children()) //wrapp with `count()`
		{
			$child_result = SimpleXMLToArray_formal($child_node);
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

function isValidTagName($tag)
{
	$pattern = '/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i';
	return preg_match($pattern, $tag, $matches) && $matches[0] == $tag;
}



$xml = '<root>
			<x />
			<y></y>
			<z> &#x9; </z>
			<k2> v2 </k2>
			<arr count="2" >
				<a attr="1" >1</a>
				<a attr="2" >2</a>
			</arr>
		</root>';

$xml = '<root xx="yy" >
			zz
		</root>';

$xml = '<root>
			<empty></empty>
			<m><![CDATA[]]></m>
			<n><![CDATA[  ]]></n>
			<a att="a" ></a>
			<b att="b" >  </b>
			<x xx="b" >xx</x>
			<b><![CDATA[  ]]></b>
			<c>xx</c>
			<c>yy</c>
			<!-- 解析不支持comment -->
			<comment>yy</comment>
			<d><![CDATA[xx]]></d>
		</root>';

// $xml = '<root>
// 	     <k1>v1</k1>
// 	     <k2>v2</k2>
// 	     <k3>
// 	       <aaa>bbb</aaa>
// 	     </k3>
// 	   </root>';

//$xml = '<root />';
//$xml = '<root></root>';
//$xml = '<root> </root>';
//$xml = '<root>content</root>';
//$xml = '<root><![CDATA[ ]]></root>';
//$xml = '<root><![CDATA[content]]></root>';

//$xml = '<root><a xx="yy" /></root>';
//$xml = '<root><a /></root>';
//$xml = '<root><a xx="11" >y</a><a xx="22" >x</a></root>';
//$xml = '<root><a>xx</a></root>';

// $simpleXml = simplexml_load_string($xml);
// echo "\r\n###simplexml_load_string: "; var_dump($simpleXml); var_dump($simpleXml->asXML());
// echo "\r\n###tag name: "; var_dump($simpleXml->getName());
// echo "\r\n###tostring: "; var_dump((string)$simpleXml);
// echo "\r\n###count: "; var_dump(count($simpleXml));
// echo "\r\n###attributes: "; var_dump($simpleXml->attributes()); var_dump($simpleXml->attributes()->asXML());
// echo "\r\n###children: "; var_dump($simpleXml->children()); var_dump($simpleXml->children()->asXML());
// exit();

//$result = simplexml_load_string($xml);
// $result = simplexml_load_string($xml, null, LIBXML_NOCDATA);
// echo "\r\n obj:\r\n";
// var_export($result);
// var_dump($result);

// echo "\r\n array:\r\n";
// $result = (array)$result;
// var_dump($result);

// echo "\r\n json:\r\n";
// $result = json_encode($result);
// var_dump($result);

// echo "\r\n json_decode:\r\n";
// $result = json_decode($result, true);
// var_dump($result);

var_dump(simplexml_load_string($xml, null, LIBXML_COMPACT|LIBXML_NOCDATA));

echo "\r\n SimpleXMLToArray() result:\r\n";
var_dump(json_encode(SimpleXMLToArray($xml)));

echo "\r\n SimpleXMLToArray_objvars() result:\r\n";
var_dump(json_encode(SimpleXMLToArray_objvars($xml)));

echo "\r\n SimpleXMLToArray_formal() result:\r\n";
var_dump(json_encode(SimpleXMLToArray_formal($xml)));


echo "\r\n bc result:\r\n";
var_dump(json_encode(simplexml_to_array(simplexml_load_string($xml))));



function simplexml_to_array($xml)
{
// 	static $N = 0;

// 	$x = $N++;
// 	echo "Step ======================".($x)."\r\n";
// 	var_dump($xml);

	if (get_class($xml) == 'SimpleXMLElement')
	{
		$attributes = $xml->attributes();
		foreach ($attributes as $k => $v)
		{
			if ($v)
			{
				$a[$k] = ( string )$v;
			}
		}
		$x   = $xml;
		$xml = get_object_vars($xml);
	}
	if (is_array($xml))
	{
// 		var_dump($xml);
// 		echo "END array --- Step ======================".($x)."\r\n";

		if (count($xml) == 0)
		{
			//echo "EMPTY:"; var_dump($x, ( string )$x); echo "\r\n";
			return ( string )$x; // for CDATA
		}
		foreach ($xml as $key => $value)
		{
			$r[$key] = simplexml_to_array($value);
		}
		if (isset ($a))
		{
			$r['@attributes'] = $a; // Attributes
		}

		return $r;
	}

// 	echo "END --- Step ======================".($x)."\r\n";
	return ( string )$xml;
}



function KeyValueArrayToXML($sRootName, $kvArray, $formatOutput = false, $encoding = 'UTF-8')
{
	$simpleXml = new SimpleXMLElement("<$sRootName />");

	AddArrayToSimpleXML($simpleXml, $kvArray);

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

function AddArrayToSimpleXML(&$simpleXml, $kvArray)
{
	foreach ($kvArray as $k => $v)
	{
		if (is_array($v))
		{
			$childNode = $simpleXml->addChild($k);
			AddArrayToSimpleXML($childNode, $v);
		}
		else
		{
			$simpleXml->addChild($k, ($v)); //htmlspecialchars
		}
	}
}


$arr = array(
		'a' => '1',
		'b' => '2',
		'c' => array('a' => '1<x>&%;',
					'b' => '哈哈',
					//'c' => iconv("UTF-8", "GBK//ignore", '哈哈'),
					),
);

var_dump(KeyValueArrayToXML('root', $arr));
var_dump(KeyValueArrayToXML('root', $arr, true, 'GB2312'));

