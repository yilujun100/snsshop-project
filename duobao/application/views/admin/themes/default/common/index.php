<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="welcome">
    <h3><i class="icon-status-ok"></i>登陆成功</h3>
    <p>欢迎登陆<strong><?=$user['sName']?></strong>,上次登陆时间<b><?=empty($user['iLastLoginTime'])?'':date(TIME_FORMATTER, $user['iLastLoginTime'])?></b></p>
</div>