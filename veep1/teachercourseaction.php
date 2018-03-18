<?
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

require_once("header.php");

   // echo "courseid=" .$courseid . "; payquantity=" . $payquantity . "<br/>";
$url = "addteacher.php";
$checkerOfCourse = new CheckerOfCourse();
$QUERY_STRING_1 = $checkerOfCourse->recoverChar($QUERY_STRING_1);
$url .= "?" . $QUERY_STRING_1 . "#" . $teacher_id;
$dsql = new DSQL();
if ($ac == "selectcourseid") {//为教师分配课堂。传入两个参数 课程 id: courseid ，人次： payquantity
    $SQL = "SELECT id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity-ifnull(trueusage,0) as  quantity ,moocid,step 
    	FROM courses where id= '$courseid'";
    //echo $SQL . "<br/>";
    $dsql->query($SQL);
    $dsql->next_record();
    $quantity = $dsql->f("quantity");

    if($payquantity<=$quantity){// 传入的数量少于现有数量

        $id = $dsql->f('id');
        $name = $dsql->f('name');
        $COURSE_NAME = $dsql->f('name');
        $moocurl = $dsql->f('moocurl');
        $starttime = $dsql->f('starttime');
        $endtime = $dsql->f('endtime');
        $userid = $dsql->f('userid');
        $created_at = $dsql->f('created_at');
        $updated_at = $dsql->f('updated_at');
        $coursesimg = $dsql->f('coursesimg');
        $content = $dsql->f('content');
        $useremail = $dsql->f('useremail');
        $code = $dsql->f('code');
        $trueusage = $dsql->f('trueusage');


        $moocid = $dsql->f('moocid');
        $step = $dsql->f('step');
        $isshow = $dsql->f('isshow');
        $status = $dsql->f('status');
        $sort = $dsql->f('sort');

        $SQL = "SELECT truename FROM users where id=$teacher_id and  (usertype=". CheckerOfCourse::PID_TEACHER ." or usertype=". CheckerOfCourse::PID_FEETEACHER .") ";//付费教师或教师
		//echo $SQL . "<br/>";
        $dsql->query($SQL);
        if($dsql->next_record()){//用户存在
            $teachername = $dsql->f("truename");


            //$SQL = "SELECT max(teachersequence)as teachersequence,count(*) as c FROM courses where useremail='$email'  and pid= '$courseid'";
            $SQL = "select max(TEACHER_SEQUENCE) as teachersequence, count(*) as c from LESSON where TEACHER_ID = $teacher_id and COURSE_ID=$courseid";
            //echo $SQL . "<br/>";
            $dsql->query($SQL);
            $teachersequence=1;
            if($dsql->next_record()){
                $teachersequence = $dsql->f("teachersequence");
                $c = $dsql->f("c");
                $teachersequence = $teachersequence+1;
            }
            //课程名+学校名+教师名+序号
            $name =  "$name-$teachername-$teachersequence";
            //echo $name . "<br/>";


            //$SQL = "insert into courses (id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,teachercontent,teacherlogo,code,payquantity,moocid,step,coursesgroupid,isclass,pid,teachersequence,sort, teacher_id)values( 0,'$name','$moocurl','$starttime','$endtime','$auth_id',now(),now(),'$coursesimg','$content','$email','$teachercontent','$teacherlogo','$code','$payquantity','$moocid','$step','$coursesgroupid','1','$courseid','$teachersequence','$sort', '$teacher_id')";
            $SQL = "insert into LESSON 
            		(NAME, MOOC_URL, START_TIME, END_TIME, ASSIGNER_ID, 
            		CREATE_TIME, IMG_URL, INTRODUCTION, TEACHER_ID,
            		CODE, STUDENT_LIMIT, MOOC_ID,
            		COURSE_ID, TEACHER_SEQUENCE, COURSE_NAME, TEACHER_NAME, ASSIGNER_NAME, 
            		SORT_ORDER, STATUS, SHOWN, ASSIGN_TIME) 
            	values(
            		'$name','$moocurl','$starttime','$endtime','$auth_id',
            		now(), '$coursesimg','$content','$teacher_id',
            		'$code','$payquantity','$moocid',
            		'$courseid', '$teachersequence', '$COURSE_NAME', '$teachername', '$auth_username', 
            		'$sort', 0, 0, now())";
            //echo $name . "<br/>";
            if ($dsql->query($SQL)) {
				echo "<br><script>alert('课堂分配成功'); window.location='". $url ."'; </script>\n";//
            } else {
                //echo "<br> 课程${value}配置成功 , \n";
            }

            $dsql->query("select last_insert_id() as lid ");
            $dsql->next_record();
            $lid = $dsql->f("lid");
            //echo $lid . "<br/>";
            $rand = rand(1000,9999);
            $code = $lid.$rand;//生成选课码
            //$dsql->query("update courses set payquantityusage=payquantityusage+$payquantity where id = '$courseid'");
            $SQL = "update LESSON set  CODE='$code' where ID = '$lid'";//更新CODE字段
            $dsql->query($SQL);
            //echo $SQL . "<br/>";
            $SQL = "insert into coursesexperiment (
            	id,starttime,endtime,score,count,
            	scoringmode,timemode,islimittime,isshowscore,userid,
            	coursesid,experimentsid,name,sort, COURSE_TYPE)
            	(select 
            	0 as  id,starttime,endtime,score,count,
            	scoringmode,timemode,islimittime,isshowscore,'$auth_id' as userid, 
            	'$lid' as coursesid,experimentsid,name,sort, ". MetaDataGenerator::COURSE_TYPE_KETANG .
            	" from coursesexperiment 
            	where coursesid = '$courseid' and COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KECHENG.")";
            //echo $SQL . "<br/>";

            if ($dsql->query($SQL)) {
                echo "<br>  ${value}实验配置成功.  \n";
            } else {
            }

        }else{//用户不存在
            echo "<br><script>alert('老师不存在或者老师角色不对');window.location='". $url ."'</script>\n";
        }
    }else{//数量超过限额
        echo "<br><script>alert('课程配置次数超过限制，最大$quantity'); window.location='". $url ."';</script>\n"; //
    }
}else if ($ac == "selectcourse") {
/*
    $dsql->query("SELECT id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity-payquantityusage as quantity ,moocid,step FROM courses where  id='$courseid'");
    $dsql->next_record();
    $teachername = $dsql->f("truename");
    while(list($key,$value)=each($courseid)){
        $SQL = "insert into courses (id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,teachercontent,teacherlogo,code,payquantity,moocid,step,coursesgroupid,isclass)(select 0 as id,concat(name,'-$teachername-$value') as name ,moocurl,starttime,endtime,'$auth_id' as userid,now() as created_at,now() as updated_at,coursesimg,content,'$email' as useremail,teachercontent,teacherlogo,code,'$payquantity' as payquantity,moocid,step,coursesgroupid, 1  as isclass from courses where id ='$value')";
        if (!$dsql->query($SQL)) {

        } else {
            echo "<br> 课程${value}配置成功 , \n";
            echo "<br><script>alert('课程配置成功');window.location='". $url ."'</script>\n";
        }

        $dsql->query("select last_insert_id() as lid ");
        $dsql->next_record();
        $lid = $dsql->f("lid");

        $SQL = "insert into coursesexperiment (id,starttime,endtime,score,count,scoringmode,timemode,islimittime,isshowscore,userid,coursesid,experimentsid,name,sort)(select 0 as  id,starttime,endtime,score,count,scoringmode,timemode,islimittime,isshowscore,'$auth_id' as userid, '$lid' as coursesid,experimentsid,name,sort from coursesexperiment where coursesid = '$value' )";

        if (!$dsql->query($SQL)) {
        } else {
            echo "<br>  ${value}实验配置成功.  \n";
        }
    }
*/
}
include("footer.php");
?>