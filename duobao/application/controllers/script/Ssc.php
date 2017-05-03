<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ssc extends Script_Base
{
    /**
     * @var string
     */
    protected $log_type = 'Shishicai';

    /**
     * Ssc constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('ssc_model');
        $this->load->driver('cache');
    }

    /**
     * @return int
     */
    public function run()
    {
        $now = time();
        if ($now >= mktime(2, 30, 0) && $now <= mktime(9, 30, 0)) {
            return 0;
        }
        list($current_issue, $max_time) = $this->ssc_model->get_current($now);
        $row = $this->ssc_model->get_row($current_issue);
        if ($row) {
            return 0;
        }
        if ($win = $this->caipiaokong($current_issue)) {
            $data = array(
                'iIssue' => $current_issue,
                'iWinNum' => $win['win_num'],
                'sSrc' => 'caipiaokong',
                'sApi' => json_encode($win['api']),
            );
        } else if ($now >= $max_time - 9) {
            $this->log("can't get win_num | issue: " . $current_issue . ' | time: ' . date('Y-m-d H:i:s', $now));
            $data = array(
                'iIssue' => $current_issue,
                'iWinNum' => '00000',
                'sSrc' => 'caipiaokong',
                'sApi' => '',
            );
        } else {
            return 0;
        }
        if (! $this->ssc_model->add_row($data)) {
            $this->log('ssc_model add_row failed | data: ' . json_encode($data));
            return 1;
        }
        return 0;
    }

    /**
     * 彩票控api
     *
     * @link http://www.caipiaokong.com/open/3.html
     *
     * @param $issue
     */
    protected function caipiaokong($issue)
    {
        $name = 'cqssc';
        $uid = '395123';
        $token = 'f1d4a12d0ad84bb500fc2ce5293f640add8cf6d4';
        $freq = 3;

        $last_time_cache_key = "caipiaokong_{$name}_{$uid}";
        $last_time = $this->cache->memcached->get($last_time_cache_key);
        $now = time();
        if ($last_time && $now - $last_time < $freq) {
            return;
        }
        $this->cache->memcached->save($last_time_cache_key, $now);

        $api = "http://api.caipiaokong.com/lottery/?name={$name}&format=json&uid={$uid}&token={$token}&num=3";
        $response = file_get_contents($api);
        $data = json_decode($response, TRUE);
        if (JSON_ERROR_NONE !== ($json_error = json_last_error())) {
            $this->log("parse json error | error: {$json_error} | response: {$response}");
        }
        $key = '20' . $issue;
        if (! empty($data[$key])) {
            $ret['api'] = $data[$key];
            $ret['win_num'] = str_replace(',', '', $data[$key]['number']);
            return $ret;
        }
    }

    /**
     * 调试
     */
    public function debug()
    {
        $time = mktime(21, 50, 0);
        print_r($this->ssc_model->get_next($time));
        echo "\n=======\n";
        $time = mktime(23, 55, 0);
        print_r($this->ssc_model->get_current($time));
        echo "\n";
    }
}