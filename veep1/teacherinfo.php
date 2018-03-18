<?

error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

include_once ("header.php");
require_once ("Classes/TeacherShower.php");
if (!isset($teacherId)) {//缺省使用李凤霞
    $teacherId = 1057;
}
$teacherShower = new TeacherShower($teacherId);
$teacher = $teacherShower->getTeacherInfo();

?>
<style type="text/css">
    .clss{
        width: 950px;
        margin: auto;
    }
    .headerimg{
        width: 220px;
        margin: auto;
        margin-top: 25px;
    }
    .headerimg img{
        border-radius: 5px;
        height: 220px;
        width: 220px;
    }
    .headername{
        text-align: center;
        margin-top: 5px;
    }
    .headername:hover{
        color: #1C7A80;
    }
    .testbtm1{
        position: relative;
        margin-top: 20px;
        width: 950px;
        background: #fff none repeat scroll 0 0;
    border: 1px solid #ccc;
    /*height: 650px;*/
    margin-bottom: 15px;
    }
    .clsrt{
        /*float: left;*/
        width: 880px;
        margin: auto;
        padding: 15px 30px;
        top: 0;
    }
    .clsinfo{
        top: 5px;
        margin-bottom: 45px;
    }
    .hercourse{
        margin-bottom: 10px;
    }
/*  .courselists{
        width: 880px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-top:  1px solid #ccc;
    }
    .courselists div{
        float: left;
    }
    .courselists:hover{
        background: #82B2B5;
    }
    .courselists a:hover{
        color: #fff;
    }
    .listsli div{
        border-bottom: 1px solid #ccc;
    }
    .courselists div:first-child{
        width: 20%;
    }
    .courselists div:last-child{
        width: 78%;
    }*/
    /*20161020*/
.ul_lists li {
    float: left;
    width: 200px;
    background: #fff;
    margin: 5px 10px;
    box-shadow: 0 0 3px #ccc;
}
.ul_lists .md_img {
    width: 200px;
    height: 150px;
}
.ul_lists .md_img img {
    width: 200px;
    height: 150px;
}
.ul_lists li.marleft {
    margin-left: 0;
}
.ul_lists li.marright {
    margin-right: 0;
}
.midinfo {
    padding: 5px 15px;
}
.midinfo a {
    color: #6B6673;
}
.midinfo a:hover {
    color: #39f;
}
</style>
<script>
    // (function (){
    //  var daoh = document.getElementsByTagName("li");
    //  for(var i =0;i < daoh.length;i ++){
    //      daoh[i].removeAttribute("id");
    //  }
    //  daoh[1].setAttribute("id","zhuye");
    // })();
</script>
    <div class="clss">
        <div class="headerimg">
            <a href="<? echo $teacher->getImg() ?>" target="_blank">
                <img src="<? echo $teacher->getImg() ?>" alt="<? echo $teacher->getName() ?>" title="<? echo $teacher->getName() ?>">
            </a>
            <div class="headername"><? echo $teacher->getName() . $teacher->getAcademicTitleName() ?></div>
        </div>
        <div class="testbtm1">
                <div class="" style="padding: 25px 30px;"><!-- clsrt -->
                    <span >详细介绍：</span>
                    <div class="clsinfo">
                        <? echo $teacher->getIntroduction() ?>
                    </div>
                    <span >学生总人次：<? echo $teacher->getStudentAmount() ?></span>
                    <div class="courses">
                        <div class="hercourse">TA的课堂：</div>
                        <ul class="ul_lists" style="width: 890px">
                          <? foreach($teacher->getLessonList() as $lesson) {
                            $lessonName = $lesson->getName();
                            $lessonNameShorten = MetaDataGenerator::getShortenString($lessonName, 12);
                            $href = "lesson.php?lessonId=" . $lesson->getId();
                            $shown = $lesson->getShown();
                            $online = "";
                            if ($shown == 0) {
                                $online="(已下线)";
                                $href = "javascript:none";
                            }
                            ?>
                            <li>
                                <div class="md_img"><a href="<? echo $href ?>"><img src="<? echo $lesson->getImg() ?>" width="200" height="150" alt="<? echo $lessonName ?>" title="<? echo $lessonName ?>"></a></div>
                                <div class="midinfo">
                                    <p class="infoname"><a href="<? echo $href ?>" title="<? echo $lessonName ?>"><? echo $lessonNameShorten ?></a></p>
                                    <p class="infoname">选课人数：<? echo $lesson->getStudentAmount() . $online?>    </p>
                                </div>
                            </li>
                          <? } ?>


                            <!-- <li class="courselists">
                                <a href="">
                                    <div>2</div>
                                    <div>大学计算机（示范课） - 李凤霞 - 2</div>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </div>
        </div>
        <div class="headerimg">
            <div class="headername" ><a href="teacherinfolist.php">返回教师列表</div>
        </div>
    </div>

    <script type="text/javascript">
        $(function(){
            var lists = $('.testbtm1').height();
            console.log(lists);
            var clsrtheg = $('.clsrt').height();
            // $('.testbtm1').height(clsrtheg+30);
            $('.ul_lists').height(Math.ceil($('.ul_lists li').length/4)*223);
            // console.log(Math.ceil($('.ul_lists li').length/4))
        });


    </script>
    <?
include("footer.php");
?>

