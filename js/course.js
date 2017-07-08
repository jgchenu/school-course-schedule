// layui模块
layui.use(['jquery','layer','element'], function(){ 
  var layer = layui.layer;
  var element = layui.element();
  var status= "course";
  var today=new Date();
  $.support.cors =true; 
  // 画面载入时加载动画
  layer.load();
        setTimeout(function(){
          layer.closeAll('loading');
        }, 300);
  // 课程表添加按钮的函数
  $('#add_btn').click(function(){
  	layer.open({
  	type: 1, 
  	content: $('#add_course'),
  	scrollbar: false,
  	anim:2,
    shadeClose:true,
    closeBtn:2,
  	btn:['保存'],
  	yes:function(index,layero){
  		var $add_course=$("#add_course"),$rows=$("tr.row"),color_num=parseInt(Math.random()*9+1);
  		var weekday=$add_course.find("select[name='weekday'] option:selected").val(),
  		course_num=$add_course.find("select[name='course_num'] option:selected").val(),
  		course_place=$add_course.find("input[name='course_place']").val(),
  		course_name=$add_course.find("input[name='course_name']").val();
  		console.log(course_place);
      if(weekday==0||course_num==0||course_name==''){
        layer.msg('添加失败',{
        icon:5,
        time:1000
      });
      }else{
        $rows.eq(course_num).find("td").eq(weekday).removeClass().addClass("color"+color_num).html(course_name+"<br>"+course_place+"<br>");
      layer.msg('添加成功',{
        icon:6,
        time:1000
      });
      layer.close(index);
      }
  	},
});
    // 弹出窗口函数结束
}); 
  // 点击弹出按钮函数结束
 
// 点击切换类型按钮开始
  $("#table_btn").click(function(){
  		$("#course_list").hide();
		  $(".desk_table").show();
      $("body").css('background','#fff');
		
	});
	$("#list_btn").click(function(){
		$(".desk_table").hide();
		$("#course_list").show();
    $("body").css('background','#eceff6');
	});
// 点击切换按钮结束

// 点击重置课表执行函数
  $("#load_course").click(function(){
        $.cookie("remember", "false", { expire: -1 });
        $.cookie("stu_id", "", { expires: -1 });
        $.cookie("stu_name", "", { expires: -1 });
        window.location.href="index.html";
  });

  // ajax 加载课程表的信息
  $.ajax({
            type:'POST', //请求方式，默认get
            url :"http://keepfriend.cn/kcb/course_schedule.php",
            data:{stu_id:$.cookie("stu_id"),stu_name:$.cookie("stu_name"),status:status},
            dataType: "json", 
            // 请求成功时的回调函数
            success:function(data){ 
                if(data.header.status=="0"){
                layer.msg(data.header.extra_info);
                }else if(data.header.status=="1"){
                    //遍历每一行 
               $.each(data.data,function(key,val){
                  // 遍历每一列
                  $.each(val,function(index,value){
                    if(!value.course_name||!value.course_place){
                       value.course_place="";
                       value.course_name="";
                    }
                        var color_num=parseInt(Math.random()*9+1);
                        // 加载表格课程
                        $("tr.row").eq(parseInt(key)).find("td").eq(parseInt(index)).removeClass().addClass("color"+color_num)
                        .html(value.course_name+"<br>"+value.course_place); 
                        // 加载列表课程
                        $("div.col").eq(parseInt(index)-1).find("p").eq(parseInt(key)-1)
                        .html(value.course_name+"@"+value.course_place);
                        // 判断表格没有课程的格子不要加载颜色，判断没有课程的列表的节数不要显示
                        if(value.course_name==""||value.course_place==""){
                        $("tr.row").eq(parseInt(key)).find("td").eq(parseInt(index)).removeClass();
                        $("div.col").eq(parseInt(index)-1).find("p").eq(parseInt(key)-1).parent().hide();
                        }
                        // 判断今天是星期几，将对应的课程展开
                        if(today.getDay()<5){
                          $("div.col").eq(today.getDay()).addClass("layui-show");    
                        }  
                                                       
                  });
                  //遍历每一行结束
               });
               // 遍历每一列结束
              }

              $("h1.title span").html(data.header.week_info);

              console.log(data.header);
            },error:function(data){
               layer.msg("加载出现错误！可能是网络问题");
               // 请求失败弹出提示
            }
         });

});