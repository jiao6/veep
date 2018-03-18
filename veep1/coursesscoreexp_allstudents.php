<?
error_reporting(0);
session_start();

require_once("config/config.php");
require_once("config/dsql.php");

if (!$auth) loginFalse();
function loginFalse()
{
    Header("Location:login.php");
}
require_once("config/MetaDataGenerator.php");

header("Content-type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=score_$coursesid.xls");



if (!isset($dsql)) {
    $dsql = new DSQL();
}

$whereinfo = "  ";

if ($search) {
    $whereinfo = " and ( (L.NAME like '%$search%' ) or (e.name  like '%$search%') or (u.truename  like '%$search%') or  (u.email  like '%$search%') ) ";
}
if($auth_pid==2||$auth_pid==4){
    $whereinfo .= " and (L.TEACHER_ID='$auth_id' or L.ASSIGNER_ID='$auth_id' or L.ID in (select coursesid from coursesuser
 where teacheremail='$auth_email'))";
}else if($auth_pid==1){

    $whereinfo = " and ( eu.userid='$auth_id'  )";
}

if ($lessonId) {
    $whereinfo .= " and (  L.ID = '$lessonId' )";
}
if ($coursesid) {//http://vrsygc.chinacloudapp.cn/coursesscoreexp_allstudents.php?coursesid=327
    $whereinfo .= " and (  L.COURSE_ID = '$coursesid' )";
}



$SQL = "SELECT u.truename,eu.*,L.NAME as cname,e.name as ename FROM  experimentsuser eu, experiments e ,LESSON L,users u where eu.userid=u.id and e.id=eu.experimentsid and L.ID=eu.coursesid  $whereinfo   ";
//echo $SQL;
$dsql->query($SQL);

$olddepth =1;
while($dsql->next_record()) {
    $id = $dsql->f('id');
    $experimentsid = $dsql->f('experimentsid');
    $coursesid = $dsql->f('coursesid');
    $userid = $dsql->f('userid');
    $created_at = $dsql->f('created_at');
    $useremail = $dsql->f('useremail');
    $count = $dsql->f('count');
    $consumingtime = $dsql->f('consumingtime');

    $consumingtime = time2second($consumingtime);


    $score = $dsql->f('score');
    $cname = $dsql->f('cname');
    $ename = $dsql->f('ename');
    $truename = $dsql->f('truename');
    $allscors["$experimentsid|$coursesid|$userid"] = "$score<td>$consumingtime<td>$count";
//   / echo " $experimentsid|$coursesid|$userid === $score<td>$consumingtime<td>$count";
}

?>
<html>
<head>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <body>
            <table class="mytable"  border="1">
                <tr class="r1">
                    <th>课堂名称</th>
                    <th>实验名称</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>提交时间</th>
                    <th>分数</th>
                    <th>耗时</th>
                    <th>答题次数</th>

                </tr>
<?
$SQL = "select u.truename as truename ,u.email,L.NAME as cname,e.name as ename , cu.created_at,u.id as userid,L.ID as coursesid,e.experimentsid as experimentsid
	from coursesuser cu ,coursesexperiment e, LESSON L, users u
	where e.COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." and  cu.userid=u.id and L.ID=cu.coursesid and  cu.coursesid =  e.coursesid   $whereinfo
	order by cu.userid";
//echo $SQL;

$dsql->query($SQL);
$nbsp = $pernbsp = "|---------";
$olddepth =1;
while($dsql->next_record()) {

    $experimentsid = $dsql->f('experimentsid');
    $coursesid = $dsql->f('coursesid');
    $userid = $dsql->f('userid');
    $created_at = $dsql->f('created_at');
    $useremail = $dsql->f('email');

    $truename = $dsql->f('truename');
    $cname = $dsql->f('cname');
    $ename = $dsql->f('ename');

    echo "<tr class=\"r3\">
                        <td>$cname</td>
                        <td>$ename</td>
                        <td>$truename</td>
                        <td>$useremail</td>
                        <td>$created_at  </td>
                      ";
    $scoreinfo = $allscors["$experimentsid|$coursesid|$userid"];
    if($scoreinfo!=''){
        echo "<td>$scoreinfo</td></tr>";
    }else{
        echo "<td><td><td></td></tr>";

    }

}


function time2second($remain){
    //计算小时数

    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;


    $time ="$hours 时$mins 分$secs 秒";

    return $time;
}

    ?>
            </table>

