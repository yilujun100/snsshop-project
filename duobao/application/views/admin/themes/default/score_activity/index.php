<div class="table-hd clearfix">
    <h3 class="table-tit fl">积分兑换活动</h3>
    <span class="table-button fr">
        <a href="javascript:;" class="btn-opera btn-opera-primary" id="addBtn">新增积分兑换活动</a>
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
                <th width="5%">兑换单ID</th>
                <th width="5%">兑换商品ID</th>
                <th width="15%">兑换商品名称</th>
                <th width="5%">兑换商品总数</th>
                <th width="5%">所需积分数量</th>
                <th width="5%">优惠积分数量</th>
                <th width="5%">单人单次兑换个数</th>
                <th width="5%">单人最多兑换个数</th>
                <th width="10%">上线日期</th>
                <th width="10%">下线日期</th>
                <th width="5%">状态</th>
                <th width="">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($activity_list['list'])){
            foreach ($activity_list['list'] as $activity) {
                $edit_lit[$activity['iActivityId']] = array(
                    'goods_id' => $activity['iGoodsId'],
                    'platform' => Lib_Constants::$platforms[$activity['iPlatForm']],
                    'goods_name' =>$activity['sGiftName'],
                    'total' =>$activity['iTotal'],
                    'single' =>$activity['iSingle'],
                    'max' =>$activity['iMaxLimit'],
                    'ori' => $activity['iOriScore'],
                    'pre' => $activity['iPreScore'],
                    'start' => date('Y-m-d',$activity['iStartTime']),
                    'end' => date('Y-m-d',$activity['iEndTime']),
                );
            ?>
                <tr>
                    <td><?=$activity['iActivityId']?></td>
                    <td><?=$activity['iGoodsId']?></td>
                    <td><?=$activity['sGiftName']?></td>
                    <td><?=$activity['iTotal']?></td>
                    <td><?=$activity['iOriScore']?></td>
                    <td><?=$activity['iPreScore']?></td>
                    <td><?=$activity['iSingle']?></td>
                    <td><?=$activity['iMaxLimit']?></td>
                    <td><?=date('Y-m-d', $activity['iStartTime'])?></td>
                    <td><?=$activity['iEndTime'] ? date('Y-m-d', $activity['iEndTime']) : '无限'?></td>
                    <td><?=Lib_Constants::$publish_states[$activity['iState']]?></td>
                    <td>
                        <?php
                            Lib_Constants::get_publish_opt_btn($activity['iActivityId'], 'score_activity', $activity['iState']);
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
                <td colspan="12">
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
                <label for="gift_count">商品ID</label>
                <input type="text" class="w80" name="goods_id" value="0" class="goods_id" id="goods_id" onblur="ADMIN.score_activity.getGoodsInfo(this)" onfocus="ADMIN.score_activity.resetGrouponInfo(this)"/>
            </div>
            <div class="f-item">
                <label for="gift_count">商品数量</label>
                <input type="text" class="w80" name="total_limit" value="0" id="total_limit" />
            </div>
            <div class="f-item">
                <label for="gift_count">所需积分数量</label>
                <input type="text" class="w80" name="ori_score" value="0" id="ori_score" />
            </div>
            <div class="f-item">
                <label for="gift_count">优惠积分数量</label>
                <input type="text" class="w80" name="pre_score" value="0" id="pre_score" />
            </div>

            <div class="f-item">
                <label for="gift_count">单人单次限购数</label>
                <input type="text" class="w80" name="single" value="0" id="single" />
            </div>
            <div class="f-item">
                <label for="gift_count">单人最多购买数</label>
                <input type="text" class="w80" name="max" value="0" id="max" />
            </div>
            <div class="f-item">
                <label for="start_time">上线时间</label>
                <input type="text" name="start_time" value="" class="start_time" readonly />
            </div>
            <div class="f-item">
                <label for="end_time">下线时间</label>
                <input type="text" name="end_time" value="" class="end_time" readonly />
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
                <label for="platform">积分兑换活动ID</label>
                <label class="txt-label" id="activity_id"></label>
                <input type="hidden" name="id" value=""/>
            </div>
            <div class="f-item">
                <label for="awards_type">平台</label>
                <label class="txt-label" id="platform"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">商品</label>
                <label class="txt-label" id="goods"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">数量</label>
                <label class="txt-label" id="total"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">所需积分数量</label>
                <label class="txt-label" id="ori"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">优惠积分数量</label>
                <input type="text" class="w80" name="pre_score"  id="pre" value="0" id="pre_score" />
            </div>
            <div class="f-item">
                <label for="gift_count">单人单次限购数</label>
                <label class="txt-label" id="single"></label>
            </div>
            <div class="f-item">
                <label for="gift_count">单人最多购买数</label>
                <label class="txt-label" id="max"></label>
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
    ADMIN.score_activity = {
        getGoodsInfo : function($obj) {
            $uri = '/admin/score_activity/goods_info'
            ADMIN._post($uri,{goods_id:$obj.value}, function($data) {
                if(!$($obj).parent().parent().find('#goods_info')) {
                    $($obj).parent().parent().find('#goods_info .txt-label').html($data.name)
                    $($obj).parent().parent().find('#goods_info').show();
                } else {
                    var $con = [];
                    $con.push('<div class="f-item" id="goods_info">');
                    $con.push('<label></label>');
                    $con.push('<label class="txt-label">');
                    $con.push($data.name);
                    $con.push('</label>');
                    $con.push('</div>');
                    $($obj).parent().after($con.join(''));
                }
            });
        },
        resetGrouponInfo : function($obj) {
            $($obj).parent().parent().find('#goods_info').hide();
        },
        edit : function($params, $id) {
            $uri = $id ? '/admin/score_activity/edit' : '/admin/score_activity/add';
            ADMIN._post($uri, $params);
        },
        dialogConfA : {
            title:'积分兑换活动 - 新增'
        },
        dialogConfE : {
            title:'积分兑换活动 - 编辑'
        },
        validate : {
            rules: {
                platform: {required: true},
                goods_id: {required: true, number: true, isUnsinged:true,},
                total_limit: {required: true, number: true, isUnsinged:true},
                ori_score: {required: true, number: true, isUnsinged:true},
                pre_score: {required: true, number: true},
                single: {required: true, number: true, isUnsinged:true},
                max: {required: true, number: true, isUnsinged:true}
            },
            messages: {
                platform: {required: '请选择奖励类型'},
                goods_id: {required: '请输入商品ID', number: '必须是数字'},
                total_limit: {required: '请输入商品数量', number: '必须是数字'},
                ori_score: {required: '请输入所需积分数量', number: '必须是数字'},
                pre_score: {required: '请输入优惠积分数量', number: '必须是数字'},
                single: {required: '请输入单人单次限购数量', number: '必须是数字'},
                max: {required: '请输入单人最多购买限制数量', number: '必须是数字'}
            },
            submitHandler: function (form) {
                var id = parseInt($(form.id).val());
                ADMIN.score_activity.edit($(form).serialize(),id);
            }
        },
        resetInit : function($obj, $tid, $id) {
            var $default_start = $default_end = '+1w';
            switch ($tid) {
                case '#editView':
                    $($obj).find('input[name=id]').attr('value', $id);
                    $($obj).find('#activity_id').html($id);
                    $($obj).find('#platform').html(pageRows[$id]['platform']);
                    $($obj).find('#goods').html(pageRows[$id]['goods_name']);
                    $($obj).find('#total').html(pageRows[$id]['total']);
                    $($obj).find('#single').html(pageRows[$id]['single']);
                    $($obj).find('#ori').html(pageRows[$id]['ori']);
                    $($obj).find('#pre').attr('value', pageRows[$id]['pre']);
                    $($obj).find('#max').html(pageRows[$id]['max']);
                    $($obj).find('#single').html(pageRows[$id]['single']);
                    $($obj).find('#start_time').html(pageRows[$id]['start']);
                    $($obj).find('.end_time').attr('value',pageRows[$id]['end']);
                    break;
                default :
            }

            $($obj).find('#promptForm').validate(ADMIN.score_activity.validate);
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
        ADMIN.Opts.edit('', 'score_activity','#addView');
    });
})
</script>