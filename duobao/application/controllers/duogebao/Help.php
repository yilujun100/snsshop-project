<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 夺个包帮助中心
 *
 * Class Message
 */
class Help extends Duogebao_Base
{
    /**
     * 是否需要验证登陆
     *
     * @var array
     */
    protected $need_login_methods = array();

    /**
     * Message constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 信息中心
     */
    public function index()
    {
        $this->set_wx_share('help');
        $item = $this->get('item', 'index');
        $this->render(array(), 'help/' . $item);
    }
}