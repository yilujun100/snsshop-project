<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=!empty($page_title)?$page_title:"百分好礼"?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="Copyright" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!-- style base -->
    <link rel="stylesheet" href="<?=$cdn_common_url?>css/base.css?v=<?=$version?>">
    <!-- style common -->
    <link rel="stylesheet" href="<?=$resource_url?>css/common.css?v=<?=$version?>">
    <!-- layer skin extend style -->
    <link rel="stylesheet" href="<?=$cdn_common_url?>css/layer_skin_extend.css?v=<?=$version?>">
    <!-- style swiper -->
    <link rel="stylesheet" href="<?=$cdn_third_url?>js/swiper/swiper.min.css?v=<?=$version?>">
    <!-- style layout -->
    <?php foreach ($css as $v) { ?>
        <link rel="stylesheet" href="<?=$cdn_project_url?>css/<?=$v?>.css?v=<?=$version?>">
    <?php } ?>
    <!-- jquery lib script -->
    <script type="text/javascript" src="<?=$cdn_common_url?>js/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript" src="<?=$cdn_third_url?>layer/layer.js?ver=<?=$version?>"></script>
    <script type="text/javascript" src="<?=$cdn_common_url?>js/jquery.common.js?ver=<?=$version?>"></script>
    <script>
        if(typeof DUOBAO == 'undefined') {
            var DUOBAO = {};
        }
        DUOBAO.redirect_uri = {wxuser:'<?=$passport_wx_url?>'};
    </script>
    <?php foreach ($js as $v) { ?>
        <script type="text/javascript" src="<?=$cdn_project_url?>js/<?=$v?>.js?v=<?=$version?>"></script>
    <?php } ?>
    <?php foreach ($third as $v) { if ('.css' === strrchr($v, '.css')) { ?>
        <link rel="stylesheet" href="<?=$cdn_third_url?>/<?=$v?>?v=<?=$version?>">
    <?php } else if ('.js' === strrchr($v, '.js')) { ?>
        <script type="text/javascript" src="<?=$cdn_third_url?>/<?=$v?>?v=<?=$version?>"></script>
    <?php }} ?>
</head>