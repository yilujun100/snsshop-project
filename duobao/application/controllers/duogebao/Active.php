<?php


class Active extends Duogebao_Base
{
    const ORDER_RESULT_AD_POSITION = 3;
    protected $need_login_methods = array('detail','active_buy','collect_buy','cart_buy','ajax_order','ajax_multi_order','order_callback','goods_detail');

    public function __construct()
    {
        parent::__construct();
    }

    //夺宝活动列表页
    public function lists()
    {
        //商品分类
        $goods_cate = $this->get_api('goods_cate');
        $this->assign('goods_cate',empty($goods_cate['retData']) ? array() : $goods_cate['retData']);

        $keyword = $this->get_post('keyword');
        $listKeywords = $this->get_post('listKeywords');
        $keyword = empty($listKeywords)?$keyword:$listKeywords;
        $cls = $this->get_post('cls');
        $crazy = $this->get_post('crazy');
        $history = $this->get_post('history');
        $orderby = $this->get_post('orderby','review');
        $ordertype = $this->get_post('ordertype','asc');
        $p_index = $this->get_post('p_index',1);
        $where = array();
        if(!empty($keyword)){
            $where['keyword'] = $keyword;
        }
        if(!empty($cls)){
            $where['cls'] = $cls;
        }
        if(!empty($crazy)){
            $where['crazy'] = $crazy;
        }
        if(!empty($history)){
            $where['history'] = intval($history);
        }
        $where['orderby'] = $orderby;
        $where['ordertype'] = $ordertype;
        $where['p_index'] = $p_index;
        $where['p_size'] = 10;

        $list = $this->get_api('active_search',$where);
        $list = $list['retCode'] == 0 ? $list['retData'] : array();
        $this->assign('where',$where);
        $this->render(array('list'=>$list),'active/active_list');
    }

    public function ajax_lists()
    {
        //商品分类
        $goods_cate = $this->get_api('goods_cate');
        $this->assign('goods_cate',empty($goods_cate['retData']) ? array() : $goods_cate['retData']);

        $keyword = $this->get_post('keyword');
        $cls = $this->get_post('cls');
        $crazy = $this->get_post('crazy');
        $history = $this->get_post('history');
        $orderby = $this->get_post('orderby','review');
        $ordertype = $this->get_post('ordertype','asc');
        $p_index = $this->get_post('p_index',1);

        $where = array();
        if(!empty($keyword)){
            $where['keyword'] = $keyword;
        }
        if(!empty($cls)){
            $where['cls'] = $cls;
        }
        if(!empty($crazy)){
            $where['crazy'] = $crazy;
        }
        if(!empty($history)){
            $where['history'] = intval($history);
        }
        $where['orderby'] = $orderby;
        $where['ordertype'] = $ordertype;
        $where['p_index'] = $p_index;
        $where['p_size'] = 10;

        $list = $this->get_api('active_search',$where);

        if($list['retCode'] == 0)
        {
            $list['retData']['index_where'] = $where;
        }
        $list = $list['retCode'] == 0 ? $list['retData'] : array();
        $this->assign('where',$where);
        if(!empty($list))
        {
            foreach($list['list'] as &$li){
                $li['url'] = gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])));
//                $li['buy_url'] = gen_uri('/active/active_buy',array('peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])));
                $li['buy_url'] = gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])),'payment');
                $li['peroid_str'] = period_code_encode($li['iActId'],$li['iPeroid']);
            }
        }

        $this->render_result(Lib_Errors::SUCC,$list);
    }

    //夺宝活动详情
    public function detail()
    {
        $id = $this->get_post('id');
        $share = $this->get_post('share',0);
        $is_current_peroid = $this->get_post('current_peroid',0);
        if(empty($id) || !$peroid_arr = period_code_decode($id)){
            show_error('夺宝活动参数错误！');
        }

        list($act_id,$peroid) = $peroid_arr;

        if(!empty($is_current_peroid)){
            $detail = $this->get_api('current_peroid',array('act_id'=>$act_id));
        }

        if(isset($detail['retCode']) && $detail['retCode'] != 0){
            $this->log->error('Active','not fund active detail | peroid['.$id.']');
            show_error('夺宝活动参数错误！');
        }
        if(!isset($detail) || empty($detail['retData'])){
            $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        }
        $detail = $detail['retData'];
        $peroid = $detail['iPeroid'];
        if(empty($detail)){
            show_error('夺宝活动不存在！');
        }
        $this->assign('peroid_str',period_code_encode($act_id,$peroid));
        $this->assign('menus_show',false);
        $this->assign('share',$share);

        if (Lib_Constants::ACTIVE_TYPE_CUSTOM == $detail['iActType']) {
            $this->load->model('user_model');
            $user = $this->user_model->get_user_by_uin($detail['iInitiator']);
            $this->set_wx_share('active_custom', array_merge($detail, array('user'=>$user)));
        } else {
            $this->set_wx_share('active', $detail);
        }
        switch($detail['iLotState']){
            case Lib_Constants::ACTIVE_LOT_STATE_GOING:
                $summary = $this->get_api('summary_list',array('act_id'=>$act_id,'peroid'=>$peroid));
                $summary = $summary['retCode']==0?$summary['retData']: array();
                $my_code = array();
                $order_summary = $this->get_api('get_summary_order',array('act_id'=>$act_id,'peroid'=>$peroid,'uin'=>$this->user['uin']));
                $order_summary = $order_summary['retCode'] == Lib_Errors::SUCC ? $order_summary['retData'] : array();
                foreach($order_summary as $val){
                    if($val['iUin'] == $this->user['uin']){
                        $json_code = json_decode($val['sLuckyCodes']);
                        $my_code = array_merge($my_code,is_array($json_code) ? $json_code : array());
                    }
                }
                sort($my_code);
                $this->assign('my_code',$my_code);
                $this->assign('summary',isset($summary['list'])?$summary['list']:array());
                $this->render(array('detail'=>$detail),'active/detail_going');
                break;

            case Lib_Constants::ACTIVE_LOT_STATE_OPENED:
                $summary = $this->get_api('summary_list',array('act_id'=>$act_id,'peroid'=>$peroid));
                $summary = $summary['retCode']==0?$summary['retData']: array();
                $my_code = array();
                $order_summary = $this->get_api('get_summary_order',array('act_id'=>$act_id,'peroid'=>$peroid,'uin'=>$this->user['uin']));
                $order_summary = $order_summary['retCode'] == Lib_Errors::SUCC ? $order_summary['retData'] : array();
                foreach($order_summary as $val){
                    if($val['iUin'] == $this->user['uin']){
                        $json_code = json_decode($val['sLuckyCodes']);
                        $my_code = array_merge($my_code,is_array($json_code) ? $json_code : array());
                    }
                }
                sort($my_code);
                $this->assign('is_winner',$this->user['uin'] == $detail['iWinnerUin']);
                $this->assign('summary',isset($summary['list'])?$summary['list']:array());
                $this->assign('my_code',$my_code);
                $this->render(array('detail'=>$detail),'active/detail_opened');
                break;
            default:
                $summary = $this->get_api('summary_list',array('act_id'=>$act_id,'peroid'=>$peroid));
                $summary = $summary['retCode']==0?$summary['retData']: array();
                $my_code = array();
                $order_summary = $this->get_api('get_summary_order',array('act_id'=>$act_id,'peroid'=>$peroid,'uin'=>$this->user['uin']));
                $order_summary = $order_summary['retCode'] == Lib_Errors::SUCC ? $order_summary['retData'] : array();
                foreach($order_summary as $val){
                    if($val['iUin'] == $this->user['uin']){
                        $json_code = json_decode($val['sLuckyCodes']);
                        $my_code = array_merge($my_code,is_array($json_code) ? $json_code : array());
                    }
                }
                sort($my_code);
                $this->assign('my_code',$my_code);
                $this->assign('summary',isset($summary['list'])?$summary['list']:array());
                $this->render(array('detail'=>$detail),'active/detail_default');
        }
    }

    /**
     * 详情里的购买记录
     */
    public function ajax_summary_list()
    {
        $id = $this->get_post('id');
        $page_index = $this->get_post('p_index',2);
        $page_size = $this->get_post('p_size',20);

        list($act_id,$peroid) = period_code_decode($id);
        $summary = $this->get_api('summary_list',array('act_id'=>$act_id,'peroid'=>$peroid,'page_index'=>$page_index,'page_size'=>$page_size));
        $summary = $summary['retCode']==0?$summary['retData']: array();
        foreach($summary['list'] as &$li){
            $li['iIP'] = !empty($li['iIP']) && is_numeric($li['iIP']) ? long2ip($li['iIP']) : $li['iIP'];
        }

        $this->render_result(Lib_Errors::SUCC,$summary);
    }


    public function active_winner()
    {
        $page_index = $this->get_post('p_index',1);
        $page_size = $this->get_post('p_size',20);

        $list = $this->get_api('active_winner',array('page_index'=>$page_index,'page_size'=>$page_size));
        if($list['retCode'] == Lib_Errors::SUCC){
            $list = $list['retData']['list'];
        }else{
            $list = array();
        }

        if($this->input->is_ajax_request()){
            foreach($list as &$val){
                $val['iLotDate'] = date('Y-m-d H:i:s',$val['iLotTime']);
            }
            $this->render_result(Lib_Errors::SUCC,$list);
        }
        $this->render(array('list'=>$list),'active/active_winner');
    }


    //购买夺宝活动
    public function active_buy()
    {
        $peroid_str = $this->get_post('peroid_str');
        $back_url = $this->get_post('back_url',$this->config->item('pay_result_url'));
        if(empty($peroid_str)){
            show_error('夺宝活动参数错误！');
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        if($detail['retCode'] != 0){
            $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
            show_error('查询夺宝活动失败！');
        }
        $detail = $detail['retData'];

        //判断是否可购买
        if($detail['iIsCurrent'] != Lib_Constants::ACTIVE_CURRENT_PEROID || $detail['iLotState'] != Lib_Constants::ACTIVE_STATE_DEFAULT || $detail['iProcess'] == 100){
            $this->log->notice('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
            $this->assign('stock_out',1);
        }else{
            $this->assign('stock_out',0);
        }
        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('peroid_str',$peroid_str);
        $this->assign('stock',$detail['iLotCount']-$detail['iSoldCount']);
        $this->assign('detail',$detail);

        //用户夺宝券
        /*$coupon = $this->get_api('user_ext_info',array('uin'=>$this->user['uin']));
        $this->assign('coupon',$coupon['retCode'] == 0 ? $coupon['retData']['coupon'] : 0);*/

        $this->assign('order_callback',$back_url);
        $this->render(array(),'active/active_buy');
    }


    //兑换
    public function active_exchange()
    {
        $peroid_str = $this->get_post('peroid_str');
        if(empty($peroid_str)){
            show_error('夺宝活动参数错误！');
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);
        $detail = $this->get_api('active_config',array('in_str'=>$act_id));
        if($detail['retCode'] != 0 || empty($detail['retData'][0])){
            $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
            show_error('夺宝活动配置失败！');
        }

        $detail = $detail['retData'][0];

        //判断是否可购买
        $stock = $detail['iLotCount'];
        if($detail['iState'] != Lib_Constants::PUBLISH_STATE_ONLINE){
            $stock = 0;
        }
        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('peroid_str',$peroid_str);
        $this->assign('stock',$stock);
        $this->assign('detail',$detail);

        $this->assign('order_callback',$this->config->item('pay_result_url'));
        $this->render(array(),'active/active_exchange');
    }

    //购买收藏夺宝活动
    public function collect_buy()
    {
        $in_str = $this->get_post('fav');
        if(empty($in_str)){
            show_error('夺宝活动参数错误！');
        }
        $in_str = implode(',',$in_str);

        //查询所对应的夺宝单
        $total = 0;
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$in_str));
        foreach($active_list['retData'] as $li){
            if($li['iProcess'] == 100 || $li['iLotState'] != Lib_Constants::ACTIVE_STATE_DEFAULT){
                $this->log->error('Active','active is nvalid | active['.json_encode($active_list).'] | '.__METHOD__);
                show_error('查询失败！');
            }
            $total += $li['iCodePrice'];
        }

        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('total',$total);
        $this->assign('order_callback',$this->config->item('pay_result_url'));
        $this->render(array('active'=>$active_list['retData']),'active/collect_buy');
    }

    //购买购物车物品
    public function cart_buy()
    {
        $api_ret = $this->get_api('user_ext_info', array('uin' => $this->user['uin']));
        if ($api_ret['retCode'] == Lib_Errors::SUCC) {
            $user_ext = $api_ret['retData'];
        } else {
            $user_ext = array();
        }
        $this->assign('user_ext', $user_ext);
        $this->assign('user', $this->user);

        $active_arr = $this->get_post('peroid_str');
        if(empty($active_arr)){
            show_error('参数错误！');
        }
        $active = $data = $qty_arr = array();
        foreach($active_arr as $key => $peroid_str){
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $active[$act_id] = $peroid;
            $qty_arr[$act_id] = $this->get_post($peroid_str.'_num');
        }

        //查询所对应的夺宝单
        $total = 0;
        $in_str = implode(',',array_keys($active));
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$in_str));
        $active_list = $active_list['retData'];
        $check = true;
        foreach($active_list as $val){
            if(!isset($active[$val['iActId']]) || $val['iPeroid'] != $active[$val['iActId']]){
                $check = false;
            }
            $total += $qty_arr[$val['iActId']]*$val['iCodePrice'];
        }
        if(count($active) != count($active_list) || !$check){
            $this->log->error('Active',Lib_Errors::ACTIVE_OVER_TIME.' | active['.json_encode($active).'] | active_list['.json_encode($active_list).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $this->config->load('pay');
        $this->assign('pay_url',$this->config->item('pay_url'));
        $this->assign('total',$total);
        $this->assign('qty_arr',$qty_arr);
        $this->assign('order_callback',$this->config->item('pay_result_url'));
        $this->render(array('active'=>$active_list),'active/cart_buy');
    }

    //订单回调显示
    public function order_callback()
    {
        $order_id = $this->get_post('order_id');
        $return_code = $this->get_post('return_code');
        $pay_stata = $this->get_post('pay_stata');

        $order = $this->get_api('order_detail',array('uin'=>$this->user['uin'],'order_id'=>$order_id));
        if($order['retCode'] != 0){
            $this->log->notice('Active','order not find | params['.json_encode($_GET).'] | '.__METHOD__);
        }
        $active_order = $order['retData']['active_order'];
        $merage_order = $order['retData']['merage_order'];
        //$detail = $this->get_api('active_detail',array('act_id'=>$order['iActId'],'peroid'=>$order['iActId']));

        $banner_advert = $this->get_api('ad_list', array('position_id'=>self::ORDER_RESULT_AD_POSITION));
        $this->assign('banner_advert',empty($banner_advert['retData']) ? array() : $banner_advert['retData']);

        $recommend = $this->get_api('active_search',array('p_size'=>3));
        $this->assign('recommend',$recommend['retCode'] == 0 ? $recommend['retData'] : array());

        $this->render(array('active_order'=>$active_order,'merage_order'=>$merage_order),'active/order_callback');
    }

    //创建多小个订单,即合并提交
    public function ajax_multi_order()
    {
        $total = $this->get_post('total');
        $qty = $this->get_post('qty');
        $active_arr = $this->get_post('active_arr');
        $is_cart = $this->get_post('cart',0);

        if(empty($active_arr) || empty($total)){
            $this->log->error('Active',Lib_Errors::PARAMETER_ERR.' | params: '.json_encode(array('total'=>$total,'qty'=>$qty,'active_arr'=>$active_arr)).' | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $active = $data = $qty_arr = array();
        foreach($active_arr as $key => $peroid_str){
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $active[$act_id] = $peroid;
            $qty_arr[$act_id] = !empty($qty) ? $qty[$key] : $this->get_post($peroid_str.'_num');
        }

        //检查夺宝单参数
        $in_str = implode(',',array_keys($active));
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$in_str));
        $active_list = $active_list['retData'];
        $check = true;
        foreach($active_list as $val){
            if(!isset($active[$val['iActId']]) || $val['iPeroid'] != $active[$val['iActId']]){
                $check = false;
            }
            $data[] = array(
                'act_id' => $val['iActId'],
                'goods_id' => $val['iGoodsId'],
                'peroid' => $val['iPeroid'],
                'count' => $qty_arr[$val['iActId']]
            );
        }
        if(count($active) != count($active_list) || !$check){
            $this->log->error('Active',Lib_Errors::ACTIVE_OVER_TIME.' | active['.json_encode($active).'] | active_list['.json_encode($active_list).'] | '.__METHOD__);
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $order = $this->get_api('create_active_order',array(
            'multi' => 1,
            'actives' => $data,
            'total' => $total,
            'uin'=>$this->user['uin'],'act_type'=>Lib_Constants::ACTIVE_TYPE_SYS,'buy_type'=>Lib_Constants::ORDER_TYPE_ACTIVE));
        if(isset($order['retCode']) && $order['retCode'] == 0){
            //如果是购物车，则清空
            if(!empty($is_cart)){
                $this->get_api('del_carts',array('act_ids'=>implode(',',array_keys($active)),'uin'=>$this->user['uin']));
            }
            $disabled = false;
            $pay_disabled = false;
            $this->render_result(Lib_Errors::SUCC,array('order_id'=>$order['retData']['order_id'],'pay_coupon'=>$total/Lib_Constants::COUPON_UNIT_PRICE,'disabled'=>$disabled,'pay_disabled'=>$pay_disabled,'payagent'=>Lib_Constants::ORDER_PAY_TYPE_WX));
        }else{
            $this->log->notice('Active','create active order fail | return['.json_encode($order).'] | '.__METHOD__);
            $this->render_result(isset($order['retCode']) ? $order['retCode'] : Lib_Errors::SVR_ERR);
        }
    }


    //购买车检查夺宝活动参数
    public function ajax_check_active()
    {
        $active_arr = $this->get_post('peroid_str');
        if(empty($active_arr)){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }

        $active = $data = $qty_arr = array();
        foreach($active_arr as $key => $peroid_str){
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $active[$act_id] = $peroid;
            $qty_arr[$act_id] = !empty($qty) ? $qty[$key] : $this->get_post($peroid_str.'_num');
        }

        //检查夺宝单参数
        $err_data = array();
        $in_str = implode(',',array_keys($active));
        $active_list = $this->get_api('active_currect_list',array('in_str'=>$in_str));
        $active_list = $active_list['retCode'] == Lib_Errors::SUCC ? $active_list['retData'] : array();
        $check = true;
        foreach($active_list as $val){
            if(!isset($active[$val['iActId']]) || $val['iPeroid'] != $active[$val['iActId']]){
                $check = false;
                $err_data[] = array('id'=>period_code_encode($val['iActId'],$val['iPeroid']),'msg'=>Lib_Errors::get_error(Lib_Errors::ACTIVE_OVER_TIME));
            }elseif($val['iLotCount'] - $val['iSoldCount'] < $qty_arr[$val['iActId']]){
                $check = false;
                $err_data[] = array('id'=>period_code_encode($val['iActId'],$val['iPeroid']),'msg'=>Lib_Errors::get_error(Lib_Errors::ACTIVE_NOT_STOCK));
            }elseif($qty_arr[$val['iActId']] > $val['iBuyCount']){
                $check = false;
                $err_data[] = array('id'=>period_code_encode($val['iActId'],$val['iPeroid']),'msg'=>Lib_Errors::get_error(Lib_Errors::ACTIVE_USER_TIME_UPPER));
            }
        }
        if(count($active) != count($active_list)){
            $check = false;
            foreach($active as $act_id=>$peroid){
                if(!isset($active_list[$act_id])){
                    $err_data[] = array('id'=>period_code_encode($act_id,$peroid),'msg'=>Lib_Errors::get_error(Lib_Errors::ACTIVE_OUTLINE));
                }
            }
        }


        if(!$check){
            $this->render_result(Lib_Errors::PARAMETER_ERR,$err_data);
        }

        $this->render_result(Lib_Errors::SUCC);
    }

    //创建订单
//    public function ajax_order(){
//        $peroid_str = $this->get_post('peroid_str');
//        $total = $this->get_post('total');
//        $price = $this->get_post('price');
//        $qty = $this->get_post('qty');
//        $type = $this->get_post('type');  ///区别兑换或者购买
//
//        if(empty($peroid_str) || empty($qty) || (!empty($total) && empty($price))){
//            $this->render_result(Lib_Errors::PARAMETER_ERR);
//        }
//        list($act_id,$peroid) = period_code_decode($peroid_str);
//
//        if(empty($type)){
//            $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
//            if($detail['retCode'] != 0){
//                $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
//                $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
//            }
//            $detail = $detail['retData'];
//
//            if($detail['iIsCurrent'] != Lib_Constants::ACTIVE_CURRENT_PEROID || $detail['iLotState'] != Lib_Constants::ACTIVE_STATE_DEFAULT || $detail['iProcess'] >= 100){
//                $this->log->error('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
//                $this->render_result(Lib_Errors::ACTIVE_NOT_STOCK);
//            }
//        }else{
//            $detail = $this->get_api('active_config',array('in_str'=>$act_id));
//            if($detail['retCode'] != 0 || empty($detail['retData'][0])){
//                $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
//                $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
//            }
//            $detail = $detail['retData'][0];
//            if($detail['iState'] != Lib_Constants::ACTIVE_STATE_ONLINE){
//                $this->log->error('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
//                $this->render_result(Lib_Errors::ACTIVE_NOT_STOCK);
//            }
//        }
//
//        $type = empty($type) ? Lib_Constants::ORDER_TYPE_ACTIVE : Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE;
//        $order = $this->get_api('create_active_order',array('act_id'=>$act_id,'peroid'=>$peroid,'goods_id'=>$detail['iGoodsId'],'qty'=>$qty,'total'=>$total,'uin'=>$this->user['uin'],'act_type'=>Lib_Constants::ACTIVE_TYPE_SYS,'buy_type'=>$type,'share_uin' => get_cookie('share_u')));
//        if(isset($order['retCode']) && $order['retCode'] == 0){
//            $this->render_result(Lib_Errors::SUCC,$order['retData']);
//        }else{
//            $this->log->notice('Active','create active order fail | return['.json_encode($order).'] | '.__METHOD__);
//            $this->render_result(isset($order['retCode']) ? $order['retCode'] : Lib_Errors::SVR_ERR);
//        }
//    }

    //创建订单
    public function ajax_order(){
        $peroid_str = $this->get_post('peroid_str');
        $price = $this->get_post('price');
        $qty = $this->get_post('qty');
        $total = $price*$qty;
        $type = $this->get_post('type');  ///区别兑换或者购买

        if(empty($peroid_str) || empty($qty) || (!empty($total) && empty($price))){
            $this->render_result(Lib_Errors::PARAMETER_ERR);
        }
        list($act_id,$peroid) = period_code_decode($peroid_str);

        if(empty($type)){
            $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
            if($detail['retCode'] != 0){
                $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
                $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
            }
            $detail = $detail['retData'];

            if($detail['iIsCurrent'] != Lib_Constants::ACTIVE_CURRENT_PEROID || $detail['iLotState'] != Lib_Constants::ACTIVE_STATE_DEFAULT || $detail['iProcess'] >= 100){
                $this->log->error('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
                $this->render_result(Lib_Errors::ACTIVE_NOT_STOCK);
            }
        }else{
            $detail = $this->get_api('active_config',array('in_str'=>$act_id));
            if($detail['retCode'] != 0 || empty($detail['retData'][0])){
                $this->log->error('Active','not fund active detail | peroid['.$peroid_str.'] | '.__METHOD__);
                $this->render_result(Lib_Errors::ACTIVE_NOT_FOUND);
            }
            $detail = $detail['retData'][0];
            if($detail['iState'] != Lib_Constants::ACTIVE_STATE_ONLINE){
                $this->log->error('Active','active under stock | peroid['.$peroid_str.'] | '.__METHOD__);
                $this->render_result(Lib_Errors::ACTIVE_NOT_STOCK);
            }
        }

        $type = empty($type) ? Lib_Constants::ORDER_TYPE_ACTIVE : Lib_Constants::ORDER_TYPE_ACTIVE_EXCHANGE;
        $order_api = $this->get_api('create_active_order',array('act_id'=>$act_id,'peroid'=>$peroid,'goods_id'=>$detail['iGoodsId'],'qty'=>$qty,'total'=>$total,'uin'=>$this->user['uin'],'act_type'=>Lib_Constants::ACTIVE_TYPE_SYS,'buy_type'=>$type,'share_uin' => get_cookie('share_u')));

        if(isset($order_api['retCode']) && $order_api['retCode'] == 0){

            if($order_api['retData']['is_paid'] == 1)
            {
//                $this->config->load('pay');
                $order_api['retData']['pay_redirect'] = gen_uri('/free/result',array('peroid_str'=>$peroid_str,'share_uid'=>$this->user['uin']));
                $this->render_result(Lib_Errors::SUCC,$order_api['retData']);
            }
            else
            {

                $back_url = $this->get_post('back_url',$this->config->item('pay_result_url'));
                $pay_redirect = empty($back_url)?$this->config->item('pay_redirect'):$back_url;
                $order_info = array(
                    'order_id'  =>  $order_api['retData']['order_id'],
                    'callback_url'  =>  $pay_redirect,
                    'pay_agent' =>  Lib_Constants::ORDER_PAY_TYPE_WX
                );
                $this->load->service('pay_service');
                $api_result = $this->pay_service->skip_order_pay($order_info);
                if($api_result['type'] == 'output' || $api_result['type'] == 'render_result' || $api_result['type'] == 'render')
                {

                    $this->render_result($api_result['code'],$api_result['error_msg']);
                }
            }
//            $this->render_result(Lib_Errors::SUCC,$order['retData']);
        }else{
            $this->log->notice('Active','create active order fail | return['.json_encode($order_api).'] | '.__METHOD__);
            $this->render_result(isset($order_api['retCode']) ? $order_api['retCode'] : Lib_Errors::SVR_ERR);
        }
    }




    //往期揭晓
    public function active_past()
    {
        $peroid_str = $this->get_post('peroid_str');
        if(empty($peroid_str)){
            show_error('参数错误！');
        }

        list($act_id,$peroid) = period_code_decode($peroid_str);
        $list = $this->get_api('active_past',array('act_id'=>$act_id));
        if($list['retCode'] != 0){
            $this->log->error('Active','not fund active detail | peroid['.$peroid_str.']');
            show_error('查询失败！');
        }

        $this->render(array('list'=>$list['retData']),'active/active_past');
    }

    //图文详情
    public function goods_detail()
    {
        $goods_id = $this->get_post('goods_id');
        $act_id = $this->get_post('act_id',1);
        $peroid_str = $this->get_post('peroid_str');
        if(empty($goods_id)){
            show_error('参数错误！');
        }

        $detail = $this->get_api('goods_detail',array('goods_id'=>$goods_id));
        if($detail['retCode'] != 0){
            show_error('查询失败！');
        }
        $this->assign('detail',$detail['retCode'] == 0 ? $detail['retData'] : array());
        $this->assign('act_id',$act_id);
        $this->assign('menus_show',false);
        $this->assign('peroid_str',$peroid_str);

        $this->render(array(),'active/goods_detail');
    }


    //计算详情
    public function calc_detail()
    {
        $peroid_str = $this->get_post('peroid_str');
        if(empty($peroid_str)){
            show_error('参数错误！');
        }

        list($act_id,$peroid) = period_code_decode($peroid_str);
        $detail = $this->get_api('active_detail',array('act_id'=>$act_id,'peroid'=>$peroid));
        if($detail['retCode'] != 0){
            $this->log->error('Active','not fund active detail | peroid['.$peroid_str.']');
            show_error('查询失败！');
        }
        $detail = $detail['retData'];
        $detail['sLotBasis'] = json_decode($detail['sLotBasis']) == null ? array() : json_decode($detail['sLotBasis']);
        $this->assign('peroid_str',$peroid_str);
        $this->assign('detail',$detail);
        $this->assign('show',$detail['iLotTime'] > time() || empty($detail['iLotTime']) || $detail['iLotState'] != Lib_Constants::ACTIVE_LOT_STATE_OPENED ? 'false' : 'true');

        $this->render(array(),'active/calc_detail');
    }
}