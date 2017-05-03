<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?=$site_name?></title>
    <!-- style base -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/base.css?v=<?=$version?>">
    <!-- style common -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/common.css?v=<?=$version?>">
    <!-- style layout -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/layout.css?v=<?=$version?>">
    <link rel="stylesheet" href="/<?=$theme_dir?>css/layer_skin_extend.css?v=<?=$version?>">
    <?php foreach ($css as $v) {?>
    <link rel="stylesheet" href="/<?=$theme_dir?>css/<?=$v?>.css?v=<?=$version?>">
    <?php }?>
    <!-- jquery lib js -->
    <script type="text/javascript" src="/<?=$theme_dir?>js/jquery-1.9.1.min.js?v=<?=$version?>"></script>
    <script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-custom.min.js?v=<?=$version?>"></script>
    <script type="text/javascript" src="/<?=$theme_dir?>js/jquery.common.admin.min.js?v=<?=$version?>"></script>
    <!-- common js -->
    <script type="text/javascript" src="/<?=$theme_dir?>js/layer/layer.js?v=<?=$version?>"></script>
    <script type="text/javascript" src="/<?=$theme_dir?>js/lib.js?v=<?=$version?>"></script>
    <?php foreach ($js as $v) {?>
    <script type="text/javascript" src="/<?=$theme_dir?>js/<?=$v?>.js?v=<?=$version?>"></script>
    <?php }?>
    <?php foreach ($third as $v) {if ('.css' === strrchr($v, '.css')) {?>
    <link rel="stylesheet" href="/<?=$v?>?v=<?=$version?>">
    <?php } else if ('.js' === strrchr($v, '.js')) { ?>
    <script type="text/javascript" src="/<?=$v?>?v=<?=$version?>"></script>
    <?php }}?>
</head>
<body>
<!-- topbar start -->
<div class="topbar">
    <h1 class="title fl"><?=$site_name?></h1>
    <div class="menu fl">
        <ul>
            <?php foreach ($admin_menus as $v) {?>
                <li><a href="<?=empty($v['node'])?'javascript:;':$menu_dir . $v['node']?>" <?=empty($v['current'])?'':'class="current"'?> ><?=$v['name']?></a></li>
            <?php }?>
        </ul>
    </div>
    <div class="topbar-opera fr">
        <?php if(empty($user)) {?>
            <a href="/admin/login/index">登录</a></a>
        <?php } else {?>
            <a href="javascript:;"><?=$user['sNickName'];?></a> | <a href="/admin/login/logout">退出<i class="icon-signout"></i></a>
        <?php }?>
    </div>
</div>
<!-- topbar end -->