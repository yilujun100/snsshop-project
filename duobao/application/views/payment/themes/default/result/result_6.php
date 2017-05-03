<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$buy_type_desc?>成功 拼团</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="">
    <meta name="Copyright" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/base.css">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/common.css">
    <link rel="stylesheet" href="<?=$resource_url?>groupon/css/layout.css">
</head>
<body>
<div class="viewport v-payment">
    <!-- pay success start -->
    <div class="pay-success">
        <h3><i class="icon-ok"></i>支付成功</h3>
        <p>恭喜你，<strong>「<?=$buy_type_desc?>」</strong>成功！<br>快分享给小伙伴们拼起来！<br>活动商品数量有限哦！</p>
        <a href="<?=$redirect_url?>" class="btn-pay-share">分享给小伙伴</a>
    </div>
    <!-- pay success end -->
</div>
</body>
</html>