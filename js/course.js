layui.use(['jquery', 'layer', 'element'], function() {
    var layer = layui.layer;
    var element = layui.element();
    var status = "course";
    var today = new Date();
    var token = localStorage.getItem('kbtoken');
    if (!localStorage.getItem('kbtoken')) { window.location.href = "index.html"; }
    if (!$.cookie("stu_id") || !$.cookie("stu_name") || !localStorage.getItem('kbtoken')) { window.href = "index.html"; }
    $.support.cors = true;
    layer.load(2);
    setTimeout(function() {
        layer.closeAll('loading');
    }, 1000);
    $("#table_btn").click(function() {
        $("#course_list").hide();
        $(".desk_table").show();
        $("body").css('background', '#fff');

    });
    $("#list_btn").click(function() {
        $(".desk_table").hide();
        $("#course_list").show();
        $("body").css('background', '#eceff6');
    });

    $("#load_course").click(function() {
        $.cookie("remember", "false", { expire: -1 });
        $.cookie("stu_id", "", { expires: -1 });
        $.cookie("stu_name", "", { expires: -1 });
        window.location.href = "login.html";
    });


    $.ajax({
        type: 'POST',
        url: "http://www.szer.me/kcb/course_schedule.php",
        data: { stu_id: $.cookie("stu_id"), stu_name: $.cookie("stu_name"), status: status, 'token': token },
        dataType: "json",
        success: function(data) {
            console.log(data);
            if (data.header.status == "0") {
                layer.msg(data.header.extra_info);
            } else if (data.header.status == "1") {

                var $table = $("table.desk_table");
                var $list = $("div#course_list");
                $.each(data.desk, function(key, val) {
                    $table.append("<tr><td class='tr_color'>" + key + "</td></tr>");
                    $.each(val, function(index, value) {
                        if (!value.course_name || !value.course_place) {
                            value.course_place = "";
                            value.course_name = "";
                        }
                        if (value.course_continuous) {
                            return true;
                        }
                        var $tr = $table.find("tr").eq(key);
                        $tr.append("<td" + " rowspan=" + value.course_duration + " class=color" + (value.course_id + 1) + ">" + value.course_name + "<br/>" + value.course_place + "</td>");
                    });
                });

                $.each(data.list, function(key, val) {
                    var col = $list.find(".col").eq(key - 1);
                    if (val.length == 0) {
                        col.append("<div>无课</div>");
                    } else {


                        $.each(val, function(index, value) {
                            col.append("<div><h3>" + value.course_section + "</h3>" +
                                "<p>" + value.course_name + "@" + value.course_place + "</p></div>");
                        });
                    }
                });
            }

            $("h1.title").html("第" + data.header.week_info + "周");
            if (1 < today.getDay() && today.getDay() < 6) {
                $("div.col").eq(today.getDay() - 1).addClass("layui-show");
            }
        },
        error: function(data) {
            layer.msg("加载出现错误！可能是网络问题");
        }
    });

});