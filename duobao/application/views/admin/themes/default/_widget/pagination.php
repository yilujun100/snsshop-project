<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="pager">
    <em>共<?=$count?>条</em>
    <span class="total-pagers"><i class="pager-curr"><?=$page_index?></i>/<?=$page_count?>页</span>
    <a href="javascript:;" class="page-prev">&lt;</a>
    <a href="javascript:;" class="page-next">&gt;</a>
    <em>跳转到</em>
    <input type="text" class="pager-jump" id="page-go-val" />
    <a href="javascript:;" class="btn-jump page-go">GO</a>
</div>

<script type="text/javascript">
    $(function () {
        var PAGE = ADMIN.page(<?=$page_index?>, <?=$page_count?>, '<?=empty($page_sort)?'':$page_sort?>', '<?=empty($page_order)?'':$page_order?>');
        $('.page-prev').click(function () {
            PAGE.prev();
        });
        $('.page-next').click(function () {
            PAGE.next();
        });
        $('.page-go').click(function () {
            PAGE.go($('#page-go-val').val());
        });
    });
</script>