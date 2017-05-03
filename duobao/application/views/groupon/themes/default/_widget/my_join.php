<?php
//我的团列表
!isset($my_join_list['list']) OR $my_join_list = $my_join_list['list'];
if (!empty($my_join_list)) {
    foreach ($my_join_list as $item) {
        $sign = gen_sign($item['iDiyUin'], $item['iDiyId']);
?>
    <div class="grid group-item mt-10">
        <div class="goods-info">
            <div class="goods-pic">
                <img src="<?=$item['sImg']?>" width="84" height="84" alt="">
            </div>
            <div class="goods-info-basic">
                <div class="goods-name"><?=$item['sGoodsName']?></div>
                <div class="goods-tag"><label><?=$item['iIsColonel'] ? Lib_Constants::$groupon_order[Lib_Constants::GROUPON_ORDER_DIY]:Lib_Constants::$groupon_order[Lib_Constants::GROUPON_ORDER_JOIN]?></label></div>
                <div class="goods-price clearfix"><span class="fl"><?=$item['iPeopleNum']?>人团&nbsp;&nbsp;<strong>¥ <?=price_format($item['iGrouponPrice'])?></strong></span><span class="fr"><em class="status-group status-g-success"><?=Lib_Constants::$groupon_diy_states[$item['iState']]?></em></span></div>
            </div>
        </div>
        <div class="group-item-bott">
            <a href="<?=gen_uri('/active/diy_detail', array('diy_id'=>$item['iDiyId'], 'sign'=>$sign), 'groupon')?>" class="btn-view-group">查看团</a>
            <a href="<?=gen_uri('/order/detail', array('order_id'=>$item['sJoinOrderId']), 'groupon')?>" class="btn-view-order">查看订单</a>
        </div>
    </div>
<?php } } ?>