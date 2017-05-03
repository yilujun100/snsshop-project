<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<link type="text/css" href="/<?=$theme_dir?>css/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/<?=$theme_dir?>css/jquery-ui-timepicker-addon.css?v=<?=$version?>">
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-addon.js?v=<?=$version?>"></script>
<script type="text/javascript" src="/<?=$theme_dir?>js/jquery-ui-timepicker-zh-CN.js?v=<?=$version?>"></script>
<div class="form-hd clearfix">
    <h3 class="form-tit">添加/编辑夺宝单</h3>
</div>
<div class="form-con">
    <form class="form form-label-145" id="act-form">
        <div class="f-item">
            <label for="goodsId">商品ID<b class="required">*</b></label>
            <input type="text" name="goodsId" id="goodsId" placeholder="商品ID" value="<?=empty($item['iGoodsId'])?'':$item['iGoodsId']?>">
        </div>
        <div class="f-item f-item-tips goods-tips hide">
            <label></label>
            <p><span>成本价：</span><span style="margin-left: 0;" class="cost-price"></span></p>
            <p><span>最低售价：</span><span style="margin-left: 0;" class="lowest-price"></span></p>
            <p><span>商品名称：</span><span style="margin-left: 0;" class="goods-name"></span></p>
        </div>
        <div class="f-item">
            <label for="goodsId">商品名称<b class="required">*</b></label>
            <input type="text" name="goodsName" id="goodsName" placeholder="商品ID" value="<?=empty($item['sGoodsName'])?'':$item['sGoodsName']?>">
        </div>
        <div class="f-item">
            <label for="actHeat">机器人开奖热度<b class="required">*</b></label>
            <input type="text" name="actHeat" id="actHeat" placeholder="机器人开奖热度" value="<?=empty($item['iHeat'])?'':$item['iHeat']?>">
            <p class="form-item-tips">单位：分钟；控制机器人自动购码速度，值越小购买越快（即开奖越快）；不小于10</p>
        </div>
        <div class="f-item">
            <label for="actCodePrice">单个夺宝码价格<b class="required">*</b></label>
            <span>
                <?php $first = true; foreach (Lib_Constants::$code_price_opt as $v) {?>
                    <p><input type="radio" name="actCodePriceRadio" value="<?=$v?>"
                        <?php
                            if ($first && ! isset($item['codePriceRadio'])) {
                                echo 'checked="checked"';
                            } else if (isset($item['codePriceRadio']) && $v == $item['codePriceRadio']) {
                                echo 'checked="checked"';
                            }
                        ?>
                        ><label><?=intval($v / 100)?>元</label></p>
                <?php ;$first = false;}?>
                <p><input type="radio" name="actCodePriceRadio" value="-1" <?=isset($item['codePriceRadio'])&&-1==$item['codePriceRadio']?'checked="checked"':''?>><label>其他</label></p>
                <p><input type="text" name="actCodePriceCustom" placeholder="自定义" value="<?=empty($item['iCodePrice'])?'':price_format($item['iCodePrice'])?>" <?=isset($item['codePriceRadio'])&&-1==$item['codePriceRadio']?'':'class="hide"'?> style="width: 80px"></p>
            </span>
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
            <label for="actLotCount">开奖码数<b class="required">*</b></label>
            <input type="text" name="actLotCount" id="actLotCount" placeholder="开奖码数" value="<?=empty($item['iLotCount'])?'':$item['iLotCount']?>">
        </div>
        <div class="f-item f-item-tips lot-tips hide">
            <label></label>
            <p><span>单期实际收入：</span><span style="margin-left: 0;" class="total-price"></span></p>
            <p><span>利润率为：</span><span style="margin-left: 0;" class="lot-profit"></span></p>
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
            <label for="actBegin">开始时间<b class="required">*</b></label>
            <input type="text" name="actBegin" id="actBegin" class="date datetime" value="<?=empty($item['iBeginTime'])?date(TIME_FORMATTER):date(TIME_FORMATTER, $item['iBeginTime'])?>">
        </div>
        <div class="f-item">
            <label for="actEnd">结束时间<b class="required">*</b></label>
            <input type="text" name="actEnd" id="actEnd" class="date datetime" value="<?=empty($item['iEndTime'])?date(TIME_FORMATTER, strtotime('+3 month')):date(TIME_FORMATTER, $item['iEndTime'])?>">
        </div>
        <div class="f-item">
            <label></label>
            <button type="submit" class="btn-form btn-form-submit">提交</button>
            <button type="reset" class="btn-form btn-form-cancel">取消</button>
            <input type="hidden" name="op" value="<?=empty($item)?'add':'edit'?>">
        </div>
    </form>
</div>

<script type="text/javascript">
    $(function () {
        var $form = $('#act-form'),
            $goods = $('#goodsId', $form),
            $codePriceRadio = $(':radio[name="actCodePriceRadio"]', $form),
            $codePriceCustom = $(':text[name="actCodePriceCustom"]', $form),
            $actBegin = $('#actBegin', $form),
            $actEnd = $('#actEnd', $form),
            $cancel = $('.btn-form-cancel', $form),
            validator;

        $cancel.click(function () {
            $.yRedirect('<?=node_url('active/index')?>');
        });

        $actBegin.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });
        $actEnd.datetimepicker({
            showSecond: true,
            timeFormat: "HH:mm:ss",
            dateFormat: "yy-mm-dd"
        });
        /*$actBegin.datepicker({
            defaultDate: new Date(),
            minDate: new Date(),
            changeMonth: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function( selectedDate ) {
                $actEnd.datepicker( "option", "minDate", selectedDate );
            }
        });*/

        /*$actEnd.datepicker({
            defaultDate: "+3m",
            changeMonth: true,
            numberOfMonths: 1,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            onClose: function( selectedDate ) {
                $actBegin.datepicker( "option", "maxDate", selectedDate );
            }
        });*/

        validator = $form.validate({
            rules: {
                goodsId: {digits: true, min: 1, goodsId: true},
                actHeat: {digits: true, min: 10},
                actCodePriceRadio: {required: true},
                actCodePriceCustom: {
                    required: {
                        depends: function () {
                            return -1 == $codePriceRadio.filter(':checked').val();
                        }
                    },
                    price: true,
                    min: 1
                },
                actLotCount: {required: true, digits: true, min: 1, lotCount: true},
                actPeriodCount: {required: true, digits: true, min: 1},
                actPeriodBuyCount: {required: true, digits: true, min: 1, periodBuyCount: true},
                actBuyCount: {required: true, digits: true, min: 1, buyCount: true},
                actBegin: {required: true, date: true},
                actEnd: {required: true,  date: true}
            },
            submitHandler: function () {
                var op = $('input[name="op"]', $form).val(),
                    url;
                if (-1 === $.inArray(op, ['add', 'edit'])) {
                    return;
                }
                if ('add' === op) {
                    url = '<?=node_url('active/add')?>';
                } else {
                    url = '<?=node_url('active/edit')?>' + '/<?=empty($item['iActId'])?'':$item['iActId']?>';
                }
                $form.yAjax({
                    url: url,
                    data: $form.serialize(),
                    success: function (data) {
                        if (0 === data.retCode) {
                            $.ySuccess('添加/编辑成功');
                            $.yRedirect('<?=node_url('active/index')?>', 1000);
                        } else {
                            $.yError(data.retMsg);
                        }
                    }
                });
            }
        });

        <?php if (! empty($item)) {?>
            $goods.data('goods', {id: '<?=$item['iGoodsId']?>', name: '<?=$item['sGoodsName']?>', cost:'<?=$item['iCostPrice']?>',lowest:'<?=$item['iLowestPrice']?>'});
        <?php }?>

        $goods.keyup(function () {
            $('#actLotCount').val('');
        });

        $codePriceRadio.click(function () {
            var $this = $(this),
                val = $this.filter(':checked').val();
            if (-1 == val) {
                $codePriceCustom.yShow();
            } else {
                validator.element($codePriceCustom);
                $codePriceCustom.yHide();
            }
        });

        $.validator.addMethod("goodsId", function(value, element) {
            var deferred = $.Deferred(),
                $this = $(element),
                $tips = $this.parent().next('.goods-tips');
            $.ajax({
                url: "<?=node_url('goods/get_goods_info')?>" + '/' + value,
                async: false,
                dataType: "json",
                success: function(data) {
                    $tips.yHide();
                    if (0 === data.retCode) {
                        deferred.resolve();
                        $this.data('goods', data.retData);
                        $tips.find('.cost-price').text($.yFormatPrice(data.retData.cost));
                        $tips.find('.lowest-price').text($.yFormatPrice(data.retData.lowest));
                        $tips.find('.goods-name').text(data.retData.name);
                        $tips.yShow();
                        if( $('#goodsName').val() == ''){
                            $('#goodsName').val(data.retData.name);
                        }
                    } else {
                        $.yVadAssign('goodsId', data.retMsg);
                        $this.removeData('goods');
                        deferred.reject();
                    }
                }
            });
            return deferred.state() === "resolved" ? true : false;
        });

        $.validator.addMethod("lotCount", function(value, element) {
            var $this = $(element),
                $tips = $this.parent().next('.lot-tips'),
                codePriceRadio = $.yToInt($codePriceRadio.filter(':checked').val()),
                goods = $goods.data('goods'),
                codePrice, lotPrice;
            $tips.yHide();
            if (! goods) {
                $.yVadAssign('lotCount', 'depend', '商品ID');
                return false;
            }
            if (-1 == codePriceRadio) {
                if ($codePriceCustom.val() < 1) {
                    $.yVadAssign('lotCount', 'depend', '单个夺宝码价格');
                    return false;
                }
                codePrice = $codePriceCustom.val() * 100;
            } else {
                codePrice = codePriceRadio;
            }
            lotPrice = codePrice * value;
            if (lotPrice < goods.lowest && lotPrice != 0) {
                $.yVadAssign('lotCount', 'greater_equal', '单期实际收入', '商品最低售价', '【单期实际收入=「单个夺宝码价格」x「开奖码数」】');
                return false;
            }

            $tips.find('.total-price').text($.yFormatPrice(lotPrice));
            $tips.find('.lot-profit').text($.yFormatPercent((lotPrice - goods.lowest) / goods.lowest));
            $tips.yShow();

            return true;
        });

        $.validator.addMethod("periodBuyCount", function(value) {
            var periodBuyCount = $.yToInt(value);
            if (periodBuyCount > $.yToInt($('#actLotCount').val())) {
                $.yVadAssign('periodBuyCount', 'lesser_equal', '单人单期最多购买码数', '开奖码数');
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
