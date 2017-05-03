<div class="viewport v-prize-addr">
    <!-- prize status -->
    <div class="grid column prize-status">
        <div class="column-hd">
            <h3>奖品状态</h3>
        </div>
        <div class="prize-status-con">
            <ul>
                <li>
                    <i class="dotted"></i>
                    <p class="clearfix">
                        <em class="fl"><?=$deliver['iType']==1?'获得':'兑换'?>奖品</em>
                        <span class="fr"><?=(isset($deliver['sExtField'][1]) ? date('Y-m-d H:i:s',$deliver['sExtField'][1]) : '')?></span>
                    </p>
                </li>
                <li class="<?=($deliver['status'] == 2 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 2 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <?php if($deliver['status'] == 2){ ?>
                        <?php if(!empty($addr)){ ?>
                            <div class="step-addr-wrap clearfix" >
                                    <span class="fl">
                                        <em>填写收货信息</em>
                                        <p class="addr-info" id="updata-addr"><?=$addr['sProvince']?> <?=$addr['sCity']?> <?=$addr['sDistrict']?> <?=$addr['sAddress']?> <?=$addr['sName']?><i class="step-arrow"></i></p>
                                    </span>
<!--                                <a href="javascript:void(0)" class="btn btn-error fr" id="addrWrite"><span>填写收货信息</span></a>-->
                                <a href="javascript:;" class="btn btn-error fr" id="addrConfirm"><span>确认收货信息</span></a>
                            </div>
                        <?php }else{ ?>
                            <div class="step-addr-wrap clearfix">
                                    <span class="fl">
                                        <em>填写收货信息</em>
                                    </span>
                                <!--                                <a href="javascript:void(0)" class="btn btn-error fr" id="addrWrite"><span>填写收货信息</span></a>-->
                                <a href="javascript:;" class="btn btn-error fr" id="addrWrite"><span>填写收货信息</span></a>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                        <p class="clearfix">
                            <em class="fl">填写收货信息</em>
                            <span class="fr"><?=(isset($deliver['sExtField'][2]) ? date('Y-m-d H:i:s',$deliver['sExtField'][2]) : '')?></span>
                        </p>
                    <?php } ?>
                    <s></s>
                </li>
                <li class="<?=($deliver['status'] == 3 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 3 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p class="clearfix">
                        <em class="fl">奖品待发货</em>
                        <span class="fr"><?=(isset($deliver['sExtField'][3]) ? date('Y-m-d H:i:s',$deliver['sExtField'][3]) : ($deliver['status'] == 2 ? '' : '等待发货'))?></span>
                    </p>
                    <s></s>
                </li>
                <li class="<?=($deliver['status'] == 4 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 4 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p class="clearfix">
                        <em class="fl">奖品已发货</em>
                        <span class="fr"><?=(isset($deliver['sExtField'][4]) ? date('Y-m-d H:i:s',$deliver['sExtField'][4]) : '')?></span>
                    </p>
                    <s></s>
                </li>
                <li <?=($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5 ? 'step-current' : '')?>>
                    <i class="<?=($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p class="clearfix">
                        <em class="fl">确认收货</em>
                        <?php if($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5){ ?>
                            <a href="javascript:void(0)" class="btn btn-error fr" id="confirm-deliver"><span>确认收货</span></a>
                        <?php }else{ ?>
                            <span class="fr"><?=(isset($deliver['sExtField'][5]) ? date('Y-m-d H:i:s',$deliver['sExtField'][5]) : '')?></span>
                        <?php } ?>
                    </p>
                    <s></s>
                </li>
                <li class="<?=($deliver['status'] == 6 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 6 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p class="clearfix">
                        <em class="fl">奖品已签收</em>
                        <?php if($deliver['status'] == 6 && $is_active_order){?>
                            <a class="btn btn-error fr" href="<?=gen_uri('/share/add',array('period_code'=>$peroid_str))?>"><span>晒单</span></a>
                        <?php } ?>
                    </p>
                    <s></s>
                </li>
            </ul>
        </div>
    </div>

    <!-- logistics info -->
    <?php if($deliver['iDeliverStatus'] != Lib_Constants::DELIVER_NOT_CONFIRM_STATUS){ ?>
        <div class="grid column logis-info mt-10">
            <div class="column-hd">
                <h3>物流信息</h3>
            </div>
            <div class="logis-info-con">
                <p>物流公司：<?=$deliver['sExpressName']?></p>
                <p>物流单号：<?=$deliver['sExpressId']?></p>
            </div>
        </div>
    <?php } ?>

    <!-- receiving info -->
    <?php if(!empty($deliver['sName']) && !empty($deliver['sMobile'])){ ?>
        <div class="grid column receiving-info mt-10">
            <div class="column-hd">
                <h3>收货信息</h3>
            </div>
            <div class="receiving-info-con">
                <p class="clearfix">
                    <label class="fl">收货人：</label>
                    <span class="fl"><?=$deliver['sName']?></span>
                </p>
                <p class="clearfix">
                    <label class="fl">联系电话：</label>
                    <span class="fl"><?=$deliver['sMobile']?></span>
                </p>
                <p class="clearfix">
                    <label class="fl">邮寄地址：</label>
                    <span class="fl"><?=$deliver['sAddress']?></span>
                </p>
            </div>
        </div>
    <?php } ?>

    <!-- prize info -->
    <?php if(!empty($detail)){ ?>
        <div class="grid column addr-prize-info mt-10">
            <div class="column-hd">
                <h3><?=$deliver['iType']==1?'奖品信息':'兑换详情'?></h3>
            </div>
            <div class="addr-prize-info-con">
                <div class="prize-pic">
                    <a href="<?=gen_uri('/active/detail',array('id'=>$peroid_str))?>">
                        <img src="<?=$detail['sImg']?>" width="84" height="84" alt="">
                    </a>
                </div>
                <div class="prize-info-basic">
                    <h3 class="prize-name"><?=$detail['sGoodsName']?></h3>
                    <p>期号：<strong><?=$peroid_str?></strong></p>
                    <?php
                        if($deliver['iType'] == 2)
                        {
                    ?>
                            <p>商品价格：¥<?=$detail['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE?></p>
                            <p>用劵张数：<?=$detail['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE?>张</p>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- <div class="tips" style="position: relative; margin-top: 20px; bottom: inherit;"><a href="#">*点击绑定手机号码</a>可以第一时间获得奖品最新状态哦！</div> -->
    <!-- pop addr confirm start -->
    <?php if(!empty($addr)){ ?>
        <div class="pop-mask-addr"></div>
        <div class="pop-addr-confirm">
            <p>姓名：<?=$addr['sName']?></p>
            <p>手机：<?=$addr['sMobile']?></p>
            <p>地区：<?=$addr['sProvince']?> <?=$addr['sCity']?> <?=$addr['sDistrict']?></p>
            <p class="pop-addr-detail">详细地址：<?=$addr['sAddress']?></p>
            <p>注：请仔细核对您的收货地址,确认后将不可更改,我们将对该地址进行发货。</p>
            <a href="javascript:;" class="btn btn-block btn-error btn-pop-addr-confirm"><span>确认收货地址</span></a>
            <a href="javascript:;" class="pop-close"></a>
        </div>
    <?php } ?>

    <!-- pop addr confirm end -->
</div>
<script>
    $(function(){
        $('#addrConfirm').click(function(){
            $('.pop-mask-addr, .pop-addr-confirm').show();
//            layer.confirm('亲，确认地址后不能修改哦~~', {
//                    title: false,
//                    closeBtn: false
//                },
//                function(){
//                    layer.closeAll();
//                    DUOBAO._post('<?//=gen_uri('/my/ajax_addr_confirm')?>//',{'order_id':'<?//=$order_id?>//'},function($res){
//                        if($res.retCode == 0){
//                            layer.msg('确认成功~~',function(){
//                                location.reload();
//                            });
//                        }else{
//                            layer.msg('确认失败~~');
//                        }
//                    })
//                });
        });

        $('.btn-pop-addr-confirm').on('click', function(){
            $('.pop-mask-addr, .pop-addr-confirm').hide();
            layer.closeAll();
            DUOBAO._post('<?=gen_uri('/my/ajax_addr_confirm')?>',{'order_id':'<?=$order_id?>'},function($res){
                if($res.retCode == 0){
                    layer.msg('确认成功~~',{shift:-1},function(){
                        location.reload();
                    });
                }else{
                    layer.msg('确认失败~~');
                }
            })
        });

        $('#confirm-deliver').click(function(){
            DUOBAO.popWinConfirm.init('确认收到奖品吗?', '确认收货', '取消', checkDeliver);
//            layer.confirm('亲，确认之前请确认收到商品哦~~', {
//                    title: false,
//                    closeBtn: false
//                },
//                function(){
//                    layer.closeAll();
//                    DUOBAO._post('<?//=gen_uri('/my/ajax_deliver_confirm')?>//',{'order_id':'<?//=$order_id?>//'},function($res){
//                        if($res.retCode == 0){
//                            layer.msg('确认成功~~',function(){
//                                location.reload();
//                            });
//                        }else{
//                            layer.msg('确认失败~~');
//                        }
//                    })
//                });
        })

        function checkDeliver()
        {
            layer.closeAll();
            DUOBAO._post('<?=gen_uri('/my/ajax_deliver_confirm')?>',{'order_id':'<?=$order_id?>'},function($res){
                if($res.retCode == 0){
                    layer.msg('确认成功~~',function(){
                        location.reload();
                    });
                }else{
                    layer.msg('确认失败~~');
                }
            })
        }

        $('#updata-addr,#addrWrite').click(function(){
            location.href = '<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$order_id,'peroid_str'=>$peroid_str)),'is_return'=>1,'show_confirm'=>1,'order_id'=>$order_id))?>';
        })

        $(".pop-close").click(function(){
            $('.pop-mask-addr, .pop-addr-confirm').hide();
        })
    })
</script>