<?php

class Lib_SysLog
{
    //日志相关配置
    const NETWORKLOG_HEAD_LEN = 13;
    const NETWORKLOG_VER = 1;
    const NETWORKLOG_CMD = 100;
    const NETWORKLOG_ADDRESS = "10.97.39.10"; //10.208.140.235
    const NETWORKLOG_PORT = 13454;

    //log level
    const NETWORKLOG_LOG_EMERG = 0;  /* system is unusable               */
    const NETWORKLOG_LOG_ALERT = 1;  /* action must be taken immediately */
    const NETWORKLOG_LOG_CRIT = 2;   /* critical conditions              *///关键点发生异常(CallServer超时，TTC超时)
    const NETWORKLOG_LOG_ERROR = 3;  /* error conditions                 *///错误日志(前台参数验证错误，调用Server返回错误)
    const NETWORKLOG_LOG_WARNING = 4;/* warning conditions               */
    const NETWORKLOG_LOG_NOTICE = 5; /* normal but significant condition *///统计相关日志
    const NETWORKLOG_LOG_INFO = 6;   /* informational                    *///流水日志
    const NETWORKLOG_LOG_DEBUG = 7;  /* debug-level messages             */
    const NETWORKLOG_LOG_TRACE = 8;  /* trace-level messages             */
    //应用id
    const APP_TUAN_ID = 10;
    const APP_YZ_ID = 20;
    const APP_EX_ID = 30;
    const APP_OPENCM_ID = 40;
    const GAEA_ID = 50;
    const OPENCM_COUPON_ID = 70;
    const APP_MAPI_ID = 80;
    const APP_DEFAULT_ID = 90;
    const APP_DBTOOLS_ID = 100;
    const APP_PASSPORT_ID = 110;
    const APP_GPQQ_ID = 120;
    const APP_GAOPENG_ID = 130;
    const APP_MAPI2_ID = 140;
    const APP_GATEWAY_ID = 150;
    const APP_WKD_ID = 160;

    //统计来源
    const NETWORKLOG_LOG_SRC_DEFAULT = 0;


    public static function NetworkLog($appid, $level, $content, $src = self::NETWORKLOG_LOG_SRC_DEFAULT)
    {
        $errMsg = '';
        $contentlen = strlen($content) + 1;//var_dump($content);
        $pkglen = self::NETWORKLOG_HEAD_LEN + $contentlen;

        $buffer = pack("n6Ca$contentlen", $pkglen, self::NETWORKLOG_VER, self::NETWORKLOG_CMD, $src, 0, $appid, $level, $content);
        self::UdpSend($buffer, $pkglen, $errMsg, self::NETWORKLOG_ADDRESS, self::NETWORKLOG_PORT);

        return true;
    }

    private static function UdpSend($strSend, $iSendLen, &$sErrMsg, $strAddress, $iPort, $iTimeout = 1)
    {
        $errno = 0;
        $errstr = "";
        $fp = fsockopen('tcp://' . $strAddress, $iPort, $errno, $errstr, $iTimeout);   //@TODO 第二个参数不是port吗？

        if (!$fp)
        {
            $sErrMsg = "ERROR: $errno - $errstr";
            return false;
        }

        stream_set_timeout($fp, $iTimeout);
        $ret = fwrite($fp, $strSend, $iSendLen);
        if ($ret != $iSendLen)
        {
            $sErrMsg = "fwrite failed. ret:[$ret]";
            if (isset($stream_info['timed_out']))
            {
                $sErrMsg .= ' socket_timed_out';
            }
            return false;
        }

        fclose($fp);

        return true;
    }

    public static function getAppLogId()
    {
        $appLogId = System_Lib_App::app()->getConfig('appLogId');
        if (is_null($appLogId))
        {
            $appLogId = self::APP_DEFAULT_ID;
        }

        return $appLogId;
    }


    public static function formatTrace($trace)
    {
        $result = array();
        $traceline = '#%s %s(%s): %s(%s)';
        $key = 0;
        foreach ($trace as $key => $stackPoint)
        {

            if (isset($stackPoint['args']))
            {
                foreach ($stackPoint['args'] as &$arg)
                {
                    $arg = is_scalar($arg) ? var_export($arg, true) : (is_object($arg) ? get_class($arg) : gettype($arg));
                }
            }
            else
            {
                $stackPoint['args'] = array();
            }
            unset($arg);
            $fn = isset($stackPoint['class']) ? "{$stackPoint['class']}{$stackPoint['type']}{$stackPoint['function']}" : $stackPoint['function'];
            $result[] = sprintf(
                $traceline,
                $key,
                isset($stackPoint['file']) ? $stackPoint['file'] : '',
                isset($stackPoint['line']) ? $stackPoint['line'] : '',
                $fn,
                implode(', ', $stackPoint['args'])
            );
        }

        $result[] = '#' . ++$key . ' {main}';
        return $result;
    }

}

