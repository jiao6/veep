<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("Classes/LessonController.php");

if (!$auth) loginFalse();

$lessonController = new LessonController();
if ($todo == "updateTags") {//修改课堂的标签
	$lessonController->updateTags($lessonId, $tagNames);
	exit;
} else if ($todo == "getSimiliarTags"){
	$lessonController->getSimiliarTags($txt);
	exit;
}

require_once("config/CheckerOfCourse.php");
$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);
$isATeacher = CheckerOfCourse::isATeacher($auth_pid);

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课程图片
$COURSE_IMAGE_DIR = CheckerOfCourse::COURSE_IMAGE_DIR;//图片存放位置

$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
//echo $QUERY_STRING. "<br/>"; #
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
//echo $QUERY_STRING_1. "<br/>"; #
$destUrl = "lessonlist.php?page=" . $page . "#" . $lessonId;


function loginFalse(){
    Header("Location:login.php");
}

require_once("header.php");

$dsql = new DSQL();

if ($ac == "editupdate") {//修改课堂
    $SQL = "select  STARTTIME, ENDTIME  from courses where id in (select COURSE_ID from LESSON where id='$lessonId') ";
        //echo "ac=" . $ac . "; lessonId=". $lessonId  . "; SQL=". $SQL . "<br/>";
    $dsql->query($SQL);
    if (($isFeeTeacher || $isAdmin)&& $dsql->next_record()) {
        $courseStartTime = $dsql->f('STARTTIME');
        $courseEndTime = $dsql->f('ENDTIME');
        //echo "courseStartTime=" . $courseStartTime . "; courseEndTime=". $courseEndTime . "; STUDENT_LIMIT=". $STUDENT_LIMIT . "<br/>";
        if (strtotime($END_TIME) < strtotime($START_TIME)) {
            echo "<br><script>alert('$name 修改失败，关闭时间不能早于课程开始时间:$coursesstarttime');window.location='" . $destUrl . "'</script>\n";
            exit;

        }
        if (strtotime($coursesstarttime) > strtotime($START_TIME)) {
            echo "<br><script>alert('$name 修改失败，开始时间不能早于课程开始时间:$coursesstarttime'.strtotime($START_TIME).'::'.strtotime($coursesstarttime));window.location='" . $destUrl . "'</script>\n";
            exit;
        }
        if (strtotime($courseEndTime) < strtotime($END_TIME)) {
            echo "";
            echo "<br><script>alert(' $name 修改失败，结束时间不能晚于课程结束时间:$courseEndTime');window.location='" . $destUrl . "'</script>\n";
            exit;
        }
    }
    if ($isTeacher) {//老师，只能修改介绍
        $SQL = "update  LESSON set  INTRODUCTION='$INTRODUCTION' where id = '$lessonId' and (TEACHER_ID='$auth_id'   ) ";
    } else if ($isFeeTeacher) {//付费 老师
        if ($STUDENT_LIMIT > 0) {//STUDENT_LIMIT 设定人数大于 0
            /* 现在课堂上限不能修改，所以下面的作废了
            $SQL = "SELECT id,name,MOOC_URL,START_TIME,END_TIME,ASSIGNER_ID,CREATE_TIME,UPDATE_TIME,IMG_URL,INTRODUCTION,TEACHER_ID,CODE,STUDENT_LIMIT,STUDENT_AMOUNT ,MOOC_ID,step,pid   FROM LESSON where id= '$lessonId'";
            $dsql->query($SQL);
            if ($dsql->next_record()) {
                $oldpayquantity = $dsql->f("STUDENT_LIMIT");
                $STUDENT_AMOUNT = $dsql->f("STUDENT_AMOUNT");
                $pid = $dsql->f("pid");
                //STUDENT_LIMIT='$STUDENT_LIMIT', 不修改数量
                $SQL = "update  LESSON set  $preSQL START_TIME='$START_TIME',END_TIME='$END_TIME',TEACHER_ID='$TEACHER_ID',INTRODUCTION='$INTRODUCTION', SORT_ORDER='$SORT_ORDER'    where id = '$lessonId' and (  ASSIGNER_ID = '$auth_id') ";
            }*/
        } else {//不修改课程人数，邮箱不能改，付费教师能修改自己分配的课堂
            $SQL = "update  LESSON set
                START_TIME='$START_TIME', END_TIME='$END_TIME', INTRODUCTION='$INTRODUCTION', SORT_ORDER='$SORT_ORDER'
              where id = '$lessonId' and (ASSIGNER_ID='$auth_id' ) ";
        }

    } else if ($isAdmin) {//管理员。,STUDENT_LIMIT='$STUDENT_LIMIT'。payquantity不修改
        $SQL = "update  LESSON set
         name='$name', MOOC_URL='$MOOC_URL', MOOC_ID='$MOOC_ID',
         START_TIME='$START_TIME',END_TIME='$END_TIME', UPDATE_TIME=now(),
         INTRODUCTION='$INTRODUCTION',
         SORT_ORDER='$SORT_ORDER'
         where ID = '$lessonId' ";//TEACHER_ID='$TEACHER_ID',
    }
    if ($dsql->query($SQL)) {
        //echo $SQL."<br/>";
        echo "<br><script>alert('修改成功');window.location='" . $destUrl . "'</script>\n";
    } else {
        echo "<br>修改失败！<script>window.location='" . $destUrl . "'</script>\n";
        exit;
    }
} else if ($ac == "show") {//上线
    $SQL = "update  LESSON  set SHOWN='1' where id = $lessonId  ";
    $info[0] = "{'result':'FAIL', 'message':'修改失败！'}";//更新则返回 SUCCESS
    if ($dsql->query($SQL)) {
        $info[0] = "{'result':'SUCC', 'message':'修改成功！'}";//更新则返回 SUCCESS
    }
    echo ('{"data":['.implode(",",$info).']}');
    exit;
} else if ($ac == "blank") {//下线
    $SQL = "update  LESSON  set SHOWN='0' where id = $lessonId  ";
    if ($dsql->query($SQL)) {
        echo " 修改成功";
    } else {
        echo " 修改失败！ ";
    }
    exit;
} else if ($ac == "del") {//删除
    $SQL = "SELECT id, name, STUDENT_LIMIT, STUDENT_AMOUNT FROM LESSON where id= '$lessonId'";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        if ($isAdmin) {//管理员
            $SQL = "update  LESSON  set status='$status' where id = $lessonId ";
        } else if ($isFeeTeacher) {
            $SQL = "update  LESSON  set status='$status' where id = $lessonId and ASSIGNER_ID = '$auth_id' ";
        }
        if ($dsql->query($SQL)) {
            echo "<br><script>alert('删除成功');window.location='" . $destUrl . "'</script>\n";
        } else {
            echo "<br><script>alert('删除失败！');window.location.href='" . $destUrl . "'</script>\n";
            exit;
            //$SQL = "update LESSON set STUDENT_AMOUNT=STUDENT_AMOUNT-($STUDENT_LIMIT-$STUDENT_AMOUNT) where id = '$pid'";
            //$dsql->query($SQL);
        }
    }


} else if ($ac == "import") {// hblg_zcy@126.com
    $myemail = explode("\n", $email);
    $SQL = "delete from coursesemail where coursesid = $lessonId ";
    //echo $SQL;
    $dsql->query($SQL);
    for ($i = 0; $i < count($myemail); $i++) {
        $email = trim($myemail[$i]);
        if (strlen($email) > 4) {//邮箱，长度至少 5 位 1@2.3
            $SQL = "insert into coursesemail (coursesid, email)values('$lessonId', '" . $email . "')";
            //echo $SQL;
            $dsql->query($SQL);
        }
    }
    echo "<br><script>alert('成功');window.location='" . $destUrl . "'</script>\n";
} else if (isset($status) && $id > 0) {
        $SQL = "update  LESSON set status=$status where id =$id";
        if (!$dsql->query($SQL)) {
            //echo "un  success:$SQL ";
            echo "<br>修改用户状态失败！\n";
            exit;
        } else {
            echo "修改用户状态成功";
        }
} else if ($ac == "edit") {//展示课堂信息，下一步是修改入库
    $lesson = $lessonController->load($lessonId);
    /**/
    if (empty($lesson)) {
        CheckerOfCourse::jumpTo("$lessonId 号记录不存在！", "lessonlist.php");
    } else {
        //echo $lesson->getName() . " is full" . "<br/>";
    }
    $name = $lesson->getName();
    $IMG_URL = $lesson->getImg();
    $INTRODUCTION = $lesson->getIntroduction();
    $SORT_ORDER = $lesson->getSortOrder();
    $START_TIME = $lesson->getStartTime();
    $END_TIME = $lesson->getEndTime();
            ?>
            <script type="text/javascript" language="javascript" src="js/laydate.js">
            </script>
            <style type="text/css">
                .lessonlist {
                    background: #1E8997;
                }
                .lessonlist a {
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
              <li><a href="lessonlist.php">课堂管理</a></li>
              <li>修改</li>
          </ul>
        <div class="rhead">
            <div class="rhead1 f_center">修改【<? echo $lessonId." 号课堂 ".MetaDataGenerator::getShortenString($name, MetaDataGenerator::STRING_TRUNCATE_LENGTH_OF_INFO_PAGE) ?>】</div>
        </div>
                    <div class="rgwin">
                        <center>
                            <a href='<? print($IMG_URL) ?>' target="_blank"><img height='150'src="<? print($IMG_URL) ?>"/></a> 
                        </center>
                        <form class="rgform form-x" method="post" enctype="multipart/form-data">
                            <?
                                if ($isTeacher) {//教师只能修改图片、介绍
                            ?>
                                <!--div><label for="text1">课堂图片 </label><input type="file" name="IMG_URL"></div-->
                            <? include "page_element/lesson_image.php" ?><? include "page_element/lesson_introduction.php" ?>
                            <?
                                } else if ($isFeeTeacher || $isAdmin) {
                                // 付费教师，修改图片、介绍等
                                    if ($isAdmin) {//管理员能修改名字
                            ?>
                                <div class="form-group">
                                    <div class="label"> 
                                        <label for="name">名称 </label>
                                    </div>
                                    <div class="field">
                                        <input type="text" name="name" placeholder="名称" datatype="*" value="<? print($name) ?>" maxlength="50"><span class='hint'>*</span>
                                    </div>
                                </div>
                                <?
                                    }
                                ?>
                                <? include "page_element/lesson_image.php" ?>
                                <? include "page_element/lesson_introduction.php" ?>
                                <div class="form-group">
                                    <div class="label"> 
                                        <label for="text1">负责人邮箱 </label><span class='hint'>*</span>
                                    </div>
                                    <div class="field">
                                        <input class="input" type="text" name="" placeholder="负责人邮箱" maxlength="50"datatype="e" readOnly disabled value="<? print($lesson->getCourseName()) ?>">
                                    </div>
                                </div>
                                <? include "page_element/lesson_times.php" ?>
                                <? include "page_element/lesson_sortorder.php" ?>
                                <? if ($isAdmin) { ?>

                                <div class="form-group">
                                    <div class="label"> 
                                        <label for="text1">慕课课堂 </label>
                                    </div>
                                        <select name="MOOC_ID" size=1 onchange="show(this.options[this.options.selectedIndex].value)">
                                        <?
                                            $MOOC_ID = $lesson->getMoocId();
                                            $MOOC_URL = $lesson->getMoocUrl();
                                            $SQL = "SELECT  id,  name  FROM mooc  ORDER BY id DESC ";
                                            $dsql->query($SQL);
                                            while ($dsql->next_record()) {
                                                $id = $dsql->f('id');
                                                $name = $dsql->f('name');
                                                if ($id == $MOOC_ID) {
                                                    $select = "selected='selected' ";
                                                }
                                                echo "<option value=\"$id\" $select>$name</option>";
                                            }
                                        ?>
                                        </select>
                                </div>
                                <div id="MOOC_URL" class="form-group">
                                    <div class="label">
                                        <label for="text1">MOOC地址 </label>
                                    </div>
                                    <div class="field">
                                        <input class="input" type="text" name="MOOC_URL"placeholder="MOOC地址" maxlength="50"value="<? print($MOOC_URL) ?>">
                                    </div>
                                </div>
                                <?
                                    }
                                ?>
                                <div id="CODE" class="form-group">
                                    <div class="label">
                                        <label for="text1">选课码 </label>
                                    </div>
                                    <div class="field">
                                        <input class="input" type="text" name="CODE"disabled="disabled"placeholder="选课码" maxlength="50"value="<? print($lesson->getLessonCode()) ?>">
                                    </div>
                                </div>
                                <? $tagList = $lessonController->getRecommendTagList() ?>
                                <? include "page_element/tags_selector.php";
                            }
                            ?>
                            <div class="form-group">
                                <div class="label"> 
                                    <label for="text3"> </label>
                                </div>
                                <div class="field">
                                    <input class="button bg-main" type="submit" name="submit" value="修改">
                                    <input type="hidden" name='lessonId' value="<? print($lessonId) ?>">
                                    <input type="hidden" name='ac' value="editupdate">
                                    <input type="hidden" name='STUDENT_LIMIT' value="-1">
                                </div>
                            </div>
                        </form>
                    </div>

<script type="text/javascript" language="javascript">
    function show(s) {
        //alert(s);
        if (s == 1) {
            $("#STUDENT_LIMIT").show();
            $("#CODE").show();
            $("#MOOC_URL").hide();

        } else {
            $("#MOOC_URL").show();
            $("#STUDENT_LIMIT").hide();
            $("#CODE").hide();
        }
    }
</script>
<script type="text/javascript" src="js/validform.js"></script>
<script type="text/javascript">
    $(function () {
        $(".rgform").Validform({
            tiptype: 3,
            label: ".label",
            showAllError: true

        });
    })
</script>
<script type="text/javascript">
    $('.rt').height(700);
    $('.contain').height(800);
</script>
<?
    }
    include("footer.php");
    include "page_element/tags_selector_bubble.php";
    include("page_element/bubble_alert.php");
?>