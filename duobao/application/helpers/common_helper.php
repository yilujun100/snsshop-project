<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 输出调试函数
 *
 * @param array $args
 */
function pr($args = array()) {
    $escape_html = true;
    $bg_color = '#EEEEE0';
    $txt_color = '#000000';
    $args = func_get_args();

    foreach($args as $arr){
        echo sprintf('<pre style="background-color: %s; color: %s;">', $bg_color, $txt_color);
        if($arr) {
            if($escape_html){
                echo htmlspecialchars( print_r($arr, true) );
            }else{
                print_r($arr);
            }
        }else {
            var_dump($arr);
        }
        echo '</pre>';
    }
}


/**
 * cdn取图片resize链接
 * @param $img_url
 * @param $width
 * @param $height
 * @return string
 */
function get_img_resize_url($img_url, $width, $height)
{
    if(!$width && !$height) {
        return $img_url;
    }

    $last_index = strrpos($img_url, '.');
    return substr($img_url,0, $last_index).'_'.$width.'_'.$height.substr($img_url, $last_index);
}


/**
 * API统一加密方法
 * @param $data
 * @param $key
 * @return string
 */
function encrypt($data, $key)
{
    $bit = 192;
    set_include_path(APPPATH . '/third_party/phpseclib0.3.1');
    require_once 'Crypt/AES.php';
    $aes = new Crypt_AES();
    $aes->setKeyLength($bit);
    $aes->setKey($key);
    $data = is_array($data) ? http_build_query($data) : $data;
    $value = $aes->encrypt($data);

    return strtr(base64_encode($value), '+/=', '-_.');
}

/**
 * API统一解密方法
 * @param $data
 * @param $key
 * @return bool|int|string
 */
function decrypt($data, $key)
{
    $bit = 192;
    set_include_path(APPPATH . '/third_party/phpseclib0.3.1');
    require_once 'Crypt/AES.php';
    $aes = new Crypt_AES();
    $aes->setKeyLength($bit);
    $aes->setKey($key);
    $value = $aes->decrypt(base64_decode(strtr($data, '-_.', '+/=')));

    if(strstr($value,'=') !== false){
        parse_str($value,$value);
    }
    return $value;
}

if (! function_exists('array_column')) {
    /**
     * 返回数组中指定的一列
     *
     * @param array  $input
     * @param        $column_key
     * @param string $index_key
     */
    function array_column (array $input , $column_key, $index_key = '')
    {
        $arr = array();
        foreach ($input as $item) {
            if (! isset($item[$column_key])) {
                continue;
            }
            if ($index_key && isset($item[$index_key])) {
                $arr[$item[$index_key]] = $item[$column_key];
            } else {
                $arr[] = $item[$column_key];
            }
        }
    }
}

/**
 * 加密
 * @param $string 需要加密的字符串
 * @param $key
 * @return string
 */
function str_encode($string, $key)
{
    $encode_str = '';

    $base64_str = base64_encode($string);
    $base64_str_len = strlen($base64_str);
    $base64_key = base64_encode($key);
    $base64_key_len = strlen($base64_key);

    for($i = 0; $i < $base64_str_len ; $i ++)
    {
        $str_ord = ord($base64_str[$i]);
        $key_ord = ord($base64_key[$i % $base64_key_len]);
        $ord = $str_ord ^ $key_ord;
        $chr = chr($ord);
        $encode_str .= $chr;
    }

    return base64_encode($encode_str);
}


/**
 * 解密
 * @param $string 需要解密的字符串
 * @param $key
 * @return string
 */
function str_decode($string, $key)
{
    $decode_str = '';

    $base64_str = base64_decode($string);
    $base64_str_len = strlen($base64_str);
    $base64_key = base64_encode($key);
    $base64_key_len = strlen($base64_key);

    for($i = 0; $i < $base64_str_len ; $i ++)
    {
        $ord = ord($base64_str[$i]);
        $key_ord = ord($base64_key[$i % $base64_key_len]);
        $str_ord = $ord ^ $key_ord;
        $chr = chr($str_ord);
        $decode_str .= $chr;
    }

    return base64_decode($decode_str);
}

/**
 * 检查指定值是否为错误码
 *
 * @param $val
 *
 * @return bool
 */
function is_error_code ($val)
{
    return is_int($val) && $val < 0;
}

/**
 * 判定返回结果是否为 success
 *
 * @param $val
 *
 * @return bool
 */
function is_success($val)
{
    return Lib_Errors::SUCC === $val;
}

/**
 * 检查当前用户是否已授予指定节点的访问权限
 *
 * @param $node
 * @param $role_id
 *
 * @return mixed
 */
function is_granted($node, $role_id = null)
{
    $CI = &get_instance();
    if (isset($CI->user_service) && is_object($CI->user_service)) {
        return $CI->user_service->is_granted($node, $role_id);
    }
}

/**
 * 返回节点完整url
 *
 * @param $node
 *
 * @return mixed
 */
function node_url($node)
{
    $CI = &get_instance();
    $dir = $CI->router->fetch_directory();
    if ($dir) {
        $node = $dir . ltrim($node, '/');
    }
    return  '/' . $node;
}

/**
 * 返回UTF8字符串长度
 *
 * @todo 待完善
 *
 * @return integer
 */
function utf8_strlen($str)
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($str);
    }
    return strlen($str);
}


/**
 * 获取真实IP
 * @return string
 */
function get_ip()
{
    $IPaddress='127.0.0.0';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $IPaddress = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $IPaddress = getenv("HTTP_CLIENT_IP");
        } else {
            $IPaddress = getenv("REMOTE_ADDR");
        }
    }
    if (false !== strpos($IPaddress, ',')) {
        $IPaddress = reset(explode(',', $IPaddress));
    }
    return $IPaddress;
}


/**
 * 数字格式化
 *
 * @param        $num
 * @param int    $decimals
 *
 * @return string
 */
function num_format($num, $decimals = 2)
{
    return number_format($num, $decimals, '.', '');
}

/**
 * 格式化价格
 *
 * @param $price
 *
 * @return string
 */
function price_format($price)
{
    return num_format($price / 100);
}

/**
 * 格式化百分数
 *
 * @param $percent
 *
 * @return string
 */
function percent_format($percent)
{
    return num_format($percent * 100) . '%';
}

/**
 * 将阿拉伯整数转为汉字形式
 *
 * @param $integer
 *
 * @return string
 */
function cn_int($integer) {
    static $cn_int_arr = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十');
    $int = intval($integer);
    if ($int <= 10) {
        return $cn_int_arr[$int];
    }
    return '';
}

/**
 * 生成夺宝期号
 * @param $act_id 夺宝活动Id
 * @param $period_id 夺宝期数Id
 */
function period_code_encode($act_id, $period_id) {
    return $act_id.str_pad($period_id, 7, 0, STR_PAD_LEFT);
}

/**
 * 解析夺宝期号
 * 与period_code_encode相反
 * @param $act_id 夺宝活动Id
 */
function period_code_decode($period_code) {
    if (!$period_code || !is_numeric($period_code)) {
        return false;
    }
    $len = strlen($period_code);
    if ($len<=7) {
        return false;
    }
    return array(intval(substr($period_code, 0, ($len-7))), intval(substr($period_code, -7)));
}

/**
 * 输出带参数的uri
 * 输出 user/index?uin=XXXX
 * 后期可扩展成友好uri格式  user/index/XXX
 * @param $uri
 * @param $params
 */
function gen_uri($uri, $params = array(), $module='duogebao') {
    $config = $module.'_url';
    if (!$params || !is_array($params)) return  config_item($config).$uri;
    return config_item($config).$uri.'?'.http_build_query($params);
}

/**
 * 生成支付 uri
 *
 * @param array $params
 *
 * @return string
 */
function pay_uri($params = array()) {
    return gen_uri('/pay/cashier', $params, 'payment');
}


/**
 * @param $time
 * @return bool|string
 */
function tran_time($time) {
    $rtime = date("m-d H:i",$time);
    $htime = date("H:i",$time);

    $time = time() - $time;
    $days = intval($time/(3600*24));

    if ($time < 60) {
        $str = '刚刚';
    }elseif ($time < 60 * 60) {
        $min = floor($time/60);
        $str = $min.'分钟前';
    }elseif ($time < 60 * 60 * 24) {
        $h = floor($time/(60*60));
        $str = $h.'小时前';
    }elseif ($time < 60 * 60 * 24 * 3) {
        $d = floor($time/(60*60*24));
        if($d==1)
            $str = '昨天';
        else
            $str = '前天';
    }else {
        //$str = $days.'天前';
        $str = '3天前';
    }
    return $str;
}


/**
 * 中文字符截取
 * @param $string
 * @param int $strlen
 * @param string $etc
 * @param string $charset
 * @return string
 */
function cn_substr($string, $strlen = 20, $etc = '...', $charset = 'utf-8')
{
    $slen = mb_strlen($string, $charset);
    if ($slen > $strlen+2){
        $tstr = mb_substr($string, 0, $strlen, $charset);
        $matches = array();
        $mcount = preg_match_all("/[\x{4e00}-\x{9fa5}]/u", $tstr, $matches);
        unset($matches);
        $offset = ($strlen - $mcount) * 0.35;//0;//intval((3*mb_strlen($tstr,$charset)-strlen($tstr))*0.35);
        return preg_replace('/\&\w*$/', '', mb_substr($string, 0, $strlen + $offset, $charset)) . $etc;
    }else{
        return $string;
    }
}

/**
 * 生成福袋分享sign
 * @param $uin
 * @param $bagId
 * @return string
 */
function gen_sign($uin,$bagId)
{
    return substr(md5(Lib_Constants::SIGN_PARAM.$bagId.$uin),-14,-2);
}

/**
 * 获取mime类型
 *
 * @param $content_type
 *
 * @return string
 */
function get_mime($content_type)
{
    $mimes = get_mimes();
    if (! isset($mimes[$content_type])) {
        $content_type = 'text';
    }
    if (is_array($mimes[$content_type])) {
        return $mimes[$content_type][0];
    }
    return $mimes[$content_type];
}


/**
 * send_mail()
 * 发送邮件
 * @param string $to
 * @param string $subject
 * @param string $message
 * @return
 */
function send_mail($to = '', $subject = '', $message = '') {
    $CI = &get_instance();
    $CI->config->load('email');
    $CI->load->library('email');
    $CI->email->from(config_item('smtp_user'), config_item('smtp_user'));
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($message);
    if ($CI->email->send()) {
        return true;
    }
    return $CI->email->print_debugger();
}

/**
 * send_shortmsg()
 * 发送短信
 * @param string OR array $mobile
 * @param string $content
 * @return void
 */
function send_shortmsg($mobile = '', $msg = '') {
    if (! $mobile) {
        return array('error' => -1, 'msg' => '手机号码不能为空');
    } elseif (! $msg) {
        return array('error' => -1, 'msg' => '发送内容不能为空');
    }
    if (is_array($mobile)) {
        $mobile = implode(',', $mobile);
    }

    $arr = array(
        'mobile' => $mobile,
        'content' => $msg,
        'sign' => '买啥嘞 maishalei.com',
        'platform' => 2);
    $r = do_http('http://sms.vikduo.com/index.php?type=2', $arr, 'p');
    $rarr = json_decode($r, true);
    if (isset($rarr['code']) && $rarr['code'] == 200) {
        return res(0);
    } else {
        log_message('error', 'send_shortmsg:' . $r);
        return array('error' => -1, 'msg' => '短信发送失败，请重试');
    }
}

/**
 * show_addr()
 * 根据省市县三级code返回字符串
 * @param string $prov
 * @param string $city
 * @param string $dist
 * @param string $type
 * @return void
 */
function show_addr($prov = '', $city = '', $dist = '', $type = 'str', $delimiter = ' ') {
    if (! $prov) {
        return '';
    }
    $CI = &get_instance();
    if (! isset($CI->config->config['areaData'])) {
        $CI->config->load('area');
    }
    $data = json_decode($CI->config->item('areaData'), true);

    $result = array('prov' => $data[$prov]['n']);
    if ($city) {
        $result += array('prov' => $data[$prov]['n'], 'city' => $data[$prov]['c'][$city]['n']);
    }
    if ($dist) {
        $result += array('dist' => isset($data[$prov]['c'][$city]['c'][$dist]['n']) ? $data[$prov]['c'][$city]['c'][$dist]['n'] : '');
    }
    if ($type == 'str') {
        return implode($delimiter, $result);
    } else {
        return $result;
    }
}

/**
 * add2code()
 * 地址转地址码
 * @param string $address
 * @return void
 */
function add2code($prov = '', $city = '', $dist = '', $only_code = true) {
    $CI = &get_instance();
    $CI->config->load('area');
    $area = json_decode($CI->config->item('areaData'), true);
    $address = $prov;
    if ($dist) {
        $area = $area[$prov]['c'][$city]['c'];
        $address = $dist;
    } elseif ($city && ! $dist) {
        $area = $area[$prov]['c'];
        $address = $city;
    }

    $arr = array();
    foreach ($area as $line) {
        if (strpos($line['n'], $address) === 0) {
            unset($line['c']);
            $arr = $line;
            break;
        }
    }
    $arr = $arr ? $arr : reset($area);
    return $only_code ? $arr['i'] : $arr;
}

/**
 * get_provs()
 * 获取所有省份或特定
 * @param array $id
 * @param string $return
 * @return
 */
function get_provs($id = array(), $return = 'array') {
    $CI = &get_instance();
    if (! isset($CI->config->config['areaData'])) {
        $CI->config->load('area');
    }
    $area = json_decode($CI->config->item('areaData'), true);
    $data = array();
    foreach ($area as $line) {
        $data[$line['i']] = $line['n'];
    }
    if (! $id) {
        return $data;
    } elseif (! is_array($id)) {
        return $data[$id];
    } else {
        $data = array();
        foreach ($id as $line) {
            $data[$line] = $area[$line]['n'];
        }
        if ($return == 'array') {
            return $data;
        } else {
            return implode($return, $data);
        }
    }

}

/**
 * 模型通用返回
 * @param int $error 错误码
 * @param string $data 返回data
 */
function res($error = 0, $data = array()) {
    $errors = array(
        0 => '操作成功',
        -21 => '缺少必要参数',
        -22 => '操作失败');

    $CI = &get_instance();
    if (! isset($CI->errors)) {
        $CI->errors = array();
    }
    $errors = $CI->errors + $errors;

    $r['error'] = (int)$error;

    $r['msg'] = isset($errors[$r['error']]) ? $errors[$r['error']] : '';
    $r['type'] = 'success';
    if ($error < 0) {
        $r['type'] = 'error';
    }

    $r['data'] = $data;

    return $r;
}

/**
 * create_qr()
 * 二维码生成
 * @param string $text
 * @return
 */
function create_qr($text = '', $return_path = false, $level = 'L', $size = 10) {
    if (! $text) {
        return false;
    }
    if (strpos($text, 'http') === 0) {
        $text .= (strpos($text, '?') !== false ? '&' : '?') . 'qrcode';
    }
    if (! $return_path) {
        return base_url() . 'api/create_qr?text=' . urlencode($text);
    } else {
        $text_md5 = md5($text);
        $qpath = '/data/qrcode/' . substr($text_md5, 0, 2) . '/' . $text_md5 . '.png';
        $fcpath = str_replace('\\', '/', FCPATH);
        mkdirs($fcpath . '/data/qrcode/' . substr($text_md5, 0, 2) . '/');
        if (! file_exists($fcpath . $qpath)) {
            include $fcpath . 'application/libraries/qrcode/qrlib.php';
            QRcode::png($text, $fcpath . $qpath, $level, $size, 1);
        }

        return base_url() . $qpath;
    }

}

/**
 * pagination()
 * 分页
 * @param integer $total
 * @param integer $limit
 * @return
 */
function pagination($total = 0, $limit = 10) {
    $CI = &get_instance();
    $CI->load->library('pagination');
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $CI->pagination->initialize($config);
    return $CI->pagination->create_links();
}

/**
 * queryArr()
 * 后端查询
 * @return
 */
function query_arr() {
    $CI = &get_instance();
    $key = $CI->input->get('q_key', true);
    $val = $CI->input->get('q_val', true);
    return array('q_key' => $key, 'q_val' => $val);
}

/**
 * loc2dis()
 * 根据经纬度差算距离
 * @param integer $flat
 * @param integer $flng
 * @param integer $tlat
 * @param integer $tlng
 * @return
 */
function loc2dis($flat = 0, $flng = 0, $tlat = 0, $tlng = 0) {
    if (! $flat && ! $flng) {
        return false;
    }
    $a = abs($flat - $tlat);
    $b = abs($flng - $tlng);
    $c = hypot($a, $b);

    return floor($c * 100000);
}

/**
 * second_formart()
 * 秒格式化
 * @param integer $s
 * @return void
 */
function second_formart($s = 0) {
    $_d = 0;
    $_h = 0;
    $_m = 0;
    $_str = '';
    if ($s >= 86400) {
        $_d = floor($s / 86400);
        $s = $s % 86400;
        $_str = $_d . '天';
    }
    if ($s >= 3600) {
        $_h = floor($s / 3600);
        $s = $s % 3600;
        $_str .= $_h . '小时';
    }
    if ($s >= 60) {
        $_m = floor($s / 60);
        $s = $s % 60;
        $_str .= $_m . '分钟';
    }

    return $_str . ($s ? $s . '秒' : '');
}



/**
 * query_express()
 * 查询快递
 * @param string $express
 * @param string $no
 * @return
 */
function query_express($express = '', $no = '') {
    $CI = &get_instance();
    $expresss = config_item('express_arr');
    if ($express == 'none' || ! in_array($express, array_keys($expresss))) {
        return array();
    }
    $url = 'http://www.kuaidi100.com/query';
    $return = do_http($url, array(
        'type' => $express,
        'postid' => $no,
        'id' => 1,
        'valicode' => '',
        'temp' => '0.6954276447650045'));

    if (! $return) {
        return array();
    }
    $returnArray = json_decode($return, true);
    if ($returnArray['message'] != 'ok') {
        return array();
    }
    return $returnArray['data'];
}

/**
 * remove_xss()
 * 去除XSS（跨站脚本攻击）的函数
 * @param string $string
 * @return
 */
function remove_xss($string = '') {
    if (is_array($string)) {
        return array_map('cleanXss', $string);
    }

    //从网上抄的不知道这几个是干什么的
    $string = preg_replace('/%0[0-8bcef]/', '', $string);
    $string = preg_replace('/%1[0-9a-f]/', '', $string);
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

    $string = preg_replace('/<meta.+?>/is', '', $string); //过滤 meta 标签
    $string = preg_replace('/<script.+?<\/script>/is', '', $string); //过滤 script 标签
    $string = preg_replace('/<iframe.+?<\/iframe>/is', '', $string); //过滤 iframe 标签 1
    $string = preg_replace('/<iframe.+?>/is', '', $string); //过滤 iframe 标签 2

    //过滤标签属性
    $string = preg_replace_callback('/(\<\w+\s)(.+?)(?=( \/)?\>)/is', function ($m) {
            //去除标签上的 on.. 开头的 JS 事件，以下一个 xxx= 属性或者尾部为终点
            $m[2] = preg_replace('/\son[a-z]+\s*\=.+?(\s\w+\s*\=|$)/is', '\1', $m[2]); //去除 A 标签中 href 属性为 javascript: 开头的内容
            if (strtolower($m[1]) == '<a ') {
                $m[2] = preg_replace('/href\s*=["\'\s]*javascript\s*:.+?(\s\w+\s*\=|$)/is', 'href="#"\1', $m[2]); }

            return $m[1] . $m[2]; }
        , $string);

    $string = preg_replace('/(<\w+)\s+/is', '\1 ', $string); //过滤标签头部多余的空格
    $string = preg_replace('/(<\w+.*?)\s*?( \/>|>)/is', '\1\2', $string); //过滤标签尾部多余的空格

    return $string;
}

/**
 * filter_arr()
 * 过滤(保留)数组中的某些字段，返回剩下的部分
 * @param mixed $arr
 * @param mixed $fields
 * @param bool $remove 是否过滤
 * @return
 */
function filter_arr($arr = array(), $fields = '', $remove = true, $sign_arr = false) {
    if (! is_array($arr) || ! $arr || ! count($arr) || ! $fields || $fields == '*') {
        return $arr;
    }
    $a = array();
    $f = explode(',', $fields);

    if (! $sign_arr) {
        foreach ($arr as $key => $value) {
            foreach ($f as $line) {
                if (isset($value[$line])) {
                    if ($remove) {
                        unset($value[$line]);
                    } else {
                        $a[$key][$line] = $value[$line];
                    }
                }
            }
            $remove && $a[$key] = $value;
        }
    } else {
        foreach ($f as $key) {
            if (isset($arr[$key])) {
                if ($remove) {
                    unset($arr[$key]);
                } else {
                    $a[$key] = $arr[$key];
                }
            }
        }
        $remove && $a = $arr;
    }
    return $a;
}

/**
 * show404()
 *
 * @return void
 */
function show404($msg = '') {
    $CI = &get_instance();
    $html['header_title'] = '404';
    $html['msg'] = $msg ? $msg : '亲，您要找的页面不见了~';
    echo $CI->load->view(TEMPLATE_PATH . '404', $html, true);
    exit;
}

/**
 * create_guid()
 *
 * @return
 */
function create_guid() {
    if (function_exists('com_create_guid')) {
        $uuid = com_create_guid();
    } else {
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);
        $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
    }
    return str_replace(array('{', '}'), array(''), $uuid);
}

/**
 * jweixin()
 * 生成微信js
 * @return
 */
function js_weixin($debug = false) {
    $CI = &get_instance();
    $CI->load->library('weixin_lib');
    $CI->load->config('third_party');
    $config = config_item('auth_config');
    return $CI->weixin_lib->weixin_js($config['web']['weixin']['appid'], $config['web']['weixin']['appsecret'], $debug);
}

/**
 * get_http_img()
 * 获取网络图片
 * @param string $url
 * @return
 */
function get_http_img($url = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();

    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $return_content;
}

/**
 * generate_captcha()
 * 生成验证码
 * @return void
 */
function generate_captcha() {
    $CI = &get_instance();
    $word = rand(1000, 9999);
    $img_path = 'data/captcha/' . date('Ymd');
    mkdirs(FCPATH . $img_path);
    $CI->load->helper('captcha');
    $vals = array(
        'word' => $word,
        'img_path' => FCPATH . $img_path . '/',
        'img_url' => base_url($img_path),
        'img_width' => 60,
        'img_height' => 30,
        'font_size' => 20);
    $cap = create_captcha($vals);
    $CI->load->library('encrypt');
    $ver_str = base64_encode($CI->encrypt->encode(json_encode(array(
        'word' => $word,
        'img_path' => $vals['img_path'] . $cap['filename'],
        'time' => TIMESTAMP))));

    return array('img_url' => $vals['img_url'] . '/' . $cap['filename'], 'ver_str' => $ver_str);
}

/**
 * test_my_captcha()
 * 检测填写验证码
 * @param string $captcha_code
 * @param string $ver_str
 * @return
 */
function test_captcha($captcha_code = '', $ver_str = '', $send_to = '') {
    if (! $captcha_code || ! $ver_str) {
        return false;
    }
    $CI = &get_instance();
    $CI->load->library('encrypt');
    $ver_arr = json_decode($CI->encrypt->decode(base64_decode($ver_str)), true);
    if (! $ver_arr) {
        return false;
    }
    if ($captcha_code == $ver_arr['word'] && TIMESTAMP - $ver_arr['time'] < 600) {
        if (file_exists($ver_arr['img_path'])) {
            @unlink($ver_arr['img_path']);
            $CI->cache->file->save('img_captcha_' . $send_to . '_' . $ver_arr['word'], 1, 600 - (TIMESTAMP - $ver_arr['time']));
            return true;
        } else {
            // 发送次数
            $send_time = $CI->cache->file->get('img_captcha_' . $send_to . '_' . $ver_arr['word']);
            if (! $send_time || $send_time == 5) {
                $CI->cache->file->clean('img_captcha_' . $send_to . '_' . $ver_arr['word']);
                return false;
            }
            $CI->cache->file->save('img_captcha_' . $send_to . '_' . $ver_arr['word'], $send_time + 1, 600 - (TIMESTAMP - $ver_arr['time']));
            return true;
        }
    } else {
        return false;
    }
}

/**
 * upload_to_cdn()
 * 上传到cdn
 * @param string $file_path
 * @param string $file_name
 * @param string $file_ext
 * @return
 */
function upload_to_cdn($file_path = '', $file_name = '', $file_ext = '', $is_file_path = true) {
    include APPPATH . 'libraries/nextcdn/NextCdn.php';
    $next_cdn = new Nextcdn();
    $file_ext = in_array($file_ext, array('jpeg', 'JPEG')) ? 'jpg' : $file_ext;
    $cdn_up = $is_file_path ? $next_cdn->upload_file($file_path, $file_name, $file_ext) : $next_cdn->upload_file_raw($file_path, $file_name, $file_ext);
    $cdn_up_arr = json_decode($cdn_up, true);
    if (! $cdn_up || ! $cdn_up_arr || ! isset($cdn_up_arr['url'])) {
        return array('error' => -1, 'msg' => 'cdn上传出现错误，请稍候再试！');
    }
    return array('error' => 0, 'file_url' => substr($cdn_up_arr['url'], -1) == '.' ? $cdn_up_arr['url'] . 'jpg' : $cdn_up_arr['url']);
}

/**
 * upload_files()
 * 文件上传
 * @param string $input
 * @param string $path
 * @param array $config
 * @return
 */
function upload_files($input = '', $path = 'item', $config = array()) {
    $path = preg_replace('/[^\w]+/', '', $path);
    $path = $path ? $path : 'item';

    $CI = &get_instance();
    $CI->config->load('upload');
    $config = array_merge($CI->config->item('upload'), $config);
    $config['encrypt_name'] = true;
    $upload_path = '/data/' . $path . '/' . date('Ymd') . '/';
    $config['upload_path'] = getcwd() . $upload_path;
    mkdirs($config['upload_path']);

    if ($_FILES[$input]['type'] == 'application/octet-stream') {
        $up = upload_files_no_ext($_FILES[$input]['tmp_name'], $_FILES[$input]['size'], $config['upload_path']);
    } else {
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        $_up = $CI->upload->do_upload($input);
        if (! $_up) {
            $up = array('error' => -1, 'msg' => strip_tags($CI->upload->display_errors()));
        } else {
            $up = array('error' => 0, 'data' => $CI->upload->data());
        }
    }

    if ($up['error'] == 0) {
        $data = $up['data'];

        //旋转 针对IOS摄像头图片
        $rotate = $CI->input->get_post('rotate', true);
        if ($rotate && $data['is_image']) {
            $exif = exif_read_data($data['full_path']); //获取exif信息
            if (isset($exif['Orientation']) && $exif['Orientation'] == 6) {
                rotate_img($data['full_path'], $data['file_ext']);
            }
        }

        //剪裁
        $crop = $CI->input->get_post('crop', true);
        if ($crop != 0 && $data['is_image'] && $crop && strpos($crop, '*') !== false) {
            crop_img($crop, $config['upload_path'] . $data['file_name']);
        }
        //压缩
        $compress = $CI->input->get_post('compress', true);
        if ($data['is_image'] && $compress != 0) {
            compress_img($config['upload_path'] . $data['file_name'], $data['file_ext'], 80);
        }

        // 本地外上传到cdn
        if ($data['is_image']) {
            $upload_to_cdn = upload_to_cdn($data['full_path'], $data['raw_name'], substr($data['file_ext'], 1));
            if ($upload_to_cdn['error'] < 0) {
                return $upload_to_cdn;
            }
            $return['file_path'] = $upload_to_cdn['file_url'];
        } else {
            $return['file_path'] = base_url($upload_path . $data['file_name']);
        }
        $return['url'] = $return['file_path'];
        $return['local_full_path'] = $data['full_path'];
        $return['error'] = 0;
        $return['msg'] = '上传成功';
    } else {
        $return['error'] = -1;
        $return['msg'] = $up['msg'];
    }
    return $return;
}

/**
 * 特殊文件上传，针对android上传文件
 * @param string $file_path
 * @param int $file_size
 * @param $upload_path
 * @return array
 */
function upload_files_no_ext($file_path = '', $file_size = 0, $upload_path = '') {
    if (! $file_path || ! $file_size) {
        return array('error' => -1, 'msg' => '未选择上传文件');
    }

    $mine = mime_content_type($file_path);
    $CI = &get_instance();
    $mimes = get_mimes();
    $CI->config->load('upload');
    $upload = config_item('upload');

    $file_mime = mime_content_type($file_path);

    $file_ext = '';
    foreach ($mimes as $k => $v) {
        if (is_array($v) && in_array($file_mime, $v)) {
            $file_ext = $k;
            break;
        } elseif ($v == $file_mime) {
            $file_ext = $k;
            break;
        }
    }

    if (! $file_ext) {
        return array('error' => -1, 'msg' => '非法文件类型');
    } elseif (! in_array($file_ext, explode('|', $upload['allowed_types']))) {
        return array('error' => -1, 'msg' => '不允许的文件类型');
    } elseif ($file_size > $upload['max_size'] * 1024) {
        return array('error' => -1, 'msg' => '超过了上传限制大小');
    }

    $is_image = false;
    if (in_array($file_ext, array(
        'png',
        'gif',
        'jpeg',
        'jpg'))) {
        $is_image = true;
    }

    $file_ext = '.' . $file_ext;
    $raw_name = md5(microtime(true));
    $flie_name = $raw_name . $file_ext;

    if (move_uploaded_file($file_path, $upload_path . $flie_name)) {
        return array(
            'error' => 0,
            'msg' => 'success',
            'data' => array(
                'is_image' => $is_image,
                'file_name' => $flie_name,
                'file_ext' => $file_ext,
                'full_path' => $upload_path . $flie_name,
                'raw_name' => $raw_name));
    } else {
        return array('error' => -1, 'msg' => '文件转移失败，请重试');
    }
}

/**
 * compress_img()
 * 压缩图片
 * @param string $img_path
 * @param integer $quality
 * @param string $ext
 * @return void
 */
function compress_img($img_path = '', $ext = '.jpg', $quality = 80) {
    switch ($ext) {
        case '.png':
            $quality = ($quality - 100) / 11.111111;
            $quality = round(abs($quality));
            $im = @imagecreatefrompng($img_path);
            @imagepng($im, $img_path, $quality);
            @imagedestroy($im);
            break;
        case '.jpg':
        case '.jpeg':
            $im = @imagecreatefromjpeg($img_path);
            @imagejpeg($im, $img_path, $quality);
            @imagedestroy($im);
            break;
    }
}

/**
 * rotate_img()
 * 旋转图片
 * @param string $img_path
 * @param string $ext
 * @param integer $quality
 * @return void
 */
function rotate_img($img_path = '', $file_ext = '.jpg') {
    switch($file_ext) {
        case '.jpg':
        case '.jpeg':
            $im = @imagecreatefromjpeg($img_path);
            break;
        case '.png':
            $im = @imagecreatefrompng($img_path);
            break;
        case '.gif':
            $im = @imagecreatefromgif($img_path);
            break;
    }
    $rotate = imagerotate($im, -90, 0);
    @imagepng($rotate, $img_path);
    @imagedestroy($im);
}

/**
 * crop_img()
 * 裁剪图片
 * @param string $crop x*y 宽*高
 * @param string $img_path
 * @return void
 */
function crop_img($crop = '', $img_path = '') {
    $CI = &get_instance();
    $crop = explode('*', $crop);
    //剪裁的宽高
    $crop_width = (int)$crop[0];
    $crop_height = (int)$crop[1];
    if ($crop_width || $crop_height) {
        $CI->load->library('image_lib');
        list($width, $height) = getimagesize($img_path);
        $config['source_image'] = $img_path;
        $config['maintain_ratio'] = true;
        if ($width >= $height) {
            $config['master_dim'] = 'height';
        } else {
            $config['master_dim'] = 'width';
        }

        if (! $crop_width) {
            $crop_width = $width * $crop_height / $height;
        } elseif (! $crop_height) {
            $crop_height = $height * $crop_width / $width;
        }

        $config['width'] = $crop_width;
        $config['height'] = $crop_height;
        $CI->image_lib->initialize($config);
        $CI->image_lib->resize();
        $config['maintain_ratio'] = false;
        list($width, $height) = getimagesize($img_path);
        if (! ($width == $crop_width && $height == $crop_height)) {
            if ($width > $crop_width) {
                $config['x_axis'] = ($width - $crop_width) / 2;
            } else {
                $config['y_axis'] = ($height - $crop_height) / 2;
            }
            $CI->image_lib->initialize($config);
            $CI->image_lib->crop();
        }
    }
}

/**
 * jsonencode_ch()
 * json后保持中文
 * @param mixed $arr
 * @return
 */
function jsonencode_ch($arr = array()) {
    if (phpversion() >= 5.4) {
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    } else {
        return json_encode(url_encode($arr));
    }
}

/**
 * mkdirs()
 * 创建目录
 * @param mixed $dir
 * @param integer $mode
 * @return void
 */
function mkdirs($dir, $mode = 0777) {
    if (! file_exists($dir)) {
        mkdirs(dirname($dir), $mode);
        mkdir($dir, $mode);
        file_put_contents($dir . '/index.html', '');
    }
}

/**
 * 是否是今天
 * @param $time
 */
function is_today($time)
{
    $today = date('Ymd');
    return date('Ymd',$time) == $today ? true : false;
}

/**
 * 取当前url
 * @return string
 */
function current_url()
{
    $http =  isset($_SERVER['https'])? 'https://':'http://';
    return $http.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

/**
 * 取一级域名
 * @param $host
 * @return string
 */
function get_first_domain($host)
{
    return substr($host, (strpos($host, '.')));
}

 function get_excute_time($startTime)
{
    return number_format(microtime(true) - $startTime, 3, '.', '');
}

if (!function_exists('getallheaders')) {
    function getallheaders() {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

/**
 * 编译模板
 *
 * @param $template
 * @param $data
 *
 * @return mixed
 */
function compile_template($template, $data)
{
    $pattern_variable = '/\{\{([\w\d]+)(?:\.([\w\d]+))?(?:\|([\d]+))?\}\}/';
    preg_match_all($pattern_variable, $template, $matches, PREG_SET_ORDER);
    $result = $template;
    foreach ($matches as $match) {
        $value = '';
        if (! empty($data[$match[1]])) {
            $value = $data[$match[1]];
        }
        if (! empty($match[2])) {
            if (isset($value[$match[2]])) {
                $value = $value[$match[2]];
            } else {
                $value = '';
            }
        }
        if (! $value) {
            continue;
        }
        if (! empty($match[3])) {
            $value = cn_substr($value, $match[3]);
        }
        if (! is_string($value) && ! is_numeric($value)) {
            continue;
        }
        $result = str_replace($match[0], $value, $result);
    }
    return $result;
}

/**
 * 获取配置变量
 *
 * @param $key
 * @param $default
 * @return null | string
 */
function get_variable ($key , $default = null)
{
    if (! $key) {
        return null;
    }
    $ci = get_instance();
    $ci->load->model('variable_model');
    $variable = $ci->variable_model->get_row($key);
    if ($variable && isset($variable['sValue'])) {
        $json = json_decode($variable['sValue'], true);
        return (JSON_ERROR_NONE===json_last_error())?$json:$variable['sValue'];
    }
    return $default;
}


/**
 * 检查模板参数
 *
 * @param $template
 * @param $data
 *
 * @return bool
 */
function check_temp_data($template, $data)
{
    $variable_pattern = '/\{\{(\w+)\.DATA\}\}/';
    preg_match_all($variable_pattern, $template, $matches);
    foreach ($matches[1] as $match) {
        if (! isset($data[$match])) {
            return $match;
        } else if (is_array($data[$match]) && ! isset($data[$match]['value'])) {
            return $match;
        }
    }
    return true;
}


/**
 * 编译模板
 *
 * @param $template
 * @param $data
 *
 * @return mixed
 */
function compile_temp($template, $data)
{
    $variable_pattern = '/\{\{(\w+)\.DATA\}\}/';
    preg_match_all($variable_pattern, $template, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $key = $match[1];
        if (is_array($data[$key])) {
            $value = $data[$key]['value'];
            if (! empty($data[$key]['color'])) {
                $value = sprintf('<span style="color: %s">%s</span>', $data[$key]['color'], $value);
            }
        } else {
            $value = $data[$key];
        }
        $template = str_replace('{{'.$key.'.DATA}}', $value, $template);
    }

    //$template = stripslashes($template);//如果有转义，则反转一下
    $modeIf='/\{\{if\s+([\w]+)\.DATA([\>,\<,=]{1,2}[\w]+)\}\}/';
    $modeEndIf='/\{\{\/if\}\}/';
    $modeElse='/\{\{else\}\}/';
    if(preg_match($modeIf,$template) && preg_match($modeEndIf,$template)){ //if{}else{}
        $template = preg_replace($modeIf,"<?php if(\$data['$1']$2){?>",$template);
        $template = preg_replace($modeEndIf,"<?php } ?>",$template);
        if(preg_match($modeElse,$template)){
            $template = preg_replace($modeElse,"<?php }else{ ?>",$template);
        }
        $logPath = config_item('log_path');
        $logPath = $logPath ? rtrim($logPath, '/') . DIRECTORY_SEPARATOR : APPPATH . 'logs' . DIRECTORY_SEPARATOR;
        $temp_file = $logPath.'temp_'.time().'.php';
        file_put_contents($temp_file,$template);

        ob_start();
        ob_implicit_flush(false);
        require($temp_file);
        unlink($temp_file);
        return trim(ob_get_clean());
    }
    return $template;
}

/**
 * 统计代码生成KEY函数
 * @return string
 */
function get_nex_to_key() {
    $key = md5($_SERVER['SCRIPT_NAME']);
    $CI = &get_instance();
    $id = $CI->input->get('id', '');
    if(empty($id) || trim($id) == '')
    {
        $peroid_str = $CI->input->get('peroid_str', '');
		if(!empty($peroid_str))
		{
			if(trim($peroid_str) != '')
			{
				$key = $peroid_str;
			}
		}
    }
    else
    {
        $key = $id;
    }
    return $key;
}

/**
 * memcached加锁
 * @param $key
 */
function set_lock($key)
{
   return true;
}

/**
 * memcached解锁
 * @param $key
 */
function unset_lock($key)
{
   return true;
}

/**
 * 按比例随机取出数组单元
 *
 * @param $ratio_arr
 *
 * @return int|string
 */
function array_ratio_random($ratio_arr) {
    if (100 !== array_sum($ratio_arr)) {
        return -1;
    }
    $rand = mt_rand(0, 99);
    $next = 0;
    foreach ($ratio_arr as $key => $item) {
        $current = $next;
        $next += $item;
        if ($rand >= $current && $rand < $next) {
            return $key;
        }
    }
}


/**
 * 分配开奖时间
 * @param $heat 商品热度
 * @param $velocity 加速速率
 * @param $rate 时间基准率
 * @param $rand 随机数范围
 * @return int
 */
function lottery_datetime($heat,$velocity = 1,$rate = 1,$rand = array())
{
    if(is_string($rand)) $rand = json_decode($rand);
    if(empty($rand) || !is_array($rand)) $rand = array(0.5,1.5);

    $rand = rand($rand[0]*10,$rand[1]*10)/10;
    return intval($heat*$velocity*$rate*$rand);
}


/**
 * 随机任务数
 * @param $number 总参与次数
 * @param array $conf   参与次数比例
 * @return int
 */
function lottery_rand_task($number,$conf = array())
{
    if(is_string($conf)) $conf = json_decode($conf);
    if(empty($conf) || !is_array($conf)) $conf = array('1'=>40,'2'=>5,'5'=>10,'10'=>5,'20'=>5,'100'=>1);

    //总参与次数要比正常多一次，防止中间有次任务失败或异常情况导致奖品开不出来
    $number = $number * 0.2 > 20 ? $number + $number * 0.2 : $number + 20;
    $number = ceil($number);
    $rand_num = array();
    while($number > 0){
        $rand_num[] = $rand = lottery_rand_number($number,$conf);
        $number = $number - $rand;
    }

    return $rand_num;
}


/**
 * 随机数
 * @param $number   随机数的最大值
 * @param array $conf  随机数出现的权重比例
 * @param float $cardinal  基数
 * @return int
 */
function lottery_rand_number($number,$conf = array(),$cardinal = 0.2)
{
    $number = intval($number); //强制转成整
    if($number<= 0 || empty($conf)) return 0;

    //配置数大于$nubmer，则去掉
    foreach($conf as $key => $val){
        if($key > $number){
            unset($conf[$key]);
        }
    }

    $max = array_sum($conf);
    $rand = mt_rand(1,100);
    $keys = array_keys($conf);

    $return = 0;
    if($rand <= $max){ //随机数常用配置范围内
        $total = 0;
        foreach($conf as $key => $val){
            $total = $val+$total;
            if($rand <= $total){
                $return = $key;
                break;
            }
        }
    }else{//不在配置范围内
        while(empty($return)){
            $number_cardinal = $number*$cardinal > 2 ? intval($number*$cardinal) : 2;
            $return = mt_rand(1,$number >= 10 ? ($number*$cardinal > 500 ? 500 : $number_cardinal) : $number);
        }
    }

    return $return;
}

/**
 * 判断是否为机器人用户
 * @param $uin
 */
function is_robot($uin)
{
    if (strlen($uin) != 18 || !is_numeric($uin)) {
        return null;
    }
    return substr($uin, 0, 2) == 10 ? true : false;
}