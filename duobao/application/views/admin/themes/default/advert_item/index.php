<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="grid filter-wrap">
    <form action="" class="form-filter clearfix" id="search-form">
        <div class="form-item">
            <p>
                <label for="advert_position">广告位</label>
                <select name="advert_position" id="advert_position">
                    <option value="-1">请选择</option>
                    <?php foreach ($position_list as $v) {?>
                        <option value="<?=$v['iPositionId']?>" <?=isset($advert_position)&&$advert_position==$v['iPositionId']?'selected="selected"':''?>><?=$v['sName']?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label for="advert_title">广告标题</label>
                <input type="text" name="advert_search_title" id="advert_search_title" value="<?=empty($advert_title)?'':$advert_title;?>" style="width: 250px;">
            </p>
            <p>
                <label for="advert_search_state">状态</label>
                <select name="advert_search_state" id="advert_search_state">
                    <option value="-1">请选择</option>
                    <?php foreach (Lib_Constants::$publish_states as $k => $v) {?>
                        <option value="<?=$k?>" <?=$k == $advert_state ? 'selected' : ''?>><?=$v?></option>
                    <?php }?>
                </select>
            </p>
            <p>
                <label></label>
                <?php $this->widget('button', array('node'=>'advert_item/index','button'=>Lib_Constants::BTN_OK,'id'=>'admin-advert_item-search-do'))?>
                <?php $this->widget('button', array('node'=>'advert_item/index','button'=>Lib_Constants::BTN_RESET,'id'=>'admin-advert_item-search-reset'))?>
            </p>
        </div>
    </form>
</div>

<div class="table-con">
    <span class="table-button fr">
        <?php $this->widget('button', array('node'=>'advert_item/add','button'=>Lib_Constants::BTN_ADD,'id'=>'admin-advert_item-add'))?>
    </span>
    <table class="table  mt-10 fr">
        <thead>
        <tr>
            <th width="20%">标题</th>
            <th width="10%">预览图</th>
            <th width="15%">链接</th>
            <th width="10%">广告位</th>
            <th width="5%">排序</th>
            <th width="7%">上线时间</th>
            <th width="7%">下线时间</th>
            <th width="6%">状态</th>
            <th width="20%" style="min-width: 270px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result_list['count'] <= 0) {?>
            <tr><td colspan="9">暂无数据</td></tr>
        <?php } else { foreach ($result_list['list'] as $v) { ?>
            <tr>
                <td class="text-left"><?=$v['sTitle']?></td>
                <td><?=empty($v['sImg'])?'':'<img src="'.$v['sImg'].'" width="100" height="100">'?></td>
                <td class="text-left"><?=empty($v['sTarget'])?'':'<a href="'.$v['sTarget'].'" target="_blank">'.$v['sTarget'].'</a>'?></td>
                <td><?=Lib_Constants::$advert_position[$v['iPositionId']]?></td>
                <td><?=$v['iSort']?></td>
                <td><?=empty($v['iBeginTime'])?'':date(DATE_FORMATTER, $v['iBeginTime'])?></td>
                <td><?=empty($v['iEndTime'])?'':date(DATE_FORMATTER, $v['iEndTime'])?></td>
                <td><?=Lib_Constants::$publish_states[$v['iState']]?></td>
                <td><?php Lib_Constants::get_publish_opt_btn($v['iAdId'], 'advert_item', $v['iState']);?></td>
            </tr>
        <?php }}?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="9"><?php $this->widget('pagination', $result_list)?></td>
        </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        var $add = $('#admin-advert_item-add'),
            $search_do = $('#admin-advert_item-search-do'),
            $search_rest = $('#admin-advert_item-search-reset');

        $add.click(function () {
            $.yRedirect('<?=node_url('advert_item/add')?>');
        });

        ADMIN.Opts.edit = function (id) {
            $.yRedirect('<?=node_url('advert_item/edit')?>' + '/' + id);
        };

        $search_do.click(function () {
            var search = {},
                search_str,
                advert_position = $('#advert_position').val(),
                advert_search_title = $('#advert_search_title').val(),
                advert_search_state = $('#advert_search_state').val();
            if (advert_position > 0) {
                search.advert_position = advert_position;
            }
            if (advert_search_title) {
                search.advert_title = advert_search_title;
            }
            if (advert_search_state > -1) {
                search.advert_state = advert_search_state;
            }
            search_str = $.param(search);
            if (! search_str) {
                $.yError('请先输入正确的查询条件');
                return;
            }
            $.yRedirect('<?=node_url('advert_item/index')?>' + '?' + search_str);
        });
        $search_rest.click(function () {
            $.yRedirect('<?=node_url('advert_item/index')?>');
        });
    });
</script>