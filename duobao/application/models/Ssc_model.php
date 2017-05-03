<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ssc_model extends MY_Model
{
    protected $db_group_name = DATABASE_YYDB; //分组名
    protected $table_name = 't_ssc'; // 表名
    protected $table_primary = 'iIssue'; // 表名主键名称
    protected $cache_row_key_column = 'iIssue'; // 单条表记录缓存字段
    protected $auto_update_time = TRUE; // 自动更新createtime或updatetime
    protected $need_cache_row = TRUE; // 是否需要缓存表记录

    /**
     * 获取期号对应的时时彩数据
     *
     * @param int $issue
     *
     * @return bool|mixed
     */
    public function get_win($issue)
    {
        if ($row = $this->get_row($issue)) {
            return $row;
        }
        list($max, $min) = $this->get_issue_time($issue);
        if (time() >= $max) {
            $data = array(
                'iIssue' => $issue,
                'iWinNum' => '00000',
                'sSrc' => 'ssc_model',
                'sApi' => 'can\'t get issue info until the max time',
            );
            if (! $this->add_row($data)) {
                $this->log->error('ssc_model | get_win', 'add_row failed');
                return;
            }
            return $this->get_row($issue);
        }
    }

    /**
     * 获取某时间点下一期时时彩相关信息
     *
     * @param $time
     *
     * @return array
     */
    public function get_next_ssc($time)
    {
        list($issue, $max, $min) = $this->get_next($time);

        return array(
            'issue' => $issue,
            'max' => $max,
            'min' => $min,
        );
    }

    /**
     * 获取时时彩期数对应的关键时间点
     *
     * @param $issue
     *
     * @return array
     */
    public function get_issue_time($issue)
    {
        $num = intval(substr($issue, -3));
        $year = intval('20' . substr($issue, 0, 2));
        $month = intval(substr($issue, 2, 2));
        $date = intval(substr($issue, 4, 2));
        $begin = mktime(0, 0, 0, $month, $date, $year);
        $time10 = mktime(10, 0, 0, $month, $date, $year);
        $time22 = mktime(22, 0, 0, $month, $date, $year);
        if ($num < 24) {
            $min = $begin + $num * 300;
            if (23 == $num) {
                $max = $time10 - 1;
            } else {
                $max = $min + 300 - 1;
            }
        } else if ($num >= 24 && $num <= 95) {
            $tmp = $num - 24;
            $min = $time10 + $tmp * 600;
            $max = $min + 600 - 1;
        } else if ($num >= 96) {
            $tmp = $num - 96;
            $min = $time22 + $tmp * 300;
            $max = $min + 300 - 1;
        }
        return array($max, $min);
    }

    /**
     * 获取某个时间（默认当前时间）对应的下一期时时彩期号、最后时间等
     *
     * @param int $time
     *
     * @return array
     */
    public function get_next($time)
    {
        $prefix = date('ymd', $time);
        $year = date('Y', $time);
        $month = date('n', $time);
        $date = date('j', $time);
        $begin = mktime(0, 0, 0, $month, $date, $year);
        $time2 = mktime(2, 2, 0, $month, $date, $year);
        $time10 = mktime(10, 0, 0, $month, $date, $year);
        $time22 = mktime(22, 0, 0, $month, $date, $year);
        $end = mktime(23, 59, 59, $month, $date, $year) + 1;
        if ($time >= $begin && $time < $time2 - 300) {
            $tmp = floor(($time - $begin) / 300) + 1;
            $issue = str_pad($tmp, 3, '0', STR_PAD_LEFT);
            $min = $begin + $tmp * 300;
            $max = $min + 300 - 1;
        } else if ($time >= $time2 - 300 && $time < $time10) {
            $issue = '024';
            $min = $time10;
            $max = $min + 600 - 1;
        } else if ($time >= $time10 && $time < $time22) {
            $tmp = floor(($time - $time10) / 600) + 1;
            $issue = '0' . (24 + $tmp);
            if ($time < $time22 - 600) {
                $min = $time10 + $tmp * 600;
                $max = $min + 600 - 1;
            } else {
                $min = $time22;
                $max = $min + 300 - 1;
            }
        } else if ($time >= $time22 && $time < $end) {
            $tmp = floor(($time - $time22) / 300) + 1;
            $issue = str_pad((96 + $tmp), 3, '0', STR_PAD_LEFT);
            $min = $time22 + $tmp * 300 - 1;
            $max = $min + 300;
        }
        return array($prefix.$issue, $max, $min);
    }

    /**
     * 获取某个时间（默认当前时间）对应的时时当前彩期号、最后时间等
     *
     * @param int $time
     *
     * @return int
     */
    public function get_current($time = 0)
    {
        if (! $time) {
            $time = time();
        }
        $prefix = date('ymd', $time);
        $year = date('Y', $time);
        $month = date('n', $time);
        $date = date('j', $time);
        $begin = mktime(0, 0, 0, $month, $date, $year);
        $time2 = mktime(2, 0, 0, $month, $date, $year);
        $time10 = mktime(10, 0, 0, $month, $date, $year);
        $time22 = mktime(22, 0, 0, $month, $date, $year);
        $end = mktime(23, 59, 59, $month, $date, $year) + 1;
        if ($time >= $begin && $time < $begin + 300) {
            $prefix = date('ymd', $begin - 1);
            $issue = '120';
            $min = $begin - 1;
            $max = $min + 300 - 1;
        } else if ($time >= $begin + 300 && $time < $time2 - 300) {
            $tmp = floor(($time - $begin) / 300);
            $issue = str_pad($tmp, 3, '0', STR_PAD_LEFT);
            $min = $begin + $tmp * 300;
            $max = $min + 300 - 1;
        } else if ($time >= $time2 - 300 && $time < $time10) {
            $issue = '023';
            $min = $time2 - 300;
            $max = $time10 - 1;
        } else if ($time >= $time10 && $time < $time22) {
            $tmp = floor(($time - $time10) / 600);
            $issue = '0' . (24 + $tmp);
            $min = $time10 + $tmp * 600;
            $max = $min + 600 - 1;
        } else if ($time >= $time22 && $time <= $end) {
            $tmp = floor(($time - $time22) / 300);
            $issue = str_pad((96 + $tmp), 3, '0', STR_PAD_LEFT);
            $min = $time22 + $tmp * 300;
            $max = $min + 300 - 1;
        }
        return array($prefix.$issue, $max, $min);
    }
}