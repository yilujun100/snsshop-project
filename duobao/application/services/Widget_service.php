<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 组件服务
 *
 * Class Widget_service
 */
class Widget_service extends  MY_Service
{
    /**
     * 调用组件时传入的数据
     *
     * @var array
     */
    private $data;

    /**
     * Widget_service constructor.
     *
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * 活动详情右边图标显示组件
     * @return array
     */
    public function right_icon()
    {
        $peroid_str = $this->data['peroid_str'];
        $act_id = isset($this->data['act_id']) ? $this->data['act_id'] : '';
        $peroid = isset($this->data['peroid']) ? $this->data['peroid'] : '';
        if(!empty($peroid_str)){
            list($act_id,$peroid) = period_code_decode($peroid_str);
        }
        if(empty($act_id)){
            return array();
        }


        $collect = $this->get_api('get_collect',array('act_id'=>$act_id,'uin'=>$this->user['uin']));
        $data['collect'] = $collect['retData'];
        if($peroid_str) {
            $data['peroid_str'] = $peroid_str;
            $data['act_id'] = $act_id;
            $data['peroid'] = $peroid;
        }
        return $data;
    }


    /**
     * 活动详情下边加下购买车组件
     * @return array
     */
    public function cart_bottom()
    {
        $peroid_str = $this->data['peroid_str'];
        if(!empty($peroid_str)){
            list($act_id,$peroid) = period_code_decode($peroid_str);
            $detail = $this->get_api('current_peroid',array('act_id'=>$act_id));
            if($detail['retCode'] == Lib_Errors::SUCC){
                $detail = $detail['retData'];
            }else{
                $detail = array();
            }
        }
        $detail = !isset($detail) || empty($detail) ? array() : $detail;

        $collect = $this->get_api('my_cart_count',array('uin'=>$this->user['uin']));
        $detail['collect'] = $collect['retCode'] == Lib_Errors::SUCC ? $collect['retData'] : 0;
        $detail['peroid'] = $peroid;

        return $detail;
    }

    //菜单组件
    public function menus()
    {
        //获取用户缓存信息
        $this->get_user();

        //如果用户没有登陆,即获取cookie
        if(empty($this->user)){
            $cart_num = 0;
        }else{
           $result =  $this->get_api('my_cart_count',array('uin'=>$this->user['uin']));
           $cart_num =  $result['retCode'] == Lib_Errors::SUCC ? $result['retData'] : 0;
        }

        return array('cart_num'=>$cart_num);
    }

    private function get_user()
    {
        $this->load->service('user_service');
        //校验登陆
        if($uin = $this->user_service->valid_user_login()) {
            $api_ret = $this->get_api('user_base_info', array('uin' => $uin));
            if ($api_ret['retCode'] == Lib_Errors::SUCC && !empty($api_ret['retData'])) {
                $this->user = $api_ret['retData'];
            }
        }
        return $this->user;
    }

    public function diy_menus()
    {
        $data = array(
            'base_node' => array(
                array('node_name'=>'首页', 'node_url'=>node_url('/home/index'),'node_class'=>'icon-entry icon-home'),
                array('node_name'=>'订单', 'node_url'=>node_url('/order/index'),'node_class'=>'icon-entry icon-order')
            )
        );
        $extend = array();
        $groupon_diy = $this->assign('groupon_diy');
        $groupon_detail = $this->assign('groupon_detail');
        $is_my = $this->assign('is_my'); //本人
        $is_detail = $this->assign('is_detail'); //拼团活动详情页

        if (!empty($groupon_detail)) {
            $groupon_spec = $groupon_detail['groupon_spec'];
            if (!empty($is_detail) ) { //拼团活动详情页
                $left_stock = $groupon_detail['iStock'] - $groupon_detail['iSoldCount'];
                if ($left_stock > 0) { //有库存
                    $extend[] = array(
                        'node_name'=>'<em>¥'.price_format($groupon_detail['iPrice']).'</em><span>1人任性买</span>',
                        'node_class'=>'btn-buy-single',
                        'node_url'=>gen_uri('/pay/cashier', array('order_type'=>Lib_Constants::ORDER_TYPE_GROUPON,'buy_type'=>Lib_Constants::GROUPON_ORDER_DIRECT,'groupon_id'=>$groupon_detail['iGrouponId']), 'payment')
                    );
                    if ($left_stock >= $groupon_detail['groupon_spec']['iPeopleNum']) {
                        $extend[] = array(
                            'node_name'=>'<em>¥'.price_format($groupon_spec['iDiscountPrice']).'</em><span>'.$groupon_spec['iPeopleNum'].'人团</span>',
                            'node_class'=>'btn-buy-group',
                            'node_url'=>gen_uri('/pay/cashier', array('order_type'=>Lib_Constants::ORDER_TYPE_GROUPON,'buy_type'=>Lib_Constants::GROUPON_ORDER_DIY,'groupon_id'=>$groupon_detail['iGrouponId'], 'spec_id'=>$groupon_spec['iSpecId']), 'payment')
                        );
                    } else { //开团库存不足
                        $extend[] = array(
                            'node_name'=>'<em>¥'.price_format($groupon_spec['iDiscountPrice']).'</em><span>库存不足</span>',
                            'node_class'=>'low-stocks',
                        );
                    }
                } else { //库存不足
                    $extend = array(
                        array(
                            'node_name'=>'<em>¥'.price_format($groupon_detail['iPrice']).'</em><span>任性买(库存不足)</span>',
                            'node_class'=>'low-stocks',
                        ),
                        array(
                            'node_name'=>'<em>¥'.price_format($groupon_spec['iDiscountPrice']).'</em><span>库存不足</span>',
                            'node_class'=>'low-stocks',
                        )
                    );
                }
            } elseif ($groupon_diy) { //开团详情页
                $now = time();
                if ($groupon_diy['iEndTime'] <= $now || $groupon_diy['iFinished'] == Lib_Constants::GROUPON_DIY_FINISHED) { //拼团已结束或者已经成功开团
                    if ($is_my) { //本人 重新开团
                        $extend = array(
                            array(
                                'node_name'=>'<span>再次开团</span>',
                                'node_class'=>'btn-join',
                                'node_url'=>gen_uri('/active/detail', array('gid'=>$groupon_detail['iGrouponId'],'spec_id'=>$groupon_diy['iSpecId']), 'groupon')
                            )
                        );
                    } else { //非本人
                        $extend = array(
                            array(
                                'node_name'=>'<span>去开团</span>',
                                'node_class'=>'btn-join',
                                'node_url'=>gen_uri('/active/detail', array('gid'=>$groupon_detail['iGrouponId'],'spec_id'=>$groupon_diy['iSpecId']), 'groupon')
                            )
                        );
                    }
                } else {
                    $is_joined = false;
                    if(!$is_my) { //已凑团
                        $diy_join_list = $this->assign('diy_join_list');
                        $diy_join_list = (empty($diy_join_list) || empty($diy_join_list['list'])) ? array() : $diy_join_list['list'];
                        $user = $this->assign('user');
                        if ($diy_join_list && $user) {
                            foreach ($diy_join_list as $join) {
                                if ($join['iUin'] == $user['uin']) {
                                    $is_joined = true;
                                    break;
                                }
                            }
                        }
                    }

                    if ($is_my || $is_joined) { //本人或已凑团
                        $extend = array(
                            array(
                                'node_name'=>'分享给小伙伴',
                                'node_class'=>'btn-share',
                                'node_id'=>'groupon_diy_share'
                            )
                        );
                    } else { //非本人
                        $extend = array(
                            array(
                                'node_name'=>'¥'.price_format($groupon_spec['iDiscountPrice']).'立即凑团',
                                'node_class'=>'btn-join',
                                'node_url'=>gen_uri('/pay/cashier', array('order_type'=>Lib_Constants::ORDER_TYPE_GROUPON,'buy_type'=>Lib_Constants::GROUPON_ORDER_JOIN,'groupon_id'=>$groupon_detail['iGrouponId'], 'spec_id'=>$groupon_spec['iSpecId'], 'diy_id'=>$groupon_diy['iDiyId']), 'payment')
                            )
                        );
                    }
                }
            }
        }

        $data['extend_node'] = $extend;
        return $data;
    }
}
