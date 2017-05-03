<div class="viewport v-my-group">
    <!-- my group start -->
    <div class="my-group">
        <div class="tab">
            <div class="tab-hd">
                <?php
                $data_empty = empty($my_join_list['page_count']);
                $is_empty = $data_empty ? 'is-empty ' : '';
                $is_render = $data_empty ? 'is-render ' : '';
                $can_load_more  = $data_empty || (!empty($my_join_list['page_count']) && $my_join_list['page_index']>=$my_join_list['page_count']) ? 'false' : 'true';
                ?>
                <a href="javascript:;" class="<?=$is_empty?>is-render tab-active" data-url="<?=gen_uri('/my/ajax_my_groupons', array(), 'groupon')?>" data-tem="content-all" data-load="<?=$can_load_more?>">全部</a>
                <a href="javascript:;" class="<?=$is_empty?><?=$is_render?>" data-url="<?=gen_uri('/my/ajax_my_groupons', array('diy_type'=>Lib_Constants::GROUPON_DIY_ING), 'groupon')?>" data-tem="content-ongoing" data-load="<?=$can_load_more?>">拼团中</a>
                <a href="javascript:;" class="<?=$is_empty?><?=$is_render?>" data-url="<?=gen_uri('/my/ajax_my_groupons', array('diy_type'=>Lib_Constants::GROUPON_DIY_DONE), 'groupon')?>" data-tem="content-done" data-load="<?=$can_load_more?>">已成团</a>
                <a href="javascript:;" class="<?=$is_empty?><?=$is_render?>" data-url="<?=gen_uri('/my/ajax_my_groupons', array('diy_type'=>Lib_Constants::GROUPON_DIY_FAILED), 'groupon')?>" data-tem="content-failure" data-load="<?=$can_load_more?>">拼团失败</a>
            </div>
            <div class="tab-con">
                <!-- 全部 -->
                <div class="content-all" style="display: block;" >
                    <?php $this->widget('my_join', array('my_join_list'=>$my_join_list))?>
                </div>
                <!-- 拼团中 -->
                <div class="content-ongoing"></div>
                <!-- 已成团 -->
                <div class="content-done"></div>
                <!-- 拼团失败 -->
                <div class="content-failure"></div>
                <div class="content-empty" style="display:<?=$data_empty ? 'block' : 'none'?>">
                    <i class="icon-list"></i>
                    <p>您还没有任何拼团记录哦~</p>
                    <a href="<?=gen_uri('/home/index', array(), 'groupon')?>">去拼团</a>
                </div>
            </div>
        </div>
    </div>
    <!-- my group end -->
    <?php $this->widget('load_more',array())?>
</div>

<script>
    $(function(){
        // tab
        MAISHA.MyGroupons = function(_this) {
            if (_this.hasClass('is-empty')) {
                $(".content-empty").show();
            } else {
                var url = _this.attr('data-url');
                if (url != undefined && !_this.hasClass('is-render')) {
                    MAISHA._post(url,{},function(rs){
                        MyGrouponRender(rs, _this, 1);
                    });
                }
            }
        }

        var MyGrouponRender = function(rs, _this, is_tab){
            _this.addClass('is-render');
            if(!rs || rs.retCode != 0){
                layer.msg('加载数据失败~');
                return ;
            }else{
                $(".content-empty").hide();
                if(rs.retData && rs.retData.html) {
                    var tem = _this.attr('data-tem');
                    $('.'+tem).append(rs.retData.html);
                    if (rs.retData.page_index >= rs.retData.page_count) {
                        _this.attr('data-load','false');
                    }
                } else {
                    _this.attr('data-load','false');
                    if(is_tab) { //切换tab时无数据显示无数据
                        _this.addClass('is-empty');
                        $(".content-empty").show();
                    }
                }
            }
        }
        //分页
        MAISHA.loadMore('.my-group .tab-hd>a ',function(rs,index,_this){
            MyGrouponRender(rs, _this);
        });

        MAISHA.changeTab('.tab-hd a', '.tab-con > div', MAISHA.MyGroupons);
    })
</script>