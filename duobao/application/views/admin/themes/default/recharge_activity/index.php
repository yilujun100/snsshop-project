<div class="table-hd clearfix" xmlns="http://www.w3.org/1999/html">
    <h3 class="table-tit fl">充值购券优惠活动</h3>
    <span class="table-button fr">
        <a href="javascript:;" class="btn-opera btn-opera-primary" id="addBtn">新增充值购券优惠活动</a>
    </span>
</div>
<div class="table-con">
    <?php
    $edit_lit = array();
    if ($activity_list['count']) {
    ?>
    <table class="table">
        <thead>
            <tr>
                <th width="5%" rowspan="2">充值购券活动ID</th>
                <th width="20%" rowspan="2">活动说明</th>
                <th width="20%" colspan="3" >配置</th>
                <th width="10%" rowspan="2">上线日期</th>
                <th width="10%" rowspan="2">下线日期</th>
                <th width="5%" rowspan="2">状态</th>
                <th width="" rowspan="2">操作</th>
            </tr>
            <tr>
                <th>编号</th>
                <th>>=购买夺宝券数</th>
                <th>赠送夺宝券数</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($activity_list['list'])){
            foreach ($activity_list['list'] as $activity) {
                $edit_lit[$activity['iActivityId']] = array(
                    'platform' => Lib_Constants::$platforms[$activity['iPlatForm']],
                    'desc' => $activity['sDesc'],
                    'conf' => json_decode($activity['sConf'],true),
                    'start' => date('Y-m-d',$activity['iStartTime']),
                    'end' => date('Y-m-d',$activity['iEndTime'])
                );
                $conf_arr = $activity['sConf'] ? json_decode($activity['sConf'], true) : '';
                $len = count($conf_arr);
                $rowspan = $len>1 ? 'rowspan="'.$len.'"' : '';
            ?>
                <tr>
                    <td <?=$rowspan?>><?=$activity['iActivityId']?></td>
                    <td <?=$rowspan?>><?=$activity['sDesc']?></td>
                    <td>1</td>
                    <td><?=$conf_arr[0]['c']?></td>
                    <td><?=$conf_arr[0]['s'] ? $conf_arr[0]['s'] : ' - '?></td>
                    <td <?=$rowspan?>><?=date('Y-m-d', $activity['iStartTime'])?></td>
                    <td <?=$rowspan?>><?=$activity['iEndTime'] ? date('Y-m-d', $activity['iEndTime']) : '无限'?></td>
                    <td <?=$rowspan?>><?=Lib_Constants::$publish_states[$activity['iState']]?></td>
                    <td <?=$rowspan?>>
                        <?php
                            Lib_Constants::get_publish_opt_btn($activity['iActivityId'], 'recharge_activity', $activity['iState']);
                        ?>
                    </td>
                </tr>
                <?php
                if($conf_arr) {
                    $i =0;
                    foreach($conf_arr as $conf) {
                        $i++;
                        if($i<2) continue;
                        ?>
        <tr>
                        <td><?=$i?></td>
                        <td><?=$conf['c']?></td>
                        <td><?=$conf['s'] ? $conf['s'] : ''?></td>
        </tr>
                    <?php }} else {
                    ?>

                <?php
                } ?>
            <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10">
                    <?php $this->widget('pagination', $activity_list); ?>
                </td>
            </tr>
        </tfoot>
    </table>
        <?php
        } else {
            echo "暂无记录!!!";
        }
        ?>
</div>
<style>
    .f-item .dl-1 input[type="text"]{
        width: 50px;height: 16px;text-align:left; margin-top:2px;
    }
</style>
<!-- 添加 -->
<div id="addView" style="display: none">
    <div class="pop-detail-item" >
        <form method="post" id="promptForm" action="#">
            <div class="f-item">
                <label for="platform">平台</label>
                <span>
                    <p>
                        <?php foreach(Lib_Constants::$platforms as $key=>$val) { ?>
                            <input type="radio" name="platform" value="<?=$key?>" class="chose-input"><label class="chose-label"><?=$val?></label>
                        <? } ?>
                    </p>
                </span>
            </div>
            <div class="f-item">
                <label for="gift_count">券设置</label>
                <label class="txt-label">
                    <dl class="dl-1">
                        <dd>
                            <dl class="dl-2">
                                <dt class="dd-1">坑位</dt>
                                <dt class="dd-2">购买张数</dt>
                                <dt class="dd-2">赠送张数</dt>
                            </dl>
                            <?php
                            $i = 1;
                            for($i=1;$i<8;$i++) {
                            ?>
                                <dl class="dl-2">
                                    <dd class="dd-1"><?=$i?></dd>
                                    <dt class="dd-2"><span>>=</span><input data-idx="<?=$i-1?>"  type="text" name="key[]" value="" style="float: none;margin-left:5px; "/></dt>
                                    <dd class="dd-2"><input type="text"   name="val[]" value=""/></dd>
                                </dl>
                            <?php
                            }
                            ?>
                        </dd>
                    </dl>
                </label>
            </div>
            <div class="f-item">
                <label for="end_time">上线时间</label>
                <input type="text" name="start_time" value="" class="start_time" readonly />
            </div>
            <div class="f-item">
                <label for="end_time">下线时间</label>
                <input type="text" name="end_time"  value="" class="end_time" readonly />
            </div>
            <div class="f-item">
                <label for="desc">活动说明</label>
                <textarea name="desc" id="desc" cols="30" rows="10" style="width: 300px;height: 80px;" placeholder="请输入活动说明"></textarea>
            </div>
            <div class="f-item">
                <label></label>
                <button type="submit" class="btn-form btn-form-submit">确认</button>
                <button type="reset" class="btn-form btn-form-cancel">重置</button>
            </div>
        </form>
    </div>
</div>

<!-- 添加 -->
<div id="editView" style="display: none">
    <div class="pop-detail-item" >
        <form method="post" id="promptForm" action="#">
            <div class="f-item">
                <label for="platform">充值购券活动ID</label>
                <label class="txt-label" id="activity_id"></label>
                <input type="hidden" name="id" value=""/>
            </div>
            <div class="f-item">
                <label for="awards_type">平台</label>
                <label class="txt-label" id="platform"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">券设置</label>
                <label class="txt-label">
                    <dl class="dl-1">
                        <dd>
                            <dl class="dl-2">
                                <dt class="dd-1">坑位</dt>
                                <dt class="dd-2">购买张数</dt>
                                <dt class="dd-2">赠送张数</dt>
                            </dl>
                            <?php
                            $i = 1;
                            for($i=1;$i<8;$i++) {
                                ?>
                                <dl class="dl-2">
                                    <dd class="dd-1"><?=$i?></dd>
                                    <dt class="dd-2">>=<input type="text" name="key[]" value="" style="float: none;margin-left:5px; "/></dt>
                                    <dd class="dd-2"><input type="text"   name="val[]" value=""/></dd>
                                </dl>
                            <?php
                            }
                            ?>
                        </dd>
                    </dl>
                </label>
            </div>
            <div class="f-item">
                <label for="start_time">上线时间</label>
                <label class="txt-label" id="start_time"></label>
            </div>
            <div class="f-item">
                <label for="end_time">下线时间</label>
                <input type="text" name="end_time" value="" class="end_time" readonly />
            </div>
            <div class="f-item">
                <label for="desc">活动说明</label>
                <textarea name="desc" id="desc" cols="60" rows="5" style="width: 300px;height: 80px;" placeholder="请输入活动说明"></textarea>
            </div>
            <div class="f-item">
                <label></label>
                <button type="submit" class="btn-form btn-form-submit">确认</button>
                <button type="reset" class="btn-form btn-form-cancel">重置</button>
            </div>
        </form>
    </div>
</div>
<script>


$(function(){
    var pageRows = <?=json_encode($edit_lit)?>;
    $.validator.addMethod('isUnsigned', function(value,element) {
        if(value <= 0) {
            return false
        }
        return true;
    },'数字必须大于0');

    $.validator.addMethod('recharge', function(value,element) {
        if(value == ''){
            return true;
        }
        if(value <= 0) {
            return false
        }
        return true;
    },'请输入正确充值金额');

    $.validator.addMethod('recharge_require', function(value,element) {
        var val = [];
        $('.ui-dialog input[name="key[]"').each(function(i){
            if($(this).val()) {
                val[i] = $(this).val();
            }
        })
        if(val.length == 0) {
            return false
        }
        return true;
    },'请输入充值金额');
    ADMIN.recharge_activity = {
        edit : function($params, $id) {
            $uri = $id ? '/admin/recharge_activity/edit' : '/admin/recharge_activity/add';
            ADMIN._post($uri, $params);
        },
        dialogConfA : {
            title:'充值购券优惠活动 - 新增',height:700
        },
        dialogConfE : {
            title:'充值购券优惠活动 - 编辑',height:700
        },
        validate : {
            rules: {
                platform: {required: true},
                'key[]': {recharge_require:true, recharge:true},
                desc: {required: true,maxlength:500},
                start_time: {required: true, date: true}
            },
            messages: {
                platform: {required: '请选择奖励类型'},
                'key[]': {},
                desc: {required: '请填说明',maxlength:'最多500字'},
                start_time: {required: true, date: true}
            },
            submitHandler: function (form) {
                var id = parseInt($(form.id).val());
                ADMIN.recharge_activity.edit($(form).serialize(),id);
            }
        },
        resetInit : function($obj, $tid, $id) {
            var $default_start = $default_end = '+1w';
            switch ($tid) {
                case '#editView':
                    $($obj).find('input[name=id]').attr('value', $id);
                    $($obj).find('#activity_id').html($id);
                    $($obj).find('#platform').html(pageRows[$id]['platform']);
                    for(var $i= 0,$len = pageRows[$id]['conf'].length; $i< $len; $i++) {
                        $($obj).find('input[name="key[]"]').eq($i).attr('value',pageRows[$id]['conf'][$i].c);
                        $($obj).find('input[name="val[]"]').eq($i).attr('value',pageRows[$id]['conf'][$i].s);
                    }
                    $($obj).find('#start_time').html(pageRows[$id]['start']);
                    $($obj).find('.end_time').attr('value',pageRows[$id]['end']);
                    $($obj).find('#desc').html(pageRows[$id]['desc']);
                    break;
                default :
            }

            $($obj).find('#promptForm').validate(ADMIN.recharge_activity.validate);
            $(".popup .start_time").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onClose: function( selectedDate ) {
                    $(".popup .start_time" ).datepicker( "option", "minDate", selectedDate );
                }
            });
            $(".popup .end_time").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onClose: function( selectedDate ) {
                    $(".popup .end_time" ).datepicker( "option", "minDate", selectedDate );
                }
            });
        }
    };

    $('#addBtn').on('click', function(){
        ADMIN.Opts.edit('', 'recharge_activity','#addView');
    });
})
</script>