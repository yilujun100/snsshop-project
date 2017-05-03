<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Robot_check extends Script_Base
{
    /**
     * @var string
     */
    protected $log_type = 'Robot_check';

    /**
     * 自动禁用中奖过多的机器人
     *
     * @return int
     */
    public function win_limit()
    {
        $limit = 3;
        $begin = strtotime('-7 day');

        $this->load->model('active_peroid_model');
        $sql = "SELECT iWinnerUin as iUin FROM t_active_peroid WHERE iIsRobot=1 AND iLotState=2 AND iWinnerUin>0 AND iBeginTime>={$begin} GROUP BY iWinnerUin HAVING COUNT(*) >= {$limit}";
        $result = $this->active_peroid_model->query($sql);
        if (! $result) {
            return 0;
        }

        $this->load->model('robot_model');
        $disableTime = strtotime('+7 day');
        $in_str = implode(',', array_column($result, 'iUin'));
        $sql = "UPDATE t_robot SET iState=2,iDisableTime={$disableTime} WHERE iUin IN ({$in_str})";
        if (!$this->robot_model->query($sql)) {
            $this->log('disable robot failed | ' . $this->robot_model->db->last_query());
            return 1;
        }
        return 0;
    }

    /**
     * 按条件自动恢复禁用（非永久禁用）的机器人
     *
     * @return int
     */
    public function recover()
    {
        $now = time();
        $data = array(
            'iState' => Lib_Constants::ROBOT_STATE_ENABLED
        );
        $where = array(
            'iState' => Lib_Constants::ROBOT_STATE_DISABLED,
            'iDisableTime !=' => -1,
            'iDisableTime <=' => $now,
        );
        $this->load->model('robot_model');
        if (!$this->robot_model->update_rows($data, $where)) {
            $this->log('recover robot failed | ' . $this->robot_model->db->last_query());
            return 1;
        }
        return 0;
    }
}