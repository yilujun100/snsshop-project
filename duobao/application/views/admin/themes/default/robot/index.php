<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="robot_uin">Uin</label>
                <input type="text" name="robot_uin" id="robot_uin" value="<?=empty($robot_uin)?'':$robot_uin;?>" class="uin">
            </p>
            <p>
                <label for="robot_nickname">昵称</label>
                <input type="text" name="robot_nickname" id="robot_nickname" value="<?=empty($robot_nickname)?'':$robot_nickname;?>" style="width: 120px;">
            </p>
            <!--<p>
                <label for="robot_gender">性别</label>
                <select name="robot_gender" id="robot_gender">
                    <option value="-1">请选择</option>
                    <?php /*foreach (Lib_Constants::$genders as $k => $v) {*/?>
                        <option value="<?/*=$k*/?>" <?/*=$k == $robot_gender ? 'selected' : ''*/?>><?/*=$v*/?></option>
                    <?php /*}*/?>
                </select>
            </p>-->
            <p>
                <label for="robot_state">状态</label>
                <select name="robot_state" id="robot_state">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$robot_states as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $robot_state ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'robot/index','button'=>Lib_Constants::BTN_OK,'id'=>'robot-search-do'))?>
                <?php $this->widget('button', array('node'=>'robot/index','button'=>Lib_Constants::BTN_RESET,'id'=>'robot-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="15%">Uin</th>
            <th width="20%">昵称</th>
            <th width="15%">头像</th>
            <th width="10%">创建日期</th>
            <th width="15%">状态</th>
            <th width="25%" style="min-width: 270px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="6">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iUin']?></td>
                <td class="text-left"><?=$v['sNickName']?></td>
                <td><img src="<?=$v['sHeadImg']?>" style="width:80px; height:80px;"></td>
                <td><?=date(DATE_FORMATTER, $v['iCreateTime'])?></td>
                <td>
                    <?php
                        if (Lib_Constants::ROBOT_STATE_DISABLED == $v['iState']) {
                            if (-1 == $v['iDisableTime']) {
                                echo '永久禁用';
                            } else {
                                echo '禁用到 ' . date(TIME_FORMATTER, $v['iDisableTime']);
                            }
                        } else {
                            echo Lib_Constants::$robot_states[$v['iState']];
                        }
                    ?>
                </td>
                <td data-id="<?=$v['iUin']?>">
                    <?php
                    if (Lib_Constants::ROBOT_STATE_ENABLED == $v['iState']) {
                        $this->widget('button', array('node'=>'robot/state','button'=>Lib_Constants::BTN_INACTIVE,'class'=>'robot-state','attr'=>array('data-op'=>'disable')));
                    } else if (Lib_Constants::ROBOT_STATE_DISABLED == $v['iState']) {
                        $this->widget('button', array('node'=>'robot/state','button'=>Lib_Constants::BTN_ACTIVATED,'class'=>'robot-state','attr'=>array('data-op'=>'enable')));
                    }
                    ?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="6"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<div class="form-con" id="state-popup" style="display: none">
    <form class="form" id="state-form">
        <div class="f-item">
            <p>
                <input type="radio" name="disable_type" value="1" checked="checked">
                <label for="disable_day">禁用天数</label>
                <input type="text" name="disable_day" id="disable_day" placeholder="禁用天数" style="margin-left: 5px; width: 100px;">
            </p>
        </div>
        <div class="f-item">
            <p>
                <input type="radio" name="disable_type" value="2">
                <label for="disable_type">永久禁用</label>
            </p>
        </div>
        <input type="hidden" name="robot_state" value="2">
        <input type="hidden" name="robot_uin" value="">
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $search_do = $('#robot-search-do'),
            $search_rest = $('#robot-search-reset'),
            $state = $('.robot-state'),
            $popup = $('#state-popup').yForm('禁用机器人', 420),
            $form = $('#state-form'),
            validator;

        $search_do.click(function () {
            var search = {},
                search_str,
                robot_uin = $.trim($('#robot_uin').val()),
                robot_nickname = $.trim($('#robot_nickname').val()),
//                robot_gender = parseInt($.trim($('#robot_gender').val()), 10),
                robot_state = parseInt($('#robot_state').val(), 10);
            if (robot_uin) {
                search.robot_uin = robot_uin;
            }
            if (robot_nickname) {
                search.robot_nickname = robot_nickname;
            }
            /*if (robot_gender > -1) {
                search.robot_gender = robot_gender;
            }*/
            if (robot_state > -1) {
                search.robot_state = robot_state;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('robot/index')?>' + '?' + search_str);
        });

        $search_rest.click(function () {
            $.yRedirect('<?=node_url('robot/index')?>');
        });

        validator = $form.validate({
            rules: {
                disable_type: {required: true},
                disable_day: {disable_day: true}
            },
            submitHandler: function () {
                var url = '<?=node_url('robot/state')?>';
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('禁用成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });

        $state.click(function () {
            var $this = $(this),
                op = $(this).attr('data-op'),
                url = '<?=node_url('robot/state')?>',
                uin = $(this).parent().attr('data-id'),
                state;
            if ('disable' == op) {
                validator.resetForm();
                $form[0].reset();
                $form.find('input[name="robot_uin"]').val(uin);
                $popup.yForm('open');
                return;
            } else {
                state = 1;
                $this.yAjax({
                    url: url,
                    data: {robot_state: state, robot_uin: uin},
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('启用成功');
                            $.yRefresh(1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });

        $.validator.addMethod('disable_day', function (value) {
            if (1 == $(':radio[name="disable_type"]:checked', $form).val()) {
                return value && /^[1-9][0-9]*$/.test(value);
            }
            return true;
        }, '请输入正确的禁用天数');
    });
</script>