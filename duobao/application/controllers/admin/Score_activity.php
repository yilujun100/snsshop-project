<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 积分兑换活动
 * Class Awards_activity
 */
class Score_activity extends Admin_Base {
    protected $relation_model = 'score_activity_model';
    public function __construct() {
        parent::__construct();
        $this->load->model('score_activity_model');
    }

    /**
     * 奖励管理
     */
    public function index() {
        $data['js'] = array('jquery.validate','jquery_validate_extend','jquery-ui-datepicker-zh-CN');
        $data['css'] = array('smart-forms','smart-themes/red','font-awesome.min');
        //上线的奖励类型列表
        $data['activity_list'] = $this->score_activity_model->row_list('*', array(), array(), $this->get('page', 1));
        $this->render($data);
    }

    /**
     * 奖励管理
     */
    public function add() {
        if ($this->input->is_ajax_request()) {
            $valid = $this->form_validate();
            if ($valid['errors']) {
                $this->render_result(Lib_Errors::PARAMETER_ERR, array(), implode('<br/>',$valid['errors']));
            }
            $params = $valid['params'];
            $data = array(
                'iGoodsId' => $params['goods_id'],
                'sGiftName' => $params['goods_name'],
                'iShortName' => $params['goods_name'],
                'iOriScore' => $params['ori_score'],
                'iPreScore' => $params['pre_score'],
                'iSingle' => $params['single'],
                'iMaxLimit' => $params['max'],
                'iTotal' => $params['total_limit'],
                'iGoodsType' => $params['type'],
                'iStartTime' => $params['start_time'],
                'iEndTime' => $params['end_time'],
                'iPlatForm' => $params['platform'],
                'sImg' => $params['img'],
                'iCouponNum' => $params['coupon_num'],
                'iState' => Lib_Constants::PUBLISH_STATE_READY
            );
            if ($insert_id = $this->score_activity_model->add_row($data)) {
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
                $this->render_result(Lib_Errors::PARAMETER_ERR,array(), implode('<br/>',$valid['errors']));
            }
            $params = $valid['params'];
            $data = array(
                'iPreScore' => $params['pre_score'],
                'iEndTime' => $params['end_time'],
            );
            if ($this->score_activity_model->update_row($data, $params['id'])) {
                $this->render_result(Lib_Errors::SUCC);
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
    public function goods_info() {
        if ($this->input->is_ajax_request()) {
            $goods_id  = $this->post('goods_id', 0);
            if(!$goods_id) {
                $this->render_result(Lib_Errors::PARAMETER_ERR);
            }
            $this->load->model('goods_item_model');
            if ($goods_item = $this->goods_item_model->get_row($goods_id)) {
                $this->render_result(Lib_Errors::SUCC, array('name'=>$goods_item['sName']));
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
            $params['goods_id'] = intval($this->post('goods_id', 0));
            $params['platform'] = intval($this->post('platform',0));
            $params['total_limit'] = intval($this->post('total_limit',0));
            $params['ori_score'] = intval($this->post('ori_score',0));
            $params['single'] = intval($this->post('single',0));
            $params['max'] = intval($this->post('max',0));
        } else {
            $params['id'] = $this->post('id', 0);
        }

        $params['pre_score'] = intval($this->post('pre_score',0));
        $start = $this->post('start_time');
        $end = $this->post('end_time');

        $errors = array();
        if($type == 'add') {
            if (!$params['goods_id']) {
                $errors['goods_id'] = '请输入商品ID';
            } else {
                $this->load->model('goods_item_model');
                if (!$goods_info = $this->goods_item_model->get_row($params['goods_id'])) {
                    $errors['goods_id'] = '商品ID错误';
                } else {
                    if($goods_info['iState'] != Lib_Constants::PUBLISH_STATE_ONLINE) {
                        $errors['goods_id'] = '商品未上线';
                    }
                    if ( $goods_info['iType'] != Lib_Constants::GOODS_TYPE_TICKET) {
                        $errors['goods_id'] = '商品必须为券类型';
                    }
                    $params['goods_name'] = $goods_info['sName'];
                    $params['type'] = $goods_info['iType'];
                    $params['coupon_num'] = intval(price_format($goods_info['iLowestPrice']));
                    $params['img'] = $goods_info['sImg'];
                }
            }
            if (!$params['platform'] || !array_key_exists($params['platform'], Lib_Constants::$platforms)) {
                $errors['platform'] = '请选择平台';
            }
            if ($params['total_limit'] <=0) {
                $errors['total_limit'] = '商品数量错误';
            }
            if ($params['ori_score'] <=0) {
                $errors['ori_score'] = '所需积分数量错误';
            }

            if ($params['single'] <=0) {
                $errors['single'] = '单人单次购买限制错误';
            }
            if ($params['max'] <=0) {
                $errors['max'] = '单人最多购买限制错误';
            }
            if ($params['single'] > $params['max']) {
                $errors['single'] = '单人单次购买限制数量大于单人最多购买限制数量';
            }
            if ($params['max'] > $params['total_limit']) {
                $errors['max'] = '单人最多购买限制数量大于商品数量';
            }
            if(!$start) {
                $errors['start_time'] = '请选择活动开始时间';
            } elseif (!strtotime($start)) {
                $errors['start_time'] = '活动开始时间格式错误';
            }
            $params['start_time'] = strtotime($start);
        } else {
            if (!$params['id']) {
                $errors['id'] = '积分活动ID不能为空';
            } else {
                if (!$ori_row = $this->score_activity_model->get_row($params['id'])) {
                    $errors['id'] = '积分活动活动ID错误';
                }
                $params['ori_score'] = intval($ori_row['iOriScore']);
            }
            $params['start_time'] = $ori_row['iStartTime'];
        }

        if ($params['pre_score'] <0) {
            $errors['pre_score'] = '优惠积分数量错误';
        }
        if ($params['pre_score'] && $params['pre_score'] >= $params['ori_score']) {
            $errors['pre_score'] = '优惠积分数量大于积分兑换数量';
        }

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