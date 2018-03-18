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
require_once("header.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}
if ($search) {
    $whereinfo = " and ( (e.name  like '%$search%') or (u.truename  like '%$search%') or (u.email  like '%$search%') ) ";
}
//查出课程名字
$sql = "select name, userid, TEACHER_ID from courses where id=" . $courseId;
$dsql->query($sql);
$dsql->next_record();
$courseName = $dsql->f("name");
$assignerId = $dsql->f("userid");
$teacherId = $dsql->f("TEACHER_ID");
//echo $courseName . "assignerId=" . $assignerId . "; $teacherId=" . $teacherId  . "; auth_id=" . $auth_id. "<br/>";
if ($auth_id == $assignerId || $auth_id == $teacherId) {
} else {//
	CheckerOfCourse::jumpTo($courseName . " 不是你的课程，拒绝访问。", "courseslistfeeteacher.php");
}
//拼接出  (-1, 115, 177, 178, 179) 
$sql = "select ID, NAME from LESSON where COURSE_ID=" . $courseId;
$lessonIds = "-1";
$dsql->query($sql);
while ($dsql->next_record()) {//查出课程名称
	$lessonId = $dsql->f("ID");
	$lessonName = $dsql->f("NAME");
	$lessonIds .= ", " . $lessonId;
}
//echo $lessonIds . "<br/>";


$numrows = 6;
$SQL = "select u.truename, eu.*, e.name as ename, l.NAME as lessonName 
    FROM experimentsuser eu, experiments e , users u, LESSON l
        where eu.userid=u.id and e.id=eu.experimentsid and l.ID=eu.coursesid and 
        coursesid in (". $lessonIds. ") $whereinfo ORDER by id desc ";
//echo $SQL. "<br/>";
$sql = MetaDataGenerator::generateCountSql($SQL);
//echo $sql. "<br/>";

$dsql->query($sql);
$dsql->next_record();
$numrows = $dsql->f("allcount");
/**/
require_once("config/Pagination.php");
if (!isset ($pagesize))
    $pagesize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
if ((!isset ($page)) or $page < 1)
    $page = 1;

$pagination = new Pagination($numrows, $pagesize, $page);
$pages = $pagination->getPageCount();
$offset = ($page - 1) * $pagesize;

$MAX_EMAIL_LENGTH = 18;



?>
<style type="text/css">
    .courseslistfeeteacher {
        background: #1E8997;
    }
    .courseslistfeeteacher a {
        color: #fff !important;
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
              <li><a href="courseslistfeeteacher.php">课程管理</a></li>
              <li>查看成绩</li>
          </ul>
            <div class="rhead">
                <div class="rhead1 f_center"><? echo $courseId ?>号课程【<? echo MetaDataGenerator::getShortenString($courseName, $MAX_EMAIL_LENGTH) ?>】的成绩
                	<form id="frm2" action="coursesscoreexp.php?courseId=<?print($courseId)?>&search=<?print($search)?>&ac=exp" method="post">
                		<input hidden name="sql" id="sql" value="<? echo $SQL ?>">
                		<input hidden name="courseName" id="courseName" value="<? echo $courseName ?>">
                	</form>
                </div>
                <a href="javascript:frm2.submit()" class="button bg-sub">导出成绩</a>
                <form method="post" style="float: right;margin-right: 20px;">
                    <div class="form-group">
                        <div class="field">
                            <input type="text" name="search" maxlength="30" style="height: 30px; line-height: 30px;text-indent: 10px;width: 300px;border: 1px solid #ccc;">
                            <input type="submit" id="submit" name="submit" class="submit button button-small bg-main" value="搜索">
                        </div>
                    </div>
                </form>
            </div>
            <table class="rt_table">
                <tr class="r1">
                    <th>序号</th>
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
				$SQL .= "limit $offset, $pagesize ";
                    //echo $SQL. "<br/>";
                    $dsql->query($SQL);
                    $nbsp = $pernbsp = "|---------";
                    while ($dsql->next_record()) {
                        $id = $dsql->f('id');
                        $experimentsid = $dsql->f('experimentsid');
                        $useremail = $dsql->f('useremail');//
                        $userid = $dsql->f('userid');
                        $created_at = $dsql->f('created_at');
                        $created_at = MetaDataGenerator::getTimeString($dsql->f('created_at'), true, "y年m月d日G时");
                        $lessonName = $dsql->f('lessonName');
                        $count = $dsql->f('count');
                        $consumingtime = $dsql->f('consumingtime');
                        $score = $dsql->f('score');
                        $cname = $dsql->f('cname');
                        $ename = $dsql->f('ename');
                        $truename = $dsql->f('truename');

                        echo "<tr class=\"r3\">
                            <td align='right'>$id</td>
                            <td title='". $lessonName ."'>". MetaDataGenerator::getShortenString($lessonName, $MAX_EMAIL_LENGTH). "</td>
                            <td title='". $ename ."'>". MetaDataGenerator::getShortenString($ename, $MAX_EMAIL_LENGTH). "</td>
                            <td title='". $truename ."'>". MetaDataGenerator::getShortenString($truename, 3). "</td>
                            <td title='". $useremail ."'>". MetaDataGenerator::getShortenString($useremail, $MAX_EMAIL_LENGTH). "</td>
                            <td> $created_at</td>
                            <td align='right'> ".MetaDataGenerator::time2second($consumingtime)."</td>
                            <td align='right'>$count</td>
                            <td align='right'>$score</td>
                        </tr>";
                    }
                ?>
                <tr>
                    <td colspan="9">
                        <?
                            $url = "coursesstudentscore.php";
                            $queryString = "?courseId=$courseId&search=$search&page=";
                            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
                            $PARAM_PAGE_NO = "page";//放页号的 url 参数
                            $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                        ?>
                    </td>
                </tr>
            </table>

<?
include("footer.php");
?>