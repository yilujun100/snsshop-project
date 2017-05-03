<a href="javascript:;" class="back-top" id="backTop"><i class="arrow-up"></i>回到顶部</a>
<script type="text/javascript" charset="utf-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php $this->widget('weixin_share', array()) ?>

<script type="text/javascript" src="<?=$cdn_third_url?>layer/layer.js?ver=<?=$version?>"></script>
<script type="text/javascript" src="<?=$cdn_common_url?>js/jquery.common.js?ver=<?=$version?>"></script>

<?php if(defined('ENVIRONMENT') && ENVIRONMENT === 'production'){ ?>
<script type="text/javascript" src="http://tajs.qq.com/stats?sId=55791706" charset="UTF-8"></script>
<?php } ?>

<script>
    $(function(){
        <?php if (!empty($active_is_end)) { ?>
        // 活动结束 通用遮罩
         $('.pop-mask, .pop-acti-end').show();
        <?php } ?>

        MAISHA.backToTop();
    })
</script>