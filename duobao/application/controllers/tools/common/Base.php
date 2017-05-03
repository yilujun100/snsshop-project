<?php

/**
 * @author Nick.He (hehaipeng@group-net.cn)
 * @date 2013.07.10
 */

class WTG_BModel_Base extends WTuan_MAPI_Common
{
	public static function parseParams($params)
	{
		return array_merge(array(
			'clientId'      => self::CLIENT_ID_WTG,
			'version'       => '2.0',
			'clientVer'     => '2.1',
		), $params);
	}

	public static function getDataByMapi($url, $params)
	{
		return self::getMapi($url, self::parseParams($params));
	}

}