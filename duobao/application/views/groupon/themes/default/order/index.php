<div class="viewport v-my-order">
    <!-- my order start -->
    <div class="my-order">
        <div class="tab">
            <div class="tab-hd" id="groupon_order_tab">
                <a href="javascript:;" data-url="<?=node_url('order/ajax_my_order')?>" class="init tab-active">全部</a>
                <a href="javascript:;" data-url="<?=node_url('order/ajax_my_order?order_state=unpaid')?>">待付款</a>
                <a href="javascript:;" data-url="<?=node_url('order/ajax_my_order?order_state=undelivered')?>">待发货</a>
                <a href="javascript:;" data-url="<?=node_url('order/ajax_my_order?order_state=delivered')?>">待收货</a>
            </div>
            <div class="tab-con" id="groupon_order_con">
                <!-- 全部 -->
                <div class="content-all" <?php if (!empty($result_data['count'])) {?>style="display: block;"<?php }?>>
                    <?php if (!empty($result_data['count'])) {$this->widget('order_list', array('order_list'=>$result_data['list']));}?>
                </div>
                <!-- 待付款 -->
                <div class="content-no-paid"></div>
                <!-- 待发货 -->
                <div class="content-send"></div>
                <!-- 待收货 -->
                <div class="content-receipt"></div>
                <!-- 记录为空 -->
                <div class="content-empty" <?php if (empty($result_data['count'])) {?>style="display: block;"<?php }?>>
                    <i class="icon-list"></i>
                    <p>您还没有相应订单哦~</p>
                    <a href="<?=gen_uri('/home/index', array(), 'groupon')?>">去拼团</a>
                </div>
            </div>
            <div id="loadMore" class="Fh_laod" style="display:none;"><i></i><span class="Fh_laod-text">加载中</span></div>
            <div id="no-more" class="no-more" style="display:none;">没有更多,逛逛其他吧!</div>
            <div id="no-record" style="display: none"><img src="<?=$resource_url?>images/empty_pic.png" width="82" height="82" alt=""><h3>暂无相关记录，敬请期待哦~</h3></div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?=$cdn_common_url?>css/layer_skin_extend.css?ver=<?=$version?>">
<script type="text/javascript" src="<?=$cdn_groupon_url?>js/order.js?version=<?=$version?>"></script>