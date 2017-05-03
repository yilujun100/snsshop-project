<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 夺个包帮助中心
 *
 * Class Message
 */
class Guide extends Duogebao_Base
{
    public $layout_name = null;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 百分好礼教学
     */
    public function course()
    {
        $type = $this->get_post('type',0);
        $this->assign('menus_show',false);

        if(empty($type)){
            $this->render(array(),'guide/course_0');
        }else{
            $this->render(array(),'guide/course_1');
        }
    }


    /**
     * 积分活动
     */
    public function act510()
    {
        $this->render();
    }


    //晒单指南
    public function share()
    {
        $this->render();
    }
}