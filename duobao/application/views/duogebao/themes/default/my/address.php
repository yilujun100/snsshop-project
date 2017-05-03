<link rel="stylesheet" href="<?=$resource_url?>css/layer_skin_extend.css">
<link rel="stylesheet" href="<?=$resource_url?>css/mobiscroll/mobiscroll.animation.css">
<link rel="stylesheet" href="<?=$resource_url?>css/mobiscroll/mobiscroll.icons.css">
<link rel="stylesheet" href="<?=$resource_url?>css/mobiscroll/mobiscroll.frame.css">
<link rel="stylesheet" href="<?=$resource_url?>css/mobiscroll/mobiscroll.scroller.css">
<div class="viewport v-receipt-addr">
    <input type="hidden" id="addrStatus" value="<?=(empty($list) ? 0 : 1)?>"> <!-- 0: 地址为空 1：地址不为空 -->
    <input type="hidden" id="addr-id" value="<?=(empty($list) ? 0 : $list['iAddressID'])?>">
    <input type="hidden" id="order_id" value="<?=(empty($order_id)?'':$order_id)?>">
    <!-- address new -->
    <div class="addr-manage addr-new">
        <div class="grid receiving-addr-new">
            <form action="" class="form-addr form-addr-new">
                <p>
                    <input type="text" name="consignee" id="consignee" placeholder="收货人">
                </p>
                <p>
                    <input type="number" name="tel" id="tel" placeholder="联系电话">
                </p>
                <div class="item-addr-area">
                    <!-- <input type="text" name="area" id="area" placeholder="省、市、区"> -->
                    <ul id="addrArea" style="display: none;"></ul>
                    <input type="hidden" name="addrArea" id="addrAreaClone" />
                </div>
                <p>
                    <input type="text" name="addrDetail" id="addrDetail" placeholder="详细地址">
                </p>
            </form>
        </div>
        <a href="javascript:;" class="btn btn-block btn-error btn-addr btn-addr-save" id="addrNewSave"><span>保存</span></a>
    </div>

    <!-- address edit -->
    <?php if(!empty($list)){ ?>
        <div class="addr-manage addr-edit" style="display:none;">
            <div class="grid receiving-addr-edit">
                <form action="" class="form-addr form-addr-edit">
                    <p>
                        <input type="text" name="consigneeEdit" id="consigneeEdit" placeholder="收货人" value="<?=$list['sName']?>">
                    </p>
                    <p>
                        <input type="number" name="telEdit" id="telEdit" placeholder="联系电话" value="<?=$list['sMobile']?>">
                    </p>
                    <div class="item-addr-area" id="itemAddrEditArea" data-area-edit="<?=$list['sProvince']?> <?=$list['sCity']?> <?=$list['sDistrict']?>">
                        <!-- <input type="text" name="areaEdit" id="areaEdit" placeholder="省、市、区" value="广东 深圳市 南山区"> -->
                        <ul id="addrArea1" style="display: none;"></ul>
                        <input type="hidden" name="addrArea1" id="addrAreaClone1" />
                    </div>
                    <p>
                        <input type="text" name="addrDetailEdit" id="addrDetailEdit" placeholder="详细地址" value="<?=$list['sAddress']?>">
                    </p>
                </form>
            </div>
            <a href="javascript:;" class="btn btn-block btn-error btn-addr btn-addr-save" id="addrEditSave"><span>保存</span></a>
        </div>
    <?php } ?>

    <div class="pop-mask-addr"></div>
    <div class="pop-addr-confirm">
        <p id="config_name"></p>
        <p id="config_tel"></p>
        <p id="config_area"></p>
        <p id="config_address" class="pop-addr-detail"></p>
        <p>注：请仔细核对您的收货地址,我们将对该地址进行发货。</p>
        <a href="javascript:;" class="btn btn-block btn-error btn-pop-addr-confirm"><span>确认收货地址</span></a>
        <a href="javascript:;" class="pop-close"></a>
    </div>

</div>
<script>
    $(function(){
        DUOBAO.show_confirm = <?=$show_confirm?>;
        DUOBAO.isEmptyAddr = $('#addrStatus').val();
        DUOBAO.url = {
            'save_addr' : '<?=(gen_uri('/address/ajax_save'))?>',
            'redirect_url' : '<?=(empty($redirect_url) ? gen_uri('/my/index') : $redirect_url)?>',
            'confirm_addr' : '<?=gen_uri('/my/ajax_addr_confirm')?>'
        }

    })

</script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.core.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.frame.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.scroller.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.select.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.listbase.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/mobiscroll.treelist.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/mobiscroll/i18n/mobiscroll.i18n.zh.js"></script>
<script type="text/javascript" src="<?=$resource_url?>js/addr_manage.js" type="text/javascript"></script>
<script src="<?=$resource_url?>js/regiondata.js" type="text/javascript"></script>
