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


require_once("header.php");


if (!isset($dsql)) {
    $dsql = new DSQL();
}

$whereinfo = "  ";
if ($search) {
    $whereinfo .= " and ( (l.name like '%$search%' ) or (e.name  like '%$search%') or (u.truename  like '%$search%') or  (u.email  like '%$search%') ) ";
}


if ($coursesid) {
    $whereinfo .= " and (  L.COURSE_ID = $coursesid)";
}
if ($auth_pid == 2 || $auth_pid == 4) {
$whereinfo .= " and (L.TEACHER_ID='$auth_id' or L.ASSIGNER_ID=$auth_id or L.ID in (select coursesid from coursesuser
 where teacheremail='$auth_email'))";

} else if ($auth_pid != 3) {
    exit;
}


$SQL = "SELECT count(*) as allcount  from experimentsuser eu, experiments e ,LESSON L,users u where eu.userid=u.id and e.id=eu.experimentsid and L.ID=eu.coursesid  $whereinfo ";

                //echo $SQL;

                $dsql->query($SQL);


$dsql->next_record();
$numrows = $dsql->f("allcount");

if (!isset ($pagesize))
    $pagesize = 40;
if ((!isset ($page)) or $page < 1)
    $page = 1;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize)
    $pages++;
if ($page > $pages)
    $page = $pages;
$offset = ($page - 1) * $pagesize;


$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;

?>
<style type="text/css">
    .studentscore {
        background: #1E8997;
    }
    .studentscore a {
        color: #fff;
    }
</style>
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
</div>
<div class="admin">
            <div class="bread">
                <li class="rhead1">成绩</li>
                <li class="rhead2"><a href="coursesscoreexp.php?ac=exp&coursesid=<?print($coursesid)?>">导出成绩</a></li>
            </div>
            <div class="rfg"></div>
            <form method="post" style="float: right;margin-right: 20px;margin-top: 10px">
                <div>
                    <input type="text" name="search" style="height: 34px; line-height: 34px; overflow: visible; right: 0; text-indent: 10px; width: 300px;">

                    <input type="submit" id="submit" name="submit" class="submit" value="搜索">
                </div>
            </form>
            <table class="rt_table">
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
                if ($offset < 0) $offset = 0;
                $SQL = "SELECT u.truename,eu.*,L.NAME AS CNAME,e.name as ename FROM  experimentsuser eu, experiments e ,LESSON L,users u where eu.userid=u.id and e.id=eu.experimentsid and L.ID=eu.coursesid  $whereinfo  limit $offset,$pagesize    ";
                //echo $SQL;
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 1;
                while ($dsql->next_record()) {
                    $id = $dsql->f('id');
                    $experimentsid = $dsql->f('experimentsid');
                    $coursesid = $dsql->f('coursesid');
                    $userid = $dsql->f('userid');
                    $created_at = $dsql->f('created_at');
                    $useremail = $dsql->f('useremail');
                    $count = $dsql->f('count');
                    $consumingtime = $dsql->f('consumingtime');
                    $score = $dsql->f('score');
                    $cname = $dsql->f('CNAME');
                    $ename = $dsql->f('ename');
                    $truename = $dsql->f('truename');

                    echo "<tr class=\"r3\">
        <td>$cname</td>
        <td>$ename</td>

        <td>$truename</td>
        <td>$useremail</td>

        <td> $created_at</td>
        <td> ".time2second($consumingtime)."</td>
        <td>$count</td>
        <td>$score</td>
    </tr>";
                }
                ?>
            </table>
            <?
            echo "<br><div id=text14>共 $numrows 条 <a href='?id=$id&search=$search&page=$first'>首页</a><a href='?id=$id&search=$search&page=$prev'>上一页</a><a href='?id=$id&search=$search&&page=$next'>下一页</a><a href='?id=$id&search=$search&page=$last'>尾页</a><span>[$page/$pages]</span></div>";
            ?>
      

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

include("footer.php");

?>
</div>