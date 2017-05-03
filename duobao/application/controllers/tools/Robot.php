<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Robot extends Tools_Base
{
    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
    }

    public function fixRobotIp()
    {
        $begin = microtime(TRUE);
        $total = 24118;
        $page_size = 2000;
        $page_count = ceil($total / $page_size);
        $normal_offset = 8000;
        $db = $this->load->database('yydb_m1', TRUE);
        for ($i = 0; $i < $page_count; $i ++) {
            $db->reconnect();
            $exception_offset = $i * $page_size;
            $sql = "SELECT iId,iUin FROM t_robot WHERE iState=2 ORDER BY iId LIMIT $exception_offset,$page_size";
            if (! ($r1 = $db->query($sql))) {
                continue;
            }
            $exception_robot = $r1->result_array();
            foreach ($exception_robot as $v) {
                do {
                    $not = FALSE;
                    $sql = "SELECT sIp FROM t_robot WHERE iState=1 ORDER BY iId LIMIT $normal_offset,1";
                    $normal_robot = $db->query($sql)->result_array();
                    if ($normal_robot && ! empty($normal_robot[0]['sIp'])) {
                        $ip_piece = explode('.', $normal_robot[0]['sIp']);
                        if (count($ip_piece) !=4) {
                            $not = TRUE;
                        } else {
                            do {
                                $invalid = FALSE;
                                if ($ip_piece[3] < 254) {
                                    $ip_piece[3] ++;
                                } else if ($ip_piece[2] < 254) {
                                    $ip_piece[2] ++;
                                } else {
                                    $not = TRUE;
                                    break;
                                }
                                $new_ip = implode('.', $ip_piece);
                                $sql = "SELECT sIp FROM t_robot WHERE sIp='$new_ip' LIMIT 1";
                                if ($db->query($sql, TRUE)->num_rows() < 1) {
                                    $db->where('iUin', $v['iUin'])->limit(1)->update('t_robot', array('sIp'=>$new_ip));
                                    $this->log->alert(str_pad($normal_offset, 5), str_pad($normal_robot[0]['sIp'], 15), array($v['iUin'], $new_ip));
                                } else {
                                    $invalid = TRUE;
                                }
                                $this->log->critical(str_pad($normal_offset, 5), str_pad($normal_robot[0]['sIp'], 15), array($v['iUin'], $new_ip));
                            } while ($invalid);
                        }
                    }
                    $normal_offset ++;
                } while($not);
            }
        }
        echo 'Elapsed: ' . (microtime(TRUE) - $begin);
    }

    public function fixShareIP()
    {
        $this->load->model('share_model');
        $this->load->model('robot_model');
        $sql = "SELECT iShareId,iUin FROM yydb.`t_share` WHERE iIsRobot=1";
        $share_list = $this->share_model->query($sql);
        foreach ($share_list as $v) {
            if (($robot = $this->robot_model->get_row($v['iUin'])) && ! empty($robot['sIp'])) {
                $this->share_model->update_row(array('iIp'=>ip2long($robot['sIp'])), $v['iShareId']);
            }
        }
    }
}