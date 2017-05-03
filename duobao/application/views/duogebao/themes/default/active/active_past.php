<div class="viewport v-past-announced">
    <?php if(!empty($list)){ ?>
        <?php foreach($list as $li){ ?>
            <?php if($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_GOING){ ?>
                <div class="top-tips">
                    <p>期 号：<?=period_code_encode($li['iActId'],$li['iPeroid'])?><strong>即将揭晓</strong></p>
                </div>
            <?php }elseif($li['iLotState'] == Lib_Constants::ACTIVE_LOT_STATE_OPENED){ ?>
                <div class="grid announced-item mt-10">
                    <div class="announced-item-con">
                        <dl>
                            <dt>
                                <img src="<?=$li['sWinnerHeadImg']?>" width="52" height="52" alt="">
                            </dt>
                            <dd>
                                <p><?=$li['sWinnerNickname']?></p>
                                <p>用户编号：<?=$li['iWinnerUin']?></p>
                                <p>本期购买：<strong><?=$li['iWinnerCount']?></strong>人次</p>
                                <p>幸运码：<strong><?=$li['sWinnerCode']?></strong></p>
                            </dd>
                        </dl>
                    </div>
                    <div class="announced-item-bott">
                        <div class="prize-info-past">
                            <p><label>中奖商品：</label><?=$li['sGoodsName']?></p>
                            <p><label>揭晓时间：</label><?=date('Y-m-d H:i:s',$li['iLotTime'])?></p>
                            <p><label>期号：</label><?=period_code_encode($li['iActId'],$li['iPeroid'])?></p>
                            <img src="<?=$li['sImg']?>" width="60" height="60" class="prize-past-pic" alt="">
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    <?php }else{ ?>
        <div class="win-list-empty">
            <i class="icon-e-search"></i>
            <p>暂无中奖信息<br>快去参与活动吧</p>
            <a href="<?=gen_uri('/home/index')?>" class="btn btn-error btn-e-join"><span>立即参与</span></a>
        </div>
    <?php } ?>
</div>