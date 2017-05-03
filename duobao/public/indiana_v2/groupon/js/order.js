$(function () {

    $('#groupon_order_tab > a').on('click', function() {
        var $this = $(this);
        var index = $this.parent().find('a').index($this);
        var $con = $('#groupon_order_con > div').eq(index);
        var url = $this.attr('data-url');
        $('#groupon_order_con > div').hide();
        $this.parent().find('a').removeClass('tab-active');
        $this.addClass('tab-active');
        if($this.hasClass('init')) {
            if ($this.hasClass('empty')) {
                $('.content-empty').show();
            } else {
                $con.show();
            }
            return;
        }
        MAISHA._get(url, function (rs) {
            if (! rs || rs.retCode != 0) {
                MAISHA.error('数据加载失败~~');
                return;
            }
            if (rs.retData.count > 0) {
                $con.html(rs.retData.html).show();
            } else {
                $this.addClass('empty');
                $('.content-empty').show();
            }
            $this.addClass('init');
        });
    });

    MAISHA.loadMore('#groupon_order_tab > a', function (rs, index, $this) {
        if (! rs || rs.retCode != 0) {
            MAISHA.error('数据加载失败~~');
            $this.attr('data-load', 'false');
            return ;
        }
        if(rs.retData.page_index >= rs.retData.page_count) {
            $this.attr('data-load', 'false');
        }
        var $con = $('#groupon_order_con > div').eq(index);
        if (rs.retData.count > 0) {
            $con.append(rs.retData.html);
        }
    });

    var clickOP = {
        cancel: ((function () {
            function yes (btn, index) {
                MAISHA._post(btn.attr('data-url'), {order_id:btn.attr('data-id')}, function (res) {
                    layer.close(index);
                    if (res && 0 == res.retCode) {
                        MAISHA.success('取消订单成功', function () {location.reload();});
                        return;
                    }
                    MAISHA.error('取消订单失败');
                });
            }
            return function (ev) {
                var $this = $(this);
                layer.confirm('您确认要取消该订单吗？', {
                    title: false,
                    closeBtn: false,
                    btn: ['确认', '关闭']
                }, function (index) {
                    yes($this, index, ev);
                });
            }
        }())),
        del: ((function () {
            function yes (btn, index) {
                MAISHA._post(btn.attr('data-url'), {order_id:btn.attr('data-id')}, function (res) {
                    layer.close(index);
                    if (res && 0 == res.retCode) {
                        MAISHA.success('删除订单成功', function () {location.reload();});
                        return;
                    }
                    MAISHA.error('删除订单失败');
                });
            }
            return function (ev) {
                var $this = $(this);
                layer.confirm('您确认要删除该订单吗？', {
                    title: false,
                    closeBtn: false,
                    btn: ['确认', '关闭']
                }, function (index) {
                    yes($this, index, ev);
                });
            }
        }())),
        receipt: ((function () {
            function yes (btn, index) {
                MAISHA._post(btn.attr('data-url'), {order_id:btn.attr('data-id')}, function (res) {
                    layer.close(index);
                    if (res && 0 == res.retCode) {
                        MAISHA.success('确认收货成功', function () {location.reload();});
                        return;
                    }
                    MAISHA.error('确认收货失败');
                });
            }
            return function (ev) {
                var $this = $(this);
                layer.confirm('您确认收到货了吗？', {
                    title: false,
                    closeBtn: false,
                    btn: ['确认', '关闭']
                }, function (index) {
                    yes($this, index, ev);
                });
            }
        }()))
    };

    $('body').on('click','[data-op]',function (ev) {
        var $this = $(this),
            op = $this.attr('data-op');
        if (op && clickOP[op] && clickOP[op].call) {
            clickOP[op].call($this, ev);
        }
    });
});