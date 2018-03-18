<?
error_reporting(0);
session_start();
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
if(!isset($dsql)){
	$dsql = new DSQL();
}


if( $ac=="del" ){
	$SQL= "delete from  coursesexperiment where id='$id' ";
	//echo $SQL;
	if($dsql->query($SQL)){
		echo "$id 删除成功";
	}else{
		echo "$id 删除失败";
	}
}else if( $id>0 ){
	$isLesson = ($COURSE_TYPE == MetaDataGenerator::COURSE_TYPE_KETANG) ;
	if ($isLesson) {
		$SQL= "select START_TIME, END_TIME from LESSON  where ID = '$theId' ";
	} else {
		$SQL= "select starttime as START_TIME, endtime as END_TIME from courses where id = '$theId' ";
	}
	//echo $SQL;
	//if(strtotime($time2)>strtotime($time1) && strtotime($time2)<strtotime($time3)){
		if($dsql->query($SQL)){
			$dsql->next_record();
			$coursesstarttime = $dsql->f('START_TIME');
			$coursesendtime = $dsql->f('END_TIME');
			//echo " $coursesstarttime :: $starttime";

			//1、限时限定为1-120(分钟)
			//2、分值限定为1-100
			//3、答题次数限定为1-100（整数）
			$count = intval($count);
			if($timemode<0||$timemode>120){
				echo " $name 修改失败，限时限定为1-120(分钟)，如不限时请填写0";
				exit;
			}
			if($score<0||$score>100){
				echo " $name 修改失败，分值限定为1-100";
				exit;
			}
			 
			if($count<0||$count>10){
				echo " $name 修改失败，答题次数限定为整数1-10";
				exit;
			}

			if(strtotime($endtime)<strtotime($starttime)){
				echo " $name 修改失败，实验关闭时间不能早于课堂开始时间:$coursesstarttime";
				exit;

			}else if(strtotime($coursesstarttime)>strtotime($starttime)){
				echo " $name 修改失败，实验开始时间不能早于课堂开始时间:$coursesstarttime";
				exit;
			}else if(strtotime($coursesendtime)<strtotime($endtime)){
				echo " $name 修改失败，实验结束时间不能晚于课堂结束时间:$coursesendtime";
				exit;
			}else{
				$SQL= "update coursesexperiment set name='$name',starttime='$starttime',endtime='$endtime',score='$score',count='$count',scoringmode='$scoringmode',timemode='$timemode',islimittime='$islimittime',isshowscore='$isshowscore',sort = '$sort' where id = '$id' ";
				//echo $SQL;
				if($dsql->query($SQL)){
					echo "修改成功";
				}else{
					echo "修改失败";
				}
			}



		}


	 
}else
if( $action=="forumdetail" ){

}

?>