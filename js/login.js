layui.use(['layer'], function() {
    var layer = layui.layer;
    var status = "login";
    var token;
    var code;
    $("#remember_me").attr('checked', 'checked');
    $.support.cors = true;

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    if (localStorage.getItem('kbtoken')) {
        token = localStorage.getItem('kbtoken');
        if ($.cookie("remember") == "true") {
            $.ajax({
                type: 'POST',
                url: "",
                data: { 'stu_id': $.cookie("stu_id"), 'stu_name': $.cookie("stu_name"), status: status, 'token': token },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.header.status == "0") {
                        layer.msg(data.header.extra_info);
                    } else if (data.header.status == "1") {
                        window.location.href = "course.html";
                    }

                },
                error: function(data) {
                    layer.msg("导入出现错误！请检查数据格式");
                },
            });
        }
    } else {
        code = getQueryString('code');
        if (!code) { window.location.href = "index.html" } else {
            $.ajax({
                type: 'POST',
                url: "",
                dataType: "json",
                data: { 'code': code },
                success: function(data) {
                    console.log(data);
                    localStorage.setItem('kbtoken', data.retdata.token);
                    console.log(data.retdata.token);
                    token = localStorage.getItem('kbtoken');
                },
                error: function(data) {
                    console.log('error:' + data);
                }
            });
        }

    }



    var stu_id = $("#stu_id").val();
    var stu_name = $("#stu_name").val();
    var reg = /^\d+$/;
    if (!stu_id || !stu_name || !reg.test(stu_id)) {
        $("button.btn-default").css('backgroundColor', '#e6e6e6');
    }
    $('#stu_id').on('input', function() {
        stu_id = $("#stu_id").val();
        stu_name = $("#stu_name").val();
        if (!stu_id || !stu_name || !reg.test(stu_id)) {
            $("button.btn-default").css('backgroundColor', '#e6e6e6');
            return false;
        } else {
            $("button.btn-default").css('backgroundColor', '#d26465');
        }
    });
    $('#stu_name').on('input', function() {
        stu_id = $("#stu_id").val();
        stu_name = $("#stu_name").val();
        if (!stu_id || !stu_name || !reg.test(stu_id)) {
            $("button.btn-default").css('backgroundColor', '#e6e6e6');
            return false;
        } else {
            $("button.btn-default").css('backgroundColor', '#d26465');
        }
    })

    $("button.btn-default").click(function() {
        stu_id = $("#stu_id").val();
        stu_name = $("#stu_name").val();
        if (stu_id == '') {
            layer.msg("学号不能为空");
            return false;
        }
        if (!reg.test(stu_id)) {
            layer.msg("请输入正确的学号格式");
            return false;
        }
        if (stu_name == '') {
            layer.msg("姓名不能为空");
            return false;
        }
        layer.load(2);
        setTimeout(function() {
            layer.closeAll('loading');
        }, 300);
        if ($("#remember_me").is(':checked')) {
            $.cookie("remember", "true", { expires: 180 });
            $.cookie("stu_id", stu_id, { expires: 180 });
            $.cookie("stu_name", stu_name, { expires: 180 });
        } else {
            $.cookie("remember", "false", { expire: 1 });
            $.cookie("stu_id", stu_id, { expires: 1 });
            $.cookie("stu_name", stu_name, { expires: 1 });
        }

        $.ajax({
            type: 'POST',
            url: "",
            data: { 'stu_id': stu_id, stu_name: stu_name, status: status, 'token': token },
            dataType: "json",
            success: function(data) {
                if (data.header.status == "0") {
                    layer.msg(data.header.extra_info);
                } else if (data.header.status == "1") {
                    window.location.href = "course.html";
                }
            },
            error: function(data) {
                layer.msg("导入出现错误！请检查数据格式");
            },
        });
    });

});