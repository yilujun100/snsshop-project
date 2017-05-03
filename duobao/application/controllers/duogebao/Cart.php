<?php


class Cart extends Duogebao_Base
{
    protected $need_login_methods = array('ajax_add','del','lists','index');

    public function __construct()
    {
        parent::__construct();
        $this->assign('menus_active_index', 4);
    }


    public function index()
    {
        $this->lists();
    }

    public function ajax_add()
    {
        $peroid_str = $this->get_post('peroid_str');

        if(empty($peroid_str)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);

        //检查夺宝单是否存在
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        if($detail['retCode'] != 0){
            $this->log->error('Cart','not fund active detail | peroid['.$peroid_str.']');
            redirect('/');
        }
        $detail = $detail['retData'];

        $result = $this->get_api('add_cart',array('uin'=>$this->user['uin'],'goods_id'=>$detail['iGoodsId'],'act_id'=>$act_id));
        if($result['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result(Lib_Errors::CART_SVR_ERR);
        }
    }

    public function del()
    {
        $peroid_str = $this->get_post('peroid_str');

        if(empty($peroid_str)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        $ids = array();
        if(is_array($peroid_str)){
            foreach($peroid_str as $str){
                list($act_id,$peroid) = period_code_decode($str);
                $ids[] = $act_id;
            }
        }else{
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $ids[] = $act_id;
        }
        $act_ids = implode(',',$ids);


        $result = $this->get_api('del_carts',array('uin'=>$this->user['uin'],'act_ids'=>$act_ids));
        if($result['retCode'] == Lib_Errors::SUCC){
            $this->render_result(Lib_Errors::SUCC);
        }else{
            $this->render_result($result['retCode'],$result['retData']);
        }
    }



    public function lists()
    {
        $list = $this->get_api('my_cart',array('uin'=>$this->user['uin']));
        if($list['retCode'] != 0){
            $list = array('list'=>array());
        }else{
            $list = $list['retData'];
        }

        $active = $cart_list = array();
        foreach($list['list'] as $li){
            $active[$li['iActId']] = $li['iActId'];
            $cart_list[$li['iActId']] = $li;
        }
        $where_in = implode(',',$active);
        //获取有效的当期夺宝活动
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$where_in));
        if($active_list['retCode'] == 0){
            $active_list = is_array($active_list['retData']) ? $active_list['retData'] : array();
            foreach($active_list as $li){
                unset($active[$li['iActId']]);
            }
        }else{
            $active_list = array();
        }

        //获取失效的夺宝活动
        if(count($active) > 0){
            $active_block_list = $this->get_api('active_config',array('in_str'=>implode(',',$active)));
        }
        $active_block_list = !empty($active_block_list['retData']) ? $active_block_list['retData'] : array();

        //判断失效的夺宝活动是否有新一期,如有有则new_periods为新一期的期号,没有则为0
        foreach($active_block_list as $k => $block_list)
        {
            $block_active_list = $this->get_api('active_currect_list',array('in_str'=>$block_list['iActId']));
            if($block_active_list['retCode'] == 0 && !empty($block_active_list['retData']))
            {
                $active_block_list[$k]['new_periods'] = $block_active_list['retData'][0]['iPeroid'];
            }
            else
            {
                $active_block_list[$k]['new_periods'] = 0;
            }
        }


        $this->assign('active_block_list',$active_block_list);
        $this->assign('cart_list',$cart_list);

        $this->render(array('list'=>$active_list),'cart/list.php');
    }
}