<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Active extends API_Base
{
    public function __construct()
    {
        parent::__construct(true);
    }

    /**
     * 取所有当前期的活动
     */
    public function get_active_ongoing()
    {
        $this->load->model('active_peroid_model');
        $ret = $this->active_peroid_model->get_active_ongoing();
        $this->render_result(Lib_Errors::SUCC,$ret);
    }

    /**
     * 取1000条已开奖的活动期数
     * @return mixed
     */
    public function get_active_1000_opened()
    {
        $this->load->model('active_peroid_model');
        $ret = $this->active_peroid_model->get_active_1000_opened();
        $this->render_result(Lib_Errors::SUCC,$ret);
    }

    /**
     * 取1000条已开奖的活动期数
     * @return mixed
     */
    public function get_active_opening()
    {
        $this->load->model('active_peroid_model');
        $ret = $this->active_peroid_model->get_active_opening();
        $this->render_result(Lib_Errors::SUCC,$ret);
    }

    /**
     * 已揭晓和即将揭晓各多少个
     */
    public function history()
    {
        extract($this->cdata);
        $p_size = isset($p_size) ? $p_size : 5;
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_history_peroids($p_size);
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    /**
     * 所有已揭晓或即将揭晓分页
     */
    public function all_history()
    {
        extract($this->cdata);
        $p_size = isset($p_size) ? $p_size : 30;
        $p_index = isset($p_index) ? $p_index : 1;
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_active_peroids('*',$where=array('iLotState !='=> Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iGoodsId !='=>163,'iActType'=>Lib_Constants::ACTIVE_TYPE_SYS), $order_by=array('iLotTime'=> 'DESC'), $p_index, $p_size);

        $this->render_result(Lib_Errors::SUCC,$list);
    }

    /**
     * 最后疯抢数据
     */
    public function crazy()
    {
        extract($this->cdata);
        $p_size = isset($p_size) ? $p_size : 30;
        $p_index = isset($p_index) ? $p_index : 1;
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_active_peroids('*',$where=array('iLotState'=> Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iGoodsId !='=>163,'iProcess >='=> 60,'iProcess <'=> 100,'iIsCurrent' => 1,'iActType'=>Lib_Constants::ACTIVE_TYPE_SYS), $order_by=array('iProcess'=>'DESC','iCreateTime'=> 'DESC'), $p_index, $p_size);

        $this->render_result(Lib_Errors::SUCC,$list);
    }


    /**
     * 滚动中奖消息
     */
    public function msg()
    {
        extract($this->cdata);
        $p_size = isset($p_size) ? $p_size : 30;
        $p_index = isset($p_index) ? $p_index : 1;

        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_active_peroids('sGoodsName,iActId,iPeroid,iLotTime,sWinnerCode,iWinnerUin,sWinnerNickname',$where=array('iLotState'=> Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iGoodsId !='=>163), $order_by=array('iLotTime'=>'DESC'), $p_index, $p_size);

        $this->render_result(Lib_Errors::SUCC,$list);
    }


    /**
     * 1元区/10元区/苹果专区
     */
    public function zone()
    {
        extract($this->cdata);

        $cls = empty($cls) ? '100' : $cls;
        $cls = !in_array($cls,array('100','1000','iphone')) ? '100' : $cls;
        $p_size = isset($p_size) ? $p_size : 6;
        $p_index = isset($p_index) ? $p_index : 1;

        $list = array();
        $this->load->model('active_peroid_model');
        switch($cls){
            case 100:
                $list = $this->active_peroid_model->get_active_peroids('iActId,iPeroid',array('iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iGoodsId !='=>163,'iIsCurrent'=>1,'where_in'=>array('iCodePrice',array($cls,0)),'iActType'=>Lib_Constants::ACTIVE_TYPE_SYS),$order_by=array('iRecWeight'=>'DESC','iProcess'=>'DESC','iCreateTime'=> 'DESC'), $p_index, $p_size);
                break;
            case 1000:
                $list = $this->active_peroid_model->get_active_peroids('iActId,iPeroid',array('iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iGoodsId !='=>163,'iIsCurrent'=>1,'iCodePrice'=>$cls,'iActType'=>Lib_Constants::ACTIVE_TYPE_SYS),$order_by=array('iRecWeight'=>'DESC','iProcess'=>'DESC','iCreateTime'=> 'DESC'), $p_index, $p_size);
                break;
            case 'iphone':
                $list = $this->active_peroid_model->get_active_peroids('iActId,iPeroid',array('iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT,'iGoodsId !='=>163,'iIsCurrent'=>1,'iActType'=>Lib_Constants::ACTIVE_TYPE_SYS,'like'=>array('sSearchKey'=>'苹果专区')),$order_by=array('iRecWeight'=>'DESC','iProcess'=>'DESC','iCreateTime'=> 'DESC'), $p_index, $p_size);
                break;
        }

        $this->render_result(Lib_Errors::SUCC,$list);
    }

    public function detail(){
        extract($this->cdata);

        if(empty($act_id) || empty($peroid)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_row(array('iActId'=>$act_id,'iPeroid'=>$peroid));

        $this->render_result(Lib_Errors::SUCC,$list);
    }

    //0元夺宝列表
    public function active_free()
    {
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_rows(array('iIsCurrent' => 1,'iCodePrice'=>0,'iLotState'=>Lib_Constants::ACTIVE_LOT_STATE_DEFAULT));
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    //当个活动的所有往期记录
    public function active_past()
    {
        extract($this->cdata);

        if(empty($act_id)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->get_rows(array('iActId'=>$act_id,'iIsCurrent' => 0));
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    public function active_winner()
    {
        extract($this->cdata);

        $page_size = empty($page_size) ? 30 : $page_size;
        $page_index = empty($page_index) ? 1 : intval($page_index);
        $this->load->model('active_peroid_model');
        //$list = $this->active_peroid_model->row_list('iActId,iPeroid',array('iLotState' => Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iIsCurrent' => 0),$order_by=array('iLotTime'=>'DESC'), $page_index, $page_size);
        $list = $this->active_peroid_model->row_list('iPeroidCode,iActId,iPeroid,iGoodsId,sGoodsName,sImg,iCodePrice,iLotTime,iLotState,sWinnerCode,sWinnerNickname,iWinnerUin,sWinnerHeadImg,iWinnerCount,sWinnerOrder,iLotNumA,iLotNumB',array('iLotState' => Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iIsCurrent' => 0),$order_by=array('iLotTime'=>'DESC'), $page_index, $page_size);
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    public function my_active_winner()
    {
        extract($this->cdata);
        if(empty($uin)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $page_size = empty($page_size) ? 30 : $page_size;
        $page_index = empty($page_index) ? 1 : intval($page_index);
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->row_list('iActId,iPeroid',array('iLotState' => Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iIsCurrent' => 0,'iWinnerUin'=>$uin),$order_by=array('iLotTime'=>'DESC'), $page_index, $page_size);
        //$list = $this->active_peroid_model->row_list('iActId,iPeroid,iGoodsId,sGoodsName,sImg,iCodePrice,iLotTime,iLotState,sWinnerCode,sWinnerNickname,iWinnerUin,sWinnerHeadImg,iWinnerCount,sWinnerOrder,iLotNumA,iLotNumB',array('iLotState' => Lib_Constants::ACTIVE_LOT_STATE_OPENED,'iIsCurrent' => 0,'iWinnerUin'=>$uin),$order_by=array('iLotTime'=>'DESC'), $page_index, $page_size);
        $this->render_result(Lib_Errors::SUCC,$list);
    }

    //活动搜索列表页
    public function search_lists()
    {
        extract($this->cdata);

        $where = $order_by = array();
        if(isset($keyword)){
            $where['like'] = array('sSearchKey' => $keyword);
        }
        if(isset($cls)){
            $where['iCateId_1'] = intval($cls);
        }
        if(isset($crazy)){
            $where['iProcess >='] = Lib_Constants::ACTIVE_IS_CRAZY;
        }
        if(isset($history)){
            $where['iLotState !='] = Lib_Constants::ACTIVE_LOT_STATE_DEFAULT;
        }else{
            $where['iLotState'] = Lib_Constants::ACTIVE_LOT_STATE_DEFAULT;
        }
        $where['iActType'] = Lib_Constants::ACTIVE_TYPE_SYS;
        $where['iGoodsId !='] = 163;

        $orderby = isset($orderby) ? $orderby : 'review';
        $ordertype = isset($ordertype) && in_array($ordertype,array('desc','asc')) ? $ordertype : 'asc';
        switch($orderby){
            case 'new':
                $order_by['iCreateTime'] = $ordertype;
                break;
            case 'progress':
                $order_by['iProcess'] = $ordertype;
                break;
            case 'price':
                $order_by['iCodePrice'] = $ordertype;
                break;
            case 'lotCount':
                $order_by['iLotCount'] = $ordertype;
                break;
            default:
                $order_by['iRecWeight'] = $ordertype;
                $order_by['iCreateTime'] = 'desc';
        }
        $page_index = !empty($p_index) ? intval($p_index) : 1;
        $page_size = !empty($p_size) ? intval($p_size) : 30;
        $this->load->model('active_peroid_model');
        if($list = $this->active_peroid_model->row_list('iCodePrice,sImg,sGoodsName,iActId,iPeroid,sGoodsName,iGoodsId,sImg,iCodePrice,iProcess,iSoldCount,iIsCurrent,iCornerMark,iLotState,sWinnerNickname,iLotTime',$where,$order_by,$page_index,$page_size)){
            $list['sql'] = $this->active_peroid_model->db->last_query();
            $list['where'] = $where;
            $this->render_result(Lib_Errors::SUCC,$list);
        }else{
            $this->render_result(Lib_Errors::SVR_ERR);
        }
    }


    /**
     * 返回当前未完成的活动
     */
    public function currect_active_list()
    {
        extract($this->cdata);

        $in_str = isset($in_str) ? $in_str : 0;
        $this->load->model('active_peroid_model');
        $list = $this->active_peroid_model->currect_active_list($in_str);
        //$this->log->info('kkk',$this->active_peroid_model->db->last_query());

        $this->render_result(Lib_Errors::SUCC,$list);
    }


    public function get_active_config()
    {
        extract($this->cdata);

        $in_str = isset($in_str) ? $in_str : 0;
        $this->load->model('active_config_model');
        $list = $this->active_config_model->get_active_config($in_str);

        $this->render_result(Lib_Errors::SUCC,$list);
    }
}