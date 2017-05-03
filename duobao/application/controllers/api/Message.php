<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 消息 api
 *
 * Class Message
 */
class Message extends API_Base
{
    /**
     * 最大消息长度
     */
    const MAX_MSG_LENGTH = 2046;

    /**
     * 默认分页大小
     */
    const DEFAULT_PAGE_SIZE = 6;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        parent::__construct(true);
        $this->load->model('message_model');
    }

    /**
     * 获取用户消息列表
     */
    public function fetch_list()
    {
        extract($this->cdata);

        if (empty($uin)) {
            $this->output_json(Lib_Errors::UIN_ERROR);
        }

        $where = array('iToUin'=>$uin);
        if (! empty($msg_type)) {
            $where['iMsgType'] = intval($msg_type);
        }

        $order_by = array('iRead'=>'ASC','iCreateTime'=>'DESC');

        $page_index = ! empty($p_index) ? intval($p_index) : 1;
        $page_size = ! empty($p_size) ? intval($p_size) : self::DEFAULT_PAGE_SIZE;

        $fields = 'iMsgId,iMsgType,iToUin,sUrl,sContent,iRead,iCreateTime';

        $msg_ret = $this->message_model->row_list($fields, $where, $order_by, $page_index, $page_size);

        $this->output_json(Lib_Errors::SUCC, $msg_ret);
    }

    /**
     * 获取未读消息数目
     */
    public function fetch_unread_count()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->output_json(Lib_Errors::UIN_ERROR);
        }
        $where = array('iToUin'=>$uin,'iRead'=>0);
        $this->output_json(Lib_Errors::SUCC, $this->message_model->row_count($where));
    }

    /**
     * 标记用户消息为已读
     */
    public function read()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->output_json(Lib_Errors::UIN_ERROR);
        }
        if (empty($msg_id)) {
            $this->output_json(Lib_Errors::MESSAGE_ID_ERROR);
        }
        $where = array(
            'iMsgId' => intval($msg_id),
            'iToUin' => $uin
        );
        $data = array('iRead'=>1);
        $this->output_json(Lib_Errors::SUCC, $this->message_model->update_rows($data, $where));
    }

    /**
     * 将全部用户未读消息标记为已读
     */
    public function clean()
    {
        extract($this->cdata);
        if (empty($uin)) {
            $this->output_json(Lib_Errors::UIN_ERROR);
        }
        $where = array('iToUin'=>$uin,'iRead'=>0);
        $data = array('iRead'=>1);
        $this->output_json(Lib_Errors::SUCC, $this->message_model->update_rows($data, $where));
    }
}