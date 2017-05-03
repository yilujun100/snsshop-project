<div class="table-hd clearfix">
    <h3 class="table-tit fl">奖励类型</h3>
    <span class="table-button fr">
        <a href="javascript:;" class="btn-opera btn-opera-primary" id="addBtn">新增奖励类型</a>
    </span>
</div>
<div class="table-con">
    <?php
    $edit_lit = array();
    if ($type_list['count']) {
    ?>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">奖励类型ID</th>
                <th width="25%">奖励类型名称</th>
                <th width="15%">奖励类型简称</th>
                <th width="15%">类型英文名称</th>
                <th width="10%">状态</th>
                <th width="">操作</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($type_list['list'])){

            foreach ($type_list['list'] as $type) {
                $edit_lit[$type['iAwardsType']] = array(
                    'name' => $type['sName'],
                    'short_name' => $type['sShortName'],
                );
            ?>
                <tr>
                    <td><?=$type['iAwardsType']?></td>
                    <td><?=$type['sName']?></td>
                    <td><?=$type['sShortName']?></td>
                    <td><?=$type['sNameEn']?></td>
                    <td><?=Lib_Constants::$publish_states[$type['iState']]?></td>
                    <td>
                        <?php
                            $opts = Lib_Constants::get_publish_opt_btn($type['iAwardsType'],'awards_type', $type['iState']);
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
                <td colspan="7">
                    <?php $this->widget('pagination', $type_list) ?>
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

<!-- 添加 -->
<div id="addView" style="display: none">
    <div class="pop-detail-item" >
        <form method="post" id="promptForm" action="#">
            <div class="f-item">
                <span style="color: red;padding: 2px 10px;">新增奖励类型请联系开发GG~~</span>
            </div>
            <div class="f-item">
                <label>奖励类型名称</label>
                <input type="text" name="name" id="name" placeholder="请输入奖励类型名称">
            </div>
            <div class="f-item">
                <label>奖励类型简称</label>
                <input type="text" name="short_name" id="short_name" placeholder="前端日志展示用">
            </div>
            <div class="f-item">
                <label>类型英文名称</label>
                <input type="text" name="en_name" id="en_name" placeholder="业务逻辑用,新增联系开发GG">
            </div>

            <div class="f-item">
                <label></label>
                <button type="submit" class="btn-form btn-form-submit">确认</button>
                <button type="reset" class="btn-form btn-form-cancel">重置</button>
            </div>

        </form>
    </div>
</div>

<!-- 编辑 -->
<div id="editView" style="display: none">
    <div class="pop-detail-item" >
        <form method="post" id="promptForm" action="#">
            <div class="f-item">
                <label>类型ID</label>
                <input type="text" name="id" value="" disabled="disabled">
            </div>
            <div class="f-item">
                <label for="name">奖励类型名称</label>
                <input type="text" name="name" id="name" placeholder="请输入奖励类型名称">
            </div>
            <div class="f-item">
                <label for="short_name">奖励类型简称</label>
                <input type="text" name="short_name" id="short_name" placeholder="请输入奖励类型简称">
            </div>
            <div class="f-item">
                <label></label>
                <button type="submit" class="btn-form btn-form-submit">确认</button>
                <button type="reset" class="btn-form btn-form-cancel">重置</button>
            </div>
        </form>
    </div>
</div>

<script>
$(function(){
    var pageRows = <?=json_encode($edit_lit)?>;
    ADMIN.awards_type = {
        edit : function(params) {
            uri = params.id ? '/admin/awards_type/edit' : '/admin/awards_type/add';
            ADMIN._post(uri, params);
        },
        validate : {
            rules: {
                name: {required: true, maxlength: 50},
                short_name: {required: true, maxlength: 30},
                en_name: {required: true, maxlength: 30}
            },
            messages: {
                name: {required: '请输入奖励类型名称', maxlength: '最多50个字符'},
                short_name: {required: '请输入奖励类型简称', maxlength: '最多30个字符'},
                en_name:{required: '请输入类型英文名称', maxlength: '最多30个字符'}
            },
            submitHandler: function (form) {
                var id = parseInt($(form.id).val());
                var name = $(form.name).val();
                var short_name =  $(form.short_name).val();
                var en_name =  $(form.en_name).val();
                ADMIN.awards_type.edit({id:id, name:name,short_name:short_name, en_name:en_name});
            }
        },
        dialogConfA : {
            title:'奖励类型 - 新增',
            height:300
        },
        dialogConfE : {
            title:'奖励类型 - 编辑',
            height:250
        },
        resetInit : function($obj, $tid, $id) {
            switch ($tid) {
                case '#editView':
                    $($obj).find('input[name=id]').attr('value', $id);
                    $($obj).find('input[name="name"]').attr('value',pageRows[$id]['name']);
                    $($obj).find('input[name="short_name"]').attr('value',pageRows[$id]['short_name']);
                    break;
                default :
            }
            $($obj).find('#promptForm').validate(ADMIN.awards_type.validate);
        }
    };

    $('#addBtn').on('click', function(){
        ADMIN.Opts.edit('', 'awards_type','#addView');
    });
})
</script>