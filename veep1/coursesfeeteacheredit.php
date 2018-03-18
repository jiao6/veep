<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");

$dsql = new DSQL();


if ($ac == "editupdate") {

    if ($auth_pid == 4 || $auth_pid == 3) {//教务处 老师 管理员
        @mkdir("data");

        @mkdir("data/coursesimg");
        $preSQL = "";
        $coursesimg = "data/coursesimg/" . $_FILES["coursesimg"]["name"];

        @copy($_FILES["coursesimg"]["tmp_name"], $coursesimg);

        if ($_FILES["coursesimg"]["name"]) {
            $preSQL .= "   coursesimg='$coursesimg' ,   ";
        }
    }
    if ($auth_pid == 2) {//老师
        $SQL = "update  courses set  $preSQL content='$content'    where id = '$coursesid' and (useremail='$auth_email'   ) ";


    } else if ($auth_pid == 4) {//付费 老师 payquantity
        if($payquantity>0){

            $SQL = "update  courses set  $preSQL content='$content', payquantity='$payquantity'    where id = '$coursesid' and (  userid = '$auth_id') ";
        }else{
            $SQL = "update  courses set    $preSQL  content='$content'    where id = '$coursesid' and (useremail='$auth_email'   ) ";
        }

    } else if ($auth_pid == 3) {//管理员
        $SQL = "update  courses set  name='$name',moocurl='$moocurl',$preSQL starttime='$starttime',endtime='$endtime', updated_at=now(), content='$content',useremail='$useremail',code='$code',payquantity='$payquantity',moocid='$moocid',step='$step',coursesgroupid='$coursesgroupid'  where id = '$coursesid' ";
    }
    //echo $SQL;

    if (!$dsql->query($SQL)) {

        echo "<br>修改未成功<script>window.location='courseslistfeeteacher.php'</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='courseslistfeeteacher.php'</script>\n";

    }
} else if ($ac == "show") {
    $SQL = "update  courses  set isshow='1' where id = $coursesid  ";
    if (!$dsql->query($SQL)) {

        echo "<br>修改未成功<script>window.location.href=courseslistfeeteacher.php</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='courseslistfeeteacher.php'</script>\n";

    }
} else if ($ac == "blank") {
    $SQL = "update  courses  set isshow='0' where id = $coursesid  ";
    if (!$dsql->query($SQL)) {

        echo "<br>修改未成功<script>window.location.href=courseslistfeeteacher.php</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='courseslistfeeteacher.php'</script>\n";

    }
} else if ($ac == "del") {
    if ($auth_pid == 3) {//管理员
        $SQL = "update  courses  set status='$status' where id = $coursesid ";
    } else {
        $SQL = "update  courses  set status='$status' where id = $coursesid and   userid = '$auth_id' ";
    }
    if (!$dsql->query($SQL)) {

        echo "<br><script>alert('修改未成功');window.location.href='courseslistfeeteacher.php'</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='courseslistfeeteacher.php'</script>\n";

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
    echo "<br><script>alert('成功');window.location='courseslistfeeteacher.php'</script>\n";
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
            $content = $dsql->f('content');
            $useremail = $dsql->f('useremail');
            $code = $dsql->f('code');
            $payquantity = $dsql->f('payquantity');
            $moocid = $dsql->f('moocid');
            $step = $dsql->f('step');
            $coursesgroupid = $dsql->f('coursesgroupid');
            ?>
            <script type="text/javascript" language="javascript" src="js/laydate.js"> </script>
            <style type="text/css">
                .courseslistfeeteacher {
                    background: #1E8997;
                }
                .courseslistfeeteacher a {
                    color: #fff !important;
                }
                .laydate-icon {
                    width: 100px;
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
                <ul class="bread">
                    <li><a href="courseslistfeeteacher.php">课程管理</a></li>
                    <li>课程修改</li>
                </ul>
                <div class="rgwin">
                    <a href='<? print($coursesimg) ?>'>
                        <img height='50' src="<? print($coursesimg) ?>"/>
                    </a>
                    <form class="rgform form-x" method="post" enctype="multipart/form-data" style="width: 80%;">	
                        <?
                        if ($auth_pid == 2) {
                            ?>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程图片：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="file" name="coursesimg">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程简介：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="content" placeholder="简介" value="<? print($content) ?>">
                                </div>
                            </div>
                            <?
                        } else if ($auth_pid == 4) {
                            ?>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程图片：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="file" name="coursesimg"></div>
                                </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程简介：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="content" placeholder="简介" value="<? print($content) ?>"></div>
                                </div>
                            <?
                            if($userid==$auth_id){
                            ?>
                            <div id="payquantity" class="form-group">
                                <div class="label">
                                    <label for="text1">使用人次：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="payquantity" placeholder="人次" value="<? print($payquantity) ?>"></div>
                                </div>
                            <? }
                        } else if ($auth_pid == 3) {
                            ?>
                            <div class="form-group">
                                <div class="label">
                                    <label for="name">课程名称：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="name" placeholder="课程名称" value="<? print($name) ?>"></div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="name">选择分类：</label>
                                </div>
                                <select name="coursesgroupid" placeholder="">
                                    <option value="1">- 无 -</option>
                                    <?
                                        $SQL = "SELECT id, name, uid, pid, path, status FROM coursesgroup order by path";
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
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程图片：</label>
                                </div>
                                <div class="field">
                                    <input type="file" name="coursesimg">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">课程简介：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="content" placeholder="简介" value="<? print($content) ?>"></div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">负责人邮箱：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="useremail" placeholder=""value="<? print($useremail) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text1">慕课课程：</label>
                                </div>
                                <select name="moocid" size=1 onchange="show(this.options[this.options.selectedIndex].value)">
                                    <?
                                        $SQL = "SELECT    id ,	name 	    FROM mooc  order by id desc     ";
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
                                </select>
                            </div>
                            <div id="payquantity" class="form-group">
                                <div class="label">
                                    <label for="text1">使用人次：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="payquantity" placeholder="人次" value="<? print($payquantity) ?>">
                                </div>
                            </div>
                            <div id="code" class="form-group">
                                <div class="label">
                                    <label for="text1">选课码：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="code" disabled="disabled" placeholder="" value="<? print($code) ?>">
                                </div>
                            </div>
                            <div id="moocurl" class="form-group">
                                <div class="label">
                                    <label for="text1">MOOC地址：</label>
                                </div>
                                <div class="field">
                                    <input class="input" type="text" name="moocurl" placeholder="MOOC地址" value="<? print($moocurl) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text3">开始时间：</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="starttime" value="<? print($starttime) ?>" class="input" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text3">结束时间：</label>
                                </div>
                                <div class="field">
                                    <input type="text" name="endtime" class="input" value="<? print($endtime) ?>" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <?
                                }
                            ?>
                            <div class="form-group">
                                <div class="label">
                                    <label for="text3"></label>
                                </div>
                                <div class="field">
                                    <input type="submit" name="submit"  value="修改" class="button bg-main">
                                </div>
                                <input type="hidden" name='coursesid' value="<? print($coursesid) ?>">
                                <input type="hidden" name='ac' value="editupdate">
                            </div>
                    </form>
                </div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
    $('.rt').height(700);
    $('.contain').height(800);
</script>
<script type="text/javascript" language="javascript">
    function show(s) {
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