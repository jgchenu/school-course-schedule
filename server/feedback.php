<?php
	/*
	 * Author: Jiafan
	 * Date: 2017/05/30
	 */
	
	error_reporting(0);
	header("Content-Type: application/json; charset=utf-8");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");

	require_once 'bins/medoo.min.php';
	require_once 'function/general.php';

	$words    = $_POST["fk"];
	$stu_id   = $_POST["stu_id"];
	$stu_name = $_POST["stu_name"];

	$feedback_object->header = null;

	if(isset($words)&&$words!=""&&isset($stu_id)&&$stu_id!=""&&isset($stu_name)&&$stu_name!=""){

		$ip = getIP();
		$query_time = time();
		$query_date = date("Y-m-d H:i:s",$query_time);

		$database = new medoo(['database_name' => 'kebiao','charset' => 'utf8']);
		$database->insert("fankui", array("add_time"=>"$query_date","addtime"=>"$query_time",
            "ip"=>"$ip","sname"=>"$stu_name","sno"=>"$stu_id","words"=>"$words"));

		$show = $_COOKIE["show"];
		//header( 'Location: course_schedule.php?stu_id='.$stu_id.'&stu_name='.$stu_name.'&show='.$show );
		
		$header_object->status     = 1;
		$header_object->extra_info = "感谢您的反馈~";
		$feedback_object->header   = $header_object;
		$json_feedback_info        = json_encode($feedback_object);
		echo $json_feedback_info;

	}else{
		$header_object->status     = 0;
		$header_object->extra_info = "反馈不能为空";
		$feedback_object->header   = $header_object;
		$json_feedback_info        = json_encode($feedback_object);
		echo $json_feedback_info;
	}
?>


