<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="act_id">夺宝单ID</label>
                <input type="text" name="act_id" id="act_id" value="<?=empty($act_id)?'':$act_id;?>">
            </p>
            <p>
                <label for="goods_id">商品ID</label>
                <input type="text" name="goods_id" id="goods_id" value="<?=empty($goods_id)?'':$goods_id;?>">
            </p>
            <p>
                <label for="act_state">状态</label>
                <select name="act_state" id="act_state">
                    <option value="-1">请选择</option>
                    <?php foreach ($publish_state as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $act_state ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'active/index','button'=>Lib_Constants::BTN_OK,'id'=>'goods-search-do'))?>
                <?php $this->widget('button', array('node'=>'active/index','button'=>Lib_Constants::BTN_RESET,'id'=>'goods-search-reset'))?>
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
            <th width="5%">商品ID</th>
            <th width="25%">商品名称</th>
            <th width="5%">单码价格</th>
            <th width="10%">夺宝单角标</th>
            <th width="5%">开奖价格</th>
            <th width="5%">实际利润率</th>
            <th width="5%">单次最多购买码数</th>
            <th width="5%">单期最多购买码数</th>
            <th width="5%">总期数</th>
            <th width="5%">状态</th>
            <th width="20%" style="min-width: 270px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="12">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iActId']?></td>
                <td><?=$v['iGoodsId']?></td>
                <td class="text-left"><?=$v['sGoodsName']?></td>
                <td><?=price_format($v['iCodePrice'])?></td>
                <td><?=empty(Lib_Constants::$corner_mark[$v['iCornerMark']])?'':Lib_Constants::$corner_mark[$v['iCornerMark']]['text']?></td>
                <td><?=price_format($v['iTotalPrice'])?></td>
                <td><?=percent_format(($v['iTotalPrice'] - $v['iCostPrice']) / $v['iCostPrice'])?></td>
                <td><?=$v['iBuyCount']?></td>
                <td><?=$v['iPeroidBuyCount']?></td>
                <td><?=$v['iPeroidCount']?></td>
                <td><?=Lib_Constants::$publish_states[$v['iState']]?></td>
                <td><?php Lib_Constants::get_publish_opt_btn($v['iActId'], 'active', $v['iState']);?></td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="12"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $add = $('#goods-add'),
            $search_do = $('#goods-search-do'),
            $search_rest = $('#goods-search-reset');

        $add.click(function () {
            $.yRedirect('<?=node_url('active/add')?>');
        });

        ADMIN.Opts.edit = function (id) {
            $.yRedirect('<?=node_url('active/edit')?>' + '/' + id);
        };

        $search_do.click(function () {
            var search = {},
                search_str,
                act_id = parseInt($.trim($('#act_id').val()), 10),
                goods_id = parseInt($.trim($('#goods_id').val()), 10),
                act_state = parseInt($('#act_state').val(), 10);
            if (act_id) {
                search.act_id = act_id;
            }
            if (goods_id) {
                search.goods_id = goods_id;
            }
            if (act_state > -1) {
                search.act_state = act_state;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('active/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('active/index')?>');
        });
        // 结单
        ADMIN.Opts.terminate = function (id, elem) {
            var $btn = $(elem);
            $btn.yAjax({
                url: '<?=node_url('active/terminate')?>' + '/' + id,
                success: function (data) {
                    if (0 == data.retCode) {
                        $.ySuccess('结单成功');
                        $.yRefresh(1500);
                    } else {
                        $.yError(data.retMsg);
                    }
                }
            });
        };
        // 线上编辑
        ADMIN.Opts.edit_online = function (id) {
            $.yRedirect('<?=node_url('active/edit_online')?>' + '/' + id);
        };
    });
</script>