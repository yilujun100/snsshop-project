<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 夺宝券充值活动
 * Class Recharge_activity
 */
class Recharge_activity extends Admin_Base {
    protected $relation_model = 'recharge_activity_model';
    public function __construct() {
        parent::__construct();
        $this->load->model('recharge_activity_model');
    }

    /**
     * 奖励管理
     */
    public function index() {
        $data['js'] = array('jquery.validate','jquery_validate_extend','jquery-ui-datepicker-zh-CN');
        $data['css'] = array('smart-forms','smart-themes/red','font-awesome.min');
        //上线的奖励类型列表
        $data['activity_list'] = $this->recharge_activity_model->row_list('*', array(), array(), $this->get('page', 1));
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
                'sDesc' => $params['desc'],
                'sConf' => $params['conf'],
                'iStartTime' => $params['start_time'],
                'iEndTime' => $params['end_time'],
                'iPlatForm' => $params['platform'],
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->recharge_activity_model->add_row($data)) {
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
                'sConf' => $params['conf'],
                'iEndTime' => $params['end_time'],
                'iUpdateTime' => time()
            );
            if ($this->recharge_activity_model->update_row($data, $params['id'])) {
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
            $params['platform'] = $this->post('platform',0);
            $start = $this->post('start_time');
        } else {
            $params['id'] = $this->post('id', 0);
        }
        $params['desc'] = $this->post('desc','');
        $params['key'] = $this->post('key', 0);
        $params['val'] = $this->post('val', 0);
        $end = $this->post('end_time');

        $errors = array();
        if($type == 'add') {
            if (!$params['platform'] || !array_key_exists($params['platform'], Lib_Constants::$platforms)) {
                $errors['platform'] = '请选择平台';
            }
            if(!$start) {
                $errors['start_time'] = '请选择活动开始时间';
            } elseif (!strtotime($start)) {
                $errors['start_time'] = '活动开始时间格式错误';
            } else {
                $params['start_time'] = strtotime($start);
            }
        } else {
            if (!$params['id']) {
                $errors['id'] = '充值购券活动ID不能为空';
            } else {
                if (!$ori_row = $this->recharge_activity_model->get_row($params['id'])) {
                    $errors['id'] = '充值购券活动ID错误';
                }
                $params['start_time'] = $ori_row['iStartTime'];
            }
        }
        if(!$params['key'] || !is_array($params['key'])) {
            $errors['key'] = '请输入购买张数';
        }
        if(count($params['key']) != count($params['val'])) {
            $errors['key'] = '参数错误';
        }
        $params['conf'] = array();
        foreach($params['key'] as $key=>$val) {
            if($val) {
                $params['conf'][] = array('c'=>intval($params['key'][$key]),'s'=>intval($params['val'][$key]));
            }
        }

        if(empty($params['conf'])) {
            $errors['key'] = '充值金额设置不能为空';
        }

        if ($end_time = strtotime($end)) {
            if ($end_time < $params['start_time']) {
                $errors['end_time'] = '活动结束时间小于开始时间';
            }
        }
        $params['end_time'] = $end_time;
        $params['conf'] = json_encode($params['conf']);

        $ret['errors'] = $errors;
        $ret['params'] = $params;
        return $ret;
    }
}