<?php
require_once(APPPATH.'controllers/duogebao/Base.php');
require_once(APPPATH.'controllers/duogebao/Common.php');

class Result extends Duogebao_Base
{
    protected $need_login_methods = array('index');
    protected $disable_layout = true;

    protected $order_id;
    protected $order_type;
    protected $order;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->config->load('pay');
        $this->load->service('order_service');
    }

    /**
     * 支付结果页
     */
    public function index()
    {
        $this->order_id = $this->get_post('order_id');
        if (empty($this->order_id)) {
            show_error(Lib_Errors::PARAMETER_ERR);
            return;
        }
        $this->order_type = $this->order_service->check_order_type($this->order_id);
        if (! $this->order_type || ! in_array($this->order_type, array_keys(Lib_Constants::$order_type))) {
            show_error(Lib_Errors::get_error(Lib_Errors::ORDER_TYPE_NOT_FOUND));
            return;
        }
        $this->order = $this->order_service->get_order_detail($this->user['uin'], $this->order_id);
        if (! is_array($this->order)) {
            show_error(Lib_Errors::get_error(Lib_Errors::ORDER_NOT_FOUND));
            return;
        }
        $real_result =  'result_' . $this->order_type;
        if (! is_callable(array($this, $real_result))) {
            $this->log->error($real_result, 'cashier result undefined', array('order_type'=>$this->order_type,'order_id'=>$this->order_id,'user'=>$this->user));
            show_error(Lib_Errors::get_error(Lib_Errors::ORDER_CASHIER_UNDEFINED));
            return;
        }

        $this->assign('order_type', $this->order_type);
        $this->assign('order_id', $this->order_id);

        $this->router->set_method($real_result);
        $this->{$real_result}();
    }

    /**
     * 拼团支付结果页
     */
    protected function result_6()
    {
        $buy_type = $this->order['iBuyType'];

        switch ($buy_type) {
            case Lib_Constants::GROUPON_ORDER_DIY:
                $this->assign('redirect_url', gen_uri('/active/diy_detail',array('diy_id'=>$this->order['iDiyId'],'share'=>1),'groupon'));
                break;
            case Lib_Constants::GROUPON_ORDER_JOIN:
                $this->assign('redirect_url', gen_uri('/active/diy_detail',array('diy_id'=>$this->order['iDiyId'],'share'=>1),'groupon'));
                break;
            case Lib_Constants::GROUPON_ORDER_DIRECT:
                $this->assign('redirect_url', gen_uri('/active/detail',array('gid'=>$this->order['iGrouponId'],'spec_id'=>$this->order['iSpecId'],'share'=>1),'groupon'));
                break;
            default:
                $this->assign('redirect_url', 'javascript:;');
        }
        $this->assign('buy_type_desc', Lib_Constants::$groupon_order[$buy_type]);
        $this->render();
    }
}