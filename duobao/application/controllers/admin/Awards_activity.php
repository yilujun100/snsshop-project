<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 用户奖励活动
 * Class Awards_activity
 */
class Awards_activity extends Admin_Base {
    protected $relation_model = 'awards_activity_model';
    public function __construct() {
        parent::__construct();
        $this->load->model('awards_type_model');
        $this->load->model('awards_activity_model');
    }

    /**
     * 奖励管理
     */
    public function index() {
        $data['js'] = array('jquery.validate','jquery_validate_extend','jquery-ui-datepicker-zh-CN');
        $data['css'] = array('smart-forms','smart-themes/red','font-awesome.min');
        //上线的奖励类型列表
        $data['awards_type_list'] = $this->awards_type_model->get_onlone_awards_type();
        $data['activity_list'] = $this->awards_activity_model->row_list('*', array(), array(), $this->get('page', 1));
        $this->render($data);
    }

    /**
     * 奖励管理
     */
    public function add() {
        if ($this->input->is_ajax_request()) {
            $valid = $this->form_validate();
            if ($valid['errors']) {
                $this->render_result(Lib_Errors::PARAMETER_ERR, $valid['errors']);
            }
            $params = $valid['params'];
            $data = array(
                'iAwardsType' => $params['awards_type'],
                'sAwardsType' => $params['awards_ename'],
                'sAwardsName' => $params['awards_name'],
                'iGiftType' => $params['gift_type'],
                'iStartTime' => $params['start_time'],
                'iEndTime' => $params['end_time'],
                'iGift' => $params['gift_count'],
                'iPlatForm' => $params['platform'],
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->awards_activity_model->add_row($data)) {
                $this->render_result(Lib_Errors::SUCC, $insert_id);
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * 奖励类型-修改
     */
    public function edit() {
        if ($this->input->is_ajax_request()) {
            $valid = $this->form_validate('edit');
            if ($valid['errors']) {
                $this->render_result(Lib_Errors::PARAMETER_ERR, $valid['errors']);
            }
            $params = $valid['params'];
            $data = array(
                'iStartTime' => $params['start_time'],
                'iEndTime' => $params['end_time'],
                'iUpdateTime' => time()
            );
            if ($this->awards_activity_model->update_row($data, $params['id'])) {
                $this->render_result(Lib_Errors::SUCC);
            } else {
                $this->render_result(Lib_Errors::SVR_ERR);
            }
        } else {
            $this->render_result(Lib_Errors::EXCEPTION_REQUEST);
        }
    }

    /**
     * 表单验证
     * @param string $type
     * @return mixed
     */
    private function form_validate($type='add')
    {
        $params = array();
        if($type == 'add') {
            $params['awards_type'] = $this->post('awards_type', 0);
            $params['gift_type'] = $this->post('gift',0);
            $params['gift_count'] = $this->post('gift_count',0);
            $params['platform'] = $this->post('platform',0);
        } else {
            $params['id'] = $this->post('id', 0);
        }

        $start = $this->post('start_time');
        $end = $this->post('end_time');

        $errors = array();
        if($type == 'add') {
            $awards_type_list = $this->awards_type_model->get_onlone_awards_type();
            if (!$params['awards_type'] || !array_key_exists($params['awards_type'], $awards_type_list)) {
                $errors['awards_type'] = '请选择奖励类型';
            }
            if (!$params['gift_type'] || !array_key_exists($params['gift_type'], Lib_Constants::$awards_prizes)) {
                $errors['gift'] = '请选择奖励内容';
            }
            if(!is_numeric($params['gift_count']) || $params['gift_count'] <= 0) {
                $errors['gift_count'] = '奖励数量错误';
            }
            if (!$params['platform'] || !array_key_exists($params['platform'], Lib_Constants::$platforms)) {
                $errors['platform'] = '请选择平台';
            }
            $params['awards_name'] = $awards_type_list[$params['awards_type']]['short_name'];
            $params['awards_ename'] = $awards_type_list[$params['awards_type']]['enname'];
        } else {
            if (!$params['id']) {
                $errors['id'] = '奖励活动ID不能为空';
            } else {
                if (!$this->awards_activity_model->get_row($params['id'])) {
                    $errors['id'] = '奖励活动不存在';
                }
            }
        }

        if(!$start) {
            $errors['start_time'] = '请选择活动开始时间';
        } elseif (!strtotime($start)) {
            $errors['start_time'] = '活动开始时间格式错误';
        }
        $params['start_time'] = strtotime($start);
        if ($end_time = strtotime($end)) {
            if ($end_time < $params['start_time']) {
                $errors['end_time'] = '活动结束时间小于开始时间';
            }
        }
        $params['end_time'] = $end_time;

        $ret['errors'] = $errors;
        $ret['params'] = $params;
        return $ret;
    }
}