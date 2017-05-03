<div class="viewport v-logistics">
    <!-- logistics start -->
    <div class="logistics">
        <div class="grid logistics-goods">
            <div class="goods-pic">
                <img src="<?=$express['sGoodsImg']?>" width="68" height="68" alt="">
            </div>
            <div class="logistics-info-basic">
                <p><em>物流状态</em><span class="status-order status-o-warning">派件中</span></p>
                <p><em>快递单号</em><span><?=$express['sExpressId']?></span></p>
                <p><em>快递公司</em><span><?=$express['sExpressName']?></span></p>
            </div>
        </div>

        <div class="grid timeline mt-10">
            <ul>
                <li class="track"></li>
                <li class="step-current">
                    <i class="dotted"></i>
						<span>
							<p>您的快件已签收，感谢您使用中通快递！期待再次为您服务。</p>
							<em>2016-03-17 09:55</em>
						</span>
                </li>
                <li>
                    <i class="dotted"></i>
						<span>
							<p>您的订单在［南山站］验货完成，正在分配配送员</p>
							<em>2016-03-17 07:32</em>
						</span>
                </li>
                <li>
                    <i class="dotted"></i>
						<span>
							<p>您的订单在［深圳宝安区分拨中心］发货完成，正准备送完［南山站］</p>
							<em>2016-03-17 05:42</em>
						</span>
                </li>
                <li>
                    <i class="dotted"></i>
						<span>
							<p>您的订单在［深圳宝安区分拨中心］分拣完成</p>
							<em>2016-03-17 05:42</em>
						</span>
                </li>
                <li>
                    <i class="dotted"></i>
						<span>
							<p>您的订单在［广州亚一分拣完成］发货完成，准备送往［深圳宝安区分拨中心］</p>
							<em>2016-03-17 05:42</em>
						</span>
                </li>
            </ul>
        </div>
    </div>
    <!-- logistics end -->
</div>