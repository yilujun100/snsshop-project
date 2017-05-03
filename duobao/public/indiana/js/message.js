$(function(){

    DUOBAO.loadMore('#message-list', function(rs, index, _this) {
        if(! rs || rs.retCode != 0) {
            layer.msg('数据加载失败，请稍后再试~~',{icon:2,time:3000});
            return ;
        }
        if(rs.retData.page_index >= rs.retData.page_count) {
            _this.attr('data-load','false');
        }
        $.each(rs.retData.list, function(i, record) {
            _this.append(msg_tmpl(record));
        })
    });

    function msg_tmpl (record) {
        var html = [];
        html.push('<div class="grid info-item clearfix mb-10 '+(1==record.iRead?'msg-read':'msg-unread')+'" data-id="'+record.iMsgId+'" data-url="'+record.serverUrl+'">');
        html.push('<div class="msg-content">'+record.sContent+'</div>');
        html.push('<i class="info-time">'+time_format(record.iCreateTime)+'</i>');
        html.push('</div>');
        return html.join('\n');
    }

    function time_format(second) {
        var d = new Date(second * 1000);

        return d.getFullYear() + '-' + pref0(d.getMonth() + 1) + '-' + pref0(d.getDate()) + ' ' + pref0(d.getHours()) + ':' + pref0(d.getMinutes()) + ':' + pref0(d.getSeconds());

        function pref0 (num) {
            var prefix = ''
            if (num < 10) {
                prefix = '0';
            }
            return prefix + num;
        }
    }

    $('#message-list').on('click', '.info-item', function () {
        var $this = $(this),
            msg_id = $this.attr('data-id'),
            target = $this.attr('data-url');
        if (! msg_id) {
            return;
        }
        if (! $this.hasClass('msg-unread')) {
            location.href = target;
            return;
        }
        DUOBAO._post($('#message-list').attr('data-read'), {msg_id: msg_id}, function (res) {
            if (0 == res.retCode) {
                $this.removeClass('msg-unread').addClass('read');
                location.href = target;
            } else {
                layer.msg('把消息设置为已读状态时出错，请稍后再试~~',{icon:2,time:3000});
            }
        });
    });

    $('.msg-clean').click(function () {
        var $this = $(this);
        if ($('#message-list .msg-unread').length < 1) {
            return;
        }
        DUOBAO._post($this.attr('data-clean'), {}, function (res) {
            if (0 == res.retCode) {
                layer.msg('设置成功',{icon:2,time:1000}, function () {
                    location.reload();
                });
            } else {
                layer.msg('把全部消息设置为已读状态时出错，请稍后再试~~',{icon:2,time:3000});
            }
        });
    });
});