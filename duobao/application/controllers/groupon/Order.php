<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Groupon_Base
{
    protected $need_login_methods = array('index','ajax_my_order','detail','my_order_cancel','my_order_delete','my_order_receipt','trace');

    const PAGE_SIZE = 4;

    /**
     * 订单首页
     */
    public function index()
    {
        $this->assign('menus_active_index', 3);

        $params = array(
            'page_size' => self::PAGE_SIZE
        );

        $ret = $this->uin_api('my_order_list', $params);

        if ($ret && Lib_Errors::SUCC == $ret['retCode'] && ! empty($ret['retData'])) {
            if ($ret['retData']['count'] > 0) {
                $this->process_order_list($ret['retData']['list']);
            }
            $this->assign('result_data', $ret['retData']);
        }

        $this->render();
    }

    /**
     * 异步加载订单列表
     */
    public function ajax_my_order()
    {
        $params = array(
            'order_state' => $this->get_post('order_state', 0),
            'page_index' => $this->get_post('p_index', 1),
            'page_size' => self::PAGE_SIZE
        );
        $ret = $this->uin_api('my_order_list', $params);

        if ($ret && Lib_Errors::SUCC == $ret['retCode'] && ! empty($ret['retData'])) {
            if ($ret['retData']['count'] > 0) {
                $this->process_order_list($ret['retData']['list']);
                $html = $this->widget('order_list', array('order_list'=>$ret['retData']['list']), true);
            } else {
                $html = '';
            };
            $ret['retData']['html'] = $html;
            $this->output_json(Lib_Errors::SUCC, $ret['retData']);
        } else {
            $this->output_json(Lib_Errors::REQUEST_ERROR);
        }
    }

    /**
     * 订单详情页
     */
    public function detail()
    {
        $order_id = $this->get_post('order_id', '');
        if (! $order_id) {
            show_error(Lib_Errors::PARAMETER_ERR);
        }
        $params = array(
            'order_id' => $order_id
        );
        $ret = $this->uin_api('my_order_detail', $params);
        if ($ret && Lib_Errors::SUCC == $ret['retCode'] && ! empty($ret['retData'])) {
            $order = $ret['retData'];
            $this->process_order($order);
            $this->assign('detail', $order);
            $this->assign('menus', 'order_menus');
            $this->render();
        } else {
            show_error(Lib_Errors::get_error($ret['retCode']));
        }
    }

    /**
     * 取消订单
     */
    public function my_order_cancel()
    {
        $order_id = $this->get_post('order_id', '');
        if (! $order_id) {
            show_error(Lib_Errors::PARAMETER_ERR);
        }
        $params = array(
            'order_id' => $order_id
        );
        $ret = $this->uin_api('my_order_cancel', $params);
        if ($ret && Lib_Errors::SUCC == $ret['retCode']) {
            $this->output_json(Lib_Errors::SUCC);
        } else {
            $this->output_json(Lib_Errors::REQUEST_ERROR);
        }
    }

    /**
     * 删除订单
     */
    public function my_order_delete()
    {
        $order_id = $this->get_post('order_id', '');
        if (! $order_id) {
            show_error(Lib_Errors::PARAMETER_ERR);
        }
        $params = array(
            'order_id' => $order_id
        );
        $ret = $this->uin_api('my_order_delete', $params);
        if ($ret && Lib_Errors::SUCC == $ret['retCode']) {
            $this->output_json(Lib_Errors::SUCC);
        } else {
            $this->output_json(Lib_Errors::REQUEST_ERROR);
        }
    }

    /**
     * 确认收货
     */
    public function my_order_receipt()
    {
        $order_id = $this->get_post('order_id', '');
        if (! $order_id) {
            show_error(Lib_Errors::PARAMETER_ERR);
        }
        $params = array(
            'order_id' => $order_id
        );
        $ret = $this->uin_api('my_order_receipt', $params);
        if ($ret && Lib_Errors::SUCC == $ret['retCode']) {
            $this->output_json(Lib_Errors::SUCC);
        } else {
            $this->output_json(Lib_Errors::REQUEST_ERROR);
        }
    }

    /**
     * 订单跟踪
     */
    public function trace()
    {
        $order_id = $this->get_post('order_id', '');
        if (! $order_id) {
            show_error(Lib_Errors::PARAMETER_ERR);
        }
        $params = array(
            'order_id' => $order_id
        );
        $ret = $this->uin_api('my_order_express', $params);
        if ($ret && Lib_Errors::SUCC == $ret['retCode']) {
            $this->assign('express', $ret['retData']);
            $this->render();
        } else {
            show_error(Lib_Errors::REQUEST_ERROR);
        }
    }

    /**
     * 预处理订单列表
     *
     * @param $order_list
     */
    protected function process_order_list(& $order_list)
    {
        foreach ($order_list as & $v) {
            $this->process_order($v);
        }
    }

    /**
     * 预处理订单
     *
     * @param $order
     */
    protected function process_order(& $order)
    {
        $order['state'] = Lib_Constants::$order_state[$order['iState']];
        $order['btns'] = Lib_Constants::get_order_btn($order);
    }
}