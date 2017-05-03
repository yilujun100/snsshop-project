<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="goods_id">商品ID</label>
                <input type="text" name="goods_id" id="goods_id" value="<?=empty($goods_id)?'':$goods_id;?>">
            </p>
            <p>
                <label for="goods_name">商品名称</label>
                <input type="text" name="goods_name" id="goods_name" value="<?=$goods_name?>" style="width: 260px;">
            </p>
            <p>
                <label for="goods_cate">类目</label>
                <select name="goods_cate" id="goods_cate">
                    <option value="-1">请选择</option>
                    <?php foreach ($top_cate as $v) {?>
                        <option value="<?=$v['iCateId']?>" <?=$goods_cate == $v['iCateId'] ? 'selected' : ''?>><?=$v['sName']?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label for="goods_state">状态</label>
                <select name="goods_state" id="goods_state">
                    <option value="-1">请选择</option>
                    <?php foreach ($publish_state as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $goods_state ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'goods/index','button'=>Lib_Constants::BTN_OK,'id'=>'goods-search-do'))?>
                <?php $this->widget('button', array('node'=>'goods/index','button'=>Lib_Constants::BTN_RESET,'id'=>'goods-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'user/edit','button'=>Lib_Constants::BTN_ADD,'id'=>'goods-add'))?>
    </span>
    <table class="table mt-10 fr">
        <thead>
        <tr>
            <th width="5%">ID</th>
            <th width="7%">类目</th>
            <th width="11%">预览图</th>
            <th width="20%">名称</th>
            <th width="6%">成本价</th>
            <th width="6%">最低售价</th>
            <th width="6%">利润额</th>
            <th width="6%">利润率</th>
            <th width="6%">夺宝单数量</th>
            <th width="7%">状态</th>
            <th width="20%" style="min-width: 270px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($goods_list['count'] <= 0) {?>
            <tr><td colspan="11">暂无数据</td></tr>
        <?php } else { foreach ($goods_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iGoodsId']?></td>
                <td class="text-left"><?=$v['sCateName']?></td>
                <td><?=empty($v['sImg'])?'':'<img src="'.$v['sImg'].'" width="100" height="100">'?></td>
                <td class="text-left"><?=$v['sName']?></td>
                <td><?=price_format($v['iCostPrice'])?></td>
                <td><?=price_format($v['iLowestPrice'])?></td>
                <td><?=price_format($v['iLowestPrice'] - $v['iCostPrice'])?></td>
                <td><?=percent_format(($v['iLowestPrice'] - $v['iCostPrice']) / $v['iCostPrice'])?></td>
                <td><?=$v['iActCount']?></td>
                <td><?=Lib_Constants::$publish_states[$v['iState']]?></td>
                <td><?php Lib_Constants::get_publish_opt_btn($v['iGoodsId'], 'goods', $v['iState']);?></td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="11"><?php $this->widget('pagination', $goods_list)?></td></tr></tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $add = $('#goods-add'),
            $search_do = $('#goods-search-do'),
            $search_rest = $('#goods-search-reset');

        $add.click(function () {
            console.log('add');
            $.yRedirect('<?=node_url('goods/add')?>');
        });

        ADMIN.Opts.edit = function (id) {
            $.yRedirect('<?=node_url('goods/edit')?>' + '/' + id);
        };

        ADMIN.Opts.edit_online = function (id) {
            $.yRedirect('<?=node_url('goods/edit_online')?>' + '/' + id);
        };

        $search_do.click(function () {
            var search = {},
                search_str,
                goods_id = parseInt($.trim($('#goods_id').val()), 10),
                goods_name = $.trim($('#goods_name').val()),
                goods_cate = parseInt($('#goods_cate').val(), 10),
                goods_state = parseInt($('#goods_state').val(), 10);
            if (goods_id) {
                search.goods_id = goods_id;
            }
            if (goods_name) {
                search.goods_name = goods_name;
            }
            if (goods_cate > -1) {
                search.goods_cate = goods_cate;
            }
            if (goods_state > -1) {
                search.goods_state = goods_state;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('goods/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('goods/index')?>');
        });
    });
</script>