<?php
    /*
     * Author: Jiafan
     * Date: 2017/06/02
     * First Modified: 2017/08/25
     */
    error_reporting(0);
    header("Content-Type: application/json; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST,GET");
    header("Access-Control-Allow-Credentials:true"); 

    require 'bins/medoo.min.php';
    require 'function/general.php';
    require 'function/safe.php';
    
    $json_course_info      = null;    
    $course_object->header = null;
    $course_object->desk   = null;
    $course_object->list   = null;

    // Judging whether the data is retrieved from the front end; 判断是否从前端获取得到数据
    if((!isset($_POST["stu_id"]) || empty($_POST["stu_id"]) || num_check($_POST["stu_id"]) == null) && (!isset($_POST["stu_name"]) || empty($_POST["stu_name"])) ){
        $header_object->status     = 0;
        $header_object->extra_info = "学号或姓名不能为空";
        $course_object->header     = $header_object;
        $json_course_info          = json_encode($course_object);
        echo $json_course_info;
        exit();
    }

    // Post information from the front end and do safe filtering; 用$_POST的方法从前端获取信息, 并进行安全过滤.
    $stu_id   = num_check($_POST["stu_id"]);
    $stu_name = safe_check($_POST["stu_name"]);
    $status   = safe_check($_POST["status"]);     // $status可以为空

    // Connect the database named 'kebiao'; 连接数据库'kebiao'
    $database = new medoo(['database_name' => 'kebiao', 'charset' => 'utf8']);

    // Judging whether is information about the student in the database; 判断数据库中是否有该学生的信息
    if(!$database->has("sno_cid", [
        "AND" => [
            "name" => "$stu_name",
            "sno" => "$stu_id"
        ]
    ])){
        $header_object->status     = 0;
        $header_object->extra_info = "学号或姓名错误";
        $course_object->header     = $header_object;
        $json_course_info          = json_encode($course_object);
        echo $json_course_info;
        exit();
    }

    // Judging whether the request is sent by the login page; 判断是否是登录页面发送的请求
    if($stu_id && $stu_name && $status == "login"){
        $header_object->status     = 1;
        $header_object->extra_info = "学号与姓名均正确, 登录成功!";
        $course_object->header     = $header_object;
        $json_course_info          = json_encode($course_object);
        echo $json_course_info;
        exit();
    }

    // Record the user's information and insert into the database; 记录用户信息并存入数据库.
    $show       = "table";
    $ip         = getIP();
    $query_time = time();
    $query_day  = date("Ymd", time());
    $user_agent = $_SERVER["HTTP_USER_AGENT"];
    $database->insert("login_log", array("addtime"=>"$query_time","user_agent"=>"$user_agent","ip"=>"$ip","show_type"=>"$show","sname"=>"$stu_name","sno"=>"$stu_id","addday"=>"$query_day"));

    // According to the student number query the course information from the database and assign the result to $course_schedules; 
    // 根据学号查询数据库中的课程信息, 并将查询结果赋值给$course_schedules.
    $course_schedules = $database->select("coz_ext",[
            "[>]sno_cid" => ["coz_ext.cid" => "cid"],
            "[>]coz_info" => ["coz_ext.cid" => "cid"],
        ], [
            "sno_cid.cid",
            "sno_cid.sno",
            
            "coz_ext.cid",
            "coz_ext.time",
            "coz_ext.place",
            "coz_ext.week_begin",
            "coz_ext.week_end",
            "coz_ext.time_begin",
            "coz_ext.time_end",
            "coz_ext.time_chixu",

            "coz_info.cid",
            "coz_info.name",
            "coz_info.teacher",
            "coz_info.college",
            "coz_info.class",
        ], [
        "AND" => [
            "sno_cid.sno" => "$stu_id",
            //"wxid_cid.daytime" => "$daytime",
        ],
        "ORDER" => ['coz_ext.time_begin AESC']
    ]);

    // Some information of the week; 一些关于星期的信息.
    // When viewing on Sundays, the next week's schedule is displayed; 周日查看时，显示下一周课程表
    $week_info     = getWeekInformation(time() + 60*60*24);   
    $date          = $week_info['date'];
    $which_week    = $week_info['which_week'];
    $weekday_array = array(null, "周一", "周二", "周三", "周四", "周五");

    // The array used to store the course name；用于储存课程名的数组
    $course_name_index = 0;
    $course_name_array = array();

    // Construct the course desk data; 构造表格数据
    // Traverse object $course_schedules and fetch a 6*5 array named $course_info, which contains a variety of course information; 
    // 遍历$course_schedules对象并取出一个12*7的包含各种课程信息的叫做$course_info的数组. 
    for($weekday_num = 1; $weekday_num <= 7; $weekday_num++){
        $weekday       = $weekday_array[$weekday_num];
        $odd_week_day  = "单".$weekday;
        $even_week_day = "双".$weekday;

        $continuous_time = 0;    // The number of times the course was added; 添加同一时间课程的次数
        for($course_row = 1; $course_row <= 12; $course_row++){
            // $course_num make the array subscripts in the order of natural numbers; $course_num为使数组下标按自然数顺序排列 
            $course_num = $course_row;    
            $course_info[$course_num][$weekday_num] = (object) null;

            foreach($course_schedules as $key => $course_schedule) {
                // Match the qualifying course and $course_row is to determine whether the number of rows is equal to: the start time + the duration;
                // 匹配符合条件的课程，其中$course_row是判断行数是否等于: 开始的时间+持续的时间
                if((mb_substr($course_schedule['time'], 0, 2, 'utf-8') == $weekday || mb_substr($course_schedule['time'], 0, 3, 'utf-8') == $odd_week_day || mb_substr($course_schedule['time'], 0, 3, 'utf-8') == $even_week_day) && ($course_schedule['week_begin'] <= $which_week && $course_schedule['week_end'] >= $which_week) && $course_row == $course_schedule['time_begin'] + $continuous_time){
                    
                    // Get the name of teacher; 获取老师姓名
                    if(strpos($course_schedule['teacher'], "/") != false){
                        $course_schedule['teacher'] = substr($course_schedule['teacher'], 0, strpos($course_schedule['teacher'],"/"));
                    }
                    
                    // 判断$continuous_time是否小于持续时间
                    if ($continuous_time < $course_schedule['time_chixu']) {
                        // Add the course name to the $ course_name_array array; 将课程名添加进$course_name_array数组
                        if (array_search($course_schedule['name'], $course_name_array) === false) {
                            $course_name_array[$course_name_index++] = $course_schedule['name'];
                        }

                        $course_info[$course_num][$weekday_num]->course_name     = $course_schedule['name'];
                        $course_info[$course_num][$weekday_num]->course_place    = $course_schedule['place'];
                        $course_info[$course_num][$weekday_num]->course_duration = $course_schedule['time_chixu'];
                        $course_info[$course_num][$weekday_num]->teacher_name    = $course_schedule['teacher'];

                        if ($continuous_time == 0) {
                            $course_info[$course_num][$weekday_num]->course_continuous = false;
                        } else {
                            $course_info[$course_num][$weekday_num]->course_continuous = true;
                        }

                        $continuous_time++;
                    } else {
                    	$continuous_time = 0;
                    }
                }
            }
        }
    }

    // Set the same id for the same course name; 为相同课程名设置相同id
    for ($weekday_num = 1; $weekday_num <= 7; $weekday_num++) {
        for ($course_num = 1; $course_num <= 12; $course_num++) {
            for ($course_id = 0; $course_id < count($course_name_array); $course_id++) {
                if ($course_info[$course_num][$weekday_num]->course_name == $course_name_array[$course_id]) {
                    $course_info[$course_num][$weekday_num]->course_id = $course_id;
                }
            }
        }
    }   

    // Construct the course list data; 构造列表数据
    for ($weekday_num = 1; $weekday_num <= 7; $weekday_num++) {
        $weekday       = $weekday_array[$weekday_num];
        $odd_week_day  = "单".$weekday;
        $even_week_day = "双".$weekday;

        $course_list[$weekday_num] = array();
        for ($course_row = 1; $course_row <= 12; $course_row++) {

            $course_section     = null;
            $course_list_object = (object) null;
            foreach ($course_schedules as $key => $course_schedule) {
                
                if((mb_substr($course_schedule['time'], 0, 2, 'utf-8') == $weekday || mb_substr($course_schedule['time'], 0, 3, 'utf-8') == $odd_week_day || mb_substr($course_schedule['time'], 0, 3, 'utf-8') == $even_week_day) && ($course_schedule['week_begin'] <= $which_week && $course_schedule['week_end'] >= $which_week) && $course_row == $course_schedule['time_begin']) {

                    $course_time = $course_schedule['time'];
                    $strlen = strlen($course_time);
                    if (mb_substr($course_time, 0, 2, 'utf-8') == $weekday) {
                        $course_section = mb_substr($course_time, 2, $strlen, 'utf-8');
                    } elseif (mb_substr($course_time, 0, 3, 'utf-8') == $odd_week_day) {
                        $course_section = mb_substr($course_time, 3, $strlen, 'utf-8');
                    } elseif (mb_substr($course_time, 0, 3, 'utf-8') == $even_week_day) {
                        $course_section = mb_substr($course_time, 3, $strlen, 'utf-8');
                    }

                    $course_list_object->course_section = $course_section . "节";
                    $course_list_object->course_name    = $course_schedule['name'];
                    $course_list_object->course_place   = $course_schedule['place'];

                    if (array_search($course_list_object, $course_list[$weekday_num]) === false) {
                        array_push($course_list[$weekday_num], $course_list_object);
                    }
                }
            }
        }   
    }
    // var_dump($course_list);

    // This information returned when query the course schedule is successfully; 查询课表成功时返回的信息.
    $header_object->status     = 1;
    $header_object->extra_info = "查询成功";
    $header_object->week_info  = $which_week;
    $course_object->header     = $header_object;
    $course_object->desk       = $course_info;
    $course_object->list       = $course_list;
    
    $json_course_info = json_encode($course_object);
    echo $json_course_info;    
    
?>