<?php

/**
 *
 * Clss API_Base
 */
class Script_Base extends MY_Controller
{
    protected $log_type = 'Script';

    public function __construct()
    {
        parent::__construct();
        if ('production' === ENVIRONMENT && ! is_cli()) {
            exit('not cli');
        }
    }

    /**
     * 记录日志
     */
    public function log( $logs, $showTime = true)
    {
        $type = $this->log_type;
        $filename = $filename = APPPATH.'logs/'.$type.'_'.date('Y-m-d').'.log';
//        $filename = dirname(__FILE__).'/logs/'.$type.'_'.date('Y-m-d').'.log';
        @file_put_contents($filename, ($showTime ? '['.date('Y-m-d H:i:s').'] ' : '').$logs."\n", FILE_APPEND);
    }
}