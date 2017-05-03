<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 系统消息 service
 *
 * Class Message_service
 */
class Message_service extends MY_Service
{
    /**
     * Message_service constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('message_model');
    }

    /**
     * 发送消息
     *
     * @param      $toUin
     * @param      $data
     * @param      $tem_id
     * @param      $expire
     *
     * @return int
     */
    public function send($toUin, $data, $tem_id, $expire = 0)
    {
        $this->load->model('message_template_model');
        $tem = $this->message_template_model->get_row($tem_id);
        if (! $tem) {
            return Lib_Errors::PARAMETER_ERR;
        }

        if (empty($toUin)) {
            return Lib_Errors::MESSAGE_TO_UIN_ERROR;
        }

        $this->load->model('user_model');
        if (! $this->user_model->get_user_by_uin($toUin)) {
            return Lib_Errors::MESSAGE_TO_UIN_ERROR;
        }

        if (true !== ($variable = check_temp_data($tem['sTemplate'], $data))) {
            $this->log->error('message service', 'template data error', array('variable'=>$variable,'template'=>$tem,'data'=>$data));
            return Lib_Errors::MESSAGE_TEM_DATA_ERROR;
        }
        switch ($tem['iNotifyType']) {
            case Lib_Constants::MSG_NOTIFY_MY: // 个人中心消息
                return $this->sendMy($toUin, $data, $tem, $expire);
            case Lib_Constants::MSG_NOTIFY_SMS:  // 手机短信消息
                return $this->sendSms($toUin, $data, $tem);
            case Lib_Constants::MSG_NOTIFY_WX:  // 微信模板消息
                return $this->sendWx($toUin, $data, $tem);
        }
        return Lib_Errors::MESSAGE_NOTIFY_TYPE_INVALID;
    }

    /**
     * 发送个人中心消息
     *
     * @param $toUin
     * @param $data
     * @param $tem
     * @param $expire
     *
     * @return int
     */
    public function sendMy($toUin, $data, $tem, $expire)
    {
        if (empty($data['url'])) {
            $this->log->warning('message service', 'my center template data error', array('data'=>$data));
            return Lib_Errors::MESSAGE_TEM_DATA_ERROR;
        }
        $url = $data['url'];
        unset($data['url']);

        $content = compile_temp($tem['sTemplate'], $data);

        $msg_data = array(
            'iMsgType' => $tem['iMsgType'],
            'iToUin' => $toUin,
            'sUrl' => $url,
            'sContent' => $content,
            'iRead' => 0,
            'iExpireTime' => $expire,
        );

        $this->load->model('message_model');

        if (! $this->message_model->add_row($msg_data)) {
            $this->log->error('message service', 'message_model add_row failed', array('template'=>$tem,'data'=>$data));
            return Lib_Errors::MESSAGE_SEND_MY_FAILED;
        }
        return Lib_Errors::SUCC;
    }

    /**
     * 发送手机短信消息
     *
     * @param $toUin
     * @param $data
     * @param $tem
     *
     * @return int
     */
    public function sendSms($toUin, $data, $tem)
    {
        return Lib_Errors::SUCC;
    }

    /**
     * 发送微信模板消息
     *
     * @param $toUin
     * @param $data
     * @param $tem
     *
     * @return int
     */
    public function sendWx($toUin, $data, $tem)
    {
        if (empty($data['url'])) {
            $this->log->error('message service', 'wx template data error', array('data'=>$data));
            return Lib_Errors::MESSAGE_TEM_DATA_ERROR;
        }
        $url = $data['url'];
        unset($data['url']);

        $this->load->model('user_model');
        $user = $this->user_model->get_user_base_info($toUin, Lib_Constants::PLATFORM_WX);
        if (empty($user) || empty($user['openid'])) {
            $this->log->warning('message service', 'send wx message error | user info exception', array($toUin, $data, $tem));
            return Lib_Errors::MESSAGE_TO_UIN_ERROR;
        }

        $msg_data = array(
            'touser' => $user['openid'],
            'template_id' => $tem['sTemplate'],
            'url' => $url,
            'data' => array(),
        );

        $config = config_item('weixinConfig');

        $rs = $retry = false;
        $times = 3;
        do {
            try {
                $rs = Lib_Weixin::portal($config)->sendTemplateMsg($msg_data);
                if ($rs) {
                    $retry = false;
                } else {
                    $retry = true;
                    $this->log->warning('message service','Lib_Weixin::portal sendTemplateMsg failed', array($times, $msg_data, $toUin, $data, $tem));
                }
            } catch (Exception $e) {
                $this->log->warning('message service','Lib_Weixin::portal sendTemplateMsg exception', array($times, $msg_data, $toUin, $data, $tem));
                $retry = true;
            }
            $times--;
        } while ($retry and $times > 0);

        return $rs ? Lib_Errors::SUCC : Lib_Errors::MESSAGE_SEND_WX_FAILED;
    }
}
