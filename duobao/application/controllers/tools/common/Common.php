<?php
/**
 * @author Nick.He (hehaipeng@group-net.cn)
 * @author Kevin.Hu (huwenhua@group-net.cn)
 * @source 2013.03.14
 */
class WTuan_MAPI_Common
{
	const TTL = 3600;
	const FIRST_PAGE_NUM = 1;
	const PAGE_SIZE_DEFAULT = 10;

	const CURL_TIMEOUT = 8;

	const CLIENT_ID_ANDROID = 11;
	const CLIENT_ID_IOS = 12;
	const CLIENT_ID_WTG = 13;
	const CLIENT_ID_MOBILE = 14;

	const AES_MAPI_KEY = ':ec)if#<{*123%I1w4s&3seU'; //WTG

	public static $salt = ';I8fCa*+d['; //Touch,IOS,Android
	public static $uin = null;
	public static $isDecodeData = false;
	public static $publicSalt = null;


	public static function getMapi($url, $params)
	{
		if (!isset($params) || empty($params)) return false;

		if (substr($url, 0, 1) == '/'){
			$url = substr($url, 1);
		}

		return self::getJsonResponse($url, $params);
	}

	public static function getJsonResponse($url, $params)
	{
		//add default params
		$default = array(
			'clientId'  => self::CLIENT_ID_WTG,
			'version'   => '2.0',
			'clientVer' => '2.0',
		);
		$params = array_merge($default, $params);
		//post mapi
		if (($data = self::httpSend($url, self::getClientFlagParams($params))) !== false) {
			if (!empty($data)) {
                //remove retCode in $data['retMsg'] value
                $msg = strstr($data['retMsg'],':') != false ? explode(':',$data['retMsg']) : '';
                $data['retMsg'] = empty($msg) ? $data['retMsg'] : $msg[1];

				if (!isset($data['retCode'])) {
					WTG_Lib_Log::debug('mapi', "MAPI ERROR || retCode not isset || " . $url ." || ".http_build_query($params) . " || ret " . json_encode($data));
					throw new Exception($data['retMsg'], $data['retCode']);
				}
				if ($data['retCode'] != 0) {
                    WTG_Lib_Log::debug('mapi', "MAPI ERROR || retCode " .$data['retCode']. " || " . $url ." || ".http_build_query($params) . " || ret " . json_encode($data));
					throw new Exception($data['retMsg'], $data['retCode']);
				}
				//is decrypt
				if (self::$isDecodeData == true && is_array($data) && !empty($data['retData'])) {
					$data['retData'] = decrypt($data['retData'],self::$publicSalt);
				}
				return $data;
			}
		}
        WTG_Lib_Log::debug('mapi', "MAPI ERROR || httpSend return 0 || " . $url ." || ".http_build_query($params) . " || ret " . json_encode($data));
		throw new Exception('Unkonw exception', 999999);
	}

	/**
	 * 是否需要解密:
	 * 1、微团购的salt const AES_MAPI_KEY = ':ec)if#<{*123%I1w4s&3seU';
	 * 2、非微团购   uin倒着取-19位作为key 连接上 salt 倒着取 -5 方法为：self::getMobileClientKey()
	 * 3、基础接口明码传递时不需要skey,uin，
	 * 4、如果需要加密的接口要注意：skey和cdata平级传递，uin放到cdata中加密
	 */
	public static function getClientFlagParams($params)
	{
		!empty($params['uin']) ? self::$uin = $params['uin'] : '';
		//mustParams 
		$mustParams = array(
			'clientId'         => !empty($params['clientId']) ? $params['clientId'] : self::CLIENT_ID_WTG,
			'version'          => !empty($params['version']) ? $params['version'] : '2.0',
			'clientVer'        => !empty($params['clientVer']) ? $params['clientVer'] : '2.1',
			'skey'             => !empty($params['skey']) ? $params['skey'] : '',
		);
		//创建params 删除明码，用于cdata
		if (!empty($params['clientId'])) unset($params['clientId']);
		if (!empty($params['version'])) unset($params['version']);
		if (!empty($params['clientVer'])) unset($params['clientVer']);
		if (!empty($params['skey'])) unset($params['skey']);

		if ($mustParams['clientId'] == self::CLIENT_ID_WTG) {
			if (!empty($mustParams['skey'])) unset($params['skey']);
			self::$publicSalt = self::AES_MAPI_KEY;
			$aesParams = array();
			$aesParams['cdata'] = encrypt($params,self::$publicSalt);
			self::$isDecodeData = true;
		} else {
			self::$publicSalt = self::getMobileClientKey();
			//如果存在uin和skey就是需要加密的接口。
			if (!empty($mustParams['skey']) && !empty($params['uin'])) {
				$aesParams = array();
				$aesParams['cdata'] = encrypt($params,self::$publicSalt);
				self::$isDecodeData = true;
			} else {
				if (empty($mustParams['skey'])) unset($mustParams['skey']);
				$aesParams = $params;
				self::$isDecodeData = false;
			}
		}
		$params = array_merge($mustParams, $aesParams);
		return $params;

	}

	private static function httpSend($url, $params)
	{
		if (isset($params) && is_array($params)) {
			$params = http_build_query($params); //ADD
		}
		$resp = '';
		$mapiStartTime = microtime(true);
		$repeat = 3;
		$count = 1;
		while ($count <= $repeat) {
			$return = self::CallCURLPOST($url, $params, $resp, array(), self::CURL_TIMEOUT); //ADD
			switch ($return) {
				case  0:
					$result = 'ok';
					break;
				case -1:
					$result = 'contect error';
					break;
				case -2:
					$result = 'responseCode not 200';
					break;
			}
			if ($return === 0) {

				//返回的数据反编码
				if (empty($resp) || ($respJson = json_decode($resp, true)) === false) {
					WTG_Lib_Log::debug('mapi', "MAPI ERROR || CallCURLPOST response exception || url: ".$url." || params: ".$params.' || resp:'.print_r($resp, true).' || respJson: '.print_r($respJson, true) . ' || count: '.$count);
					return false;
				}
				if (empty($respJson)) {
					WTG_Lib_Log::debug('mapi', "MAPI ERROR || CallCURLPOST response exception 2 || url: ".$url." || params: ".$params.' || resp:'.print_r($resp, true).' || respJson: '.print_r($respJson, true) . ' || count: '.$count);
				}
				return $respJson;
			} else {
				WTG_Lib_Log::debug('mapi', ' || ' . $url . ' ' . $params . ' || count=' . $count . ' || ' . $result . ' || ' . $resp);
			}
			$count++;
			usleep(500000);
		}
		WTG_Lib_Log::debug('mapi', "MAPI ERROR || Maximum number of count || url: " . $url ." || params: ".$params.' || count: '.$count);
		return false;
	}


    public static function CallCURLPOST($reqURI, $strHttpParam, &$resp, $server, $timeout = 2)
    {
        $host = empty($server['ip']) ? '' : $server['ip'];
        $port = empty($server['port']) ? '' : $server['port'];

        $ch = curl_init();
        if ($ch === false) {
            $resp = 'curl_init error';
            return -1;
        }

        //若不指定host，$host需要传入完整的请求路径，带http://
        if (empty($host)) {
            $reqURL = $reqURI;
        } else if (empty($port)) {
            $reqURL = 'http://' . $host . $reqURI;
        } else {
            $reqURL = 'http://' . $host . ':' . $port . $reqURI;
        }

        curl_setopt($ch, CURLOPT_URL, $reqURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $strHttpParam);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

        $res = curl_exec($ch);
        $response = curl_getinfo($ch);

        if ($res === false) {
            $resp = curl_error($ch) . ' errno = ' . curl_errno($ch) . ' response: ' . str_replace("\n", "", print_r($response, true));
            curl_close($ch);
            return -1;
        }
        $resp = $res;
        curl_close($ch);
        return 0;
    }


	/**
	 * 取得非微团购产生的key
	 */
	private static function getMobileClientKey()
	{
		$key = '';
		$salt = '';
		//取后19个
		if (strlen(self::$uin) >= 19) {
			$key = substr(self::$uin, -19);
		}
		//判断长度，如果大于5，取后5个字符
		if (strlen(self::$salt) > 5) {
			$salt = substr(self::$salt, -5);
		}
		return $key . $salt;

	}
}
