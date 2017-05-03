<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Log extends CI_Log
{
    protected $LogStrategy;

    protected $logPath;

    protected $logFile;

    protected $levels = array(
        'debug',
        'info',
        'notice',
        'warning',
        'error',
        'critical',
        'alert',
        'emergency',
    );

    protected $level;

    protected $level_off = 'off';

    protected $benchmark;

    public function __construct()
    {
        $this->benchmark = load_class('Benchmark', 'core');

        $this->LogStrategy = load_class('LogStrategy', 'libraries', ENVIRONMENT);

        $logPath = config_item('log_path');

        $this->logPath = $logPath ? rtrim($logPath, '/') . DIRECTORY_SEPARATOR : APPPATH . 'logs' . DIRECTORY_SEPARATOR;

        $this->logFile = config_item('log_file');

        $this->level = config_item('log_level');

        $this->configureHandlers();
    }

    protected function configureHandlers()
    {
        $strategy = config_item('log_strategy');

        $method = 'configure'.ucfirst($strategy).'Handler';

        $this->{$method}();
    }

    protected function configureSingleHandler()
    {
        $this->LogStrategy->useFiles(
            $this->logPath . $this->logFile,
            $this->level
        );
    }

    protected function configureDailyHandler()
    {
        $this->LogStrategy->useDailyFiles(
            $this->logPath . $this->logFile,
            config_item('log_max_files'),
            $this->level
        );
    }

    protected function configureLeveldailyHandler()
    {
        $this->LogStrategy->useLevelDailyFiles(
            $this->logPath,
            config_item('log_max_files'),
            $this->level
        );
    }

    protected function configureSyslogHandler()
    {
        $this->LogStrategy->useSyslog('duobao');
    }

    protected function configureErrorlogHandler()
    {
        $this->LogStrategy->useErrorLog();
    }

    protected function configureGptcpHandler()
    {
        $this->LogStrategy->useGpTcp(
            config_item('log_networklog'),
            $this->level
        );
    }

    function __call($name, $arguments)
    {
        if ($this->is_closed()) {
            return;
        }
        if (! in_array($name, $this->levels)) {
//            throw new InvalidArgumentException('Invalid log level.');
            return;
        }
        if (count($arguments) < 2) {
//            throw new InvalidArgumentException('Invalid log arguments.');
            return;
        }
        if ($arguments[0]) {
            $arguments[1] = $arguments[0] . ' | ' . $arguments[1];
        }
        array_shift($arguments);
        try {
            call_user_func_array(array($this->LogStrategy, $name), $arguments);
        } catch (Exception $e) {
            if (! defined('STDIN')) {
                $this->close_log();
            } else {
                $this->log_exception($e);
            }
        }
    }

    public function write_log($level, $message, array $context = array())
    {
        if ($this->is_closed()) {
            return;
        }
        if (! in_array($level, $this->levels)) {
//            throw new InvalidArgumentException('Invalid log level.');
            return;
        }
        $this->mark("log(write_log)_start");
        try {
            $this->LogStrategy->write($level, $message, $context);
            $this->mark("log(write_log)_end");
        } catch (Exception $e) {
            $this->mark("log(write_log)_exception");
            if (! defined('STDIN')) {
                $this->close_log();
            } else {
                $this->log_exception($e);
            }
        }
    }

    protected function mark($mark)
    {
        if (! isset($this->benchmark->marker[$mark])) {
            $this->benchmark->mark($mark);
        }
    }

    protected function is_closed()
    {
        return $this->level === $this->level_off;
    }

    protected function close_log()
    {
        $this->level = $this->level_off;
    }

    protected function log_exception (Exception $e)
    {
        $time_suffix = date('Y-m-d_H-i', floor(time() / 300) * 300);
        $log_exception = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'log_'.$time_suffix . '.log';
        $log = '['.date('Y-m-d H:i:s').'] ' . $_SERVER['REQUEST_URI'] . ' | ' . microtime(true) . ' | ';
        $log .= $e->getCode() . ': ' . $e->getMessage() . ' | ' . $e->getLine() . ' ' . $e->getFile() . "\n";
        file_put_contents($log_exception,  $log, FILE_APPEND);

//        $this->close_log();
    }
}
