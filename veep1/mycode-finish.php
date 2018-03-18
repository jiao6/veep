<?php
session_start();

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");

//echo $ac ."<br/>" . $code;
if ($ac != "code") {//操作错误
    exit;
}

    $SQL = "select l.ID, l.STUDENT_LIMIT, l.COURSE_ID, l.NAME as className, cp.name as courseName, 
    	cp.payquantity as maxquantity 
    	from LESSON l, courses cp 
    	where l.CODE = '$code' and l.STATUS=". MetaDataGenerator::STATUS_EFFECTIVE ." and l.SHOWN=". MetaDataGenerator::SHOWN_YES ." 
    		and l.COURSE_ID = cp.id and cp.status=". MetaDataGenerator::STATUS_EFFECTIVE ." and cp.isshow=". MetaDataGenerator::SHOWN_YES;
    //echo $SQL ."<br/>";
    $dsql = new DSQL();
    $dsql->query($SQL);
    if (!$dsql->next_record()) {//这门课不存在
        $info[0] = "{\"result\":\"FAIL\", \"message\":\"这门课不存在或未上线！\"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    }
    //有这门课堂
    $lessonId = $dsql->f("ID");
    $courseid = $dsql->f("COURSE_ID");
    $className = $dsql->f("className");//课堂名字
    $courseName = $dsql->f("courseName");//课程名字
    $payquantity = $dsql->f("STUDENT_LIMIT");//该课堂的上限
    //$isshow = $dsql->f("isshow");
    $pid = $dsql->f("COURSE_ID");
    $maxquantity = $dsql->f("maxquantity");// 课程的最大上限
    $SQL = "SELECT * FROM coursesuser where coursesid='$lessonId' and  userid = '$auth_id'";
    //echo "课堂名字=" . $className. "；课程名字=" . $courseName . "；课堂人数上限=" . $maxquantity. "; SQL=". $SQL . "<br/>";
    $dsql->query($SQL);
    if ($dsql->next_record()) {//此人已经进入了课堂，更新他的状态，退出
        $dsql->query("update coursesuser  set status=0 where coursesid='$lessonId' and userid = '$auth_id'");
        $info[0] = "{\"result\":\"FAIL\", \"message\":\"您已经加入课堂【".$className."】，不能重复加入 !\"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    }
    $SQL = "SELECT count(*) as c FROM coursesuser where coursesid='$lessonId' ";
    $dsql->query($SQL);
    $dsql->next_record();
    $allcount = $dsql->f("c");//本课堂的人数
//echo "课堂人数=" . $allcount. "; 全课程上限=" . $maxquantity. "; SQL=". $SQL . "<br/>";
    if ($allcount >= $maxquantity) {//该课堂人数已经超过课程上限，退出
        $info[0] = "{\"result\":\"FAIL\", \"message\":\" 课程【".$courseName."】已经达到人次上限【". $maxquantity ."】，您来晚了……   \"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    }
    //课堂余额大于已进入数量
    $SQL = "SELECT  ifnull(sum(STUDENT_AMOUNT),0) as trueusage FROM LESSON where  COURSE_ID  ='$pid'";
    $dsql->query($SQL);
    $dsql->next_record();
    $trueusage = $dsql->f("trueusage");//算出该课程的所有课堂的使用总人数
    $trueusage = $trueusage;
//echo "该课程的所有课堂的使用总人数=" . $trueusage. "; maxquantity=". $maxquantity. "; SQL=". $SQL . "<br/>";

/*
    $SQL = "SELECT payquantity FROM courses where id ='$pid'";
    $dsql->query($SQL);
    $dsql->next_record();
    $pidpayquantity = $dsql->f("payquantity");*/
//echo "该课程的人数上限=" . $pidpayquantity. "; trueusage=" . $trueusage. "; SQL=". $SQL . "<br/>";
    if($trueusage >= $maxquantity) {//课堂总额冒了，提示，退出。
        $info[0] = "{\"result\":\"FAIL\", \"message\":\" 课程【".$courseName."】已经达到人次上限【". $maxquantity ."】，您来晚了……   \"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    }
        //上限大于所有课堂的人数，还能插入
    $SQL = "insert into coursesuser (coursesid,userid,created_at,status) values('$lessonId', '$auth_id', now(), 0)";
//echo $SQL ."<br/>";
    if ($dsql->query($SQL)) {
        $allcount++;
        $sql = "update LESSON set STUDENT_AMOUNT= $allcount where  id  ='$lessonId'";
        //echo $sql ."<br/>";
        $dsql->query($sql);
        $trueusage++;
        $sql = "update courses set trueusage= $trueusage, payquantityusage = $trueusage where  id  ='$pid'";
        //echo $sql ."<br/>";
        $dsql->query($sql);
        $info[0] = "{\"result\":\"SUCCESS\", \"message\":\" 恭喜您，加入课堂【".$className."】成功！  \"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    } else {
        $info[0] = "{\"result\":\"FAIL\", \"message\":\" 加入课堂失败……   \"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
        exit;
    }

    exit;
?>
