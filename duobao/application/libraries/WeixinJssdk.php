<?php
/**
 * @author: NICK.H
 * 微信JS SDK接口
 * Date: 2012-12-12
 */

class Lib_WeixinJssdk
{
    const  WX_JSAPI_TICKET_KEY = 'WeixinJsApiTicket';
    const  WX_JSAPI_TICKET_LOCK_KEY = 'WeixinJsApiTicketLock';

	//取jsapi_ticket
	public static function getSignPackage($url)
    {
        $weixin_config = Lib_Weixin::get_weixin_config();
        if(!$weixin_config) {
            throw new Exception('get weixinConfig failed.');
        }

        //先查cache中的值
        $jsapiTicket = Lib_Weixin::portal($weixin_config)->getJsApiTicket();
        if(!$jsapiTicket) {
            throw new Exception('get jsapi_ticket failed.');
        }

        $timestamp = time();
        $appId = $weixin_config['appId'];
        $nonceStr = self::createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    public static  function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
