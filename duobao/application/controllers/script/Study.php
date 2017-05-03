<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Study extends Script_Base
{
    protected $log_type = 'Study';

    public function run()
    {
        set_time_limit(0);
        while (true) {
            Lib_WeixinNotify::study();
            sleep(10);
        }
    }
}