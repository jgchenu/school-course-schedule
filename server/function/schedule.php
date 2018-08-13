<?php
	/*
	 * Author: Jiafan
	 * Date: 2017/05/30
	 */

	// Get time in the May schedule; 在五月的时间表中获取时间.
	// $number is course section number; $number表示第几节课.
	function getTimeOfMay($number){
		switch ($number){
			case 1:
			  $begin = "8：30";
			  $end   = "9：10";	
			  break;
			case 2:
			  $begin = "9：20";
			  $end   = "10：00";
			  break;
			case 3:
			  $begin = "10：20";
			  $end   = "11：00";
			  break;
			case 4:
			  $begin = "11：10";
			  $end   = "11：50";
			  break;
			case 5:
			  $begin = "14：30";
			  $end   = "15：10";
			  break;
			case 6:
			  $begin = "15：20";
			  $end   = "16：00";
			  break;
			case 7:
			  $begin = "16：20";
			  $end   = "17：00";
			  break;
			case 8:
			  $begin = "17：10";
			  $end   = "17：50";
			  break;
			case 9:
			  $begin = "19：00";
			  $end   = "19：40";
			  break;
			case 10:
			  $begin = "19：40";
			  $end   = "20：20";
			  break;
			case 11:
			  $begin = "20：30";
			  $end   = "21：10";
			  break;
			case 12:
			  $begin = "21：00";
			  $end   = "21：45";
			  break;
			default:
			  echo "error";
		}
		$times["begin"] = $begin;
		$times["end"] = $end;
		return $times;
	}
	
	// Get time in the Octorber schedule; 在十月的时间表中获取时间.
	// $number is course section number; $number表示第几节课.
	function getTimeOfOctober($number){
		switch ($number){
			case 1:
			  $begin = "8：30";
			  $end   = "9：10";
			  break;
			case 2:
			  $begin = "9：20";
			  $end   = "10：00";
			  break;
			case 3:
			  $begin = "10：20";
			  $end   = "11：00";
			  break;
			case 4:
			  $begin = "11：10";
			  $end   = "11：50";
			  break;
			case 5:
			  $begin = "14：00";
			  $end   = "14：40";
			  break;
			case 6:
			  $begin = "14：50";
			  $end   = "15：30";
			  break;
			case 7:
			  $begin = "15：50";
			  $end   = "16：30";
			  break;
			case 8:
			  $begin = "16：40";
			  $end   = " 17：20";
			  break;
			case 9:
			  $begin = "19：00";
			  $end   = "19：40";
			  break;
			case 10:
			  $begin = "19：40";
			  $end   = "20：20";
			  break;
			case 11:
			  $begin = "20：30";
			  $end   = "21：10";
			  break;
			case 12:
			  $begin = "21：00";
			  $end   = "21：45";
			  break;
			default:
			  echo "error";
		}
		$times["begin"] = $begin;
		$times["end"] = $end;
		return $times;
	}

	// Get time in the schedule of xili campus; 在西丽校区的时间表中获取时间.
	// $number is course section number; $number表示第几节课.
	function getTimeOfXili($number){
		switch ($number){
			case 1:
			  $begin = "8：30";
			  $end   = "9：10";
			  break;
			case 2:
			  $begin = "9：20";
			  $end   = "10：00";
			  break;
			case 3:
			  $begin = "10：10";
			  $end   = "10：50";
			  break;
			case 4:
			  $begin = "11：00";
			  $end   = "11：40";
			  break;
			case 5:
			  $begin = "11：50";
			  $end   = "12：30";
			  break;
			case 6:
			  $begin = "14：30";
			  $end   = "15：10";
			  break;
			case 7:
			  $begin = "15：20";
			  $end   = "16：00";
			  break;
			case 8:
			  $begin = "16：10";
			  $end   = "16：50";
			  break;
			case 9:
			  $begin = "17：00";
			  $end   = "17：40";
			  break;
			case 10:
			  $begin = "19：00";
			  $end   = "19：40";
			  break;
			case 11:
			  $begin = "19：50";
			  $end   = "20：30";
			  break;
			case 12:
			  $begin = "20：40";
			  $end   = "21：20";
			  break;
			default:
			  echo "error";
		}
		$times["begin"] = $begin;
		$times["end"] = $end;
		return $times;
	}

?>