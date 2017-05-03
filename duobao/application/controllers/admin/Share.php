<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Share extends Admin_Base
{
    protected $relation_model = 'share_model';

    /**
     * 构造函数
     *
     * Goods constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model($this->relation_model);
    }


    /**
     * 活动列表
     */
    public function index()
    {
        $page = $this->get('page', 1);
        $order_by = array(
            'iCreateTime' => 'DESC',
            'iUpdateTime' => 'DESC'
        );
        $act_id = intval($this->get('act_id', 0));
        $goods_id = intval($this->get('goods_id', 0));
        $act_state = intval($this->get('act_state', -1));

        $where = array();
        if ($act_id > 0) {
            $where['iActId'] = $act_id;
        }
        if ($goods_id > 0) {
            $where['iGoodsId'] = $goods_id;
        }

        $result_list = $this->share_model->row_list('*', $where, $order_by, $page);

        $viewData = array(
            'result_list' => $result_list,
            'act_id' => $act_id,
            'goods_id' => $goods_id,
            'act_state' => $act_state,
            'publish_state' => Lib_Constants::$publish_states
        );

        $this->render($viewData);
    }


    public function audit()
    {
        $share_id = $this->get_post('id');
        $opt = $this->get_post('opt');

        if(empty($share_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $share_info = $this->share_model->get_row(array('iShareId'=>$share_id));
        if(empty($share_info)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $audit = $opt == 1 ? Lib_Constants::SHARE_AUDIT_SUCC : Lib_Constants::SHARE_AUDIT_FAILD;
        if($rs = $this->share_model->update_row(array('iAudit'=>$audit),array('iShareId'=>$share_id))){
            //审核，需要推送通知
            $this->load->service('push_service');
            $data = array(
                'url' => $audit == Lib_Constants::SHARE_AUDIT_SUCC ? gen_uri('/coupon/get_free_coupon',array('peroid_str'=>period_code_encode($share_info['iActId'],$share_info['iPeriod']),'share_id'=>$share_id)) : gen_uri('/share/index'),
                'is_audit' => $audit,
                'uin' => $share_info['iUin'],
                'nick_name' => $share_info['sNickName'],
                'goods_name' => $share_info['sGoodsName']
            );
            $this->push_service->add_task(Lib_Constants::$msg_business_type[Lib_Constants::MSG_TEM_SHARE_ORDER_AUDIT],period_code_encode($share_info['iActId'],$share_info['iPeriod']).time(),$share_info['iUin'],$data);

            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}