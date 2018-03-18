<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");
require_once("header.php");

$STATUS_EFFECTIVE = MetaDataGenerator::STATUS_EFFECTIVE;
$SHOWN_YES = MetaDataGenerator::SHOWN_YES;
?><?
/* 首先根据 courseid 查找 课程信息 */
$SQL = "SELECT c.* FROM courses c 
	where  c.id = '$courseid' 
	and c.status=". $STATUS_EFFECTIVE ." and c.isshow=". $SHOWN_YES ." ";
//echo $SQL . "<br/>";
$dsql = new DSQL();
$dsql->query($SQL);

$dsql->next_record();
$id = $dsql->f('id');//就是courseid
$name = $dsql->f('name');
$teacherId = $dsql->f('TEACHER_ID');
$useremail = $dsql->f('useremail');

$moocurl = $dsql->f('moocurl');
$starttime = $dsql->f('starttime');
$endtime = $dsql->f('endtime');
$userid = $dsql->f('userid');
$created_at = $dsql->f('created_at');
$updated_at = $dsql->f('updated_at');
$coursesimg = $dsql->f('coursesimg');
$content = $dsql->f('content');
$code = $dsql->f('code');
$payquantity = $dsql->f('payquantity');
$moocid = $dsql->f('moocid');
$pid = $dsql->f('pid');
$content = $dsql->f('content');
$isclass = $dsql->f('isclass');

if (!file_exists($coursesimg)) {
    $coursesimg = CheckerOfCourse::DEFAULT_COURSE_IMAGE; //"images/course.jpg";
}

if ($isclass == MetaDataGenerator::COURSE_TYPE_KETANG) {//课堂 isclass=1；课程 0
    $SQL = "SELECT c.* FROM courses c where c.id = '$pid' and c.status=0 and c.isshow=1   ";
    //echo $SQL . "<br/>";
    $dsql->query($SQL);
    if (!$dsql->next_record()) {
    }
}
if ($isclass == MetaDataGenerator::COURSE_TYPE_KECHENG) {//以课程的方式进入
	//查找此人所属大学
    $SQL = "SELECT university FROM users where email = '$useremail' limit 1";
    //echo $SQL . "<br/>";
    $dsql->query($SQL);

    $dsql->next_record();
    $university = $dsql->f('university');
    $whereinfo = " and c.isclass=1 and userid in (select id from users where university = '$university')";
    require_once("courses.php");
    exit;
}else{

}
if($isclass == MetaDataGenerator::COURSE_TYPE_KETANG){
    $SQL = "SELECT count(*) as c FROM coursesuser where coursesid='$courseid'";
	//echo $SQL . "<br/>";
    $dsql->query($SQL);
    $dsql->next_record();
    $allcount = $dsql->f("c");
    $dsql->query("update courses set trueusage='$allcount',payquantityusage=$allcount where  id='$courseid'");

    $SQL = "SELECT  ifnull(sum(trueusage),0) as trueusage FROM courses where  pid  ='$pid' ";
//echo $SQL . "<br/>";
    $dsql->query($SQL);
    $dsql->next_record();
    $trueusage = $dsql->f("trueusage");


    $dsql->query("update courses set trueusage='$trueusage' where   id  ='$pid'");
}

if ($auth_pid) {

}else{
    require_once("login.php");
   exit;
}



?>
    <div class="contain test">
        <div class="ts_top">
            <img src="<? print($coursesimg) ?>" style="width: 280px;height: 180px">

            <div class="tst_info">
                <p class="tstname"><? print($name) ?></p>

                <p class="tstinfo"><? print($content) ?></p>

                <p>
                    <br/><br/>
                    <?
                    $SQL = "SELECT * FROM coursesemail where coursesid='$courseid' and email = '$auth_email'";
                    $dsql->query($SQL);//批量导入的用户
                    if ($dsql->next_record()) {
                        $iscoursesemail = 1;
                    }
                    $SQL = "SELECT * FROM coursesuser where coursesid='$courseid' and userid = '$auth_id' and status=" . $STATUS_EFFECTIVE;
                    $dsql->query($SQL);
                    if ($dsql->next_record()) {//选课码进入的用户
                        $isbook = $dsql->f('isbook');
                        if ($isbook == 1 && !$auth_pid > 1) {//书本的用户 需要输入 老师 邮箱
                            echo "<form method=\"post\">老师邮箱<input type=\"txt\" name=\"teacheremail\"><input type=\"hidden\" name=\"ac\" value=\" ddteacher\"><input type=\"submit\" id=\"submit\" name=\"submit\" value=\"绑定老师\"></form>";
                        }
                    }else if ($moocid > 1 || $iscoursesemail == 1) {//moocid  email 本人 有自动加入
                        $SQL = "SELECT * FROM coursesuser where coursesid='$courseid'  and  userid = '$auth_id' ";
                        // //echo $SQL . "<br/>";
                        $dsql->query($SQL);
                        if ($dsql->next_record($SQL)) {
                            echo "  <script >alert('加入成功')</script>\n";
                            $dsql->query("update coursesuser  set status=0   where coursesid='$courseid'  and  userid = '$auth_id'");
                        } else {
                            $SQL = "SELECT count(*) as c FROM coursesuser where coursesid='$courseid'  ";

                            //echo $SQL . "<br/>";
                            $dsql->query($SQL);
                            $dsql->next_record();
                            $allcount = $dsql->f("c");
                            if ($payquantity > $allcount) {
                                $SQL = "SELECT  ifnull(sum(trueusage),0) as trueusage FROM courses where  pid  ='$pid'";
                                //echo $SQL . "<br/>";
                                $dsql->query($SQL);
                                $dsql->next_record();
                                $trueusage = $dsql->f("trueusage");

                                $SQL = "SELECT payquantity  FROM courses where  id  ='$pid'";
                                $dsql->query($SQL);
                                $dsql->next_record();
                                $pidpayquantity = $dsql->f("payquantity");
                                if ($pidpayquantity > $trueusage) {
                                    $SQL = "insert into coursesuser (coursesid,userid,created_at,status) values('$courseid','$auth_id',now(),0)";
                                    // //echo $SQL . "<br/>";
                                    if (!$dsql->query($SQL)) {
                                        echo "  <script >alert('加入未成功');</script>\n";
                                    } else {


                                        if($userid == $auth_id || $useremail == $auth_email ){

                                        }else{
                                            echo "  <script >alert('加入成功');</script>\n";
                                        }

                                    }
                                } else {
                                    echo "  <script >alert('加入人次超限')</script>\n";
                                }
                            } else {
                                echo "  <script >alert('加入人次超限')</script>\n";
                            }
                        }
                        if ($auth_pid == 1) {

                        }
                    } else {


                    ?>

                <form method="post" style="float: right; position: absolute; top: 0;right: 5px;font-size: 15px">
                    选课码：<input type="txt" name="usercode" style="" class="input_border"><input type="hidden" name="ac"
                                                                                               value="add"><input
                        type="submit" id="submit" name="submit" value="加入我的课程" class="btn_blue"></form>
                <?

                }
                ?>
                </p>
                <style type="text/css">
                    .join {
                        width: 120px;
                        height: 40px;
                        text-align: center;
                        line-height: 40px;
                        background: #1C7A80;
                        float: right;
                        margin: 50px 120px 0 0;
                        border-radius: 6px;
                    }

                    .join a {
                        color: #fff;
                        text-decoration: none;
                    }
                </style>
            </div>
        </div>
        <?
        $iscourseuser = 0;
        if ($ac == "addbbs"){

        $SQL = "SELECT id FROM coursesuser  where status=0 and  coursesid = '$courseid' and userid='$auth_id' ";
        //echo $SQL . "<br/>";
        $dsql->query($SQL);

        if (!$dsql->next_record()){

        ?><!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
        </head>
        <body>
        <script>alert('请加入课程再讨论');
            window.location = "course.php?courseid=<?print($courseid)?>"</script>
        <?
        exit;

        }

        $SQL = "select id from coursesbbs where coursesid= '$courseid' and userid='$auth_id' and content='$bbscontent'";
        $dsql->query($SQL);
        if (!$dsql->next_record()) {
            $SQL = "insert into coursesbbs( id,coursesid,userid,content,createtime)values(0,'$courseid','$auth_id','$bbscontent',now())";
            $dsql->query($SQL);
        } else {

        }

        } else
            if ($ac == "addteacher") {
                $SQL = "SELECT * FROM coursesuser where coursesid='$courseid'  and  userid = '$auth_id'  and isbook=1";
                //echo $SQL . "<br/>";
                $dsql->query($SQL);
                if (!$dsql->next_record()) {
                    $SQL = " update  coursesuser  set teacheremail = '$teacheremail'  where coursesid='$courseid'  and  userid = '$auth_id' and isbook=1";
                    $dsql->query($SQL);
                    if (!$dsql->query($SQL)) {
                        echo "  <script >alert('绑定老师未成功')</script>\n";
                    } else {
                        echo "  <script >alert('绑定老师成功')</script>\n";
                    }
                }
            } else if ($ac == "add") {
                if ($code != $usercode) {
                    $SQL = "SELECT * FROM coursescode where coursesid='$courseid'  and  code = '$code'  and endtime >now() and status=0";
                    $dsql->query($SQL);
                    if ($dsql->next_record()) {
                        $day = $dsql->f("day");
                        if (!intval($day * 1) > 0) $day = 0;
                        $SQL = "update coursescode  set status=1 where coursesid='$courseid'  and  code = '$code'  and endtime >now()";
                        $dsql->query($SQL);
                        $SQL = "SELECT * FROM coursesuser where coursesid='$courseid'  and  userid = '$auth_id' ";
                        //echo $SQL . "<br/>";
                        $dsql->query($SQL);
                        if (!$dsql->next_record()) {
                            //endtime DATE_ADD('1999-01-01', INTERVAL 1 DAY);
                            $SQL = "insert into coursesuser (coursesid,userid,created_at,status,endtime) values('$courseid','$auth_id',now(),0,DATE_ADD(now(), INTERVAL $day DAY);)";
                            if (!$dsql->query($SQL)) {
                                echo "  <script >alert('加入课程未成功')</script>\n";
                            } else {
                                echo "  <script >alert('加入课程成功')</script>\n";
                                $iscourseuser = 1;

                            }
                        } else {

                        }
                    } else {
                        echo "  <script >alert('加入课程未成功,选课码不对')</script>\n";
                    }
                } else {
                    //加入 课程
                    //第一步 判断 课程 总人次

                    $SQL = "SELECT * FROM coursesuser where coursesid='$courseid'  and  userid = '$auth_id'";
                    //echo $SQL . "<br/>";
                    $dsql->query($SQL);
                    if (!$dsql->next_record()) {
                        $SQL = "SELECT count(*) as c FROM coursesuser where coursesid='$courseid'  ";
                        //echo $SQL . "<br/>";
                        $dsql->query($SQL);
                        $dsql->next_record();
                        $allcount = $dsql->f("c");

                        if ($payquantity > $allcount) {
                            $SQL = "SELECT  ifnull(sum(trueusage),0) as trueusage FROM courses where  pid  ='$pid'";
                            //echo $SQL . "<br/>";
                            $dsql->query($SQL);
                            $dsql->next_record();
                            $trueusage = $dsql->f("trueusage");

                            $SQL = "SELECT payquantity  FROM courses where  id  ='$pid'";
                            $dsql->query($SQL);
                            $dsql->next_record();
                            $pidpayquantity = $dsql->f("payquantity");
                            if ($pidpayquantity > $trueusage) {

                                $SQL = "insert into coursesuser (coursesid,userid,created_at,status) values('$courseid','$auth_id',now(),0)";
                                // //echo $SQL . "<br/>";

                                if (!$dsql->query($SQL)) {

                                    echo "  <script >alert('加入课堂未成功');window.location='course.php?courseid=$courseid'</script>\n";

                                } else {

                                    echo "  <script >alert('加入课堂成功');window.location='course.php?courseid=$courseid'</script>\n";


                                }
                            }


                        } else {

                            echo "  <script >alert('加入课程人次超限')</script>\n";
                        }

                    } else {
                        $status = $dsql->f("status");
                        if ($status != 0) {
                            $dsql->query("update  coursesuser set status=0 where coursesid='$courseid'  and  userid = '$auth_id'");
                        }
                        $iscourseuser = 1;
                        echo "  <script >alert('加入课程成功')</script>\n";
                    }
                }
                /*
                $SQL = "SELECT count(*) as c FROM coursesuser where coursesid='$courseid'";
                //echo $SQL . "<br/>";
                $dsql->query($SQL);
                $dsql->next_record();
                $allcount = $dsql->f("c");
                $dsql->query("update courses set trueusage='$allcount',payquantityusage=$allcount where  id='$courseid'");

                $SQL = "SELECT  ifnull(sum(trueusage),0) as trueusage FROM courses where  pid  ='$pid' ";
                //echo $SQL . "<br/>";
                $dsql->query($SQL);
                $dsql->next_record();
                $trueusage = $dsql->f("trueusage");


                $dsql->query("update courses set trueusage='$trueusage' where   id  ='$pid'");
                */
            }


        $SQL = "SELECT * FROM coursesuser where coursesid='$courseid'  and  userid = '$auth_id' and status=0";
        //echo $SQL . "<br/>";
        $dsql->query($SQL);
        if ($dsql->next_record()) {
            $iscourseuser = 1;
        }
        $SQL = "SELECT e.* ,ce.starttime as cestarttime,ce.endtime as ceendtime from experiments e ,coursesexperiment ce  where e.id=ce.experimentsid and ce.coursesid='$courseid'  order by ce.sort asc";
        $dsql->query($SQL);
        $i = $offset;
        $i = 0;
        while ($dsql->next_record()) {
            $i++;

            $starttime = $dsql->f('cestarttime');
            $endtime = $dsql->f('ceendtime');
            $id = $dsql->f('id');
            $name = $dsql->f('name');
            $content = $dsql->f('content');
            $softfile = $dsql->f('softfile');
            $reportfile = $dsql->f('reportfile');
            $status = $dsql->f('status');
            $groupid = $dsql->f('groupid');
            $img = $dsql->f('img');
            $type = $dsql->f('type');
            $difficulty = $dsql->f('difficulty');

            if ($iscourseuser == 1) {
                if (is_file("$reportpath/xml/test$id.xml")) {
                    $report .= "<li>
    								<div><h4><a href=\"tet.php?id=$id&courseid=$courseid\" TARGET='_blank' >实验报告$i <span>$name</span></a></h4></div>
    							</li>";
                }

                $chapter .= "	<li>
    								<div class=\"circle\">$i</div>
    								<div class=\"chapter\">
    								<h4>实验$i $name </h4>
    									<p>开始:$starttime 结束:$endtime </p>
    									<a class=\"btn btn-primary start_e\"  TARGET='_blank' href=\"experimentplay.php?id=$id&name=$name&courseid=$courseid\">开始实验</a>
    								</div>
    							</li>";
            } else {
                if (is_file("$reportpath/xml/test$id.xml")) {
                    $report .= "<li>
    								<div><h4><a href=\"tet.php?id=$id&courseid=$courseid\"  >实验报告$i <span>$name</span></a></h4></div>
    							</li>";
                }
                $chapter .= "	<li>
    								<div class=\"circle\">$i</div>
    								<div class=\"chapter\">
    								<h4>实验$i $name </h4>
    									<p>开始:$starttime 结束:$endtime </p>
    									<a class=\"btn btn-primary start_e\"    href=\"experimentplay.php?id=$id&name=$name&courseid=$courseid\">开始实验</a>
    								</div>
    							</li>";
            }


        }
        ?>
        <div class="ts_btm">
            <div class="tsb_lt">
                <ul class="tab">
                    <li style="margin-left: 0" value="3">虚拟实验</li>
                    <li value="2">实验报告</li>
                    <li value="1">实验讨论</li>
                </ul>
                <ul class="tab_tar">
                    <li class="tabr3">
                        <form method="post" class="postform">
                            <textarea class="ex_text" name=bbscontent placeholder="说说你的想法吧..." datatype="*"></textarea>
                            <a href="#"></a>
                            <input type="hidden" name='courseid' value="<? print($courseid) ?>">
                            <input type="hidden" name='ac' value="addbbs">
                            <input type="submit" id="submit" name="submit" value="评论" class="submit">
                        </form>
                        <div class="ext_tit">
                            <div>评论列表</div>
                        </div>
                        <ul class="ext_con">
                            <div></div>
                            <?
                            $SQL = "SELECT c.id,c.content,c.createtime ,u.userimg,u.truename from  coursesbbs c ,users u  where c.coursesid='$courseid'  and u.id=c.userid  order by c.id desc";
                            $dsql->query($SQL);
                            $i = $offset;
                            $i = 0;
                            while ($dsql->next_record()) {
                                $i++;
                                $id = $dsql->f('id');
                                //$name = $dsql->f('name');
                                $content = $dsql->f('content');
                                $createtime = $dsql->f('createtime');
                                $userimg = $dsql->f('userimg');
                                $truename = $dsql->f('truename');
                                $createtime = $dsql->f('createtime');
                                if (!file_exists($userimg)) {
                                    $userimg = "img/teacher.png";
                                }

                                echo "<li>
                                <div class=\"ext_uimg\"><img src=\"$userimg\">

                                    <div class=\"uname\">$truename</div>
                                </div>
                                <img src=\"img/4.png\" class=\"trian\">

                                <div class=\"ext_text\">$content</div>
                                <div class=\"ext_info\">
                                    <div>
                                        <span class=\"ext_fo\"><span>$i</span>楼</span>
                                        <span class=\"ext_time\">$createtime</span>

                                        <a class=\"ext_re\" onclick='$(\".ex_text\").val(\"回复 $truename:\" );'>回复</a>

                                    </div>
                                </div>
                                <img class=\"trian2\" src=\"img/3.png\">

                            </li>";
                            }
                            ?>

                        </ul>
                    </li>

                    <li class="tabr2">
                        <ul>
                            <? print($report) ?>
                        </ul>
                    </li>
                    <li class="tabr1">
                        <ul>

                            <?
                            print($chapter);
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <?
            $SQL = "SELECT * FROM users where email = '$useremail'     ";
            //echo $SQL . "<br/>";
            $dsql->query($SQL);

            $dsql->next_record();
            $truename = $dsql->f('truename');
            $userimg = $dsql->f('userimg');
            $content = $dsql->f('content');
            if (!file_exists($userimg)) {
                $userimg = "img/teacher.png";
            }

            ?>
            <div class="tsb_rt">
                <div class="tsbtit">授课老师<a name="teacher" /></div>
                <img src="<? print($userimg) ?>" width="84px">

                <div class="tsbname"><a href=""><? print($truename) ?></a></div>
                <div class="tsbinfo"> <? print($content) ?></div>
            </div>
        </div>
    </div>

<? //include("footer.php");
?>
    <style type="text/css">
        .about, .info {
            position: absolute;
        }

        .info {
            right: 0;
        }
    </style>
    <script>
        $(function () {
            function tabr3() {
                $(".tab_tar>li").eq(0).show().siblings().hide();
                $(".tab li").eq(2).click();
            }


            var heig2 = $(".tsb_rt").height();
            var reg = /\s+/;
            var tab = $(".tab li");
            var li = $(".tab_tar>li");
            chooseHeig();
            userre();
            Num();
            togg();
            fooN();
            colorToggle($(tab), {"background": "#FAFAFA", "color": "#1C7A80"}, {
                "background": "#1C7A80",
                "color": "#FAFAFA"
            })

            $(".tabr1").show().siblings().hide();
            $(tab).click(function () {
                var val = $(this).attr("value");
                $(li[val - 1]).show().siblings().hide();
                chooseHeig($(li[val - 1]).height());
            });
            // $(tab).eq(2).click();


            $(".ext_div").click(function () {
                var texts = $(".ex_text").val();
                var time = getDay();
                t = texts.replace(reg, "");
                if (t == "") {
                    alert("请输入字符！");
                } else {
                    var html = "<li><div class='ext_uimg'><img src='img/btm1.jpg'><div class='uname'>xxxx</div></div><img src='img/4.png' class='trian'><div class='ext_text'>" + texts + "</div>" + "<div class='ext_info'><div><span class='ext_fo'><span></span>楼</span> <span class='ext_time'>" + time + "</span> <a class='ext_re'>回复<span></span></a></div></div>" + "<img class='trian2' src='img/3.png'><div class='ext_app'><ul><div class='app_re'></div></ul></div>";
                    $(".ext_con").prepend(html);
                    $(".ex_text").val("");
                    togg();
                    fooN();
                }
                Num();
                chooseHeig();
            });

            function colorToggle(obj, a, b) {
                var fir = $(obj).first();
                $(fir).css(a).siblings().css(b).hover(function () {
                    $(this).css(a);
                }, function () {
                    $(this).css(b);
                });
                for (var i = 0; i < obj.length; i++) {
                    $(obj[i]).click(function () {
                        $(this).css(a).siblings().css(b).hover(function () {
                            $(this).css(a);
                        }, function () {
                            $(this).css(b);
                        });
                        $(this).hover(function () {
                            $(this).css(a);
                        }, function () {
                            $(this).css(a);
                        });
                    });
                }
            }

            function chooseHeig() {
                var heig1;
                for (var i = 0; i < li.length; i++) {
                    if ($(li[i]).css("display") == "list-item" || $(li[i]).css("display") == "block") {
                        heig1 = $(li[i]).height();
                    }
                }
                heig = heig1 > heig2 ? heig1 : heig2;
                if (navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.split(";")[1].replace(/[ ]/g, "") == "MSIE7.0") {
                    heig = heig - 450 + "px";
                } else {
                    heig = heig + 60 + "px";
                }
                $(".footer").css({"position": "relative", "top": heig});
            }

            function Num() {
                var lis = $(".ext_con>li");
                for (var i = 0; i < lis.length; i++) {
                    var app = $(lis[i]).find(".ext_re_u");
                    var len = "(" + app.length + ")";
                    $(lis[i]).find(".ext_re>span").html(len);
                }
            }

            function fooN() {
                var foos = $(".ext_con>li");
                var foon = $(".ext_fo>span");
                for (var i = 0; i < foos.length; i++) {
                    $(foon[foos.length - i - 1]).html(i + 1);
                }
                if (foos.length > 1) {
                    var num = Math.ceil(foos.length / 5);
                    $(".tabr3 .pages").remove();
                    $(".tabr3").append("<ul class='pages'><ul>");
                    for (var i = 0; i < num; i++) {
                        j = i + 1;
                        $(".pages").append("<li value='" + j + "'>" + j + "</li>");
                    }
                    var l = ($(".pages li").length + 1) * 30 + "px";
                    var pageli = $(".pages li");
                    var linum = $(".ext_con>li");
                    $(linum).hide();
                    for (var i = 0; i < 5; i++) {
                        $(linum[i]).show();
                    }
                    colorToggle($(pageli), {"background": "#1E8997", "color": "#fff"}, {
                        "background": "#fff",
                        "color": "#1E8997"
                    });
                    for (var i = 0; i < pageli.length; i++) {
                        $(pageli[i]).on("click", function () {
                            var n = $(this).val() - 1;
                            console.log(n);
                            $(linum).hide();
                            for (var i = 5 * n + 1; i <= 5 * (n + 1); i++) {
                                $(linum[i - 1]).show();
                            }
                            chooseHeig();
                        });
                    }
                    $(".pages").css("width", l);
                    // chooseHeig();
                }
            }

            function togg() {
                $(".trian2").hide();
                $(".ext_app").hide();
                var ex = $(".ext_re");
                for (var i = 0; i < ex.length; i++) {
                    $(ex[i]).unbind();
                    $(ex[i]).on("click", function () {
                        $(this).parent().parent().siblings(".trian2").toggle();
                        $(this).parent().parent().siblings(".ext_app").toggle();
                        var a = $(this).parent().parent().siblings(".ext_app");
                        if ($(a).find(".app_re").html() == "") {
                            $(a).find(".app_re").html("<textarea class='app_text'></textarea><div class='app_sub'>回复</div>");
                        }
                        apps();
                        chooseHeig();
                    });
                }
            }

            function apps() {
                var appsub = $(".app_sub");
                for (var i = 0; i < appsub.length; i++) {
                    $(appsub[i]).unbind();
                    $(appsub[i]).on("click", function () {
                        var text1 = $(this).siblings(".app_text").val();
                        var time1 = getDay();
                        t = text1.replace(reg, "");
                        if (t == "") {
                            alert("请输入字符！");
                        } else {
                            var html1 = "<li><div class='ext_re_u'><img src='img/btm2.jpg'><a class='ext_re_n'>学生1:</a><span class='ext_re_txt'>" + text1 + "</span></div><div class='ext_ap_info'><div><span class='ext_ap_time'>" + time1 + "</span> <a class='ext_ap_re'>回复</a></div></div></li>"
                            $(this).parent().before(html1);
                        }
                        $(this).siblings(".app_text").val("");
                        Num();
                        userre();
                        chooseHeig();
                    });
                }
            }

            function userre() {
                var usersub = $(".ext_ap_re");
                for (var i = 0; i < usersub.length; i++) {
                    $(usersub[i]).unbind();
                    usersub.on("click", function () {
                        var text2 = $(this).parent().parent().siblings(".ext_re_u").find(".ext_re_n").text();
                        $(this).parent().parent().parent().siblings(".app_re").find(".app_text").val("回复" + text2);
                    });
                }
            }

            function getDay() {
                var date = new Date();
                var year = date.getFullYear(), day = date.getDate();
                var mon = date.getMonth() + 1;
                if (mon.toString.length <= 1) {
                    mon = "0" + mon;
                }
                var time = year + "-" + mon + "-" + day;
                return time;
            }

            <?
            if($ac=="addbbs"){
                ?>tabr3();
            <?
                        }
                        ?>
        });
    </script>
    <script type="text/javascript" src="js/validform.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".postform").Validform({
                tiptype: 3,
                label: ".label",
                showAllError: true
            });
        })
    </script>
<?
include('footer.php');
?>