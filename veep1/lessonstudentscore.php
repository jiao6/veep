<?

error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}

require_once("header.php");
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}
if ($search) {
    $whereinfo = " and ( (e.name  like '%$search%') or (u.truename  like '%$search%') or (u.email  like '%$search%') ) ";
}
$numrows = 6;
/*$SQL = "SELECT count(*) as allcount  FROM  experimentsuser eu, experiments e ,courses c,users u where eu.userid=u.id and e.id=eu.experimentsid and c.id=eu.coursesid  $whereinfo    ";
$SQL = "select count(*) as allcount
    from experimentsuser eu, experiments e , users u
        where eu.userid=u.id and e.id=eu.experimentsid and coursesid=$lessonId $whereinfo ";*/
$SQL = "select u.truename, eu.*, e.name as ename
    FROM experimentsuser eu, experiments e , users u
        where eu.userid=u.id and e.id=eu.experimentsid and coursesid=$lessonId $whereinfo ORDER by id desc ";
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


//查出课堂名称
$sql = "select NAME, ASSIGNER_ID, TEACHER_ID from LESSON where ID = " . $lessonId;
$dsql->query($sql);
$dsql->next_record();
$lessonName = $dsql->f("NAME");
$assignerId = $dsql->f("ASSIGNER_ID");
$teacherId = $dsql->f("TEACHER_ID");
//echo $lessonName . "; assignerId=" . $assignerId . "; teacherId=" . $teacherId  . "; auth_id=" . $auth_id. "<br/>";
if ($auth_id == $assignerId || $auth_id == $teacherId) {
} else {//
	CheckerOfCourse::jumpTo($lessonName . " 不是你的课堂，拒绝访问。", "lessonlist.php");
}

$MAX_EMAIL_LENGTH = 18;
?>
    <div class="contain mc mc1">
        <div class="lt">
            <ul>
                <li class="gn">功能列表</li>
                <?
                include("menu.php");
                ?>
            </ul>
        </div>
        <div class="rt">
            <div class="rhead">
                <div class="rhead1"><? echo $lessonId ?>号课堂【<? echo  MetaDataGenerator::getShortenString($lessonName, $MAX_EMAIL_LENGTH) ?>】的成绩
                	<form id="frm2" action="coursesscoreexp.php?lessonId=<?print($lessonId)?>&search=<?print($search)?>&ac=exp" method="post">
                		<input hidden name="sql" id="sql" value="<? echo $SQL ?>">
                		<input hidden name="lessonName" id="lessonName" value="<? echo $lessonName ?>">
                	</form>
                </div>
                <div class="rhead2"><a href="javascript:frm2.submit()">导出成绩</a></div>
            </div>
            <div class="rfg"></div>
            <form method="post" style="float: right;margin-right: 20px;margin-top: 10px">
                <div>
                    <input type="text" name="search" maxlength="30" style="height: 34px; line-height: 34px; overflow: visible; right: 0; text-indent: 10px; width: 300px;">
                    <input type="submit" id="submit" name="submit" class="submit" value="搜索">
                </div>
            </form>
            <table class="rt_table">
                <tr class="r1">
                    <th>序号</th>
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
                    $coursesid = $dsql->f('coursesid');//课程号码有误
                    $userid = $dsql->f('userid');
                    $created_at = $dsql->f('created_at');
                    $created_at = MetaDataGenerator::getTimeString($dsql->f('created_at'), true, "y年m月d日G时");
                    $useremail = $dsql->f('useremail');
                    $count = $dsql->f('count');
                    $consumingtime = $dsql->f('consumingtime');
                    $score = $dsql->f('score');
                    $cname = $dsql->f('cname');
                    $ename = $dsql->f('ename');
                    $truename = $dsql->f('truename');

                    echo "<tr class=\"r3\">
        <td align='right'>$id</td>
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
                    <td colspan="8">
          <?
            $url = "coursesstudentscore.php";
            $queryString = "?lessonId=$lessonId&search=$search&page=";
            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
            $PARAM_PAGE_NO = "page";//放页号的 url 参数
            $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
         ?>
                    </td>
                </tr>
            </table>
        </div>

    </div>

<?

include("footer.php");

?>