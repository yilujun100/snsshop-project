<?php

require_once dirname(__FILE__) . '/ParameterHandler.class.php';

class RSAParameterHandler extends ParameterHandler
{
	/**
	 * RSA私钥
	 * @var mixed
	 * @see openssl_pkey_get_private()
	 */
	protected $rsaPrivateKey;

	/**
	 * RSA公钥
	 * @var mixed
	 * @see openssl_pkey_get_public()
	 */
	protected $rsaPublicKey;

	public function __construct()
	{
		parent::__construct();
	}

	protected function init()
	{
		parent::init();

		$this->rsaPrivateKey = '';
		$this->rsaPublicKey = '';
	}

	/**
	 * 设置RSA签名的key
	 * @see ParameterHandler::setKey()
	 */
	public function setKey($priKey, $pubKey = '')
	{
		$this->rsaPrivateKey = $priKey;
		$this->rsaPublicKey = $pubKey;
	}

	/**
	 * 计算RSA签名
	 * @see ParameterHandler::calculateSign()
	 */
	protected function calculateSign()
	{
		$sSignContent = $this->_buildSignContent();

		//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
		$res = openssl_pkey_get_private($this->rsaPrivateKey);
		//调用openssl内置签名方法，生成签名$sign
		openssl_sign($sSignContent, $sSign, $res);
		//释放资源
		openssl_free_key($res);
		//base64编码
		$sSign = base64_encode($sSign);

		$this->sDebugInfo = "[$sSignContent] => sign:$sSign";
		//echo htmlspecialchars($this->sDebugInfo);//exit();

		return $sSign;
	}

	/**
	 * 验证当前参数数组的签名是否有效.
	 * @return bool
	 */
	public function verifySign()
	{
		$sSignContent = $this->_buildSignContent();
		$sSign = $this->get('sign');

		//转换为openssl格式密钥
		$res = openssl_pkey_get_public($this->rsaPublicKey);
		//调用openssl内置方法验签
		$result = openssl_verify($sSignContent, base64_decode($sSign), $res);
		//释放资源
		openssl_free_key($res);

		$this->sDebugInfo = "[$sSignContent] sign[$sSign] => result:$result";
		//echo htmlspecialchars($this->sDebugInfo);//exit();

		return ($result === 1);
	}

	/**
	 * 用商户私钥对支付宝数据进行解密
	 * @see ParameterHandler::decrypt()
	 */
	protected function decrypt($content)
	{
		//密文经过base64解码
		$content = base64_decode($content);

		//转换为openssl密钥，必须是没有经过pkcs8转换的私钥
		$res = openssl_pkey_get_private($this->rsaPrivateKey);

		//声明明文字符串变量
		$result = '';
		//循环按照128位解密
		for ($i = 0; $i < strlen($content)/128; $i++)
		{
			$data = substr($content, $i * 128, 128);
			//拆分开长度为128的字符串片段通过私钥进行解密，返回$decrypt解析后的明文
			openssl_private_decrypt($data, $decrypt, $res);
			//明文片段拼接
			$result .= $decrypt;
		}

		//释放资源
		openssl_free_key($res);

		//返回明文
		return $result;
	}

}
