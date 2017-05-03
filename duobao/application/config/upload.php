<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//文件上传配置数组
$config = array();
$config['upload'] = array(
	'upload_path' => './data/',
	'allowed_types' => 'gif|jpg|png|xls|xlsx|ico|jpeg|JPEG|txt',
	'max_size' => '5000',
	'max_width' => 0,
	'max_height' => 0
);
?>