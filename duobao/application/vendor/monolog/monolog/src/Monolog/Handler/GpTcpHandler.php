<?php

namespace Monolog\Handler;

use Monolog\Logger;


class GpTcpHandler extends SocketHandler
{
    //log level
    const NETWORKLOG_LOG_EMERG = 0;
    const NETWORKLOG_LOG_ALERT = 1;
    const NETWORKLOG_LOG_CRIT = 2;
    const NETWORKLOG_LOG_ERROR = 3;
    const NETWORKLOG_LOG_WARNING = 4;
    const NETWORKLOG_LOG_NOTICE = 5;
    const NETWORKLOG_LOG_INFO = 6;
    const NETWORKLOG_LOG_DEBUG = 7;
    const NETWORKLOG_LOG_TRACE = 8;

    // level map
    protected static $level_map = array(
        Logger::DEBUG => self::NETWORKLOG_LOG_DEBUG,
        Logger::INFO => self::NETWORKLOG_LOG_INFO,
        Logger::NOTICE => self::NETWORKLOG_LOG_NOTICE,
        Logger::WARNING => self::NETWORKLOG_LOG_WARNING,
        Logger::ERROR => self::NETWORKLOG_LOG_ERROR,
        Logger::CRITICAL => self::NETWORKLOG_LOG_CRIT,
        Logger::ALERT => self::NETWORKLOG_LOG_ALERT,
        Logger::EMERGENCY => self::NETWORKLOG_LOG_EMERG,
    );

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
    const APP_YYDB_ID = 170;

    //统计来源
    const NETWORKLOG_LOG_SRC_DEFAULT = 0;

    // 配置项
    protected $config;

    public function __construct($config, $level = Logger::DEBUG, $bubble = true)
    {
        $this->config = $config;

        $connectionString = 'tcp://' . $config['networklog_address'] . ':' . $config['networklog_port'];

        parent::__construct($connectionString, $level, $bubble);
    }

    protected function generateDataStream($record)
    {
        return $this->buildContent($record);
    }

    private function buildContent($record)
    {
        $src = isset($this->config['networklog_src']) ? $this->config['networklog_src'] : self::NETWORKLOG_LOG_SRC_DEFAULT;
        $appid = $this->config['networklog_appid'];
        $level = self::$level_map[$record['level']];

        $content = $record['formatted'];
        $contentlen = strlen($content) + 1;
        $pkglen = $this->config['networklog_head_len'] + $contentlen;

        return pack("n6Ca$contentlen", $pkglen, $this->config['networklog_ver'], $this->config['networklog_cmd'], $src, 0, $appid, $level, $content);
    }
}
