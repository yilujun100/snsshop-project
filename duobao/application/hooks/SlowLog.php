<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SlowLog
{
    protected $benchmark;

    protected $slow_log_time = 0.2;

    public function __construct()
    {
        $this->benchmark = load_class('Benchmark', 'core');

        $slow_log_time = (int) get_variable(Lib_Constants::VAR_SLOW_LOG_TIME);
        if ($slow_log_time > 200) {
            $this->slow_log_time = $slow_log_time / 1000;
        }
    }

    public function log($params = array())
    {
        if (php_sapi_name() == 'cli' || defined('STDIN' || 1 != get_variable(Lib_Constants::VAR_SLOW_LOG_ON))) {
            return;
        }
        $elapsed_total = $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
        if ($elapsed_total < $this->slow_log_time) {
            return;
        }
        $marker = $this->benchmark->marker;
        reset($marker);
        $last_item = key($marker);
        $URL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        log_message('emergency', 'slow_log | start ------ ' . $URL);
        $log = array();
        $m = $n = 0;
        while (next($marker)) {
            if ($n > 0 && 0 == $n % 16) {
                $m ++;
            }
            $n ++;
            $item = key($marker);
            $time = current($marker);
            $elapsed_time = $this->benchmark->elapsed_time($last_item, $item);
            if (! isset($log[$m])) {
                $log[$m] = '';
            }
            $log[$m] .= "\n" . sprintf('slog_log | [%s] %s %s', $this->format_time($time), $elapsed_time, $item);
            $last_item = $item;
        }
        foreach ($log as $item) {
            log_message('emergency', $item);
        }
        log_message('emergency', 'slow_log | end -------- ' . $URL);
    }

    private function format_time($time)
    {
        list($sec, $mic) = explode('.', $time);
        return date('H:i:s', $sec) . '.' . str_pad($mic, 4, '0', STR_PAD_RIGHT);
    }
}