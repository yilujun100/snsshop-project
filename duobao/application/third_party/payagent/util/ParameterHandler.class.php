<?php

require_once dirname(__FILE__) . '/XMLArray.php';

class ParameterHandler
{
	/** 固定参与签名的key数组(保证有序). 若为空,则所有参数都参与签名 */
	protected $arrFixSignKeys;

	/** 排除参与签名的key数组. 默认为array('sign') */
	protected $arrExcludeSignKeys;

	/** 空值是否参与签名. 默认为false,即空值不参与签名 */
	protected $isSignWithEmptyValue;

	/** 用于签名的私钥, 使用MD5方式签名 */
	protected $sDigestKey;

	/** 参数数组 */
	protected $arrParameters;

	/** 调试错误信息 */
	protected $sDebugInfo;

	public function __construct()
	{
		$this->init();
	}

	protected function init()
	{
		$this->arrFixSignKeys = array();
		$this->arrExcludeSignKeys = array('sign');
		$this->isSignWithEmptyValue = false;
		$this->sDigestKey = '';
		$this->arrParameters = array();
		$this->sDebugInfo = '';
	}

	/**
	 * 初始化生成签名的规则.
	 * 同时初始化$this
	 * @param array $arrFixSignKeys 若非空, 则使用该数组对应的参数签名; 若为空, 则使用所有参数签名.
	 * @param array $arrExcludeSignKeys 指定不参加签名的参数, array('sign')
	 * @param bool $isSignWithEmptyValue 空值是否参与签名, false
	 */
	public function initCreateSign($arrFixSignKeys, $arrExcludeSignKeys, $isSignWithEmptyValue)
	{
		$this->init();

		$this->arrFixSignKeys = $arrFixSignKeys;
		$this->arrExcludeSignKeys = $arrExcludeSignKeys;
		$this->isSignWithEmptyValue = $isSignWithEmptyValue;
	}

	protected function _getSignKeysArray()
	{
		if (!empty($this->arrFixSignKeys))
		{
			return $this->arrFixSignKeys;
		}
		else
		{
			//ksort($this->arrParameters);
			//return array_keys($this->arrParameters);
			$arrSignKeys = array_keys($this->arrParameters);
			sort($arrSignKeys);
			return $arrSignKeys; //计算签名的逻辑不会影响Params数组的顺序
		}
	}

	protected function _isNeedSign($key, $value)
	{
		if (in_array($key, $this->arrExcludeSignKeys) //key配置为不参加签名
		 || ($this->isSignWithEmptyValue === false && $value === "")) //设置了空值不参与签名,但$value为空
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * 若$arrFixSignKeys非空, 则根据该数组的key创建sign, 否则所有参数都参加签名.
	 * $arrExcludeSignKeys指定计算签名需要排除的key
	 * $isSignWithEmptyValue确定空值是否参与签名
	 * @return string "k1=v1&k2=v2..."
	 */
	protected function _buildSignContent()
	{
		$sSignContent = '';

		$arrSignKeys = $this->_getSignKeysArray();
		foreach ($arrSignKeys as $key)
		{
			$value = (isset($this->arrParameters[$key])? strval($this->arrParameters[$key]) : '');
			if ($this->_isNeedSign($key, $value))
			{
				$sSignContent .= "$key=$value&"; //这里使用原始值拼接, 不能做URL encode
			}
		}
		if (!empty($sSignContent)) //去掉最后一个'&'字符
		{
			$sSignContent = substr($sSignContent, 0, -1);
		}

		return $sSignContent;
	}

	/**
	 * 初始化MD5签名key
	 * @param string $sKey MD5签名key
	 * @param string $sPrefix 计算签名时拼接的前缀, {tenpay:"&key=" alipay:""}
	 */
	public function setKey($sKey, $sPrefix="")
	{
		$this->sDigestKey = $sPrefix . $sKey;
	}

	/**
	 * 计算md5签名:
	 */
	protected function calculateSign()
	{
		$sSignContent = $this->_buildSignContent();
		$sSignContent .= $this->sDigestKey;
		$sSign = strtolower(md5($sSignContent));

		$this->sDebugInfo = "[$sSignContent] => sign:$sSign";
		//echo htmlspecialchars($this->sDebugInfo);//exit();

		return $sSign;
	}

	/**
	 * @return string 请求URL中的QueryString部分
	 */
	public function buildUrlQuery($ksortParams = false, $signToUpper = false)
	{
		if ($ksortParams)
		{
			ksort($this->arrParameters);
		}

		$sMySign = $this->calculateSign();
		if ($signToUpper) { $sMySign = strtoupper($sMySign); }
		$this->set('sign', $sMySign);

		//Generate URL-encoded query string
		return str_replace('+', '%20', http_build_query($this->arrParameters));
	}

	/**
	 * 验证当前参数数组的签名是否有效.
	 * @return bool
	 */
	public function verifySign()
	{
		$sMySign = $this->calculateSign();
		$sign = strtolower($this->get('sign'));
		$this->sDebugInfo .= " payAgentSign:$sign";

		return ($sMySign == $sign);
	}

	/**
	 * 返回生成签名的算法调试信息
	 * @return string
	 */
	public function getDebugInfo()
	{
		return $this->sDebugInfo;
	}

	/**
	 * 设置参数值
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function set($key, $value)
	{
		$this->arrParameters[$key] = $value;
	}

	/**
	 * 设置参数值, $key=<$sRootName>array2xml($arrChildren)</$sRootName>
	 * @param mixed $key
	 * @param string $sRootName xml根节点名
	 * @param array $arrChildren xml子节点键值对
	 */
	public function setArray2Xml($key, $sRootName, $arrChildren)
	{
		$this->arrParameters[$key] = XMLArray::KVArrayToXML($sRootName, $arrChildren);
	}

	/**
	 * 批量设置参数
	 * @param array $arrParams
	 * @param bool $isMerge false: 替换原参数数组; true: 与原参数数组合并
	 */
	public function setAll($arrParams, $isMerge=false)
	{
		if ($isMerge)
		{
			$this->arrParameters = array_merge($this->arrParameters, $arrParams);
		}
		else
		{
			$this->arrParameters = $arrParams;
		}
	}

	protected function decrypt($content)
	{
		return $content;
	}

	/**
	 * 将参数数组中$key对应的value解密
	 * @param mixed $key
	 */
	public function decryptParam($key)
	{
		$this->arrParameters[$key] = $this->decrypt($this->get($key));
	}

	/**
	 * 根据key取出参数值
	 * @param mixed $key
	 * @param number $type {"bool","int","float","string","array","object"}
	 * @param mixed $defval
	 * @param string $delete
	 * @return mixed 参数值
	 */
	public function get($key, $type="", $defval=null, $delete=false)
	{
		if (isset($this->arrParameters[$key]))
		{
			$defval = $this->arrParameters[$key];
			if (!empty($type))
			{
				settype($defval, $type);
			}
			if ($delete)
			{
				unset($this->arrParameters[$key]);
			}
		}
		return $defval;
	}

	public function getXml2Array($key)
	{
		return XMLArray::SimpleXMLToKVArray($this->get($key));
	}

	/**
	 * @return array 参数数组
	 */
	public function getAll()
	{
		return $this->arrParameters;
	}

}
