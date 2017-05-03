<?php
/**
 * @DESCRIPTION
 * AES 加密算法
 * 
 * @MODIFY
 * Mar 30, 2013 6:45:49 PM : create file
 * 
 * @author Kevin.Hu, Inc. <huwenhua@group-net.cn>
 * @version v2.0 
 */
class System_Lib_AES
{
	const AES_DEFAULT_KEY = '^<cF3}564i&^j3}1~jUasf9j';
	
	public static function encrypt($data, $key = self::AES_DEFAULT_KEY)
	{
		$bit = 192;
		set_include_path(PROJECT_PATH . 'System/Lib/phpseclib0.3.1');
		require_once 'Crypt/AES.php';
		$aes = new Crypt_AES();
		$aes->setKeyLength($bit);
		$aes->setKey($key);
		$value = $aes->encrypt($data);
		return $value;
	}
	
	public static function decrypt($data, $key = self::AES_DEFAULT_KEY)
	{
		$bit = 192;
		set_include_path(PROJECT_PATH . 'System/Lib/phpseclib0.3.1');
		require_once 'Crypt/AES.php';
		$aes = new Crypt_AES();
		$aes->setKeyLength($bit);
		$aes->setKey($key);
		$value = $aes->decrypt($data);
		return $value;
	}
}
?>