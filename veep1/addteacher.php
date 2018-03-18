<?php
session_start();
error_reporting(0);

require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");


$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);


$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
//echo $QUERY_STRING. "<br/>"; #
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
$checkerOfCourse = new CheckerOfCourse();
$QUERY_STRING_1 = $checkerOfCourse->conertChar($QUERY_STRING_1);
//echo "QUERY_STRING_1=" . $QUERY_STRING_1 . "<br/>";


$dsql = new DSQL();
$SQL = "SELECT *  FROM    users  a  where id = '$auth_id' ";
$dsql->query($SQL);
$dsql->next_record();
$university = $dsql->f("university");

$whereinfo = " ";

if ($search) {
    $whereinfo .= " and (    (binary   a.nickname like '%$search%')or(a.mobile like '%$search%')or(binary  a.university like '%$search%')or(binary  a.truename like '%$search%')or( binary a.phonenumber like '%$search%'))  ";
}
if ($isFeeTeacher) {
    $whereinfo = " and (  university = '$university' ) and (usertype = 2  or usertype = 4 )";
}

$SQL = "SELECT count(*) as allcount  FROM  users  a  where ( 1=1)  $whereinfo  ";
$dsql->query($SQL);


$dsql->next_record();
$numrows = $dsql->f("allcount");

require_once("config/Pagination.php");
if (!isset ($pagesize))
    $pagesize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
if ((!isset ($page)) or $page < 1)
    $page = 1;

    $pagination = new Pagination($numrows, $pagesize, $page);
    $pages = $pagination->getPageCount();
    $page  = $pagination->getPageNo();

    $offset = ($page - 1) * $pagesize;

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
            <div class="rhead1">课堂分配</div>
        </div>
        <div class="rfg"></div>
        <table class="rt_table">
            <tr>

                <td>编号</td>
                <td>邮箱</td>
                <!--td>昵称</td-->
                <td>姓名</td>
                <td>课堂数量</td>
                <!--td>使用数量</td>
                <td>是否付费</td>
                <td>付费数量</td>
                <td>已经使用</td-->

                <td>操作</td>
            </tr>
            <?
            if ($offset < 0) $offset = 0;
            //group
            $SQL = "SELECT * from users where (1=1) $whereinfo limit $offset, $pagesize";
            //echo $SQL;
            $dsql->query($SQL);
            
            while ($dsql->next_record()) {
                $id = $dsql->f('id');
                $email = $dsql->f('email');
                $truename = $dsql->f('truename');
                $userimg = $dsql->f('userimg');
                $content = $dsql->f('content');
                $email = $dsql->f('email');

				//$SQL = "select count(id) as cnt from courses where useremail='".$email."' and status=0 and isclass=" . MetaDataGenerator::COURSE_TYPE_KETANG;
				$SQL = "select count(id) as cnt from LESSON where TEACHER_ID=" . $id . " and SHOWN = ". MetaDataGenerator::SHOWN_YES ." and status = " . MetaDataGenerator::STATUS_EFFECTIVE;
				//echo $SQL . "<br/>";
				$dsql2 = new DSQL();
				$dsql2->query($SQL);
				$dsql2->next_record();
				$cnt1 = $dsql2->f("cnt");//在线课堂数量

				$SQL = "select count(id) as cnt from LESSON where TEACHER_ID=" . $id . " and SHOWN = ". MetaDataGenerator::SHOWN_NO ." and status = " . MetaDataGenerator::STATUS_EFFECTIVE;
				$dsql2->query($SQL);
				$dsql2->next_record();
				$cnt0 = $dsql2->f("cnt");//下线课堂数量


                echo "<tr><td align='right'>$id &nbsp;<a name='$id' /></td>
                                            <td>$email </td>
                                            <!--td>$nickname</td-->


                                            <td>$truename </td>
                                            <td align='right'><span title='已发布课堂'>$cnt1</span> &nbsp;<span title='未发布课堂'>($cnt0)</span>&nbsp;&nbsp;</td>


                                             ";


                echo " <td><nobr><a href=teachercourse.php?ac=addteacher&email=$email&status=0&userid=$id&QUERY_STRING_1=$QUERY_STRING_1><img src='img/edit.png' width='30' height='30' alt='课堂分配' title='课堂分配' /></a></td></tr>";

            }
            ?>
            <tr>
                <td colspan="5">
          <?
            $url = "addteacher.php";
            $queryString = "?id=$id&search=$search&page=";
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


