<div class="viewport v-calculate">
    <!-- calculate detail start -->
    <div class="calculate-detail">
        <div class="cal-formula">
            <h3>计算公式<a href="<?=gen_uri('/help/index', array('item'=>'rules_algorithm'))?>"><i>?</i></a></h3>
            <p>[(数值A+数值B)÷数值C]取余数+<?=Lib_Constants::LUCKY_CODE_BASE?></p>
        </div>

        <div class="grid cal-item">
            <div class="cal-item-inner">
                <p><img src="<?=$resource_url?>images/v2/num_a.png" width="56" height="21" alt=""></p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;">截止该商品最后参与时间最后50条全站参与记录</p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;"><strong><?=$detail['iLotNumA']?></strong></p>
                <div class="cal-item-toggle">
                    <a href="javascript:;" class="btn-slideToggle btn-slide-down">收起</a>
                    <table id="modelList">
                        <thead>
                        <tr>
                            <th colspan="2">查看参与记录详情</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($detail['sLotBasis'] as $li){ ?>
                            <?php if(is_array($li)){ ?>
                                <tr>
                                    <td><?=date('Y-m-d H:i:s',substr($li[0],0,10))?>:<?=substr($li[1],-3)?><strong>→ <?=$li[1]?></strong></td>
                                    <td><?=$li[0]?></td>
                                </tr>
                            <?php }else{ ?>
                                <tr>
                                    <td><?=$li->iCreateTime?>:<?=substr($li->iMsecTime,-3)?><strong>→ <?=$li->iMsecTime?></strong></td>
                                    <td><?=$li->sNickName?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="cal-item-1">
            <div class="cal-item-1-inner">
                <p><img src="<?=$resource_url?>images/v2/num_b.png" width="56" height="21" alt=""></p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;">最近一期中国福利彩票“老时时彩”的揭晓结果</p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;"><strong><?=(empty($detail['iLotNumB'])?'正在等待时时彩揭晓....':$detail['iLotNumB'])?></strong>(第<?=$detail['iIssue']?>期)</p>
            </div>
        </div>
        <div class="cal-item-1">
            <div class="cal-item-1-inner">
                <p><img src="<?=$resource_url?>images/v2/num_c.png" width="65" height="21" alt=""></p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;">商品开奖所需人次</p>
                <p><img src="<?=$resource_url?>images/v2/equal.png" width="14" height="10" alt="" style="margin-top: 4px; margin-right: 4px;"><strong><?=$detail['iLotCount']?></strong></p>
            </div>
        </div>
        <div class="cal-item-1">
            <div class="cal-item-1-inner">
                <p><img src="<?=$resource_url?>images/v2/result.png" width="79" height="21" alt=""></p>
                <p style="text-align: center;">幸运号码<strong style="font-weight: bold;"><?=($show == 'false' ? '等待揭晓...' : $detail['sWinnerCode'])?></strong></p>
            </div>
        </div>
    </div>
    <div class="tips-calculate">
        <a href="<?=gen_uri('/help/index', array('item'=>'rules_algorithm'))?>">查看详细活动规则和计算规则</a>
    </div>
    <!-- calculate detail end -->
</div>
<script>
    $(function(){
        $('.btn-slideToggle').on('click', function(){
            if ($(this).hasClass('btn-slide-up')) {
                $(this).removeClass('btn-slide-up').addClass('btn-slide-down');
                $(this).closest('.cal-item').css('height', '126px');
                $(this).closest('.cal-item').find('.cal-item-inner').css('height', '162px');
                if ($(window).width() < 640) {
                    $(this).closest('.cal-item').css('height', '106px');
                    $(this).closest('.cal-item').find('.cal-item-inner').css('height', '142px');
                }
                if ($(window).width() <= 320) {
                    $(this).closest('.cal-item').css('height', '124px');
                    $(this).closest('.cal-item').find('.cal-item-inner').css('height', '162px');
                }
                $('#modelList tbody').hide();
            } else {
                $(this).removeClass('btn-slide-down').addClass('btn-slide-up');
                $(this).closest('.cal-item').css('height', '2030px');
                $(this).closest('.cal-item').find('.cal-item-inner').css({'height': '2118px', 'overflow': 'hidden'});
                if ($(window).width() < 640) {
                    $(this).closest('.cal-item').css('height', '1800px');
                    $(this).closest('.cal-item').find('.cal-item-inner').css({'height': '1838px', 'overflow': 'hidden'});
                }
                $('#modelList tbody').show();
            }
        });
    })
</script>