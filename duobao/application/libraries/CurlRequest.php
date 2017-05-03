<?php

class Lib_CurlRequest
{
	const SOCKETTYPE_TCP = 1;
	const SOCKETTYPE_UDP = 2;

	const TYPE_STRING = 1;
	const TYPE_BINARY = 2;

	const DEFAULT_CURL_TIMEOUT = 2;

	/**
	 * CURL请求封装函数
	 * 若不指定host，这需要传入完整的请求路径，带http://
	 *
	 * @param string $reqURI
	 * @param array $arrayHttpParam
	 * @param string $resp
	 * @param string $errMsg
	 * @param array $server
	 * @param int $timeout
	 * @param array $extParams
	 * @return int 成功 0 没有返回 -1 返回http code不为200 -2
	 */
	public static function CallCURL($reqURI, $arrayHttpParam, &$resp, $server, $timeout = self::DEFAULT_CURL_TIMEOUT)
	{
		$host = empty($server['ip']) ? '' : $server['ip'];
		$port = empty($server['port']) ? '' : $server['port'];

		$ch = curl_init();

		foreach ($arrayHttpParam as $key => $value) {
			curl_setopt($ch, $key, $value);
		}

		//若不指定host，$host需要传入完整的请求路径，带http://
		if (empty($host)) {
			$reqURL = $reqURI;
		} else if (empty($port)) {
			$reqURL = 'http://' . $host . $reqURI;
		} else {
			$reqURL = 'http://' . $host . ':' . $port . $reqURI;
		}

        if(strstr($reqURI,'https') !== false){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        }


		curl_setopt($ch, CURLOPT_URL, $reqURL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

		$res = curl_exec($ch);
		$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);
		if ($res == NULL) {
			return -1;
		} else if ($responseCode != "200") {
			return -2;
		}
		$resp = $res;
		return 0;
	}

	public static function CallCURLPOST($reqURI, $strHttpParam, &$resp, $server, $timeout = self::DEFAULT_CURL_TIMEOUT)
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
	
	public static function callUrlPostWithReutn($reqURI, $strHttpParam, &$resp, $server, $timeout = self::DEFAULT_CURL_TIMEOUT){
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

    public static function getExcuteTime($startTime)
    {
        return number_format(microtime(true) - $startTime, 3, '.', '');
    }

}
