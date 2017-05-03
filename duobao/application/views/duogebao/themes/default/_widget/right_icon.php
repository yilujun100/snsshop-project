<!-- fixed buttons 必须act_id参数-->
<div class="r-clt-con fixed-btns">
    <?php if(!empty($widget['collect'])){  ?>
        <a href="javascript:void(0)" id="delFav" class="sup-collect btn-collected"></a>
    <?php }else{ ?>
        <a href="javascript:void(0)" id="addFav" class="sup-collect"></a>
    <?php } ?>
<!--    <a href="--><?///*=gen_uri('/active/active_exchange',array('peroid_str' => $widget['peroid_str']))*/?><!--" class="btn-exchange"><i class="icon-award"></i>兑换</a>-->
    <a href="javascript:;" class="sup-top btn-to-top"></a>

</div>



<script>
    $(function(){
        var del_url = '<?=gen_uri('/collect/del',array('act_id'=>$widget['act_id']))?>',
            add_url = '<?=gen_uri('/collect/add',array('act_id'=>$widget['act_id']))?>',
            is_add = '<?=(!empty($widget['collect']) ? 'true' : 'false')?>';

        $('.sup-collect').click(function(){
            var _this = $(this);
            DUOBAO._get(is_add == 'true' ? del_url : add_url,function(rs){
                rs = 'string' === typeof rs ? $.parseJSON(rs) : rs;
                if(rs.retCode != 0){
                    layer.msg(rs.retMsg || '失败，请稍侯再试~');
                }else{
                    layer.msg(is_add == 'true' ? '删除成功' : '收藏成功',{shift:-1}, function(){
                        if(is_add == 'true'){
                            is_add = 'false';
                            _this.removeClass('btn-collected');
                        }else{
                            is_add = 'true';
                            _this.addClass('btn-collected');
                        }
                    });
                }
            });
        });
    });
</script>