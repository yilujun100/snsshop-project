<div class="viewport v-group-more">
    <!-- group more start -->
    <div class="group-more" data-url="<?=gen_uri('/active/ajax_diy_more', array('gid'=>$groupon_id), 'groupon')?>" data-load="<?=($groupon_diy_list['page_count'] > $groupon_diy_list['page_index']) ? 'true' : 'false'?>">
        <div class="spell-group-list">
            <div class="spell-group-list-hd">
                <div class="hr"></div>
                <h3>以下小伙伴正在发起团购，您可以直接参与哦～</h3>
            </div>
            <?php
            //活动开团列表
            !isset($groupon_diy_list['list']) OR $groupon_diy_list = $groupon_diy_list['list'];
            if (!empty($groupon_diy_list)) {
                ?>
                <div class="grid spell-group-list-con">
                    <ul id="diy_more_ul">
                        <?php $this->widget('diy_list', array('diy_list' => $groupon_diy_list))?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </div>
    <!-- group more end -->
</div>
<script>
    $(function(){
        MAISHA.DiyMore = function(){
            var aCountDown = $('.count-down-1');
            for (var i=0, len=aCountDown.length; i<len; i++) {
                var leftTime = (new Date($(aCountDown[i]).attr('data-end-time')).getTime() - new Date($(aCountDown[i]).attr('data-start-time')).getTime())/1000;
                MAISHA.countDown(aCountDown[i], leftTime, 0);
            }
        };
        MAISHA.DiyMore();
        var MyGrouponRender = function(rs, _this){
            _this.addClass('is-render');
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                if(rs.retData && rs.retData.html) {
                    $('#diy_more_ul').append(rs.retData.html);
                    if (rs.retData.page_index >= rs.retData.page_count) {
                        _this.attr('data-load','false');
                    }
                    MAISHA.DiyMore();
                } else {
                    _this.attr('data-load','false');
                }
            }
        }
        //分页
        MAISHA.loadMore('.group-more',function(rs,index,_this){
            MyGrouponRender(rs, _this);
        });
    })
</script>