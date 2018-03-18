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
if ($auth_pid != 3) {
    exit;
}

if ($ac == "editupdate") {
    //error_reporting(9);
    mkdir("data");
    mkdir("data/experimentfile");
    mkdir("data/experimentimg");
    mkdir("experimentimgabc");
    $experimentfile = "data/experimentfile/". $_FILES["experimentfile"]["name"];
    $experimentimg = "data/experimentimg/". $_FILES["experimentimg"]["name"];
    copy($_FILES["experimentfile"]["tmp_name"], $experimentfile);
    copy($_FILES["experimentimg"]["tmp_name"],$experimentimg);
    if($_FILES["experimentfile"]["name"]){
        $preSQL = "   softfile='$experimentfile',    ";
    }
    if($_FILES["experimentimg"]["name"]){
        $preSQL .= "   img='$experimentimg' ,   ";
    }
    $SQL = "update  experiments set `sort`='$sort',`reportfile`='$reportfile', `name`='$name',content='$content' ,$preSQL  groupid='$groupid', `type`='$type',difficulty='$difficulty' where id = $id  ";
    //echo $SQL;
    if (!$dsql->query($SQL)) {
        //echo $SQL;
        echo "<br><script>alert('修改未成功');window.location='experimentslist.php'</script>\n";
        exit;
    } else {
       echo "<br><script>alert('修改成功');window.location='experimentslist.php'</script>\n";

    }
} else if ($ac == "del") {
    $SQL = "update  experiments  set status='$status' where id = $id  ";
    if (!$dsql->query($SQL)) {

        echo "<br>修改未成功<script>window.location.href=experimentslist.php</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='experimentslist.php'</script>\n";

    }
} else
    if ($ac == "edit") {

        $SQL = "SELECT  *  from experiments where  id ='$id'  ";
        $dsql->query($SQL);
        $i = $offset;
        if ($dsql->next_record()) {
            $i++;
            $id = $dsql->f('id');
            $name = $dsql->f('name');
            $content = $dsql->f('content');
            $sort = $dsql->f('sort');
            $softfile = $dsql->f('softfile');
            $reportfile = $dsql->f('reportfile');
            $userid = $dsql->f('userid');
            $status = $dsql->f('status');
            $groupid = $dsql->f('groupid');
            $img = $dsql->f('img');
            $type = $dsql->f('type');
            $difficulty = $dsql->f('difficulty');
            ?>
<style type="text/css">
    .experimentslist {
        background: #1E8997;
    }
    .experimentslist a{
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
              <li><a href="experimentslist.php">实验管理</a></li>
              <li>实验修改</li>
        </ul>
        <form  class="rgform form-x" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div class="label">
                    <label for="name">请选择类型</label>
                </div>
                <div class="field">
                    <select name="groupid" size=1>
                        <option value="1"> - 无 -</option>
                        <?
                            $SQL = "SELECT    id ,  name        ,uid ,  pid     ,path   ,status     FROM experimentsgroup  order by path     ";
                            //echo $SQL . "\n";
                            $dsql->query($SQL);
                            $nbsp = $pernbsp = "|---------";
                            $olddepth = 1;
                            while ($dsql->next_record()) {
                                $id = $dsql->f('id');
                                $group_name = $dsql->f('name');
                                $insertdate = $dsql->f('insertdate');
                                $userid = $dsql->f('userid');
                                $path = $dsql->f('path');
                                $newdepth = substr_count($path, ',');
                                $select = "";
                                if ($id == $groupid) {
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
            </div>
            <div class="form-group">
                <div class="label">
                    <label for="name">名称</label>
                </div>
                <div class="field">
                    <input class="input" type="text" maxlength="100" id="name" name="name"value="<? print($name) ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label for="name">简介</label>
                </div>
                <div class="field">
                    <input class="input" type="text" maxlength="100" id="content" name="content"value="<? print($content) ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label for="name">实验图片</label>
                </div>
                <div class="field">
                    <input type="file" id="experimentimg"name="experimentimg"value="<? print($experimentimg) ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="label">
                    <label for="name">实验文件</label>
                </div>
                <div class="field">
                    <input type="file" id="experimentfile"name="experimentfile"></div>
                </div>
            <div class="form-group">
                <div class="label">
                    <label for="name">实验报告地址</label>
                </div>
                <div class="field">
                    <input class="input" type="text" maxlength="100" id="reportfile" name="reportfile" value="<? print($reportfile) ?>">
                </div>
            </div>

            <? include "page_element/difficulty.php" ?><!-- 实验难度 -->
            <? include "page_element/experiment_type.php" ?><!-- 实验类型 -->
            <div class="form-group">
                <div class="label">
                    <label for="name">排序值</label>
                </div>
                <div class="field">
                    <input class="input" maxlength="6" type="text" id="sort" name="sort"  value="<? print($sort) ?>">
                </div>
            </div>
            <input type="hidden" name="ac" value="editupdate">
            <div class="form-group">
                <div class="label">
                    <label for="submit"></label>
                </div>
                <div class="field">
                    <input type="submit" id="submit" name="submit" class="button bg-main" value="保存">
                </div>
            </div>
        </form>

            <?
        }
    } else if (isset($status) && $id > 0) {

        $SQL = "update  courses set status=$status where id =$id";
        if (!$dsql->query($SQL)) {
            //echo "un  success:$SQL ";
            echo "<br>修改状态未成功\n";
            exit;
        } else {

            echo "修改状态成功";


        }
    }


include("footer.php");
?>