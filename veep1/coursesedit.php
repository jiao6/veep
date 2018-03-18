<?php
require_once("config/config.php");
require_once("config/dsql.php");
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}


require_once("config/CheckerOfCourse.php");

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课程图片
$COURSE_IMAGE_DIR = CheckerOfCourse::COURSE_IMAGE_DIR;//图片存放位置

$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
//echo $QUERY_STRING. "<br/>"; #
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
//echo $QUERY_STRING_1. "<br/>"; #
$destUrl = "courseslist.php?page=" . $page . "#" . $coursesid;
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");

$dsql = new DSQL();


if ($ac == "editupdate") {//修改课堂
    $SQL = "select  starttime,endtime  from courses   where id in (select pid from courses where id='$coursesid') ";
    $dsql->query($SQL);
    if (($auth_pid == 3||$auth_pid == 4)&& $dsql->next_record()) {

        $coursesstarttime = $dsql->f('starttime');
        $coursesendtime = $dsql->f('endtime');
        //echo " $coursesstarttime :: $starttime";
        if (strtotime($endtime) < strtotime($starttime)) {

            echo "<br><script>alert('$name 修改失败，关闭时间不能早于课程开始时间:$coursesstarttime');window.location='" . $destUrl . "'</script>\n";
            exit;

        } else
            if (strtotime($coursesstarttime) > strtotime($starttime)) {
                echo "<br><script>alert('$name 修改失败，开始时间不能早于课程开始时间:$coursesstarttime'.strtotime($starttime).'::'.strtotime($coursesstarttime));window.location='" . $destUrl . "'</script>\n";
                exit;
            } else
                if (strtotime($coursesendtime) < strtotime($endtime)) {
                    echo "";
                    echo "<br><script>alert(' $name 修改失败，结束时间不能晚于课程结束时间:$coursesendtime');window.location='" . $destUrl . "'</script>\n";
                    exit;
                }
    }



	if ($auth_pid == 2) {//老师
	    $SQL = "update  courses set  content='$content',sort='$sort'   where id = '$coursesid' and (useremail='$auth_email'   ) ";
	} else if ($auth_pid == 4) {//付费 老师 
	    if ($payquantity > 0) {//payquantity 设定人数大于 0
	        $SQL = "SELECT id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity,payquantityusage ,moocid,step,pid   FROM courses where id= '$coursesid'";
	        $dsql->query($SQL);
	        if ($dsql->next_record()) {
	            $oldpayquantity = $dsql->f("payquantity");
	            $payquantityusage = $dsql->f("payquantityusage");
	            $pid = $dsql->f("pid");
	            //payquantity='$payquantity', 不修改数量
	            $SQL = "update  courses set  $preSQL starttime='$starttime',endtime='$endtime',useremail='$useremail',content='$content', sort='$sort'    where id = '$coursesid' and (  userid = '$auth_id') ";
	        }
	    } else {//不修改课程人数，邮箱不能改，付费教师能修改自己分配的课堂
	            $SQL = "update  courses set starttime='$starttime', endtime='$endtime', content='$content', sort='$sort' where id = '$coursesid' and (userid='$auth_id'   ) ";
	    }
	
	} else if ($auth_pid == 3) {//管理员。,payquantity='$payquantity'。payquantity不修改
	            $SQL = "update  courses set  name='$name',moocurl='$moocurl',$preSQL starttime='$starttime',endtime='$endtime', updated_at=now(), content='$content',useremail='$useremail', moocid='$moocid',step='$step',coursesgroupid='$coursesgroupid' ,sort='$sort'  where id = '$coursesid' ";
	}
    if ($dsql->query($SQL)) {
    	//echo $SQL."<br/>";
        echo "<br><script>alert('修改成功');window.location='" . $destUrl . "'</script>\n";
    } else {
        echo "<br>修改未成功<script>window.location='" . $destUrl . "'</script>\n";
        exit;
    }
} else
if ($ac == "show") {
    $SQL = "update  courses  set isshow='1' where id = $coursesid  ";
    if (!$dsql->query($SQL)) {

        echo " 修改未成功 \n";
        exit;
    } else {
        echo " 修改成功 \n";

    }
} else if ($ac == "blank") {
    $SQL = "update  courses  set isshow='0' where id = $coursesid  ";
    if (!$dsql->query($SQL)) {

        echo " 修改未成功 ";
        exit;
    } else {
        echo " 修改成功";

    }
} else if ($ac == "del") {
    $SQL = "SELECT id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity,payquantityusage ,moocid,step,pid   FROM courses where id= '$coursesid'";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        $payquantity = $dsql->f("payquantity");
        $payquantityusage = $dsql->f("payquantityusage");
        $pid = $dsql->f("pid");

        if ($auth_pid == 3) {//管理员
            $SQL = "update  courses  set status='$status' where id = $coursesid ";
        } else if ($auth_pid == 4) {
            $SQL = "update  courses  set status='$status' where id = $coursesid and   userid = '$auth_id' ";
        }
        if (!$dsql->query($SQL)) {

            echo "<br><script>alert('删除未成功');window.location.href='" . $destUrl . "'</script>\n";
            exit;
        } else {

            echo "<br><script>alert('删除成功');window.location='" . $destUrl . "'</script>\n";
            //$SQL = "update courses set payquantityusage=payquantityusage-($payquantity-$payquantityusage) where id = '$pid'";
            //$dsql->query($SQL);


        }
    }


} else if ($ac == "import") {
    $myemail = explode("\n", $email);
    $SQL = "delete from coursesemail where coursesid =   '$coursesid' ";
    //echo $SQL;
    $dsql->query($SQL);
    for ($i = 0; $i < count($myemail); $i++) {

        $SQL = "insert into coursesemail (coursesid,email)values('$coursesid','" . trim($myemail[$i]) . "')";
        //echo $SQL;
        $dsql->query($SQL);
    }
    echo "<br><script>alert('成功');window.location='" . $destUrl . "'</script>\n";
} else
    if ($ac == "edit") {

        $SQL = "SELECT  *  from courses where  id ='$coursesid'  ";
        $dsql->query($SQL);
        $i = $offset;
        if ($dsql->next_record()) {
            $id = $dsql->f('id');
            $name = $dsql->f('name');
            $moocurl = $dsql->f('moocurl');
            $starttime = $dsql->f('starttime');
            $endtime = $dsql->f('endtime');
            $userid = $dsql->f('userid');
            $created_at = $dsql->f('created_at');
            $updated_at = $dsql->f('updated_at');
            $coursesimg = $dsql->f('coursesimg');
            if ($coursesimg && file_exists($coursesimg)) {//图片存在
            } else {//否则使用缺省图片
                $coursesimg = $DEFAULT_COURSE_IMAGE;
            }
            $content = $dsql->f('content');
            $useremail = $dsql->f('useremail');
            $code = $dsql->f('code');
            $payquantity = $dsql->f('payquantity');
            $moocid = $dsql->f('moocid');
            $step = $dsql->f('step');
            $sort = $dsql->f('sort');
            $coursesgroupid = $dsql->f('coursesgroupid');
            ?>
            <script type="text/javascript" language="javascript" src="js/laydate.js">
            </script>
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
                        <div class="rhead1">课堂修改</div>
                        <div class="rhead2"><a href="<? echo $destUrl ?>">课堂管理</a></div>
                    </div>
                    <script type="text/javascript" language="javascript">
                        function show(s) {
                            //alert(s);
                            if (s == 1) {
                                $("#payquantity").show();
                                $("#code").show();
                                $("#moocurl").hide();

                            } else {
                                $("#moocurl").show();
                                $("#payquantity").hide();
                                $("#code").hide();

                            }


                        }

                    </script>

                    <div class="rgwin">
                        <center><a href='<? print($coursesimg) ?>' target="_blank"><img height='50'
                                                                                        src="<? print($coursesimg) ?>"/></a>
                        </center>
                        <form class="rgform" method="post" enctype="multipart/form-data">
                            <!--type type="hidden" id="page" name="page" value="<? echo $page ?>"-->
                            <?
                            if ($auth_pid == 2) {
                                ?>
                                <!--div><label for="text1">课堂图片：</label><input type="file" name="coursesimg"></div-->
                                <div><label for="text1">图片：</label><a
                                        href="./editCourseImage-1.php?courseId=<? echo $coursesid . $QUERY_STRING_1 ?>"><input
                                            type="button" name="changeImg" id="changeImg"
                                            value="编辑 <? echo $coursesid ?> 号课堂图片"></a><!--span class='hint'>*</span-->
                                </div>
                                <div><label for="text1">简介：</label><input type="text" name="content" placeholder="简介"
                                                                          maxlength="100" datatype="*" errormsg="请输入内容"
                                                                          value="<? print($content) ?>"></div>

                                <?

                            } else if ($auth_pid == 4) {

                                ?>
                                <!--div><label for="text1">课堂图片：</label><input type="file" name="coursesimg"></div-->
                                <div><label for="text1">图片：</label><a
                                        href="./editCourseImage-1.php?courseId=<? echo $coursesid . $QUERY_STRING_1 ?>"><input
                                            type="button" name="changeImg" id="changeImg"
                                            value="编辑 <? echo $coursesid ?> 号课堂图片"></a><!--span class='hint'>*</span-->
                                </div>
                                <div><label for="text1">简介：</label><input type="text" name="content" placeholder="简介"
                                                                          maxlength="100" datatype="*" errormsg="请输入内容"
                                                                          value="<? print($content) ?>"></div>


                                <?

                                if ($userid == $auth_id) {
                                    ?>
                                    <!--div id="payquantity"><label for="text1">使用人次1：</label><input type="text"
                                                                                                 name="payquantity"
                                                                                                 placeholder="人次"
                                                                                                 maxlength="8"
                                                                                                 value="<? print($payquantity) ?>">
                                    </div-->
                                    <?
                                }
                                ?>
                                <div><label for="text1">负责人邮箱：</label><input type="text" name="useremail"
                                                                             placeholder="负责人邮箱" maxlength="50"
                                                                             datatype="e" readOnly disabled 
                                                                             value="<? print($useremail) ?>"><span
                                        class='hint'>*</span></div>
                                <div><label for="text3">开始时间：</label>
                                    <input type="text" name="starttime" value="<? print($starttime) ?>"
                                           class="laydate-icon"
                                           onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">

                                </div>
                                <div><label for="text3">结束时间：</label>
                                    <input type="text" name="endtime" class="laydate-icon" value="<? print($endtime) ?>"
                                           onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                                <div>
                                    <label for="text1">排序值：</label>
                                    <input type="text" name="sort" placeholder="排序值" maxlength="10"
                                           value="<? print($sort) ?>">
                                </div>

                                <?
                            } else if ($auth_pid == 3) {


                                ?>
                                <div><label for="name">名称：</label>

                                    <input type="text" name="name" placeholder="名称" datatype="*"
                                           value="<? print($name) ?>" maxlength="50"><span class='hint'>*</span></div>
                                <div>
                                    <label for="name">选择分类：</label>
                                    <select name="coursesgroupid" placeholder="">
                                        <option value="1">- 无 -</option>
                                        <?

                                        $SQL = "SELECT    id ,  name      ,uid ,  pid   ,path   ,status     FROM coursesgroup  order by path     ";


                                        //echo $SQL . "\n";


                                        $dsql->query($SQL);

                                        $nbsp = $pernbsp = "|---------";
                                        $olddepth = 1;
                                        while ($dsql->next_record()) {
                                            $id = $dsql->f('id');
                                            $group_name = $dsql->f('name');

                                            $insertdate = $dsql->f('insertdate');


                                            $userid = $dsql->f('userid');
                                            $group_pid = $dsql->f('pid');
                                            $path = $dsql->f('path');
                                            $newdepth = substr_count($path, ',');
                                            $select = "";
                                            if ($id == $coursesgroupid) {
                                                $select = "selected='selected' ";
                                            }
                                            if ($newdepth == $olddepth) {
                                                //$nbsp = "|--";
                                                echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";

                                            } else if ($newdepth > $olddepth) {
                                                $nbsp = $nbsp . $pernbsp;
                                                echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";
                                            } else {
                                                $depthno = $olddepth - $newdepth;
                                                $nbsp = substr($nbsp, 0, strlen($nbsp) - strlen($pernbsp) * $depthno);
                                                echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";

                                            }


                                            $olddepth = $newdepth;

                                        }


                                        ?>
                                    </select>
                                </div>
                                <!--div><label for="text1">课堂图片：</label><input type="file" name="coursesimg"><span class='hint'>*</span></div-->
                                <div><label for="text1">课堂图片：</label><a
                                        href="./editCourseImage-1.php?courseId=<? echo $coursesid . $QUERY_STRING_1 ?>"><input
                                            type="button" name="changeImg" id="changeImg"
                                            value="编辑 <? echo $coursesid ?> 号课程图片"></a><!--span class='hint'>*</span-->
                                </div>

                                <div><label for="text1">课堂简介：</label><input type="text" name="content" placeholder="简介"
                                                                            datatype="*" errormsg="请输入内容"
                                                                            value="<? print($content) ?>"
                                                                            maxlength="100"><span class='hint'>*</span>
                                </div>
                                <div><label for="text1">负责人邮箱：</label><input type="text" name="useremail"
                                                                             placeholder="负责人邮箱" maxlength="50"
                                                                             datatype="e"
                                                                             value="<? print($useremail) ?>"><span
                                        class='hint'>*</span></div>


                                <div><label for="text1">慕课课堂：</label><select name="moocid" size=1
                                                                             onchange="show(this.options[this.options.selectedIndex].value)">
                                        <?

                                        $SQL = "SELECT    id ,  name      FROM mooc  order by id desc     ";
                                        $dsql->query($SQL);
                                        $nbsp = $pernbsp = "|---------";
                                        $olddepth = 1;
                                        while ($dsql->next_record()) {
                                            $id = $dsql->f('id');
                                            $name = $dsql->f('name');
                                            if ($id == $moocid) {
                                                $select = "selected='selected' ";
                                            }
                                            echo "<option value=\"$id\" $select>$name</option>";
                                        }
                                        ?>
                                    </select></div>
                                <!--div id="payquantity"><label for="text1">使用人次：</label><input type="text"
                                                                                             name="payquantity"
                                                                                             placeholder="人次"
                                                                                             maxlength="8"
                                                                                             value="<? print($payquantity) ?>">
                                </div-->

                                <div id="code"><label for="text1">选课码：</label><input type="text" name="code"
                                                                                     disabled="disabled"
                                                                                     placeholder="选课码" maxlength="50"
                                                                                     value="<? print($code) ?>"></div>

                                <div id="moocurl">
                                    <label for="text1">MOOC地址：</label><input type="text" name="moocurl"
                                                                             placeholder="MOOC地址" maxlength="50"
                                                                             value="<? print($moocurl) ?>">
                                </div>

                                <div><label for="text3">开始时间：</label>
                                    <input type="text" name="starttime" value="<? print($starttime) ?>"
                                           class="laydate-icon"
                                           onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">

                                </div>
                                <div><label for="text3">结束时间：</label>
                                    <input type="text" name="endtime" class="laydate-icon" value="<? print($endtime) ?>"
                                           onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                                <div>
                                    <label for="text1">排序值：</label>
                                    <input type="text" name="sort" placeholder="排序值" maxlength="10"
                                           value="<? print($sort) ?>">
                                </div>
                                <?
                            }
                            ?>

                            <div><label for="text3"> </label>
                                <input type="submit" name="submit" value="修改">
                                <INPUT TYPE="hidden" name='coursesid' value="<? print($coursesid) ?>">
                                <INPUT TYPE="hidden" name='ac' value="editupdate">
                                <INPUT TYPE="hidden" name='payquantity' value="-1">
                            </div>


                            <br><br>

                        </form>
                    </div>

                </div>
            </div>


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
    } else if (isset($status) && $id > 0) {

        $SQL = "update  courses set status=$status where id =$id";
        if (!$dsql->query($SQL)) {
            //echo "un  success:$SQL ";
            echo "<br>修改用户状态未成功\n";
            exit;
        } else {

            echo "修改用户状态成功";


        }
    }


include("footer.php");
?>