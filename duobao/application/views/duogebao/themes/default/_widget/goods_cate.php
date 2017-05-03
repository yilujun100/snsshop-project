<!--必须参数$goods_cate-->
<?php $map = array('热门精选'=>'icon-hot','手机数码'=>'icon-phone','电脑平板'=>'icon-pc','家用电器'=>'icon-electric','品牌箱包'=>'icon-bag','钟表饰品'=>'icon-ornament','其他商品'=>'icon-others','运动保健'=>'icon-sports','个人美妆'=>'icon-beauty');?>
<?php if(!empty($goods_cate)){  ?>
<div class="mask" style="display: -block;"></div>
<div class="fixed-top">
<div class="fixed-top-cover"></div>
    <div class="category" id="category">
        <a href="javascript:;">夺宝分类</a>
        <div class="cate-sub">
            <ul>
                <li><a href="<?=gen_uri('/active/lists',array('keyword'=>'热门精选'))?>"><i class="icon-category-sub icon-hot"></i>热门精选</a></li>
                <li><a href="<?=gen_uri('/active/lists',array('keyword'=>'苹果专区'))?>"><i class="icon-category-sub icon-phone"></i>苹果专区</a></li>
                <?php foreach($goods_cate as $cate){   ?>
                    <li><a href="<?=gen_uri('/active/lists',array('cls'=>$cate['iCateId']))?>"><i class="icon-category-sub <?=(isset($map[$cate['sName']])?$map[$cate['sName']]:'icon-others')?>"></i><?=$cate['sName']?></a></li>
                <?php } ?>
                <li><a href="<?=gen_uri('/active/lists')?>"><i class="icon-category-sub icon-view-all"></i>查看全部</a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-top-search" >
        <form action="<?=gen_uri('/active/lists')?>" id="formTopSearch" method="post">
            <input type="search" id="listKeywords" name="listKeywords" placeholder="搜索商品、妆品、数码等">
            <input type="button" class="btn-search">
            <a href="javascript:;" class="btn-keyword-clear" style="top: 5px; right: 5px; display: none;">清除关键字</a>
        </form>

    </div>
</div>
<?php } ?>