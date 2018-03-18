<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");

require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");
$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);

$dsql = new DSQL();
$dsql2 = new DSQL();
?>
<link rel="stylesheet" type="text/css" href="css/courseku.css">
<div class="contain2 clss">
    <div class="testtop">
        <div class="testline"></div>
        <div class="testtxt"><?
            if($courseid>0){
                echo "实验课堂";
            }else{
                echo "实验课程";
            }
            ?></div>
    </div>
    <div class="left">
        <ul>
            <span style="ont-size: 20px; color: rgb(28, 122, 128); padding-left: 10px; padding-right: 98px; border-left: 4px solid rgb(120, 168, 163); display: ">课程分组</span><span
                    class=""></span>
            <?
            if ($coursesgroupid == 0) {

                ?>
                <li style="background:#1C7A80;color:#fff;margin-top: 10px"><span ><a href="courses.php" style="color:#fff">全部课程</a></span></span><span
                        class=""></span></li>
                <?
            }else{
                ?>
                <li style="margin-top: 10px"><span><a href="courses.php">全部课程</a></span><span
                        class=""></span></li>
                <?
            }

            $SQL = "SELECT id, name ,uid, pid ,path ,status FROM coursesgroup   where status=". MetaDataGenerator::STATUS_EFFECTIVE ."   and pid=1 order by id asc     ";
            if (!isset($dsql)) {//显示课程分组
                $dsql = new DSQL();
            }
            $dsql->query($SQL);
            $info = "";
            $phonelist = "";
            $type = 1;
            $olddepth = 0;
            $i = 0;
            while ($dsql->next_record()) {
                $i++;
                $id = $dsql->f('id');
                $group_name = $dsql->f('name');
                $insertdate = $dsql->f('insertdate');
                $userid = $dsql->f('userid');
                $group_pid = $dsql->f('pid');
                $path = $dsql->f('path');
                $newdepth = substr_count($path, ',');
                //echo "";
                if ($coursesgroupid == $id) {

                    ?>
                        <li style="background:#1C7A80;color:#fff">
                            <span>
                                <a href="courses.php?coursesgroupid=<? print($id) ?>" style="color:#fff">
                                    <? print($group_name) ?>
                                </a>
                            </span>
                            <span></span>
                        </li>
                        <?
                } else {

                ?>

                <li>
                    <span>
                        <a href="courses.php?coursesgroupid=<? print($id) ?>">
                            <? print($group_name) ?>
                        </a>
                    </span>
                    <span></span>
                </li>
                <?
                }
            }
            ?>
        </ul>
    </div>
    <div class="right">
        <ul class="testbtm ">
            <?
                $whereinfo = " and c.status=". MetaDataGenerator::STATUS_EFFECTIVE ." ";
                if($university){
                }else{
                    $whereinfo .= " and c.isclass=0 ";
                }
                if ($search) {
                    $whereinfo .= " and  (   c.name like '%$search%' or   c.content  like '%$search%'  or c.id in (select COURSE_ID FROM LESSON WHERE ASSIGNER_NAME ='$search'  OR TEACHER_NAME = '$search') or  c.useremail in (select email from users a  where     ( (  a.truename like '%$search%')or(a.nickname like '%$search%')or(   a.university like '%$search%') or(   a.phonenumber like '%$search%')))  )";
                }
                if ($isTeacher || $isFeeTeacher) {
                     //$whereinfo .= " and useremail='$auth_email'  ";
                }
                if($coursesgroupid){
                    $whereinfo .= " and c.coursesgroupid=$coursesgroupid  ";
                }
                if($courseid>0){
                    $whereinfo .= " and c.pid=$courseid  ";
                }


                $SQL = "SELECT count(*) as allcount   FROM courses c where c.status=". MetaDataGenerator::STATUS_EFFECTIVE ." and c.isshow=1 $whereinfo      ";
                $dsql->query($SQL);

           // echo $SQL;
                $dsql->next_record();
                $numrows = $dsql->f("allcount");
//echo "记录数：" . $numrows;

              require_once("config/Pagination.php");
              $DEFAULT_PAGE_SIZE_DEFAULT = 10;
              if (!isset ($pagesize))
                    $pagesize = $DEFAULT_PAGE_SIZE_DEFAULT;
                if ((!isset ($page)) or $page < 1)
                    $page = 1;

                $pagination = new Pagination($numrows, $pagesize, $page);
                $pages = $pagination->getPageCount();
                $page  = $pagination->getPageNo();

                $offset = ($page - 1) * $pagesize;

                $first = 1;
                $prev = $pagination->getPrev();
                $next = $pagination->getNext();
                $last = $pages;

                if ($offset < 0) $offset = 0;
                //$SQL = " SELECT c.*,u.university,u.truename FROM courses c left join  users u   on  c.useremail=u.email where   c.status=". MetaDataGenerator::STATUS_EFFECTIVE ." and  c.isshow=1 $whereinfo  order by c.sort desc     limit $offset,$pagesize    ";
                $SQL = " SELECT c.*,u.university,u.truename, u.university_id  
                	FROM courses c left join users u on c.useremail=u.email 
                	where c.status=". MetaDataGenerator::STATUS_EFFECTIVE ." and c.isshow=". MetaDataGenerator::SHOWN_YES ." $whereinfo 
                	order by c.sort desc limit $offset, $pagesize";
                //查找课程
                //echo $SQL . "<br/>";
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 1;
                $i = 0;
                while ($dsql->next_record()) {
                    $i++;
                    $id = $dsql->f('id');
                    $name = $dsql->f('name');
                    $moocurl = $dsql->f('moocurl');
                    $starttime = substr($dsql->f('starttime'),0,10);
                    $endtime = substr($dsql->f('endtime'),0,10);
                    $userid = $dsql->f('userid');
                    $created_at = $dsql->f('created_at');
                    $updated_at = $dsql->f('updated_at');
                    $coursesimg = $dsql->f('coursesimg');
                    $content = $dsql->f('content');
                    $useremail = $dsql->f('useremail');
                    $code = $dsql->f('code');
                    $payquantity = $dsql->f('payquantity');
                    $payquantityusage = $dsql->f('payquantityusage');
                    $trueusage = $dsql->f('trueusage');
                    $isclass = $dsql->f('isclass');//肯定是 0，查询条件包括 c.isclass=0

                    //$teachercontent = $dsql->f('teachercontent');
                    $university = $dsql->f('university');
                    $universityId = $dsql->f('university_id');
                    $moocid = $dsql->f('moocid');
                    //$teacherId = $dsql->f('TEACHER_ID');
                    if (!file_exists($coursesimg)) {
                        $coursesimg = CheckerOfCourse::DEFAULT_COURSE_IMAGE; //"images/course.jpg";
                    }
                    if(strlen($content)>50){
                        $content =mb_substr($content, 0,50, 'utf-8')."..";
                    }

                    if($trueusage==''){
                        $trueusage = 0;
                    }
                    /*出现教师列表，前7个*/
                    $SQL = "SELECT distinct u.truename, u.id, u.content 
                    	FROM LESSON c, users u 
                    	where c.TEACHER_ID=u.id and (c.COURSE_ID=$id) 
                    		and c.STATUS=". MetaDataGenerator::STATUS_EFFECTIVE ." and c.SHOWN=". MetaDataGenerator::SHOWN_YES ." limit 7";
                    //echo $SQL . "<br/>";
                    $dsql2->query($SQL);

					$url = "course.php?courseid=$id&isclass=0";
                    if($isclass==1){
                        $url = "lesson.php?lessonId=$id";
                    }else{
                        $url = "lessons.php?courseid=$id";
                    }
                    $teachercontent = "";
                    while ($dsql2->next_record()) {
                    	$teacherId = $dsql2->f('id');
                    	$teachername = $dsql2->f('truename');
                    	$teacherIntroduction = $dsql2->f('content');
                        $teachercontent .=  "<a title='".$teacherIntroduction."' href='teacherinfo.php?teacherId=". $teacherId ."' style='font-size:16px; '>" . $teachername."</a> ";
                    }
                    echo " <li>
                        <div class=\"clsimg\"></div>
                        <a href=\"$url\"><img src=\"$coursesimg\" height=\"180px\"></a>
                        <div class=\"clsrt\">
                            <p class=\"clsname\">
                                <a href=\"teacherinfolist.php?universityId=". $universityId ."\">$university</a>-<a href=\"$url\">$name</a>
                            </p>
                            <p class=\"clsinfo\">
	                           $teachercontent<br/>
	                           <a href=\"$url\">$content</a><br/>
	                            开课：$starttime  结束：$endtime <font align='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;选课人数：$trueusage</font>
                            </p>
                        </div>
                    </li>";
                    ?>

                    <?
                }
            if($i==0){
                if($courseid > 0){
                    echo " <li>
                        <div class=\"clsimg\"></div>
                        <div class=\"clsrt\">
                           没有课堂
                        </div>
                    </li>";
                }else{
                    echo " <li>
                        <div class=\"clsimg\"></div>
                        <div class=\"clsrt\">
                           没有课程
                        </div>
                    </li>";
                }

            }

?>
        </ul>
          <?
        //echo "<br><div id=text14>共 $numrows 条 <a href='?id=$id&search=$search&page=$first'>首页</a><a href='?id=$id&search=$search&page=$prev'>上一页</a><a href='?id=$id&search=$search&&page=$next'>下一页</a><a href='?id=$id&search=$search&page=$last'>尾页</a><span>[$page/$pages]</span></div>";
            $url = "courses.php";
            $queryString = "?courseid=$courseid&search=$search&page=";
            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
            $PARAM_PAGE_NO = "page";//放页号的 url 参数
            //$pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
        ?>
      <?
                if($numrows>$pagesize){
                    echo "
                        <div class=\"\">
                            <br><div id=text14>共 $numrows 条 <a href='?courseid=$courseid&search=$search&page=$first'>首页</a><a href='?courseid=$courseid&search=$search&page=$prev'>上一页</a><a href='?courseid=$courseid&search=$search&&page=$next'>下一页</a><a href='?courseid=$courseid&search=$search&page=$last'>尾页</a><span>[$page/$pages]</span>
                        </div>
                    ";
                }
            ?>

    </div>

</div>
<script type="text/javascript">
    $(function(){
        $('.tm-ul li').removeClass('zhuye').eq(1).addClass('zhuye');
        var lis = $('.left ul li');
        var first = $('.left ul li:first-child');
        for (var i = 1; i < lis.length; i++) {
            $(lis[i]).click(function () {
                window.location = $(this).find('a').attr('href');
                $(this).find('a').css('color','#fff');
                $(this).siblings().find('a').css('color','#000');
                $(this).css({'background': '#1C7A80'}).siblings().css({
                    'background': '#FAFAFA'
                });
            });
        }
    });
</script>

<?
include("footer.php");
?>