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
                    <p>
                        <em>获得奖品</em>
                        <span><?=(isset($deliver['sExtField'][1]) ? date('Y-m-d H:i:s',$deliver['sExtField'][1]) : '')?></span>
                    </p>
                </li>
                <li class="<?=($deliver['status'] == 2 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 2 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <?php if($deliver['status'] == 2){ ?>
                        <?php if(!empty($addr)){ ?>
                            <div class="step-addr-wrap">
                                <span>
                                    <em>填写收货信息</em>
                                    <p class="addr-info" id="updata-addr">
                                        <?=$addr['sName']?><br/>
                                        <?=$addr['sProvince']?> <?=$addr['sCity']?> <?=$addr['sDistrict']?> <?=$addr['sAddress']?><i class="step-arrow"></i>
                                    </p>
                                </span>
                                <a href="javascript:;" id="addrConfirm">确认收货信息</a>
                            </div>
                        <?php }else{ ?>
                            <p>
                                <em>填写收货信息</em>
                                <a href="javascript:;" id="addrWrite">填写收货信息</a>
                            </p>
                        <?php } ?>
                    <?php }else{ ?>
                        <p>
                            <em>填写收货信息</em>
                            <span><?=(isset($deliver['sExtField'][2]) ? date('Y-m-d H:i:s',$deliver['sExtField'][2]) : '')?></span>
                        </p>
                    <?php } ?>
                    <s></s>
                </li>
                <li class="<?=($deliver['status'] == 3 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 3 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p>
                        <em>奖品待发货</em>
                        <span><?=(isset($deliver['sExtField'][3]) ? date('Y-m-d H:i:s',$deliver['sExtField'][3]) : ($deliver['status'] == 2 ? '' : '等待发货'))?></span>
                    </p>
                    <s></s>
                </li>
                <li class="<?=($deliver['status'] == 4 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 4 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p>
                        <em>奖品已发货</em>
                        <span><?=(isset($deliver['sExtField'][4]) ? date('Y-m-d H:i:s',$deliver['sExtField'][4]) : '')?></span>
                    </p>
                    <s></s>
                </li>
                <li class="<?=($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p>
                        <em>确认收货</em>
                        <?php if($deliver['iConfirmStatus'] == Lib_Constants::DELIVER_NOT_CONFIRM_STATUS && $deliver['status'] == 5){ ?>
                            <a href="javascript:void(0)" id="confirm-deliver">确认收货</a>
                        <?php }else{ ?>
                            <span><?=(isset($deliver['sExtField'][5]) ? date('Y-m-d H:i:s',$deliver['sExtField'][5]) : '')?></span>
                        <?php } ?>
                    </p>
                    <s></s>
                </li>
                <li  class="<?=($deliver['status'] == 6 ? 'step-current' : '')?>">
                    <i class="<?=($deliver['status'] == 6 ? 'dotted dotted-on' : 'dotted')?>"></i>
                    <p>
                        <em>奖品已签收</em>
                        <?php if($deliver['status'] == 6 && $is_active_order){?>
                            <a href="<?=gen_uri('/share/add',array('period_code'=>$peroid_str))?>">晒单</a>
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
                <p>
                    <label>收货人：</label>
                    <span><?=$deliver['sName']?></span>
                </p>
                <p>
                    <label>联系电话：</label>
                    <span><?=$deliver['sMobile']?></span>
                </p>
                <p>
                    <label>邮寄地址：</label>
                    <span><?=$deliver['sAddress']?></span>
                </p>
            </div>
        </div>
    <?php } ?>

    <!-- prize info -->
    <?php if(!empty($detail)){ ?>
        <div class="grid column addr-prize-info mt-10">
            <div class="column-hd">
                <h3>奖品信息</h3>
            </div>
            <div class="addr-prize-info-con">
                <div class="prize-pic">
                    <img src="<?=$detail['sImg']?>" width="84" height="84" alt="">
                </div>
                <div class="prize-info-basic">
                    <h3 class="prize-name"><?=$detail['sName']?></h3>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    $(function(){
        $('#addrConfirm').click(function(){
            layer.confirm('亲，确认地址后不能修改哦~~', {
                    title: false,
                    closeBtn: false
                },
                function(){
                    layer.closeAll();
                    DUOBAO._post('<?=gen_uri('/my/ajax_addr_confirm')?>',{'order_id':'<?=$order_id?>'},function($res){
                        if($res.retCode == 0){
                            layer.msg('确认成功~~',function(){
                                location.reload();
                            });
                        }else{
                            layer.msg('确认失败~~');
                        }
                    })
                });
        });

        $('#confirm-deliver').click(function(){
            layer.confirm('亲，确认之前请确认收到商品哦~~', {
                    title: false,
                    closeBtn: false
                },
                function(){
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
                });
        })

        $('#updata-addr,#addrWrite').click(function(){
            location.href = '<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/order_add_address',array('order_id'=>$order_id))))?>';
        })
    })
</script>