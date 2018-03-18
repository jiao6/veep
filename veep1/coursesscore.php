<?

session_start();
require_once("config/config.php");
require_once("config/dsql.php");

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

require_once("header.php");
error_reporting(0);


if (!isset($dsql)) {
    $dsql = new DSQL();
}

$whereinfo = " ";

if ($search) {
    $whereinfo .= " and (    (binary   a.nickname like '%$search%')or(a.mobile like '%$search%')or(binary  a.university like '%$search%')or(binary  a.truename like '%$search%')or( binary a.phonenumber like '%$search%'))  ";
}
if($auth_pid==2||$auth_pid==4){
    $whereinfo .= " and ( c.userid='$auth_id'  or c.useremail = '$auth_email' or c.id in (select coursesid from coursesuser
 where teacheremail='$auth_email') )";
}else if($auth_pid==1){

    $whereinfo = " and ( eu.userid='$auth_id'  )";
}
?>
<div id="admin-nav">
    <div>
        <ul class="nav admin-nav" style="height: 0;">
            <li class="active">
                <ul class="nav nav-inline admin-nav">
                    <?
                    include("menu.php");
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="admin">
            <div class="rhead">
                <div class="rhead1">成绩</div>
                <div class="rhead2"><a href="coursesscore.php?ac=exp">导出成绩</a></div>
            </div>
            <div class="rfg"></div>
            <table class="rt_table">
                <tr class="r1">
                    <th>课号</th>
                    <th>实验号</th>
                    <th>编号 </th>
                    <th>邮箱 </th>

                    <th>提交时间 </th>
                    <th>耗时
                    <th>答题次数</th>
                    <th>分数 </th>
                </tr>
                <?
                $SQL = "SELECT eu.*,c.name as cname,e.name as ename FROM  experimentsuser eu, experiments e ,courses c where e.id=eu.experimentsid and c.id=eu.coursesid  $whereinfo";
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
                    echo "<tr class=\"r3\">
        <td>$cname</td>
        <td>$ename</td>
        <td>$id</td>
        <td>$useremail</td>

        <td> $created_at</td>
        <td>$consumingtime</td>
        <td>$count</td>
        <td>$score</td>
    </tr>";
                    ?>


                    <?
                }
                ?>
            </table>

<?
include("footer.php");

?>