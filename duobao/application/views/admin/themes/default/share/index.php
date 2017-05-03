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
            <!--<p>
                <label for="act_state">状态</label>
                <select name="act_state" id="act_state">
                    <option value="-1">请选择</option>
                    <?php /*foreach ($publish_state as $k => $v) {*/?>
                        <option value="<?/*=$k*/?>" <?/*=$k == $act_state ? 'selected' : ''*/?>><?/*=$v*/?></option>
                    <?php /*}*/?>
                </select>
            </p>-->
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
            <th width="5%">晒单ID</th>
            <th width="5%">晒单期号</th>
            <th width="25%">商品名称</th>
            <th width="5%">商品图片</th>
            <th width="5%">用户昵称</th>
            <th width="10%">点赞数</th>
            <th width="5%">查看数</th>
            <th width="5%">幸运码</th>
            <th width="5%">状态</th>
            <th width="20%" style="min-width: 270px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="12">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td><?=$v['iShareId']?></td>
                <td><?=period_code_encode($v['iActId'],$v['iPeriod'])?></td>
                <td class="text-left"><?=$v['sGoodsName']?></td>
                <td><img src="<?=$v['sGoodsImg']?>" style="width:50px;"></td>
                <td><?=$v['sNickName']?></td>
                <td><?=$v['iLikeCount']?></td>
                <td><?=$v['iLikeCount']?></td>
                <td><?=$v['iLuckyCode']?></td>
                <td><?=Lib_Constants::$share_audit[$v['iAudit']]?></td>
                <td>
                    <?php if($v['iAudit'] == Lib_Constants::SHARE_AUDIT_DEFAULT){?>
                    <a href="javascript:;" data-node="free/lottery" class="btn-opera btn-opera-info" onclick="ADMIN.Opts.lottery('<?=$v['iShareId']?>','share', 1)">审核通过</a>
                    <a href="javascript:;" data-node="free/lottery" class="btn-opera btn-opera-info" onclick="ADMIN.Opts.lottery('<?=$v['iShareId']?>','share', 2)">审核不通过</a>
                    <?php } ?>
                </td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot><tr><td colspan="12"><?php $this->widget('pagination', $result_list)?></td></tr></tfoot>
    </table>
</div>

<script>
    var ADMIN = ADMIN || {};
    ADMIN.Opts.lottery = function($id, $class, $opt, $cb) {
        layer.confirm($opt == 1 ? '审核通过?' : '审核不通过?', {
            title: false,
            closeBtn: false,
            btn: ['确认', '取消']
        }, function () {
            $uri = '/admin/'+$class+'/audit';
            var $params = {id:$id, opt:$opt};
            ADMIN._post($uri, $params, $cb);
        });

    }
</script>
<script type="text/javascript">
    $(function () {
        var $add = $('#goods-add'),
            $search_do = $('#goods-search-do'),
            $search_rest = $('#goods-search-reset');

        $add.click(function () {
            console.log('add');
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
    });
</script>