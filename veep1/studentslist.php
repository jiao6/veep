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
$whereinfo = "    ";
if ($search) {
    $whereinfo .= " and ( (C.NAME LIKE '%$search%' ) or (u.truename  like '%$search%') or  (u.email  like '%$search%') ) ";
}
if ($coursesid) {
    $whereinfo .= " and (  C.ID = $coursesid  or C.COURSEID = $coursesid)";
}
if ($auth_pid == 2 || $auth_pid == 4) {

    $whereinfo .= " and ( C.TEACHER_ID='$auth_id'  or C.ASSIGNER_ID = '$auth_id' or C.ID in (select coursesid from coursesuser
 where teacheremail='$auth_email') )";
} else if ($auth_pid != 3) {
    exit;
}
$SQL = "SELECT count(*) as allcount  FROM  coursesuser cu,LESSON C,users u where cu.userid=u.id and C.ID=cu.coursesid  $whereinfo    ";
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
    .studentslist {
        background: #1E8997;
    }
    .studentslist a {
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
   		<div class="admin">
            <ul class="bread">
                    	<li><a href="courseslistfeeteacher.php">用户管理</a></li>
                    	<li>用户修改</li>
            </ul>
            <div class="rfg"> </div>
             <form method="post" style="width:100%;float:left">
                <div class="form-group" style="float:left;margin-left: 500px;">
                    <div class="field">
                        <input type="text" name="search" style="height: 30px; line-height: 30px;text-indent: 10px;width: 300px;border: 1px solid #ccc;">
                        <input type="submit" id="submit" name="submit" class="submit button button-small bg-main" value="搜索">
                    </div>
                </div>
            </form>          
            <table class="rt_table">
                <tr class="r1">
                    <th>课堂名称</th>
                    <th>姓名</th>
                    <th>学校</th>
                    <th>邮箱</th>
                    <th>加入时间</th>
                    <th>是否有效</th>
                    <th>操作</th>
                </tr>
                <?
                if ($offset < 0) $offset = 0;
                $SQL = "SELECT u.university,u.email,u.truename,cu.*,C.NAME AS CNAME,C.ID AS COURSESID FROM  coursesuser cu,LESSON C,users u where cu.userid=u.id and C.ID=cu.coursesid  $whereinfo   limit $offset,$pagesize    ";
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
                    $cname = $dsql->f('CNAME');
                    $truename = $dsql->f('truename');
                    $coursesid = $dsql->f('coursesid');
                    $status = $dsql->f('status');
                    $university = $dsql->f('university');

                    echo "<tr class=\"r3\">
                        <td>$cname</td>
                        <td>$truename</td>
                        <td>$university</td>
                        <td>$email</td>
                        <td>$created_at</td>
                      ";
                    if ($status == 0) {
                        $status = "有效";
                        echo "<td><nobr>$status<td><nobr class='nobr'><a href='studentsedit.php?ac=del&id=$id&status=1&coursesid=$coursesid' onclick=\"return confirm('确定要删除吗')\" style='background:none'><img src='img/delete.png' width='28' height='28' alt='删除' title='删除' /></a></td></tr>";
                    } else if ($status == 1) {
                        $status = "已删除";
                        echo "<td><nobr>$status<td><nobr class='nobr'><a href='studentsedit.php?ac=del&id=$id&status=0&coursesid=$coursesid' style='background:none'><img src='img/activate.png' width='28' height='28' alt='激活' title='激活' /></a></td></tr>";
                    }

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