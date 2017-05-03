<?php

/**
 * @author: jacky
 * Date: 12-9-19
 * Time: 下午4:37
 */
class WTG_Lib_Log
{
    /**
     * 记录日志
     */
    public static function saveWinnerContact($lotId, $logs, $showTime = true)
    {
        if (!defined('LOG_ENABLE') || LOG_ENABLE == false) {
            return true;
        }
        $filename = dirname(dirname(__FILE__)).'/Public/debug/winner_lot_'.$lotId.'.log';
        @file_put_contents($filename, $logs.($showTime ? ' ['.date('Y-m-d H:i:s').'] ' : '')."\n", FILE_APPEND);
    }

	/**
	 * 记录日志
	 */
	public static function debug($type, $logs, $showTime = true)
	{
		if (!defined('LOG_ENABLE') || LOG_ENABLE == false) {
			return true;
		}
		$filename = dirname(dirname(__FILE__)).'/logs/'.$type.'_'.date('Y-m-d').'.log';
		@file_put_contents($filename, ($showTime ? '['.date('Y-m-d H:i:s').'] ' : '').$logs."\n", FILE_APPEND);
	}

	public static function state($S, $A, $userId, $dealId)
	{
		$en = System_Lib_App::getCookie("city", System_Lib_Request::TYPE_STRING);
		$cityId = 0;
		if (!empty($en)) {
			$city = WTG_BModel_Region::getCityByEnName($en);
			$cityId = $city->regionId;
		}
		$log = array(
			'S' => $S,
			'A' => $A,
			'userId' => $userId,
			'cityId' => $cityId,
			'dealId' => $dealId,
			'ip' => System_Lib_App::app()->request()->getUserIp(),
			'time' => date('Y-m-d H:i:s'),
		);
		$filename = './logs/state_'.date('Y-m-d').'.log';
		@file_put_contents($filename, join(',',$log)."\n", FILE_APPEND);
	}

	public static function sql($sqls)
	{
		if (!defined('LOG_ENABLE') || LOG_ENABLE == false) {
			return true;
		}
		$filename = './debug/sql_'.date('Y-m-d').'.log';
		$logs = "+----------------------\n";
		$logs.= "url: ".$_SERVER['REQUEST_URI']."\n";
		$logs.= "+----------------------\n";
		foreach ($sqls as $item) {
			$a = explode('?', $item['sql']);
			$sql = '';
			for ($i=0;$i<count($a);$i++) {
				$sql .= $a[$i];
				if (!empty($item['values'][$i])) {
					$sql .= $item['values'][$i];
				}
			}
			list($sec, $micro) = explode('.', $item['time']);
			$time = date('Y-m-d H:i:s', $sec).'.'.substr($micro, 0, 3);
			$logs .= "{$time} || {$item['sec']} || {$sql}\n";
		}
		$logs .= "\n";
		if (!empty($_GET['debug'])) {
			echo "\n\n<pre>\n".$logs."</pre>\n";
		}
		@file_put_contents($filename, $logs, FILE_APPEND);
	}
}
