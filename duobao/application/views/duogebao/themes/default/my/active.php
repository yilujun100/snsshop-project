<div class="viewport v-indiana-record">
    <div class="grid tab tab-record">
        <div class="tab-tit clearfix" id="indianaRecordTit">
            <a href="javascript:;" <?=($cls == 'all' ? 'class="tab-active"': '')?> data-url="<?=(gen_uri('/my/ajax_active',array('cls'=>'all')))?>" data-load="true" >全部</a>
            <a href="javascript:;" <?=($cls == 'going' ? 'class="tab-active"': '')?> data-url="<?=(gen_uri('/my/ajax_active',array('cls'=>'going')))?>" data-load="true">进行中</a>
            <a href="javascript:;" <?=($cls == 'opened' ? 'class="tab-active"': '')?> data-url="<?=(gen_uri('/my/ajax_active',array('cls'=>'opened')))?>" data-load="true">已揭晓</a>
            <a href="javascript:;" <?=($cls == 'winner' ? 'class="tab-active"': '')?> data-url="<?=(gen_uri('/my/ajax_active',array('cls'=>'winner')))?>" data-load="true">已中奖</a>
            <a href="javascript:;" <?=($cls == 'exchange' ? 'class="tab-active"': '')?> data-url="<?=(gen_uri('/my/ajax_active',array('cls'=>'exchange')))?>" data-load="true">兑换记录</a>
        </div>
        <div class="tab-con" id="indianaRecordCon">
            <!-- 全部 -->
            <div class="record-all <?=($cls != 'all' ? 'empty': '')?>" style="<?=($cls == 'all' ? 'display: block;': '')?>">
                <?php if($cls == 'all'){ ?>
                    <?php foreach($list['list'] as $li){ ?>
                        <?php if($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){ ?>
                            <div class="grid-1 record-item record-item-underway" data-code="<?=implode(',',$li['my_code'])?>">
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                        <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                    </a>
                                </div>
                                <div class="record-core-cell-1">
                                    <div class="prize-name"><?=$li['sGoodsName']?></div>
                                    <div class="list-goods-bott">
                                        <div class="list-schedule" data-lott-schedule="<?=$li['detail']['iProcess']?>%">
                                            <div class="list-progress-txt"><label>期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></label><em class="progress-bubble"><?=$li['detail']['iProcess']?>%</em></div>
                                            <div class="list-progress-bar">
                                                <span class="list-progress-bar-on"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="record-core-cell-3 clearfix">
                                        <span class="fl">您已参与<strong><?=$li['iLotCount']?></strong>人次</span>
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                    </div>
                                    <div class="record-core-opt clearfix">
                                        <a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])),'payment')?>" class="btn btn-error btn-join-again"><span>继续参与</span></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php }elseif($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_GOING){ ?>
                            <div class="grid-1 record-item" data-code="<?=implode(',',$li['my_code'])?>">
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                        <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                    </a>
                                    <label class="label-record label-warning">即将揭晓</label>
                                </div>
                                <div class="record-core-cell-1">
                                    <div class="prize-name"><?=$li['sGoodsName']?></div>
                                    <div class="record-core-cell-2 clearfix">
                                        <em class="fl">期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></em>
                                    </div>
                                    <div class="record-core-cell-3 clearfix">
                                        <div class="fl record-countDown">
                                            <em>揭晓倒计时：</em>
                                            <div class="countDown" data-start-time="<?=date('Y/m/d H:i:s')?>" data-end-time="<?=date('Y/m/d H:i:s',$li['iLotTime'])?>">
                                                00:00:000
                                                <span class="ms"></span>
                                            </div>
                                        </div>
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                    </div>
                                    <div class="record-core-opt clearfix">
                                        <a href="javascript:void(0);" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="grid-1 record-item"  <?php if($li['iIsWin'] == 0){  ?>data-code="<?=implode(',',$li['my_code'])?>"<?php } ?>>
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                        <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                    </a>
                                    <?php if($li['iIsWin']){  ?>
                                        <em class="tag-prize-win">恭喜<br>中奖</em>
                                    <?php } ?>
                                    <!-- <a href="#" class="prize-detail">奖品详情</a> -->
                                </div>
                                <div class="record-core-cell-1">
                                    <div class="prize-name"><?=$li['sGoodsName']?></div>
                                    <div class="record-core-cell-2 clearfix">
                                        <em class="fl">期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></em>
                                    </div>
                                    <div class="record-core-cell-3 clearfix">
                                        <span class="fl">您已参与<strong><?=$li['iLotCount']?></strong>人次</span>
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                    </div>
                                    <div class="record-core-cell-4">
                                        <p>获奖者：<b><?=$li['detail']['sWinnerNickname']?></b></p>
                                        <p>幸运码：<strong><?=$li['detail']['sWinnerCode']?></strong></p>
                                        <p>本期购买：<strong><?=$li['detail']['iWinnerCount']?></strong>人次</p>
                                        <p>揭晓时间：<b><?=(date('Y-m-d H:i:s',$li['detail']['iLotTime']))?></b></p>
                                    </div>
                                    <?php if($li['iIsWin']){  ?>
                                        <div class="record-core-opt clearfix">
                                            <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-default btn-default-2"><span>订单详情</span></a>
                                            <?php
                                                if($li['deliver_status'] == 2)
                                                {
                                                    if(empty($user_address))
                                                    {
                                            ?>
                                                        <a href="<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid']))),'is_return'=>1,'show_confirm'=>1,'order_id'=>$li['detail']['sWinnerOrder']))?>" class="btn btn-error"><span>填写收货信息</span></a>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <a href="javascript:void(0);" onclick="confirmAddress('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error" ><span>确认收货信息</span></a>
                                            <?php
                                                    }
                                            ?>

                                            <?php
                                                }
                                                else if($li['deliver_status'] == 4 || $li['deliver_status'] == 3 )
                                                {
                                            ?>
                                                    <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error">
                                                        <span>
                                                            <?=$li['deliver_status'] == 3?'奖品待发货':'奖品已发货' ?>
                                                        </span>
                                                    </a>
                                            <?php
                                                }
                                                else if($li['deliver_status'] == 5)
                                                {
                                            ?>
                                                    <a href="javascript:void(0);" onclick="confirmDelive('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error"><span>确认收货</span></a>
                                            <?php
                                                }
                                                else if($li['deliver_status'] == 6)
                                                {
                                                    if($li['is_active_order'])
                                                    {
                                            ?>
                                                        <a href="<?=gen_uri('/share/add',array('period_code'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>晒单</span></a>
                                            <?php
                                                    }
                                                    else
                                                    {
                                            ?>
                                                        <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>奖品已签收</span></a>
                                            <?php
                                                    }
                                                }
                                            ?>

                                        </div>
                                    <?php }else{ ?>
                                        <div class="record-core-opt clearfix">
                                            <a href="javascript:void(0);" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if(empty($list['list'])){ ?>
                        <p style="padding:10px;">暂无记录~~</p>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- 进行中 -->
            <div class="record-ongoing <?=($cls != 'going' ? 'empty': '')?>" style="<?=($cls == 'going' ? 'display: block;': '')?>">
                <?php if($cls == 'going'){ ?>
                    <?php foreach($list['list'] as $li){ ?>
                        <?php if($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_DEFAULT){ ?>
                            <div class="grid-1 record-item" data-code="<?=implode(',',$li['my_code'])?>">
                                <div class="prize-pic">
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                        <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                    </a>
                                </div>
                                <div class="record-core-cell-1">
                                    <div class="prize-name"><?=$li['sGoodsName']?></div>
                                    <div class="list-goods-bott">
                                        <div class="list-schedule" data-lott-schedule="<?=$li['detail']['iProcess']?>%">
                                            <div class="list-progress-txt"><label>期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></label><em class="progress-bubble"><?=$li['detail']['iProcess']?>%</em></div>
                                            <div class="list-progress-bar">
                                                <span class="list-progress-bar-on"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="record-core-cell-3 clearfix">
                                        <span class="fl">您已参与<strong><?=$li['iLotCount']?></strong>人次</span>
                                        <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                    </div>
                                    <div class="record-core-opt clearfix">
                                        <a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])),'payment')?>" class="btn btn-error btn-join-again"><span>继续参与</span></a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if(empty($list['list'])){ ?>
                        <p style="padding:10px;">暂无记录~~</p>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- 已揭晓 -->
            <div class="record-announced <?=($cls != 'opened' ? 'empty': '')?>" style="<?=($cls == 'opened' ? 'display: block;': '')?>">
                <?php if($cls == 'opened'){ ?>
                    <?php foreach($list['list'] as $li){ ?>
                        <div class="grid-1 record-item" <?php if($li['iIsWin'] == 0){  ?>data-code="<?=implode(',',$li['my_code'])?>"<?php } ?>>
                            <div class="prize-pic">
                                <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                    <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                </a>
                                <?php if($li['iIsWin']){  ?>
                                    <em class="tag-prize-win">恭喜<br>中奖</em>
                                <?php } ?>
                            </div>
                            <div class="record-core-cell-1">
                                <div class="prize-name"><?=$li['sGoodsName']?></div>
                                <div class="record-core-cell-2 clearfix">
                                    <em class="fl">期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></em>
                                </div>
                                <div class="record-core-cell-3 clearfix">
                                    <span class="fl">您已参与<strong><?=$li['iLotCount']?></strong>人次</span>
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                </div>
                                <div class="record-core-cell-4">
                                    <p>获奖者：<b><?=$li['detail']['sWinnerNickname']?></b></p>
                                    <p>幸运码：<strong><?=$li['detail']['sWinnerCode']?></strong></p>
                                    <p>本期购买：<strong><?=$li['detail']['iWinnerCount']?></strong>人次</p>
                                    <p>揭晓时间：<b><?=(date('Y-m-d H:i:s',$li['detail']['iLotTime']))?></b></p>
                                </div>
                                <?php if($li['iIsWin']){  ?>
                                    <div class="record-core-opt clearfix">
                                        <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-default btn-default-2"><span>订单详情</span></a>
                                        <?php
                                        if($li['deliver_status'] == 2)
                                        {
                                            if(empty($user_address))
                                            {
                                        ?>
                                                <a href="<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid']))),'is_return'=>1,'show_confirm'=>1,'order_id'=>$li['detail']['sWinnerOrder']))?>" class="btn btn-error"><span>填写收货信息</span></a>
                                        <?php
                                            }
                                            else
                                            {
                                        ?>
                                                <a href="javascript:void(0);" onclick="confirmAddress('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error" ><span>确认收货信息</span></a>
                                        <?php
                                            }
                                            ?>

                                        <?php
                                        }
                                        else if($li['deliver_status'] == 4 || $li['deliver_status'] == 3 )
                                        {
                                            ?>
                                            <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error">
                                                        <span>
                                                            <?=$li['deliver_status'] == 3?'奖品待发货':'奖品已发货' ?>
                                                        </span>
                                            </a>
                                        <?php
                                        }
                                        else if($li['deliver_status'] == 5)
                                        {
                                            ?>
                                            <a href="javascript:void(0);" onclick="confirmDelive('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error"><span>确认收货</span></a>
                                        <?php
                                        }
                                        else if($li['deliver_status'] == 6)
                                        {
                                            if($li['is_active_order'])
                                            {
                                                ?>
                                                <a href="<?=gen_uri('/share/add',array('period_code'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>晒单</span></a>
                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>奖品已签收</span></a>
                                            <?php
                                            }
                                        }
                                        ?>

                                    </div>
                                <?php }else{ ?>
                                    <div class="record-core-opt clearfix">
                                        <a href="javascript:void(0);" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>
                                    </div>
                                <?php }?>

                            </div>
                        </div>
                    <?php } ?>

                    <?php if(empty($list['list'])){ ?>
                        <p style="padding:10px;">暂无记录~~</p>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- 已中奖 -->
            <div class="record-winning <?=($cls != 'winner' ? 'empty': '')?>" style="<?=($cls == 'winner' ? 'display: block;': '')?>">
                <?php if($cls == 'winner'){ ?>
                    <?php foreach($list['list'] as $li){ ?>
                        <div class="grid-1 record-item">
                            <div class="prize-pic">
                                <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>">
                                    <img src="<?=$li['sImg']?>" width="84" height="84" alt="">
                                </a>
                                    <em class="tag-prize-win">恭喜<br>中奖</em>
                            </div>
                            <div class="record-core-cell-1">
                                <div class="prize-name"><?=$li['sGoodsName']?></div>
                                <div class="record-core-cell-2 clearfix">
                                    <em class="fl">期号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></em>
                                </div>
                                <div class="record-core-cell-3 clearfix">
                                    <span class="fl">您已参与<strong><?=$li['iWinnerCount']?></strong>人次</span>
                                    <a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn-parti-detail fr">参与详情</a>
                                </div>
                                <div class="record-core-cell-4">
                                    <p>获奖者：<b><?=$li['sWinnerNickname']?></b></p>
                                    <p>幸运码：<strong><?=$li['sWinnerCode']?></strong></p>
                                    <p>本期购买：<strong><?=$li['iWinnerCount']?></strong>人次</p>
                                    <p>揭晓时间：<b><?=(date('Y-m-d H:i:s',$li['iLotTime']))?></b></p>
                                </div>
                                    <div class="record-core-opt clearfix">
                                        <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-default btn-default-2"><span>订单详情</span></a>
                                        <?php
                                        if($li['deliver_status'] == 2)
                                        {
                                            if(empty($user_address))
                                            {
                                        ?>
                                                <a href="<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid']))),'is_return'=>1,'show_confirm'=>1,'order_id'=>$li['detail']['sWinnerOrder']))?>" class="btn btn-error"><span>填写收货信息</span></a>
                                        <?php
                                            }
                                            else
                                            {
                                        ?>
                                                <a href="javascript:void(0);" onclick="confirmAddress('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error" ><span>确认收货信息</span></a>
                                        <?php
                                            }
                                            ?>

                                        <?php
                                        }
                                        else if($li['deliver_status'] == 4 || $li['deliver_status'] == 3 )
                                        {
                                            ?>
                                            <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error">
                                                        <span>
                                                            <?=$li['deliver_status'] == 3?'奖品待发货':'奖品已发货' ?>
                                                        </span>
                                            </a>
                                        <?php
                                        }
                                        else if($li['deliver_status'] == 5)
                                        {
                                            ?>
                                            <a href="javascript:void(0);" onclick="confirmDelive('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error"><span>确认收货</span></a>
                                        <?php
                                        }
                                        else if($li['deliver_status'] == 6)
                                        {
                                            if($li['is_active_order'])
                                            {
                                                ?>
                                                <a href="<?=gen_uri('/share/add',array('period_code'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>晒单</span></a>
                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>奖品已签收</span></a>
                                            <?php
                                            }
                                        }
                                        ?>

                                    </div>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if(empty($list['list'])){ ?>
                        <p style="padding:10px;">暂无记录~~</p>
                    <?php } ?>
                <?php } ?>
            </div>
            <!-- 兑换记录 -->
            <div class="record-exchange <?=($cls != 'exchange' ? 'empty': '')?>" style="<?=($cls == 'exchange' ? 'display: block;': '')?>">
                <?php if($cls == 'exchange'){ ?>
                    <?php foreach($list['list'] as $li){ ?>
                        <div class="grid-1 record-item">
                            <div class="prize-pic">
                                <a href="<?=gen_uri('active/goods_detail',array('act_id'=>$li['iActId'],'goods_id'=>$li['iGoodsId']))?>">
                                    <img src="<?=$li['detail']['sImg']?>" width="84" height="84" alt="">
                                </a>
                                <em class="tag-prize-exchange">兑换</em>
                            </div>
                            <div class="record-core-cell-1">
                                <div class="prize-name"><?=$li['detail']['sGoodsName']?></div>
                                <div class="record-core-cell-2">
                                    <p>兑换号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?></p>
                                    <p>使用<strong><?=$li['detail']['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE?></strong>张夺宝币</p>
                                </div>
                                <div class="record-core-cell-3 clearfix">
                                    <span class="fl">已支付<strong>￥<?=$li['detail']['iTotalPrice']/Lib_Constants::COUPON_UNIT_PRICE?></strong></span>
                                </div>
                                <div class="record-core-opt clearfix">
                                    <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['sOrderId'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>兑换详情</span></a>
                                    <?php
                                    if($li['deliver_status'] == 2)
                                    {
                                        if(empty($user_address))
                                        {
                                    ?>
                                            <a href="<?=gen_uri('/my/address',array('redirect_url'=>gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid']))),'is_return'=>1,'show_confirm'=>1,'order_id'=>$li['detail']['sWinnerOrder']))?>" class="btn btn-error"><span>填写收货信息</span></a>
                                    <?php
                                        }
                                        else
                                        {
                                    ?>
                                            <a href="javascript:void(0);" onclick="confirmAddress('<?=$li['sOrderId']?>')" class="btn btn-error" ><span>确认收货信息</span></a>
                                    <?php
                                        }
                                        ?>

                                    <?php
                                    }
                                    else if($li['deliver_status'] == 4 || $li['deliver_status'] == 3 )
                                    {
                                        ?>
                                        <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error">
                                                        <span>
                                                            <?=$li['deliver_status'] == 3?'奖品待发货':'奖品已发货' ?>
                                                        </span>
                                        </a>
                                    <?php
                                    }
                                    else if($li['deliver_status'] == 5)
                                    {
                                        ?>
                                        <a href="javascript:void(0);" onclick="confirmDelive('<?=$li['detail']['sWinnerOrder']?>')" class="btn btn-error"><span>确认收货</span></a>
                                    <?php
                                    }
                                    else if($li['deliver_status'] == 6)
                                    {
                                        if($li['is_active_order'])
                                        {
                                            ?>
                                            <a href="<?=gen_uri('/share/add',array('period_code'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>晒单</span></a>
                                        <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <a href="<?=gen_uri('/my/active_win_order',array('order_id'=>$li['detail']['sWinnerOrder'],'peroid_str'=>period_code_encode($li['iActId'],$li['iPeroid'])))?>" class="btn btn-error"><span>奖品已签收</span></a>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if(empty($list['list'])){ ?>
                        <p style="padding:10px;">暂无记录~~</p>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php if(!empty($user_address)){ ?>
        <div class="pop-mask-addr"></div>
        <div class="pop-addr-confirm">
            <input type="hidden" id="confir_order_id" name="confir_order_id" value="0" >
            <p>姓名：<?=$user_address['sName']?></p>
            <p>手机：<?=$user_address['sMobile']?></p>
            <p>地区：<?=$user_address['sProvince']?> <?=$user_address['sCity']?> <?=$user_address['sDistrict']?></p>
            <p>详细地址：<?=$user_address['sAddress']?></p>
            <p>注：请仔细核对您的收货地址,确认后将不可更改,我们将对该地址进行发货。</p>
            <a href="javascript:;" class="btn btn-block btn-error btn-pop-addr-confirm"><span>确认收货地址</span></a>
            <a href="javascript:;" class="pop-close"></a>
        </div>
    <?php } ?>
</div>
<script>

    var is_address = <?=empty($user_address)?0:1;?>;

    $(function(){

        $('.pop-close').on('click', function(){
            $('.pop-mask-addr, .pop-addr-confirm').hide();
        });

        $('.list-progress-bar').each(function () {
            var _percent = $(this).parent().attr('data-lott-schedule');
            _percent = _percent.substring(0, _percent.length - 1);
            $(this).find('span.list-progress-bar-on').animate({'width': _percent + '%'}, 600);
        });

        $('.countDown').each(function() {
            var aCountDown = $(this),
                time = (new Date($(aCountDown).attr('data-end-time')).getTime() - new Date($(aCountDown).attr('data-start-time')).getTime())/1000;
            DUOBAO.countDown(this, time);
        });

        var aTit = $('#indianaRecordTit a');

        aTit.each(function(i){

            var _this = $(this);


            _this.on('click', function(){
                var index = _this.parent().find('a').index($(this));
                var content = $('#indianaRecordCon > div');
                var url = _this.attr('data-url');

                if(!content.eq(index).hasClass('empty')){
                    _this.addClass('tab-active').siblings().removeClass('tab-active');
                    content.eq(i).show().siblings().hide();
                }
                else
                {
                    DUOBAO._get(url,function(rs){
                        if(!rs || rs.retCode != 0){
                            layer.msg('加载数据失败~');
                            return ;
                        }else{
                            var html = [];
                            html.push('<ul class="list clearfix">');
                            $.each(rs.retData.list,function(i,li){

                                if(li.iLotState == 'exchange'){
                                    html.push(exchangeTpl(li));
                                }else if(li.iLotState == 2){
                                    html.push(openedTpl(li));
                                }else if(li.iLotState == 1){
                                    html.push(goingTpl(li));
                                }else{
                                    html.push(detaultTpl(li));
                                }
                            });
                            html.push('</ul>');
                            content.eq(index).html(html.join(''));
                            (function($){
                                var aCountDown = $('.countDown');
                                for (var i=0; i<aCountDown.length; i++) {
                                    var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
                                    var ele = aCountDown[i];
                                    DUOBAO.countDown(ele, leftTime);
                                }
                            })(jQuery);
                        }

                        _this.parent().find('a').removeClass('tab-active');
                        _this.addClass('tab-active');

                        content.hide();
                        content.eq(index).removeClass('empty').show();
                    });
                }

                return false;
            });

        })

        DUOBAO.loadMore('#indianaRecordTit>a',function(rs,index,_this){
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData.page_index >= rs.retData.page_count)  _this.attr('data-load','false');
                $.each(rs.retData.list,function(i,li){
                    var html = [];
                    if(li.iLotState == 'exchange'){
                        html.push(exchangeTpl(li));
                    }else if(li.iLotState == 2){
                        html.push(openedTpl(li));
                    }else if(li.iLotState == 1){
                        html.push(goingTpl(li));
                    }else{
                        html.push(detaultTpl(li));
                    }
                    $('.tab-con > div').eq(index).append(html.join(''));

                    var aCountDown = $('.countDown');
                    for (var i=0; i<aCountDown.length; i++) {
                        var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
                        var ele = aCountDown[i];
                        DUOBAO.countDown(ele, leftTime);
                    }
                })
            }
        });

        // 查看我的码
        var aBtnViewCode = $('.btn-view-my-code');
        aBtnViewCode.each(function(){
            var _this = $(this);
            _this.on('click', function(){
                var dataCode = $(this).closest('.record-item').attr('data-code');
                DUOBAO.popWinAlert.init(dataCode, '我的参与码');
                $('.pop-alert').css({'height': '240px', 'margin-top': '-120px'})
                $('.pop-alert .pop-content').css({'padding': '0 6px','line-height': '20px', 'height': '200px', 'overflow-y': 'auto', 'margin-top': '40px', 'word-break': 'break-all'});
            });
        });


    })

    function confirmAddress(order_id)
    {
        $('#confir_order_id').val(order_id);
        $('.pop-mask-addr, .pop-addr-confirm').show();
    }

    $('.btn-pop-addr-confirm').on('click', function(){
        var confir_order_id = $('#confir_order_id').val();
        if(confir_order_id == '' || confir_order_id < 0 || confir_order_id == null || confir_order_id == undefined)
        {
            layer.msg('确认失败~~');
            return false;
        }
        $('.pop-mask-addr, .pop-addr-confirm').hide();
        layer.closeAll();
        DUOBAO._post('<?=gen_uri('/my/ajax_addr_confirm')?>',{'order_id':confir_order_id},function($res){
            if($res.retCode == 0){
                layer.msg('确认成功~~',{shift:-1},function(){
                    location.reload();
                });
            }else{
                layer.msg('确认失败~~');
            }
        })
    });

    function confirmDelive(order_id)
    {
        DUOBAO.popWinConfirm.init('确认收到奖品吗?', '确认收货', '取消', function(){
            layer.closeAll();
            DUOBAO._post('<?=gen_uri('/my/ajax_deliver_confirm')?>',{'order_id':order_id},function($res){
                if($res.retCode == 0){
                    layer.msg('确认成功~~',{shift:-1},function(){
                        location.reload();
                    });
                }else{
                    layer.msg('确认失败~~');
                }
            })
        });
    }

    function show_code(str)
    {
        DUOBAO.popWinAlert.init(str,'我的参与码');
        $('.pop-alert').css({'height': '240px', 'margin-top': '-120px'})
        $('.pop-alert .pop-content').css({'line-height': '20px', 'height': '200px', 'overflow-y': 'auto', 'margin-top': '40px', 'word-break': 'break-all'});
    }

    function openedTpl($li){
        var html = [];
        if($li['detail'] == null)
        {
            return html.join('');
        }
        html.push('<div class="grid-1 record-item" >');
        html.push('<div class="prize-pic">');
        html.push('<a href="'+$li['url']+'"><img src="'+$li['detail']['sImg']+'" width="84" height="84" alt=""></a>');
        if($li.iIsWin == 1){
            html.push('<em class="tag-prize-win">恭喜<br>中奖</em>');
        }
        html.push('</div>');
        html.push('<div class="record-core-cell-1">');
        html.push('<div class="prize-name">'+$li['sGoodsName']+'</div>');
        html.push('<div class="record-core-cell-2 clearfix"><em class="fl">期号：'+$li['peroid_str']+'</em></div>');
        html.push('<div class="record-core-cell-3 clearfix"><span class="fl">您已参与<strong>'+$li['iLotCount']+'</strong>人次</span>');
        html.push('<a href="'+$li['url']+'" class="btn-parti-detail fr">参与详情</a>');
        html.push('</div>');

        html.push('<div class="record-core-cell-4">');
        html.push('<p>获奖者：<b>'+$li['detail']['sWinnerNickname']+'</b></p>');
        html.push('<p>幸运码：<strong>'+$li['detail']['sWinnerCode']+'</strong></p>');
        html.push('<p>本期购买：<strong>'+$li['detail']['iWinnerCount']+'</strong>人次</p>');
        html.push('<p>揭晓时间：<b>'+$li['detail']['iLotTime']+'</b></p>');
        html.push('</div>');
        if(1 == $li.iIsWin){
            html.push('<div class="record-core-opt clearfix"><a href="'+$li['order_detail_url']+'" class="btn btn-default btn-default-2"><span>查询中奖详情</span></a>');
            if($li.deliver_status == 2)
            {
                if(is_address == 1)
                {
                    html.push('<a href="javascript:void(0);" onclick="confirmAddress(\''+$li['detail']['sWinnerOrder']+'\')" class="btn btn-error"><span>确认收货信息</span></a></div>');
                }
                else
                {

                    html.push('<a href="'+$li['address_redirect_url']+'" class="btn btn-error"><span>填写收货信息</span></a></div>');
                }

            }
            else if($li.deliver_status == 3 || $li.deliver_status == 4)
            {
                var status_msg = $li.deliver_status == 3?'奖品待发货':'奖品已发货';
                html.push('<a href="'+$li['order_detail_url']+'" class="btn btn-error"><span>'+status_msg+'</span></a></div>');
            }
            else if($li.deliver_status == 5)
            {
                html.push('<a href="javascript:void(0);" onclick="confirmDelive(\''+$li['detail']['sWinnerOrder']+'\')" class="btn btn-error"><span>确认收货</span></a></div>');
            }
            else if($li.deliver_status == 6)
            {
                if($li.is_active_order)
                {
                    html.push('<a href="'+$li['share_add_url']+'" class="btn btn-error"><span>晒单</span></a></div>');
                }
                else
                {
                    html.push('<a href="'+$li['order_detail_url']+'" class="btn btn-error"><span>奖品已签收</span></a></div>');
                }
            }
        }
        else
        {
            html.push('<div class="record-core-opt clearfix">');
            html.push('<a href="javascript:void(0);" onclick="show_code(\''+$li['my_code']+'\')" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>');
            html.push('</div>');
        }
        html.push('</div></div>');
        return html.join("");
    }

    function goingTpl($li){
        var html = [];
        if($li['detail'] == null)
        {
            return html.join('');
        }
        html.push('<div class="grid-1 record-item">');
        html.push('<div class="prize-pic"><a href="'+ $li['url'] +'"><img src="'+$li['detail']['sImg']+'" width="84" height="84" alt=""></a><label class="label-record label-warning">即将揭晓</label></div>');
        html.push('<div class="record-core-cell-1">');
        html.push('<div class="prize-name">'+$li['sGoodsName']+'</div>');
        html.push('<div class="record-core-cell-2 clearfix"><em class="fl">期号：'+$li['peroid_str']+'</em></div>');
        html.push('<div class="record-core-cell-3 clearfix">');
        html.push('<div class="fl record-countDown">揭晓倒计时：<div class="countDown" data-start-time="'+ $li['start_time'] +'" data-end-time="'+ $li['end_time'] +'">14:34:133<span class="ms"></span></div></div>');
        // html.push('<span class="fl record-countDown" id="countDown" data-end-time="'+$li['end_time']+'" data-start-time="'+$li['start_time']+'">揭晓倒计时：00:00:000</span>');
        html.push('<a href="'+$li['url']+'" class="btn-parti-detail fr">参与详情</a>');
        html.push('</div></div></div>');
        return html.join('');
    }

    function detaultTpl($li){
        var html = [];
        if($li['detail'] == null)
        {
            return html.join('');
        }
        html.push('<div class="grid-1 record-item" data-code="'+$li['my_code']+'">');
        html.push('<div class="prize-pic"><a href="'+ $li['url'] +'"><img src="'+$li['detail']['sImg']+'" width="84" height="84" alt=""></a></div>');
        html.push('<div class="record-core-cell-1">');
        html.push('<div class="prize-name">'+$li['sGoodsName']+'</div>');
        html.push('<div class="list-goods-bott">');
        html.push('<div class="list-schedule" data-lott-schedule="'+$li['detail']['iProcess']+'%">');
        html.push('<div class="list-progress-txt"><label>期号：'+$li['peroid_str']+'</label><em class="progress-bubble">'+$li['detail']['iProcess']+'%</em></div>');
        html.push('<div class="list-progress-bar">');
        html.push('<span class="list-progress-bar-on" style="width:'+$li['detail']['iProcess']+'%"></span>');
        html.push('</div></div></div>');
        html.push('<div class="record-core-cell-3 clearfix">');
        html.push('<span class="fl">您已参与<strong>'+$li['iLotCount']+'</strong>人次</span>');
        html.push('<a href="'+$li['url']+'" class="btn-parti-detail fr">参与详情</a>');
        html.push('</div>');
        html.push('<div class="record-core-opt clearfix">');
        html.push('<a href="'+$li['buy_url']+'" class="btn btn-error btn-join-again"><span>继续参与</span></a>');
        html.push('<a href="javascript:void(0);" onclick="show_code(\''+$li['my_code']+'\')" class="btn btn-default btn-default-2 btn-view-my-code"><span>查看我的码</span></a>');
        html.push('</div></div></div>');
        return html.join('');
    }

    function exchangeTpl($li){

        var html = [];
        if($li['detail'] == null)
        {
            return html.join('');
        }
        html.push('<div class="grid-1 record-item">');
        html.push('<div class="prize-pic"><a href="'+ $li['url'] +'"><img src="'+$li['detail']['sImg']+'" width="84" height="84" alt=""></a><em class="tag-prize-exchange">兑换</em></div>');
        html.push('<div class="record-core-cell-1">');
        html.push('<div class="prize-name">'+$li['detail']['sGoodsName']+'</div>');
        html.push('<div class="record-core-cell-2">');
        html.push('<p>期号：'+$li['peroid_str']+'</p>');
        html.push('<p>使用<strong>'+($li['detail']['iTotalPrice']/<?=Lib_Constants::COUPON_UNIT_PRICE?>)+'</strong>张夺宝币</p>');
        html.push('</div>');
        html.push('<div class="record-core-cell-3 clearfix">');
        html.push('<span class="fl">已支付<strong>￥'+($li['detail']['iTotalPrice']/<?=Lib_Constants::COUPON_UNIT_PRICE?>)+'</strong></span>');
        html.push('</div>');
        html.push('<div class="record-core-opt clearfix">');
        html.push('<a  class="btn btn-error" href="'+$li['order_detail_url']+'"><span>兑换详情</span></a>');

        if($li.deliver_status == 2)
        {
            if(is_address == 1)
            {
                html.push('<a href="javascript:void(0);" onclick="confirmAddress(\''+$li['detail']['sWinnerOrder']+'\')" class="btn btn-error"><span>确认收货信息</span></a></div>');
            }
            else
            {
                html.push('<a href="'+$li['address_redirect_url']+'" class="btn btn-error"><span>填写收货信息</span></a></div>');
            }
        }
        else if($li.deliver_status == 3 || $li.deliver_status == 4)
        {
            var status_msg = $li.deliver_status == 3?'奖品待发货':'奖品已发货';
            html.push('<a href="'+$li['order_detail_url']+'" class="btn btn-error"><span>'+status_msg+'</span></a></div>');
        }
        else if($li.deliver_status == 5)
        {
            html.push('<a href="javascript:void(0);" onclick="confirmDelive(\''+$li['detail']['sWinnerOrder']+'\')" class="btn btn-error"><span>确认收货</span></a></div>');
        }
        else if($li.deliver_status == 6)
        {
            if($li.is_active_order)
            {
                html.push('<a href="'+$li['share_add_url']+'" class="btn btn-error"><span>晒单</span></a></div>');
            }
            else
            {
                html.push('<a href="'+$li['order_detail_url']+'" class="btn btn-error"><span>奖品已签收</span></a></div>');
            }
        }
        html.push('</div>');
        html.push('</div></div>');
        return html.join("");
    }
</script>