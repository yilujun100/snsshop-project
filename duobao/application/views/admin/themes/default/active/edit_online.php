<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<link type="text/css" href="/<?=$theme_dir?>css/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/<?=$theme_dir?>css/jquery-ui-timepicker-addon.css?v=<?=$version?>">
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-addon.js?v=<?=$version?>"></script>
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-zh-CN.js?v=<?=$version?>"></script>
<div class="form-hd clearfix">
    <h3 class="form-tit">线上编辑</h3>
</div>
<div class="form-con">
    <form class="form form-label-145" id="act-form">
        <div class="f-item">
            <label>同步更新当前期</label>
            <span>
                <p style="margin-right: -10px;"><input type="checkbox" name="actCurrentPeriod" id="actCurrentPeriod" value="1"></p>
            </span>
            <p class="form-item-tips">勾选则立即生效，不勾选则在下一期时生效</p>
        </div>
        <div class="f-item">
            <label for="actHeat">机器人开奖热度<b class="required">*</b></label>
            <input type="text" name="actHeat" id="actHeat" placeholder="机器人开奖热度" value="<?=empty($item['iHeat'])?'':$item['iHeat']?>">
            <p class="form-item-tips">单位：分钟；控制机器人自动购码速度，值越小购买越快（即开奖越快）；不小于10</p>
        </div>
        <div class="f-item">
            <label>夺宝单标签</label>
            <span>
                <p><input type="checkbox" name="actTag[]" value="1" <?=isset($item['tags']) && in_array(1, $item['tags']) ? 'checked="checked"' : ''?>><label for="actTag[]">苹果专区</label></p>
                <p><input type="checkbox" name="actTag[]" value="2" <?=isset($item['tags']) && in_array(2, $item['tags']) ? 'checked="checked"' : ''?>><label for="actTag[]">热门精选</label></p>
            </span>
        </div>
        <div class="f-item">
            <label for="goodsLowestPrice">夺宝单角标</label>
            <span>
                <?php foreach (Lib_Constants::$corner_mark as $k => $v) {?>
                    <p><input type="radio" name="actCornetMark" value="<?=$k?>" <?=isset($item['iCornerMark']) && $k == $item['iCornerMark'] ? 'checked="checked"' : ''?>><label for="actCornetMark"><?=$v['text']?></label></p>
                <?php }?>
            </span>
        </div>
        <div class="f-item">
            <label for="actPeriodCount">总期数<b class="required">*</b></label>
            <input type="text" name="actPeriodCount" id="actPeriodCount" placeholder="总期数" value="<?=empty($item['iPeroidCount'])?'':$item['iPeroidCount']?>">
        </div>
        <div class="f-item">
            <label for="actPeriodBuyCount">单人单期最多购买码数<b class="required">*</b></label>
            <input type="text" name="actPeriodBuyCount" id="actPeriodBuyCount" placeholder="单人单期最多购买码数" value="<?=empty($item['iPeroidBuyCount'])?'':$item['iPeroidBuyCount']?>">
        </div>
        <div class="f-item">
            <label for="actBuyCount">单人单次最多购买码数<b class="required">*</b></label>
            <input type="text" name="actBuyCount" id="actBuyCount" placeholder="单人单次最多购买码数" value="<?=empty($item['iBuyCount'])?'':$item['iBuyCount']?>">
        </div>
        <div class="f-item">
            <label for="actEnd">结束时间<b class="required">*</b></label>
            <input type="text" name="actEnd" id="actEnd" class="date datetime" value="<?=empty($item['iEndTime'])?date(TIME_FORMATTER, strtotime('+3 month')):date(TIME_FORMATTER, $item['iEndTime'])?>">
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#act-form'),
            $actEnd = $('#actEnd', $form),
            $cancel = $('.btn-form-cancel', $form),
            lotCount = <?=$item['iLotCount']?>;

        $cancel.click(function () {
            $.yRedirect('<?=node_url('active/index')?>');
        });

        $actEnd.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });

        $form.validate({
            rules: {
                actHeat: {digits: true, min: 10},
                actPeriodCount: {required: true, digits: true, min: 1},
                actPeriodBuyCount: {required: true, digits: true, min: 1, periodBuyCount: true},
                actBuyCount: {required: true, digits: true, min: 1, buyCount: true},
                actEnd: {required: true,  date: true}
            },
            submitHandler: function () {
                var url = '<?=node_url('active/edit_online')?>' + '/<?=$item['iActId']?>';
                $.yConfirm('确认要「线上编辑」吗？<br><br><span style="color: #ff0000">注：将直接修改线上活动，尤其是在勾选「同步更新当前期」时，操作后请认真核对相关数据！</span>', function () {
                    $form.yAjax({
                        url: url,
                        data: $form.serialize(),
                        success: function (data) {
                            if (0 === data.retCode) {
                                $.ySuccess('线上编辑成功');
//                                $.yRedirect('<?//=node_url('active/index')?>//', 1000);
                            } else {
                                $.yError(data.retMsg);
                            }
                        }
                    });
                });
            }
        });

        $.validator.addMethod("periodBuyCount", function(value) {
            var periodBuyCount = $.yToInt(value);
            if (periodBuyCount > lotCount) {
                $.yVadAssign('periodBuyCount', 'lesser_equal', '单人单期最多购买码数', '开奖码数: ' + lotCount);
                return false;
            }
            return true;
        });

        $.validator.addMethod("buyCount", function(value) {
            var buyCount = $.yToInt(value);
            if (buyCount > $.yToInt($('#actPeriodBuyCount').val())) {
                $.yVadAssign('buyCount', 'lesser_equal', '单人单次最多购买码数', '单人单期最多购买码数');
                return false;
            }
            return true;
        });
    });
</script>
