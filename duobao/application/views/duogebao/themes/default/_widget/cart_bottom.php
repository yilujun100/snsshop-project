<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if(!isset($widget) || empty($widget['iPeroid'])){ ?>
    <div class="detail-bottom">
                <a href="<?=gen_uri('/home/index')?>" class="btn-to-home"><i class="icon-home"></i>首页</a>
				<span class="detail-bott-btns">
					<a href="javascript:void(0)" class="btn btn-error btn-phase-new">
                        <span>活动已结束</span>
                    </a>
				</span>
        <!--<a href="<?/*=gen_uri('/cart/lists')*/?>" class="add-cart">加入清单<em><?/*=$widget['collect']*/?></em></a>-->
    </div>
<?php }elseif(isset($widget) && $widget['iPeroid'] == $widget['peroid']){ ?>
<div class="detail-bottom">
                <a href="<?=gen_uri('/home/index')?>" class="btn-to-home"><i class="icon-home"></i>首页</a>
				<span class="detail-bott-btns">
					<a href="<?=gen_uri('/pay/active_buy',array('peroid_str'=>period_code_encode($widget['iActId'],$widget['iPeroid'])),'payment')?>" class="btn btn-error">
                        <span>立即参与</span>
                    </a>
					<a href="javascript:void(0)" class="btn btn-warning" id="add-cart">
                        <span>加入清单</span>
                    </a>
				</span>
    <!--<a href="<?/*=gen_uri('/cart/lists')*/?>" class="add-cart">加入清单<em><?/*=$widget['collect']*/?></em></a>-->
</div>
<?php }else{ ?>
<div class="detail-bottom">
                <a href="<?=gen_uri('/home/index')?>" class="btn-to-home"><i class="icon-home"></i>首页</a>
<!--                <a href="--><?//=gen_uri('/home/index')?><!--" class="btn-to-home"><i class="icon-home"></i>首页</a>-->
				<span class="detail-bott-btns">
					<a href="<?=gen_uri('/active/detail',array('id'=>period_code_encode($widget['iActId'],$widget['iPeroid']),'current_peroid'=>1))?>" class="btn btn-error btn-phase-new">
                        <span>前往新一期</span>
                    </a>
				</span>
    <!--<a href="<?/*=gen_uri('/cart/lists')*/?>" class="add-cart">加入清单<em><?/*=$widget['collect']*/?></em></a>-->
</div>
<?php } ?>

<script>
$(function(){

        $('#add-cart').click(function(){
            DUOBAO._get('<?=gen_uri('/cart/ajax_add')?>?peroid_str=<?=period_code_encode(@$widget['iActId'],@$widget['iPeroid'])?>',function($res){
                if($res.retCode == -100002){
                    layer.msg('亲，获取用户信息失败~~');
                    return false;
                }else if($res.retCode == 0){
                    //操作成功
                    layer.msg('添加成功~~',{shift:-1},function(){
                        location.reload();
                    });
                }else{
                    layer.msg($res.retMsg ? $res.retMsg : '清单操作失败', {shift:-1},function(){
                        location.reload();
                    });
                }
            })
        })

})
</script>