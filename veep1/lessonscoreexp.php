<?

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
header("Content-type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=chengji_data.xls");
error_reporting(0);

if (!isset($dsql)) {
    $dsql = new DSQL();
}
//echo "sql=" . $sql . "ac=" . $ac ."<br/>";

?>
<html>
<head>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
            <table class="mytable"  border="1">
                <tr class="r1">
                    <th>课堂名称</th>
                    <th>实验名称</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>提交时间</th>
                    <th>耗时</th>
                    <th>答题次数</th>
                    <th>分数</th>
                </tr>
<?
/* 加了搜索条件的sql ，转译之后，'%田%' 变成了 \'\%田\%\'*/
$sql = str_replace("\'", "'", $sql); 
$sql = str_replace("\%", "%", $sql); 
$SQL = $sql;
//echo $SQL;
$dsql->query($SQL);
$nbsp = $pernbsp = "|---------";
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
    $score = $dsql->f('score');
    $cname = $dsql->f('cname');
    $ename = $dsql->f('ename');
    $truename = $dsql->f('truename');

    echo "<tr class=\"r3\">
        <td>$lessonName</td>
        <td>$ename</td>
        <td>$truename</td>
        <td>$useremail</td>

        <td> $created_at</td>
        <td> ".MetaDataGenerator::time2second($consumingtime)."</td>
        <td>$count</td>
        <td>$score</td>
    </tr>";
    ?>


    <?
}

    ?>
            </table>

