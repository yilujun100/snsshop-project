<div class="table-hd clearfix">
    <h3 class="table-tit fl">奖励活动</h3>
    <span class="table-button fr">
        <a href="javascript:;" class="btn-opera btn-opera-primary" id="addBtn">新增奖励活动</a>
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
                <th width="5%">奖励活动ID</th>
                <th width="5%">平台</th>
                <th width="5%">奖励类型ID</th>
                <th width="10%">奖励类型名称</th>
                <th width="5%">奖励类型英文名称</th>
                <th width="10%">奖励内容</th>
                <th width="5%">奖励数量</th>
                <th width="15%">上线日期</th>
                <th width="15%">下线日期</th>
                <th width="5%">状态</th>
                <th width="">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($activity_list['list'])){
            foreach ($activity_list['list'] as $activity) {
                $edit_lit[$activity['iAwardsId']] = array(
                    'type' => $activity['iAwardsType'],
                    'platform' => Lib_Constants::$platforms[$activity['iPlatForm']],
                    'awards_type' => $activity['sAwardsName'],
                    'eawards_type' => $activity['sAwardsType'],
                    'gift' =>Lib_Constants::$awards_prizes[$activity['iGiftType']]['name'],
                    'gift_count' => $activity['iGift'],
                    'start' => date('Y-m-d',$activity['iStartTime']),
                    'end' => date('Y-m-d',$activity['iEndTime']),
                );
            ?>
                <tr>
                    <td><?=$activity['iAwardsId']?></td>
                    <td><?=Lib_Constants::$platforms[$activity['iPlatForm']]?></td>
                    <td><?=$activity['iAwardsType']?></td>
                    <td><?=$activity['sAwardsName']?></td>
                    <td><?=$activity['sAwardsType']?></td>
                    <td><?=Lib_Constants::$awards_prizes[$activity['iGiftType']]['name']?></td>
                    <td><?=$activity['iGift']?></td>
                    <td><?=date('Y-m-d', $activity['iStartTime'])?></td>
                    <td><?=$activity['iEndTime'] ? date('Y-m-d', $activity['iEndTime']) : '无限'?></td>
                    <td><?=Lib_Constants::$publish_states[$activity['iState']]?></td>
                    <td>
                        <?php
                            Lib_Constants::get_publish_opt_btn($activity['iAwardsId'], 'awards_activity', $activity['iState']);
                        ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11">
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

<!-- 添加 -->
<div id="addView" style="display: none">
    <div class="pop-detail-item" >
        <form method="post" id="promptForm"  name="promptForm" action="#">
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
                <label for="awards_type">奖励类型</label>
                <select name="awards_type">
                    <option value="">请选择奖励类型</option>
                <?php
                if ($awards_type_list) {
                    foreach($awards_type_list as $key=>$val) {?>
                    <option value="<?=$key?>"><?=$val['name']?></option>
                <? }} ?>
                </select>
            </div>
            <div class="f-item">
                <label for="gift">奖励内容</label>
                <span>
                    <p>
                        <?php foreach(Lib_Constants::$awards_prizes as $key=>$val) {?>
                            <input type="radio" name="gift" value="<?=$key?>" class="chose-input"><label class="chose-label"><?=$val['name']?></label>
                        <? } ?>
                    </p>
                </span>
            </div>
            <div class="f-item">
                <label for="gift_count">奖励数量</label>
                <input type="text" class="w50" name="gift_count" value="0" id="gift_count" />
            </div>
            <div class="f-item">
                <label for="start_time">上线时间</label>
                <input type="text" name="start_time" value="" class="start_time" readonly />
            </div>
            <div class="f-item">
                <label for="end_time">下线时间</label>
                <input type="text" name="end_time"value="" class="end_time" readonly />
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
    <div class="pop-detail-item">
        <form method="post" id="promptForm" name="promptForm" action="#">
            <div class="f-item">
                <label for="platform">活动ID</label>
                <label class="txt-label" id="activity_id"></label>
                <input type="hidden" name="id" value=""/>
            </div>
            <div class="f-item">
                <label for="awards_type">平台</label>
                <label class="txt-label" id="platform"></label>
            </div>
            <div class="f-item">
                <label for="awards_type">奖励类型</label>
                <label class="txt-label" id="awards_type"></label>
            </div>
            <div class="f-item">
                <label for="gift">奖励内容</label>
                <label class="txt-label" id="gift"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">奖励数量</label>
                <label class="txt-label" id="gift_count"></label>
            </div>
            <div class="f-item">
                <label for="start_time">上线时间</label>
                <input type="text" name="start_time" value="" class="start_time" readonly/>
            </div>
            <div class="f-item">
                <label for="end_time">下线时间</label>
                <input type="text" name="end_time" value="" class="end_time" readonly/>
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
    var pageRows = $.parseJSON('<?=json_encode($edit_lit)?>');
    $.validator.addMethod('isUnsinged', function(value,element) {
        if(value <= 0) {
            return false
        }
        return true;
    },'数字必须大于0');
    ADMIN.awards_activity = {
        edit : function($params, $id) {
            uri = $id ? '/admin/awards_activity/edit' : '/admin/awards_activity/add';
            ADMIN._post(uri, $params);
        },
        dialogConfA : {
            title:'奖励活动 - 新增',height:380
        },
        dialogConfE : {
            title:'奖励活动 - 编辑'
        },
        validate : {
            rules: {
                platform: {required: true},
                awards_type: {required: true},
                gift: {required: true},
                gift_count: {required: true, number: true, isUnsinged:true},
            },
            messages: {
                platform: {required: '请选择平台'},
                awards_type: {required: '请选择奖励类型'},
                gift: {required: '请选择奖励内容'},
                gift_count: {required: '请输入奖励数量', number: '奖励数量必须是数字'},
                start_time: {timeSelect:'请选择开始时间'}
            },
            submitHandler: function (form) {
                var id = parseInt($(form.id).val());
                ADMIN.awards_activity.edit($(form).serialize(),id);
            }
        },
        resetInit : function($obj, $tid, $id) {
            var $default_start = $default_end = '+1w';
            switch ($tid) {
                case '#editView':
                    $($obj).find('input[name=id]').attr('value', $id);
                    $($obj).find('#activity_id').html($id);
                    $($obj).find('#platform').html(pageRows[$id]['platform']);
                    $($obj).find('#awards_type').html(pageRows[$id]['awards_type']);
                    $($obj).find('#gift').html(pageRows[$id]['gift']);
                    $($obj).find('#gift_count').html(pageRows[$id]['gift_count']);
                    $($obj).find('.start_time').attr('value',pageRows[$id]['start']);
                    $($obj).find('.end_time').attr('value',pageRows[$id]['end']);
                    break;
                default :
            }

            $($obj).find('#promptForm').validate(ADMIN.awards_activity.validate);
            $(".popup .start_time").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onClose: function( selectedDate ) {
                    $(".popup .start_time" ).datepicker( "option", "curDate", selectedDate );
                }
            });
            $(".popup .end_time").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                numberOfMonths: 1,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                onClose: function( selectedDate ) {
                    $(".popup .end_time" ).datepicker( "option", "curDate", selectedDate );
                }
            });
        }
    };

    $('#addBtn').on('click', function(){
        ADMIN.Opts.edit('', 'awards_activity','#addView');
    });
})
</script>