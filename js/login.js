
layui.use(['layer'], function(){ 
		var layer = layui.layer;
		var status="login";
		$.support.cors =true;
		 //默认点开窗口时是点击记住我 
		$("#remember_me").attr('checked', 'checked');
		//如果点了记住我，直接获取cookie的值
		if ($.cookie("remember") == "true") {
	    $.ajax({
		type:'POST', //请求方式，默认POST
        url :"http://keepfriend.cn/kcb/course_schedule.php",
        data:{stu_id:$.cookie("stu_id"),stu_name:$.cookie("stu_name"),status:status},
        // data:stu_id:$("#stu_id").val(),stu_name:$("#stu_name").val(),status:status,
        dataType: "json", 	
        success:function(data){
        	console.log(data.header);
        	if(data.header.status=="0"){
        		layer.msg(data.header.extra_info);
        	}else if(data.header.status=="1"){
        		window.location.href="course.html";
        	}
        	
        },error:function(){
        		layer.msg("导入出现错误！可能是网络问题");
        },
		});
	    }

//导入课程
		$("button.btn-default").click(function(){
			var stu_id = $("#stu_id").val();
	      	var stu_name = $("#stu_name").val();
			layer.load();
		    setTimeout(function(){
		      layer.closeAll('loading');
		    }, 300);
		  if ($("#remember_me").is(':checked')) {
		 	console.log("我打沟了！");
	      $.cookie("remember", "true", { expires: 180 }); //存储一个带180天期限的cookie
	      $.cookie("stu_id",stu_id, { expires: 180 });
	      $.cookie("stu_name",stu_name, { expires: 180 });
    }
    else {
    	  console.log("我没打沟..");
	      $.cookie("remember", "false", { expire: 1 });
	      $.cookie("stu_id", stu_id, { expires: 1 });
	      $.cookie("stu_name", stu_name, { expires: 1 });
    }
		$.ajax({
		type:'POST', //请求方式，默认POST
		url :"http://keepfriend.cn/kcb/course_schedule.php",
		data:{stu_id:stu_id,stu_name:stu_name,status:status},
        dataType: "json", 	
        success:function(data){
        	console.log(data.header);
        	if(data.header.status=="0"){
        		layer.msg(data.header.extra_info);
        	}else if(data.header.status=="1"){
        		window.location.href="course.html";
        	}
        	
        },error:function(){
        		layer.msg("导入出现错误！可能是网络问题");
        },
		});
	});
	
	
});


