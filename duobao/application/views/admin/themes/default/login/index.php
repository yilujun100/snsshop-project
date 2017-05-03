<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>请登录-<?=$site_name?></title>
    <!-- style base -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/base.css?v=<?=$version?>">
    <!-- style common -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/common.css?v=<?=$version?>">
    <!-- style layout -->
    <link rel="stylesheet" href="/<?=$theme_dir?>css/layout.css?v=<?=$version?>">
    <!-- jquery lib js -->
    <script type="text/javascript" src="/<?=$theme_dir?>js/jquery-1.9.1.min.js?v=<?=$version?>"></script>
    <!-- common js -->
    <script type="text/javascript" src="/<?=$theme_dir?>js/lib.js?v=<?=$version?>"></script>
</head>
<body class="bg-login">
<!-- login start -->
<div class="login">
    <h1><?=$site_name?></h1>
    <?php echo form_open('', array('class'=>'form-login', 'id'=>'formLogin')); ?>
        <h3>欢迎登录</h3>
        <?php echo validation_errors(); ?>
        <input type="text" name="username" id="username" placeholder="请输入用户名">
        <input type="password" name="password" id="password" placeholder="请输入密码">
        <button type="submit" id="btn-login">登 录</button>
    </form>
</div>
<!-- login end -->
<script>
    $(function(){
        // validate @后台：此处验证规则根据实际情况而定
        $('#btn-login').on('click', function(){
            var username = $('#username');
            var password = $('#password');
            var reg = {
                username: /^[a-zA-Z0-9]{5,12}$/,
                password: /^\S{6,13}$/,
            };
            var flag = false;

            if (isEmpty('#username')) {
                $('#username').focus();
            } else if (!reg.username.test(trim(username.val()))) {
                $('#username').focus();
            } else if (isEmpty('#password')) {
                $('#password').focus();
            } else if (!reg.password.test(password.val())) {
                $('#password').focus();
            } else {
                flag = true;
            }
            if (flag) { // 验证通过 提交表单
                $('#formLogin').submit();
            } else {
                return false;
            }
        });
    })
</script>
</body>
</html>