<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");

if (!$auth) loginFalse();
error_reporting(ALL);
function loginFalse()
{
    Header("Location:login.php");
}

$dsql = new DSQL();
if ($auth_pid != 3) {
    // exit;
}
    if(!$lessonId){
        $lessonId = $_SESSION["experimentsLessonId"];
    }

$SQL = "SELECT id FROM coursesuser  where status=0 and  coursesid = '$lessonId' and userid='$auth_id' ";
//echo $SQL . "\n";
$dsql->query($SQL);

if(!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<script>alert('请加入课堂再进入实验');
    window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
<?
exit;

}
    if( $lessonId>10){
$SQL = "SELECT L.ID,L.STUDENT_LIMIT as payquantity FROM LESSON  L,  courses  cp  where  L.ID = '$lessonId' and L.STATUS=0 AND L.SHOWN=1  AND L.COURSE_ID=cp.id and   cp.status=0 and cp.isshow=1  and  L.START_TIME < NOW( ) AND  L.END_TIME > NOW( )  and  cp.starttime < NOW( ) and  cp.endtime > NOW( ) ";
//echo $SQL;
    $dsql->query($SQL);
    if (!$dsql->next_record()) {

    ?><!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    <script>alert('实验不存在或者实验不在开放时间');
        window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
    <?
    exit;
    }

    }
if($action == "submitted"){
    $experimentsuserid = $_SESSION["experimentsuserid"];
    $lessonId = $_SESSION["experimentsLessonId"];
    require_once("header.php");
    $SQL = "select id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score from experimentsuser where  id='$experimentsuserid'    and  userid='$auth_id' ";
    $dsql->query($SQL);
    //echo $SQL . "\n";

    if ($dsql->next_record()) {
        $testID = $dsql->f('id');
        $experimentsid = $dsql->f('experimentsid');
       // $coursesid = $dsql->f('coursesid');
        $stucount = $dsql->f('count');

    } else {
        ?>
        <script>alert('请加入实验');
            window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
        <?
        exit;
    }
    $count = 1;
    $SQL = "SELECT starttime,endtime,score,count,scoringmode,timemode,islimittime,isshowscore 
    	FROM coursesexperiment  
    	where coursesid = '$lessonId' AND COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." and experimentsid='$experimentsid' and  starttime < NOW( ) and  endtime > NOW( )     ";
    //echo $SQL . "\n";
    $dsql->query($SQL);
    if ($dsql->next_record()) {

        $starttime = $dsql->f('starttime');
        $endtime = $dsql->f('endtime');

        $score = $dsql->f('score');
        $count = $dsql->f('count');
        $scoringmode = $dsql->f('scoringmode');
        $timemode = $dsql->f('timemode');

        $isshowscore = $dsql->f('isshowscore');

        if ($count != 0 && $stucount >= $count) {
            //echo("次数限制已经到了，你已经做了$stucount 次，课程设置最多$count 次");
            echo "<br><script>alert('次数限制已经到了，你已经做了$stucount 次，课程设置最多$count 次');window.location='lesson.php?lessonId=$lessonId'</script>\n";
            exit;
        }

    } else {
        ?>
        <script>alert('请加入实验');
            window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
        <?
        exit;

    }

    $paramsKeys = array("stuName", "email", "reportDate");//实验学生信息的三个参数
    $paramsValues = array($auth_username, $auth_email, date("Y年m月d日h:i"));//实验学生信息的三个参数
    $xmlFilepath = "$reportpath/xml/test$experimentsid.xml";//实验xml文件的路径
    $pathyear=date('y');
        $pathmonth=date('m');

        $pathlast=$auth_id%10000;
        $time = time();
    //echo $xmlFilepath;
        $studentreportFilepath =  "$reportpath/studentreport/$pathyear/$pathmonth/$pathlast";
        $studentreportFile = "$studentreportFilepath/$lessonId.$experimentsid.$auth_id.$pathyear.$pathmonth.$time.xml";
        //echo "$studentreportFilepath";

    $getResult = $_SESSION["$xmlFilepath"];//提取表单中的name属性
        mkdirs($studentreportFilepath);
        //echo "\"$xmlFilepath\", $paramsKeys, $paramsValues,\"$studentreportFilepath/$lessonId.$experimentsid.$austh_id.$pathyear.$pathmonth.$time.xml\"";
    for ($i = 1; $i < sizeof($getResult); $i++) {
        $Id = $getResult[$i];
        $paramsKeys[] = $Id;
        $paramsValues[] = SBC_DBC(trimall($_POST["$Id"]), 1);//得到答案参数
    }
   //echo "\"$xmlFilepath\", $paramsKeys, $paramsValues,\"$studentreportFilepath/$lessonId.$experimentsid.$austh_id.$pathyear.$pathmonth.$time.xml\"";
    $judgmentResult = judgment("$xmlFilepath", $paramsKeys, $paramsValues,"$studentreportFile");//评判学生分数，参数[实验xml路径，参数ID，参数Value]；其中参数数组中的前三个为学生信息，后面为答案。函数返回大小为二的数组，为{生成的评判后的实验xml路径，分数},路径写死在程序里，为/var/www/html/xml/XXXX.xml

    $result = anysxml("$judgmentResult[0]", "none", 0);//显示xml文件的内容。参数[实验xml路径，表单提交的PHP，type]；传入原始实验xml文件时，产生答题所用的表单；传入评判后的实验xml产生评判后的内容，此时参数2可以为任意字符串，但必须有。type为0时表示不生成html文件，此时$result[0]包含html的内容。type为1时表示生成html文件，此时$result[0]为html的路径。$result[1:]保存表单中input的name
    if ($isshowscore == "显示") {
        echo  "$result[0]";
    }else{
        echo "<br><script>alert('提交成功');window.location='lesson.php?lessonId=$lessonId'</script>\n";
    }
    //实验结束传递成绩 :
    $score = $judgmentResult[1];//得分
    $time = time() - $_SESSION["starttime"];//消耗时间 分钟数
    $xmlfile = $judgmentResult[0];//xml文件名
    $htmlfile = $result[0];//html文件名
    // $url = "$backurl?stuID=$stuID&couresID=$couresID&experimentID=$experimentID&reportGrade=$reportGrade&time=$time&xmlfile=	$xmlfile&htmlfile=$htmlfile";
    //echo file_get_contents($url);
    $stucount = $stucount + 1;
    $lastscore = $score;
    $consumingtime = $time;
    if ($scoringmode == "平均分") {
        $SQL = "select sum(score) as total,count(*) as c,sum(consumingtime) as consumingtime from experimentsuserlog  where  coursesid = '$lessonId' and experimentsid='$experimentsid'  and  userid='$auth_id' ";
        $dsql->query($SQL);
        if ($dsql->next_record()) {
            $total = $dsql->f('total');
            $c = $dsql->f('c');
            $consumingtime = $dsql->f('consumingtime');
            $lastscore = ($total + $score) / ($c + 1);
            $consumingtime = ($time + $consumingtime) / ($c + 1);
        }
    }
    if ($scoringmode == "最高分") {
        $SQL = "select score as max,consumingtime  from experimentsuserlog  where  coursesid = '$lessonId' and experimentsid='$experimentsid'  and  userid='$auth_id' order by score desc limit 1 ";
        $dsql->query($SQL);
        if ($dsql->next_record()) {
            $max = $dsql->f('max');
            if ($max > $score) {
                $lastscore = $max;
                $consumingtime = $dsql->f('consumingtime');
            }
        }
    }


    $SQL = "select id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score from experimentsuser where  coursesid = '$lessonId' and experimentsid='$experimentsid'  and  userid='$auth_id' ";
    $dsql->query($SQL);
//echo $SQL;
    if ($dsql->next_record()) {//存在
        $stucount = $dsql->f('count');
        $experimentsuserid = $dsql->f('id');
        $SQL = "update  experimentsuser  set count=count+1,score='$lastscore' ,consumingtime='$consumingtime' ,created_at=now()  where id='$experimentsuserid' and  coursesid = '$lessonId' and experimentsid='$experimentsid'  and  userid='$auth_id'  ";
        $dsql->query($SQL);

    } else {
        $SQL = "insert into  experimentsuser (id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score) values( 0, '$id', '$lessonId','$auth_id',now(),'$auth_email','1','$consumingtime','$score')";
        //echo $SQL;
        $dsql->query($SQL);
    }
    $SQL = "insert into  experimentsuserlog (id,experimentsuserid,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score,reportfile) values( 0,'$experimentsuserid', '$experimentsid', '$lessonId','$auth_id',now(),'$auth_email','$stucount','$time','$score','$studentreportFile')";
    $dsql->query($SQL);
}else if($id > 0){
require_once("header.php");
//做实验

if ($auth_pid != 3 && $lessonId > 0){
    $SQL = "SELECT L.ID,L.STUDENT_LIMIT as payquantity FROM LESSON  L,  courses  cp  where  L.ID = '$lessonId' and L.STATUS=0 AND L.SHOWN=1  AND L.COURSE_ID=cp.id and   cp.status=0 and cp.isshow=1  and  L.START_TIME < NOW( ) AND  L.END_TIME > NOW( )  and  cp.starttime < NOW( ) and  cp.endtime > NOW( ) ";
    //echo $SQL;

//

//echo $SQL . "\n";
$dsql->query($SQL);

if (!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<body>
<script>alert('课程 不存在 或者 课程不再开放时间');
    window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
<?
exit;

}
}

$count = 0;
$SQL = "SELECT starttime,endtime,score,count,scoringmode,timemode,islimittime,isshowscore 
	FROM coursesexperiment  
	where coursesid = '$lessonId' AND COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." AND experimentsid='$id' and  starttime < NOW( ) and  endtime > NOW( )     ";
//echo $SQL . "\n";
$dsql->query($SQL);

if (!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<body>
<script>alert('实验不存在 或者 课程不再开放时间');
    window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
<?
exit;

} else {
    $starttime = $dsql->f('starttime');
    $endtime = $dsql->f('endtime');

    $score = $dsql->f('score');
    $count = $dsql->f('count');
    $scoringmode = $dsql->f('scoringmode');
    $timemode = $dsql->f('timemode');

    $isshowscore = $dsql->f('isshowscore');

}


$SQL = "SELECT id FROM coursesuser  where coursesid = '$lessonId' and userid='$auth_id' ";
//echo $SQL . "\n";
$dsql->query($SQL);

if (!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<script>alert('请加入课程再进入实验');
    window.location = "lesson.php?lessonId=<?print($lessonId)?>"</script>
<?
exit;

}
$SQL = "select id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score from experimentsuser where  coursesid = '$lessonId' and experimentsid='$id'  and  userid='$auth_id' ";
$dsql->query($SQL);
//echo $SQL;
if ($dsql->next_record()) {//存在
    $stucount = $dsql->f('count');
    $experimentsuserid = $dsql->f('id');
    //echo " $stucount  $stucount";
    if ($count != 0 && $stucount >= $count) {
        //die("次数限制已经到了，你已经做了$stucount 次，课程设置$count 次");
        echo "<br><script>alert('次数限制已经到了，你已经做了$stucount 次，课程设置最多可以做$count 次实验');window.location='lesson.php?lessonId=$lessonId'</script>\n";
    }else if($count!=0){
        echo "<br><script>alert('不含这次您已经做了$stucount 次，课程设置最多可以做$count 次实验');</script>\n";
    }
} else {
    $SQL = "insert into  experimentsuser (id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score) values( 0, '$id', '$lessonId','$auth_id',now(),'$auth_email','0','','0')";
    //echo $SQL;
    $dsql->query($SQL);
    $SQL = "select id,experimentsid,coursesid,userid,created_at,useremail,count,consumingtime,score from experimentsuser where  coursesid = '$lessonId' and experimentsid='$id'  and  userid='$auth_id' ";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        $experimentsuserid = $dsql->f('id');
    }
}
$_SESSION["experimentsuserid"] = "$experimentsuserid";//保存id
$_SESSION["experimentsLessonId"] = "$lessonId";//保存id

//

$SQL = "select id,name,content,softfile,reportfile  from experiments where   id='$id'   ";
$dsql->query($SQL);

if ($dsql->next_record()) {
    $reportfile = $dsql->f('reportfile');
}
$xmlFilepath = "$reportpath/xml/test$id.xml";//实验xml文件的路径
//echo $xmlFilepath;
//echo "\"$xmlFilepath\",\"tet.php?testID=\".$id,0";

$result = anysxml("$xmlFilepath", "tet.php?testID=" . $id, 0);//生成实验报告表单，提交php为“course_45_report.php”

$_SESSION["$xmlFilepath"] = $result;//保存$result

$_SESSION["starttime"]=time();


echo '<DIV align="center"><div style="margin:auto auto;height: 40px;line-height:40px;text-align: center;width:100%;"><div style="width:50%;float:left"><label>邮箱:</label><label>' . $auth_email . '</label></div><div style="width:50%;float:left"><label>姓名:</label><label>' . $auth_username . '</label></div></div>';

echo '<div style="margin:auto auto;height: 40px;line-height:40px;text-align: center;width:100%;"><div style="width:50%;float:left"><label>实验日期:</label><label>' . date("Y年m月d日h:i") . '</label></div><div style="width:50%;float:left"><label><font color="red">';
if ($timemode > 0) {//倒计时


    ?>限时自动提交还剩<span id="timer"></span>

    <script language="javascript" type="text/javascript">
        setInterval(function() {
            $("#keepalive").load('test.php',"");
        }, 5000);
        /*主函数要使用的函数，进行声明*/
        var clock = new clock();
        /*指向计时器的指针*/
        var timer;
        window.onload = function () {
            /*主函数就在每50秒调用1次clock函数中的move方法即可*/
            timer = setInterval("clock.move()", 1000);
        }
        function clock() {
            /*s是clock()中的变量，非var那种全局变量，代表剩余秒数*/
            this.s =<?print($timemode*60)?>;
            this.move = function () {
                /*输出前先调用exchange函数进行秒到分秒的转换，因为exchange并非在主函数window.onload使用，因此不需要进行声明*/
                document.getElementById("timer").innerHTML = exchange(this.s);
                /*每被调用一次，剩余秒数就自减*/
                this.s = this.s - 1;
                /*如果时间耗尽，那么，弹窗，使按钮不可用，停止不停调用clock函数中的move()*/
                if (this.s < 0) {
                    alert("时间到");
                    document.forms[0].submit();
                    clearTimeout(timer);
                }

            }
        }
        function exchange(time) {
            /*javascript的除法是浮点除法，必须使用Math.floor取其整数部分*/
            this.m = Math.floor(time / 60);
            /*存在取余运算*/
            this.s = (time % 60);
            this.text = this.m + "分" + this.s + "秒";
            /*传过来的形式参数time不要使用this，而其余在本函数使用的变量则必须使用this*/
            return this.text;
        }

    </script>

    <?
}
?>
</font></label></div></div>
<DIV id="keepalive"></DIV>
<?
$score = $dsql->f('score');
$count = $dsql->f('count');
$scoringmode = $dsql->f('scoringmode');
$timemode = $dsql->f('timemode');

$isshowscore = $dsql->f('isshowscore');

echo $result[0];//直接输出html的内容
if (file_exists($reportfile)) {
    //include_once("$reportfile");
}

echo "</div>";
} else if ($chemin) {

    @mkdir("data");
    @mkdir("$reportpath");
    @mkdir("$reportpath/xml/");
    @mkdir("$reportpath/xmlphp/");


    $cheminfile = basename($chemin, ".xml");
    $id = int($cheminfile);

    //filename="../$reportpath/xml/test1.xml"

    $reportxmlfile = "$reportpath/xml/test$id.xml";
    $reportxmlphpfile = "$reportpath/xmlphp/test$id.php";
    copy($_FILES["contenu"]["tmp_name"], $reportxmlfile);
    $xmlFilepath = "../xml/test1.xml";
    $result = anysxml("$xmlFilepath", "tet.php?testID=" . $id, 0);//生成实验报告表单，提交php为“course_45_report.php”

    //echo '<div style="margin:auto auto;height: 40px;line-height:40px;text-align: center;width:75%;"><div style="width:25%;float:left"><label>邮箱:</label><label>'.$stuEmail.'</label></div><div style="width:25%;float:left"><label>姓名:</label><label>'.$stuName.'</label></div><div style="width:25%;float:left"><label>实验日期:</label><label>'.date("Y年m月d日h:i").'</label></div><div style="width:25%;float:left"><label>成绩：</label><label><font color="red"><b></b></font></label></div></div>';
    //echo $result[0];


    file_put_contents($reportxmlphpfile, $result[0]);

    $SQL = "update  experiments set `reportfile`='$reportxmlphpfile' where id = $id  ";
    //echo $SQL;

    if (!$dsql->query($SQL)) {
        //echo $SQL;
        echo "修改未成功";
        exit;
    } else {
        echo "修改成功";

    }
}


//半角全角转换函数
// 第一个参数：传入要转换的字符串
// 第二个参数：取0，半角转全角；取1，全角到半角
function SBC_DBC($str, $args2)
{
    $DBC = Array(
        '０', '１', '２', '３', '４',
        '５', '６', '７', '８', '９',
        'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ',
        'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
        'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
        'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
        'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ',
        'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ',
        'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
        'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
        'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ',
        'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ',
        'ｙ', 'ｚ', '－', '　', '：',
        '．', '，', '／', '％', '＃',
        '！', '＠', '＆', '（', '）',
        '＜', '＞', '＂', '＇', '？',
        '［', '］', '｛', '｝', '＼',
        '｜', '＋', '＝', '＿', '＾',
        '￥', '￣', '｀'
    );
    $SBC = Array( // 半角
        '0', '1', '2', '3', '4',
        '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i',
        'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x',
        'y', 'z', '-', ' ', ':',
        '.', ',', '/', '%', '#',
        '!', '@', '&', '(', ')',
        '<', '>', '"', '\'', '?',
        '[', ']', '{', '}', '\\',
        '|', '+', '=', '_', '^',
        '$', '~', '`'
    );
    if ($args2 == 0) {
        return str_replace($SBC, $DBC, $str); // 半角到全角
    } else if ($args2 == 1) {
        return str_replace($DBC, $SBC, $str); // 全角到半角
    } else {
        return false;
    }
}

function trimall($str)
{
    $qian = array(" ", "　", "\t", "\n", "\r");
    return str_replace($qian, '', $str);
}

function int($s)
{
    return (int)preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $s);
}

?>
