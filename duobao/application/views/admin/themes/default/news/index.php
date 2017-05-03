<div class="table-hd clearfix">
    <h3 class="table-tit fl">促销信息列表</h3>
    <span class="table-button fr">
        <a href="javascript:;" class="btn-opera btn-opera-primary" id="addBtn">新增</a>
    </span>
</div>
<div class="table-con">
    <?php
    $edit_lit = array();
    if ($news_list['count']) {
    ?>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">ID</th>
                <th width="25%">标题</th>
                <th width="15%">添加/修改日期</th>
                <th width="10%">状态</th>
                <th width="">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($news_list['list'])){

            foreach ($news_list['list'] as $type) {
            ?>
                <tr>
                    <td><?=$type['iNewsId']?></td>
                    <td><?=$type['sTitle']?></td>
                    <td><?=date('Y-m-d H:i:s',$type['iCreateTime'])?></td>
                    <td><?=Lib_Constants::$publish_states[$type['iState']]?></td>
                    <td>
                        <?php
                        $opts = Lib_Constants::get_publish_opt_btn($type['iNewsId'],'news', $type['iState']);
                        ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?php $this->widget('pagination', $news_list) ?>
                </td>
            </tr>
        </tfoot>
    </table>
        <?php
        } else {
            echo "暂无记录!!!";
        }
        ?>
</div>



<script type="text/javascript">
    $(function () {
        var $add = $('#addBtn') ;

        $add.click(function () {
            $.yRedirect('<?=node_url('news/add')?>');
        });

        ADMIN.Opts.edit = function (id) {
            $.yRedirect('<?=node_url('news/edit')?>' + '/' + id);
        };

    });
</script>