<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Class Tools_Base
 */
class Tools_Base extends MY_Controller
{
    protected $log_type = 'tools';
    /**
     * 记录日志
     */
    public function log( $logs, $showTime = true)
    {
        $type = $this->log_type;
        //$filename = $filename = APPPATH.$type.'_'.date('Y-m-d').'.log';
        $filename = dirname(__FILE__).'/logs/'.$type.'_'.date('Y-m-d').'.log';
        @file_put_contents($filename, ($showTime ? '['.date('Y-m-d H:i:s').'] ' : '').$logs."\n", FILE_APPEND);
    }
}