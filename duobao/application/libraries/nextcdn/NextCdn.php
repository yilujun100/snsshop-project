<?php

require('CdnConfig.php');

class NextCdn {

    public function __construct() {
        $this->file_name = null;
        $this->file_type = null;

    }

    private function _transfer($file_stream = null, $file_name = null, $file_type = null) {
        $ch = curl_init(NEXTCDN . '?file_name=' . $file_name . '&file_type=' . $file_type . '&platform=' . PLATFORM); //. '?action=' . $action
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $file_stream);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); //强制协议为1.0
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:")); //头部要送出'Expect: '
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); //强制使用IPV4协议解析域名

        $result = curl_exec($ch);
        $rs = curl_getinfo($ch);
        //$this->writelog('请求时间', $rs);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }

    function upload_file($file_path = null, $file_name = null, $file_type = null) {
        //var_dump($file_type);exit;
        return $this->_transfer(file_get_contents($file_path), $file_name, $file_type);
    }

    function upload_file_raw($stream = null, $file_name = null, $file_type = null) {
        //var_dump(file_get_contents($file_path . DS . $file_name . '.' . $file_type));exit;
        return $this->_transfer($stream, $file_name, $file_type);
    }

    public function writelog($name, $array) {
        $filename = date('Y-m-d H', strtotime('now'));
        $f = fopen('./log/' . $filename . '.txt', 'ab+');
        fwrite($f, date('Y-m-d H:i:s', strtotime("now")) . ":  $name   " . json_encode($array) . "\n");
        fclose($f);
    }

}

//$cdn_obj = new next_cdn();
//var_dump(json_decode($cdn_obj->upload_file('../imgstore', 'cc', 'jpg')), true);

