<?
require_once("config/config.php");
require_once("config/dsql.php");
error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


header("Content-type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=students_data.xls");


if (!isset($dsql)) {
    $dsql = new DSQL();
}
$whereinfo = " and ( c.isclass=1 ) ";
if ($search) {
    $whereinfo .= " and ( (c.name like '%$search%' ) or (u.truename  like '%$search%') or  (u.email  like '%$search%') ) ";
}
if ($coursesid) {
    $whereinfo .= " and (  c.id = $coursesid)";
}
if ($auth_pid == 2 || $auth_pid == 4) {

    $whereinfo .= " and ( c.userid='$auth_id'  or c.useremail = '$auth_email' or c.id in (select coursesid from coursesuser
 where teacheremail='$auth_email') )";
} else if ($auth_pid != 3) {
    exit;
}

?><html>
<head>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
            <table border="1">
                <tr class="r1">
                    <th>课堂名称</th>
                    <th>姓名</th>
                    <th>邮箱</th>
                    <th>加入时间</th>
                    <th>是否有效</th>

                </tr>
                <?
                if ($offset < 0) $offset = 0;
                $SQL = "SELECT u.email,u.truename,cu.*,c.name as cname,c.id as coursesid FROM  coursesuser cu,courses c,users u where cu.userid=u.id and c.id=cu.coursesid  $whereinfo      ";
                //echo $SQL;
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 1;
                while ($dsql->next_record()) {
                    $id = $dsql->f('id');
                    $coursesid = $dsql->f('coursesid');
                    $userid = $dsql->f('userid');
                    $created_at = $dsql->f('created_at');
                    $email = $dsql->f('email');
                    $cname = $dsql->f('cname');
                    $truename = $dsql->f('truename');
                    $coursesid = $dsql->f('coursesid');
                    $status = $dsql->f('status');

                    echo "<tr class=\"r3\">
                        <td>$cname</td>
                        <td>$truename</td>
                        <td>$email</td>
                        <td>$created_at</td>
                      ";
                    if ($status == 0) {
                        $status = "有效";
                        echo "<td><nobr>$status </tr>";
                    } else if ($status == 1) {
                        $status = "已删除";
                        echo "<td><nobr>$status</td></tr>";
                    }

                }
                ?>
            </table>

<?
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