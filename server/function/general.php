<?php
	/*
	 * Author: Jiafan
	 * Date: 2017/05/30
	 */
	require_once 'function/schedule.php';

	// Get the ip of client; 获取客户端的IP.
	function getIP(){ 
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
			$ip = getenv("REMOTE_ADDR"); 
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
			$ip = $_SERVER['REMOTE_ADDR']; 
		else 
			$ip = "unknown"; 
		return($ip); 
	}

	// According to the section number of courses, get the times of course;
    // 根据课程节数获取课程时间.
	
	// Get the course times of the houhai campus; 获取后海校区的课程时间.
	// $begin and $end is the section number of courses; $begin和$end是课程节数的意思.
	function getTimesOfHouhaiCampus($begin, $end, $day){
		$month = (int)date("m",$day);
		if($month > 9 || $month < 5){
			$times_1    = getTimeOfOctober($begin);
			$times_2    = getTimeOfOctober($end);
			$begin_time = $times_1["begin"];
			$end_time   = $times_2["end"];
		}else{
			$times_1    = getTimeOfMay($begin);
			$times_2    = getTimeOfMay($end);
			$begin_time = $times_1["begin"];
			$end_time   = $times_2["end"];
		}
		$times = $begin_time."-".$end_time;
		return $times;
	}

	// Get the course times of the xili campus; 获取西丽校区的课程时间.
	// $begin and $end is the section number of courses; $begin和$end是课程节数的意思.
	function getTimesOfXiliCampus($begin, $end){

		$times_1    = getTimeOfXili($begin);
		$times_2    = getTimeOfXili($end);
		$begin_time = $times_1["begin"];
		$end_time   = $times_2["end"];

		$times = $begin_time."-".$end_time;
		return $times;
	}

	// Get the information for the week; 获取星期的信息.
	function getWeekInformation($time){	
		
		$return_datas["date"] = date("m月d日", $time);

		// $day_of_week means today of the week; $day_of_week表示周几.
		$week_array  = array("日","一","二","三","四","五","六");
		$day_of_week = $week_array[date("w", $time)];
		$return_datas["day_of_week"] = $day_of_week;

		// $which_week means which week of this week; $which_week表示第几周.
		$today           = $time;
		$begin           = strtotime("2017-03-05 0:00:00");
		$difference_date = $today - $begin;
		$which_week      = ceil($difference_date / (60*60*24*7));
		$return_datas["which_week"] = $which_week;

		// $odd_or_even_week means whether this week is odd week or even week; $odd_or_even_week表示单周或双周.
		if($which_week % 2){
			$odd_or_even_week = "单";
		}else{
			$odd_or_even_week = "双";
		}
		$return_datas["odd_or_even_week"] = $odd_or_even_week;

		return $return_datas;
	}

?>
