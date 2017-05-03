<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 作为发起API请求基类
 * Class Duogebao_Common
 */
class Passport_Common extends MY_Controller
{
    const CURL_TIMEOUT = 8;
    const IS_DECRYPT = false;//返回值是否需要解密
    public $params = null;


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * API统一返回
     * @param $url
     * @param $params
     * @param $skey
     * @param $return 调试的时候可以设置为true,会不管结果如何，都将直接返回
     * @return array|bool|mixed
     * @throws Exception
     */
    public function getJsonResponse($url, $params,$skey,$direct = false)
    {
        if(empty($skey)){
            throw new Exception(Lib_Errors::get_error(Lib_Errors::CONFIG_ERR),Lib_Errors::CONFIG_ERR);
        }
        $params = $this->params_encrypt($params,$skey);
        if($direct){
            return $this->http_send($url, $params,$direct);
        }
        if(($data = $this->http_send($url, $params)) !== false){
            if (!empty($data)) {
                $params['cdata'] = $this->params;//这里是没有加密的，方便日志输出
                $msg = strstr($data['retMsg'],':') != false ? explode(':',$data['retMsg']) : '';
                $data['retMsg'] = empty($msg) ? $data['retMsg'] : $msg[1];

                if (!isset($data['retCode'])) {
                    get_instance()->log->error('API', "API ERROR || retCode not isset || " . $url ." || ".http_build_query($params) . " || ret " . json_encode($data));
                    throw new Exception($data['retMsg'], $data['retCode']);
                }
                if ($data['retCode'] != 0) {
                    get_instance()->log->error('API', "API ERROR || retCode " .$data['retCode']. " || " . $url ." || ".http_build_query($params) . " || ret " . json_encode($data));
                    throw new Exception($data['retMsg'], $data['retCode']);
                }
                //is decrypt
                if (self::IS_DECRYPT == true && is_array($data) && !empty($data['retData'])) {
                    $data['retData'] = decrypt($data['retData'],$skey);
                }
                return $data;
            }
        }
        throw new Exception(Lib_Errors::get_error(Lib_Errors::UNKONWN_ERR),Lib_Errors::UNKONWN_ERR);
    }


    /**
     * http请求
     * @param $url
     * @param $params
     * @param $direct
     * @return bool|mixed
     */
    private function http_send($url, $params,$direct = false)
    {
        $result = $respJson = $resp = '';
        $apiStartTime = microtime(true);
        $repeat = 3;
        $count = 1;
        while ($count <= $repeat) {
            $return = Lib_HttpClient::CallCURLPOST($url, $params, $resp, array(), self::CURL_TIMEOUT); //ADD
            switch ($return) {
                case  0:
                    $result = 'ok';
                    break;
                case -1:
                    $result = 'contect error';
                    break;
                case -2:
                    $result = 'responseCode not 200';
                    break;
            }
            $params['cdata'] = $this->params;//这里是没有加密的，方便日志输出
            if($direct) return $resp;
            if ($return === 0) {
                //返回的数据反编码
                if (empty($resp) || ($respJson = json_decode($resp, true)) === false) {
                    get_instance()->log->error('API', "API ERROR || CallCURLPOST response exception || url: ".$url." || params: ".json_encode($params).' || resp:'.json_encode($resp).' || respJson: '.$respJson . ' || count: '.$count);
                    return false;
                }
                if (empty($respJson)) {
                    get_instance()->log->error('API', "API ERROR || CallCURLPOST response exception 2 || url: ".$url." || params: ".json_encode($params).' || resp:'.json_encode($resp).' || respJson: '.$respJson . ' || count: '.$count);
                }
                return $respJson;
            } else {
                get_instance()->log->error('API', $this->getExcuteTime($apiStartTime) . ' || ' . $url . ' ' . json_encode($params) . ' || count=' . $count . ' || ' . $result . ' || ' . json_encode($resp));
            }
            $count++;
            usleep(500000);
        }
        get_instance()->log->error('API', "API ERROR || Maximum number of count || url: " . $url ." || params: ".json_encode($params).' || count: '.$count);
        return false;
    }

    /**
     * 参数加密及格式化
     * @param $params
     * @param $skey
     */
    private function params_encrypt($params,$skey)
    {
        $this->params = $params;
        $must_params = array(
            'client_id' => $params['client_id'],
            'version' => $params['version'],
            'cdata' => ''
        );

        if(count($params) > 2){ //没有这么多参数，则默认把这个两个参数当成cdata数值
            unset($params['version'],$params['client_id']);
        }
        $must_params['cdata'] = encrypt($params,$skey);

        return $must_params;
    }

    /**
     * @param $startTime
     * @return string
     */
    private function getExcuteTime($startTime)
    {
        return number_format(microtime(true) - $startTime, 3, '.', '');
    }
}