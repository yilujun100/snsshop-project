<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * service 基类
 *
 * Class MY_Service
 */
class MY_Service
{
    /**
     * MY_Service constructor.
     */
    public function __construct()
    {
        log_message('debug', "Service Class Initialized");
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    function __get($key)
    {
        $CI = & get_instance();
        if (property_exists($CI, $key)) {
            return $CI->$key;
        }
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $CI = & get_instance();
        $callable = array($CI, $name);
        if (is_callable($callable)) {
            return call_user_func_array($callable, $arguments);
        }
    }
}
