<?php
namespace Monolog\Handler;

use Monolog\Logger;

class GpRotatingFileHandler extends StreamHandler
{
    protected $path;
    protected $filename;
    protected $maxFiles;
    protected $mustRotate;
    protected $nextRotation;
    protected $filenameFormat;
    protected $dateFormat;

    public function __construct($path, $maxFiles = 0, $level = Logger::DEBUG, $bubble = true, $filePermission = null, $useLocking = false)
    {
        $this->path = $path;
        $this->filename = strtolower(Logger::getLevelName($level));
        $this->maxFiles = (int) $maxFiles;
        $this->nextRotation = new \DateTime('tomorrow');
        $this->filenameFormat = '{filename}-{date}';
        $this->dateFormat = 'Y-m-d';

        parent::__construct($this->getTimedFilename(), $level, $bubble, $filePermission, $useLocking);
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        parent::close();

        if (true === $this->mustRotate) {
            $this->rotate();
        }
    }

    public function setFilenameFormat($filenameFormat, $dateFormat)
    {
        $this->filenameFormat = $filenameFormat;
        $this->dateFormat = $dateFormat;
        $this->url = $this->getTimedFilename();
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record)
    {
        // on the first record written, if the log is new, we should rotate (once per day)
        if (null === $this->mustRotate) {
            $this->mustRotate = !file_exists($this->url);
        }

        if ($this->filename != $record['level_name']) {
            $this->filename = strtolower($record['level_name']);
            $this->mustRotate = true;
            $this->close();
        } else if ($this->nextRotation < $record['datetime']) {
            $this->mustRotate = true;
            $this->close();
        }

        parent::write($record);
    }

    /**
     * Rotates the files.
     */
    protected function rotate()
    {
        // update filename
        $this->url = $this->getTimedFilename();
        $this->nextRotation = new \DateTime('tomorrow');

        // skip GC of old logs if files are unlimited
        if (0 === $this->maxFiles) {
            return;
        }

        $logFiles = glob($this->getGlobPattern());
        if ($this->maxFiles >= count($logFiles)) {
            // no files to remove
            return;
        }

        // Sorting the files by name to remove the older ones
        usort($logFiles, function ($a, $b) {
            return strcmp($b, $a);
        });

        foreach (array_slice($logFiles, $this->maxFiles) as $file) {
            if (is_writable($file)) {
                // suppress errors here as unlink() might fail if two processes
                // are cleaning up/rotating at the same time
                set_error_handler(function ($errno, $errstr, $errfile, $errline) {});
                unlink($file);
                restore_error_handler();
            }
        }

        $this->mustRotate = false;
    }

    protected function getTimedFilename()
    {
        $file = $this->path . $this->filename;
        $fileInfo = pathinfo($file);
        $timedFilename = str_replace(
            array('{filename}', '{date}'),
            array($fileInfo['filename'], date($this->dateFormat)),
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );

        if (!empty($fileInfo['extension'])) {
            $timedFilename .= '.'.$fileInfo['extension'];
        }

        return $timedFilename;
    }

    protected function getGlobPattern()
    {
        $fileInfo = pathinfo($this->filename);
        $glob = str_replace(
            array('{filename}', '{date}'),
            array($fileInfo['filename'], '*'),
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );
        if (!empty($fileInfo['extension'])) {
            $glob .= '.'.$fileInfo['extension'];
        }

        return $glob;
    }
}
