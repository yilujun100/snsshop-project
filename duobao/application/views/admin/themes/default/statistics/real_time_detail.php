<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="container">
    <div class="table-hd clearfix">
        <h3 class="table-tit fl" style="color:red;">该页面每30分钟记录一次数据</h3>
    </div>
    <div class="table-con">
        <table class="table">
            <thead>
            <tr>
                <th width="5%">类型</th>
                <th width="9%">已支付人数</th>
                <th width="9%">已支付订单数</th>
                <th width="9%">已支付现金金额</th>
                <th width="9%">已使用券</th>
                <th width="9%">订单ARPU值</th>
                <th width="9%">未支付人数</th>
                <th width="9%">未支付订单数</th>
                <th width="9%">未支付金额</th>
                <th width="9%">活跃用户数</th>
                <th width="9%">新增用户数</th>
            </tr>
            </thead>
            <tbody>
            <?php
                if(!empty($real_list))
                {
                    foreach($real_list as $real)
                    {
            ?>
                        <tr>
                            <td><?=$real['type']?></td>
                            <td><?=$real['pay_count']?></td>
                            <td><?=$real['pay_order']?></td>
                            <td><?=$real['pay_money']?></td>
                            <td><?=$real['use_coupon']?></td>
                            <td>
                                <?=$real['pay_count']==0?0:sprintf("%.2f",($real['use_coupon']+$real['pay_money'])/$real['pay_count'])?>
                            </td>
                            <td><?=$real['not_pay_count']?></td>
                            <td><?=$real['not_pay_order']?></td>
                            <td><?=$real['not_pay_money']?></td>
                            <td><?=$real['active_user']?></td>
                            <td><?=$real['new_user']?></td>
                        </tr>
            <?php
                    }
                }
                else
                {
            ?>
                <tr>
                    <td colspan="11">暂无数据</td>
                </tr>
            <?php
                }
            ?>

            </tbody>

        </table>

        <!-- 统计需求 start @2016-6-7 -->
        <div class="tab mt-20">
            <div class="tab-hd clearfix">
                <a href="javascript:;" class="tab-active">夺宝</a>
                <a href="javascript:;">充值</a>
                <a href="javascript:;">福袋</a>
                <a href="javascript:;">兑换</a>
            </div>
            <div class="tab-con">
                <div style="display: block;">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>已支付人数</th>
                            <th>已支付订单数</th>
                            <th>已支付现金金额</th>
                            <th>已使用券</th>
                            <th>订单ARPU值</th>
                            <th>未支付人数</th>
                            <th>未支付订单数</th>
                            <th>未支付金额</th>
                            <th>活跃用户数</th>
                            <th>新增用户数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($indiana_detail_list))
                            {
                                foreach($indiana_detail_list as $indiana_detail)
                                {
                        ?>
                                    <tr>
                                        <td><?=date('H:i',$indiana_detail['iCreateTime'])?>-<?=date('H:i',$indiana_detail['endTime'])?></td>
                                        <td><?=$indiana_detail['iPayUserCount']?></td>
                                        <td><?=$indiana_detail['iPayOrderCount']?></td>
                                        <td><?=$indiana_detail['iPayMoney']/100?></td>
                                        <td><?=$indiana_detail['iUseCoupon']?></td>
                                        <td>
                                            <?=$indiana_detail['iOrderARPU']==0?0:sprintf("%.2f",$indiana_detail['iOrderARPU'])?>
                                        </td>
                                        <td><?=$indiana_detail['iNotPayUserCount']?></td>
                                        <td><?=$indiana_detail['iNotPayOrderCount']?></td>
                                        <td><?=$indiana_detail['iNotPayMoney']/100?></td>
                                        <td><?=$indiana_detail['iActivityUser']?></td>
                                        <td><?=$indiana_detail['iNewUser']?></td>
                                    </tr>
                        <?php
                                }
                            }
                            else
                            {
                        ?>
                                <tr>
                                    <td colspan="11">暂无数据</td>
                                </tr>
                        <?php
                            }
                        ?>
                        </tbody>
                    </table>

                </div>
                <div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>已支付人数</th>
                            <th>已支付订单数</th>
                            <th>已支付现金金额</th>
                            <th>已使用券</th>
                            <th>订单ARPU值</th>
                            <th>未支付人数</th>
                            <th>未支付订单数</th>
                            <th>未支付金额</th>
                            <th>活跃用户数</th>
                            <th>新增用户数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($coupon_detail_list))
                        {
                            foreach($coupon_detail_list as $coupon_detail)
                            {
                            ?>
                                <tr>
                                    <td><?=date('H:i',$coupon_detail['iCreateTime'])?>-<?=date('H:i',$coupon_detail['endTime'])?></td>
                                    <td><?=$coupon_detail['iPayUserCount']?></td>
                                    <td><?=$coupon_detail['iPayOrderCount']?></td>
                                    <td><?=$coupon_detail['iPayMoney']/100?></td>
                                    <td><?=$coupon_detail['iUseCoupon']?></td>
                                    <td>
                                        <?=$coupon_detail['iOrderARPU']==0?0:sprintf("%.2f",$coupon_detail['iOrderARPU'])?>
                                    </td>
                                    <td><?=$coupon_detail['iNotPayUserCount']?></td>
                                    <td><?=$coupon_detail['iNotPayOrderCount']?></td>
                                    <td><?=$coupon_detail['iNotPayMoney']/100?></td>
                                    <td><?=$coupon_detail['iActivityUser']?></td>
                                    <td><?=$coupon_detail['iNewUser']?></td>
                                </tr>
                        <?php
                            }
                        }
                        else
                        {
                        ?>
                            <tr>
                                <td colspan="11">暂无数据</td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
                <div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>已支付人数</th>
                            <th>已支付订单数</th>
                            <th>已支付现金金额</th>
                            <th>已使用券</th>
                            <th>订单ARPU值</th>
                            <th>未支付人数</th>
                            <th>未支付订单数</th>
                            <th>未支付金额</th>
                            <th>活跃用户数</th>
                            <th>新增用户数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($lucky_bag_detail_list))
                        {
                            foreach($lucky_bag_detail_list as $lucky_bag_detail)
                            {
                        ?>
                                <tr>
                                    <td><?=date('H:i',$lucky_bag_detail['iCreateTime'])?>-<?=date('H:i',$lucky_bag_detail['endTime'])?></td>
                                    <td><?=$lucky_bag_detail['iPayUserCount']?></td>
                                    <td><?=$lucky_bag_detail['iPayOrderCount']?></td>
                                    <td><?=$lucky_bag_detail['iPayMoney']/100?></td>
                                    <td><?=$lucky_bag_detail['iUseCoupon']?></td>
                                    <td>
                                        <?=$lucky_bag_detail['iOrderARPU']==0?0:sprintf("%.2f",$lucky_bag_detail['iOrderARPU'])?>
                                    </td>
                                    <td><?=$lucky_bag_detail['iNotPayUserCount']?></td>
                                    <td><?=$lucky_bag_detail['iNotPayOrderCount']?></td>
                                    <td><?=$lucky_bag_detail['iNotPayMoney']/100?></td>
                                    <td><?=$lucky_bag_detail['iActivityUser']?></td>
                                    <td><?=$lucky_bag_detail['iNewUser']?></td>
                                </tr>
                        <?php
                            }
                        }
                        else
                        {
                        ?>
                            <tr>
                                <td colspan="11">暂无数据</td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
                <div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>时间</th>
                            <th>已支付人数</th>
                            <th>已支付订单数</th>
                            <th>已支付现金金额</th>
                            <th>已使用券</th>
                            <th>订单ARPU值</th>
                            <th>未支付人数</th>
                            <th>未支付订单数</th>
                            <th>未支付金额</th>
                            <th>活跃用户数</th>
                            <th>新增用户数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($exchange_detail_list))
                        {
                            foreach($exchange_detail_list as $exchange_detail)
                            {
                            ?>
                                <tr>
                                    <td><?=date('H:i',$exchange_detail['iCreateTime'])?>-<?=date('H:i',$exchange_detail['endTime'])?></td>
                                    <td><?=$exchange_detail['iPayUserCount']?></td>
                                    <td><?=$exchange_detail['iPayOrderCount']?></td>
                                    <td><?=$exchange_detail['iPayMoney']/100?></td>
                                    <td><?=$exchange_detail['iUseCoupon']?></td>
                                    <td>
                                        <?=$exchange_detail['iOrderARPU']==0?0:sprintf("%.2f",$exchange_detail['iOrderARPU'])?>
                                    </td>
                                    <td><?=$exchange_detail['iNotPayUserCount']?></td>
                                    <td><?=$exchange_detail['iNotPayOrderCount']?></td>
                                    <td><?=$exchange_detail['iNotPayMoney']/100?></td>
                                    <td><?=$exchange_detail['iActivityUser']?></td>
                                    <td><?=$exchange_detail['iNewUser']?></td>
                                </tr>
                        <?php
                            }
                        }
                        else
                        {
                        ?>
                            <tr>
                                <td colspan="11">暂无数据</td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- 统计需求 end @2016-6-7 -->
    </div>
</div>

<script>
    $(function(){
        // sidebar
        setHeight();

        $('.table-search input').on({
            focus: function(){
                $(this).parent().css('border', '1px solid #f03e42');
            },
            blur: function(){
                $(this).parent().css('border', '1px solid #ddd');
            }
        });

        $('.btn-opera-primary').on('click', function(){
            layer.confirm('确认使用积分兑换吗?', {
                title: false,
                closeBtn: false
            });
        });

        $('.btn-opera-success').on('click', function(){
            layer.msg('兑换成功!', {icon: 1});
        });

        // tab
        fnTab('.tab-hd a', '.tab-con > div');
    })
</script>
</body>
</html>