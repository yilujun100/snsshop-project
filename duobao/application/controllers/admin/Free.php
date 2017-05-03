<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Free extends Admin_Base
{
    protected $relation_model = 'active_peroid_model';

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
            'iCreateTime' => 'DESC'
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
        if ($act_state > -1) {
            $where['iLotState'] = $act_state;
        }
        $where['iCodePrice'] = 0;
        $where['iProcess <'] = 100;

        $result_list = $this->active_peroid_model->get_active_peroids('*', $where, $order_by, $page);

        $viewData = array(
            'result_list' => $result_list,
            'act_id' => $act_id,
            'goods_id' => $goods_id,
            'act_state' => $act_state,
            'publish_state' => Lib_Constants::$publish_states
        );

        $this->render($viewData);
    }


    public function lottery()
    {
        $peroid_str = $this->get_post('id');
        list($act_id,$peroid) = period_code_decode($peroid_str);

        if(empty($act_id) || empty($peroid)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $peroid_item = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid));
        if(empty($peroid_item)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $data = array(
            'iProcess' => 100,
            'iLotCount' => $peroid_item['iSoldCount'], //开奖需要夺宝码数设成当期销量一致
        );
        if($rs = $this->active_peroid_model->update_row($data,array('iActId'=>$act_id,'iPeroid'=>$peroid))){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }
}