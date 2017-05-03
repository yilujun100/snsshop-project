<?php
use Monolog\Logger as MonologLogger;
use Monolog\Handler\SyslogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\GpRotatingFileHandler;
use Monolog\Handler\GpTcpHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Formatter\LineFormatter;

class MY_LogStrategy
{
    protected $monolog;

    protected $levels = array(
        'debug'     => MonologLogger::DEBUG,
        'info'      => MonologLogger::INFO,
        'notice'    => MonologLogger::NOTICE,
        'warning'   => MonologLogger::WARNING,
        'error'     => MonologLogger::ERROR,
        'critical'  => MonologLogger::CRITICAL,
        'alert'     => MonologLogger::ALERT,
        'emergency' => MonologLogger::EMERGENCY,
    );

    public function __construct($channel)
    {
        $this->monolog = new MonologLogger($channel);
    }

    public function emergency($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function alert($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function critical($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function error($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function warning($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function notice($message, array $context = array())
    {

        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function info($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function debug($message, array $context = array())
    {
        return $this->writeLog(__FUNCTION__, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
        return $this->writeLog($level, $message, $context);
    }

    public function write($level, $message, array $context = array())
    {
        return $this->writeLog($level, $message, $context);
    }

    protected function writeLog($level, $message, $context)
    {
        return $this->monolog->{$level}($message, $context);
    }

    public function useFiles($path, $level = 'debug')
    {
        $handler = new StreamHandler($path, $this->parseLevel($level));

        $this->monolog->setHandlers(array($handler));

        $handler->setFormatter($this->getDefaultFormatter());
    }

    public function useDailyFiles($path, $maxFiles = 0, $level = 'debug')
    {
        $handler = new RotatingFileHandler($path, $maxFiles, $this->parseLevel($level));

        $this->monolog->setHandlers(array($handler));

        $handler->setFormatter($this->getDefaultFormatter());
    }

    public function useLevelDailyFiles($path, $maxFiles = 0, $level = 'debug')
    {
        $handler = new GpRotatingFileHandler($path, $maxFiles, $this->parseLevel($level));

        $this->monolog->setHandlers(array($handler));

        $handler->setFormatter($this->getPartitionFormatter());
    }

    public function useSyslog($name = '', $level = 'debug')
    {
        $handler = new SyslogHandler($name, LOG_USER, $level);
        $this->monolog->setHandlers(array($handler));
    }

    public function useErrorLog($level = 'debug', $messageType = ErrorLogHandler::OPERATING_SYSTEM)
    {
        $handler = new ErrorLogHandler($messageType, $this->parseLevel($level));

        $this->monolog->setHandlers(array($handler));

        $handler->setFormatter($this->getDefaultFormatter());
    }

    public function useGpTcp($config, $level = 'debug')
    {
        $handler = new GpTcpHandler($config, $this->parseLevel($level));

        $handler->setConnectionTimeout(1);
        $handler->setTimeout(2);
        $handler->setWritingTimeout(1.8);

        $this->monolog->setHandlers(array($handler));

        $handler->setFormatter($this->getGpTcpFormatter());
    }

    protected function parseLevel($level)
    {
        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }

    protected function getDefaultFormatter()
    {
        return new LineFormatter(null, null, true, true);
    }

    protected function getPartitionFormatter()
    {
        return new LineFormatter("[%datetime%] %channel%: %message% %context% %extra%\n", null, true, true);
    }

    protected function getGpTcpFormatter()
    {
        return new LineFormatter("%channel%: %message% %context% %extra%", null, true, true);
    }
}