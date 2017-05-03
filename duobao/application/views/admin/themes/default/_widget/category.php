<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="f-item">
    <label for="cateLvl1">一级类目</label>
    <select class="cate-select" name="cateLvl1" id="cateLvl1" data-lvl="1" data-val="<?=empty($cateLvl1)?'':$cateLvl1?>">
        <option value="0">请选择</option>
        <?php foreach ($top_cate as $v) {?>
            <option value="<?=$v['iCateId']?>"><?=$v['sName']?></option>
        <?php }?>
    </select>
</div>
<div class="f-item">
    <label for="cateLvl2">二级类目</label>
    <select class="cate-select" name="cateLvl2" id="cateLvl2" data-lvl="2" data-val="<?=empty($cateLvl2)?'':$cateLvl2?>">
    </select>
</div>
<div class="f-item">
    <label for="cateLvl3">三级类目</label>
    <select class="cate-select" name="cateLvl3" id="cateLvl3" data-lvl="3" data-val="<?=empty($cateLvl3)?'':$cateLvl3?>">
    </select>
</div>
<?php if (empty($parent)) {?>
    <div class="f-item">
        <label for="cateLvl4">四级类目</label>
        <select class="cate-select" name="cateLvl4" id="cateLvl4" data-lvl="4" data-val="<?=empty($cateLvl4)?'':$cateLvl4?>">
        </select>
    </div>
<?php }?>
<script type="text/javascript">
    $(function () {
        var $cate = $('.cate-select'),
            max_lvl = get_min_lvl();
        $cate.change(function () {
            var $this = $(this),
                lvl = parseInt($this.attr('data-lvl'), 10),
                cate_id = parseInt($this.val());
            if (lvl >= max_lvl) {
                return;
            } else if (! cate_id) {
                empty_lower_lvl(lvl);
                return;
            }
            $this.yAjax({
                url: '<?=node_url('goods_category/children')?>',
                data: {cate_id: cate_id},
                success: function (data) {
                    if (0 === data.retCode) {
                        var option_html = ['<option value="0">请选择</option>'],
                            $lower = $('#cateLvl' + (lvl + 1)),
                            $lower1 = $('#cateLvl' + (lvl + 2));
                        $.each(data.retData, function (i, v) {
                            option_html.push('<option value="' + v.iCateId + '">' + v.sName + '</option>');
                        });
                        empty_lower_lvl(lvl);
                        $lower.html(option_html.join(''));
                        if ($lower.attr('data-val')) {
                            $lower.val($lower.attr('data-val'));
                        }
                        if ($lower1.attr('data-val')) {
                            $lower.change();
                        }
                    } else {
                        empty_lower_lvl(lvl);
                        console.log(data);
                    }
                }
            });
        });
        if ($('#cateLvl1').attr('data-val')) {
            $('#cateLvl1').val($('#cateLvl1').attr('data-val')).change();
        }

        function empty_lower_lvl (lvl) {
            for (var i = lvl; i < max_lvl; i ++) {
                $('#cateLvl' + (i + 1)).empty();
            }
        }
        function get_min_lvl () {
            if ($('#cateLvl4').length < 1) {
                return 3;
            }
            return 4;
        }
    });
</script>
