<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");

?><?

session_start();

if (!isset($dsql)) {
    $dsql = new DSQL();
}
if ($auth_pid != 3) {
    exit;
}

if ($action == "groupmodifyaction"){
    if (strlen($group_name) > 0) {


        $SQL = "select name, path from coursesgroup  where id = $group_pid limit 0,1";
        if (!$dsql->query($SQL)) {
            echo "";
        } else {
            $dsql->next_record();
            $lastinsid = $dsql->f("lastinsid");
            $path = $dsql->f("path");
            $pname = $dsql->f("name");
            $SQL = "update coursesgroup set path='${path},$fid' ,pid = '$group_pid' ,name = '$group_name' where id = $fid";


            if (!$dsql->query($SQL)) {

            } else {

                echo "<br><script>alert(\"更新组成功 $group_name 上级为 $pname\");window.location='coursesgrouplist.php'</script>\n";
            }

        }
    }
}else
if ($action == "forumdetail"){
$SQL = "SELECT    id ,	name 	  	,uid ,	pid 	,path 	,status    FROM coursesgroup  where id='$fid'   order by path       ";
$dsql->query($SQL);
if ($dsql->next_record()){
$id = $dsql->f('id');
$name = $dsql->f('name');

$insertdate = $dsql->f('insertdate');


$userid = $dsql->f('userid');
$pid = $dsql->f('pid');
$path = $dsql->f('path');
$newdepth = substr_count($path, ',');

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
        <ul class="bread">
			<li><a href="coursesgrouplist.php">课程分组</a></li>
			<li>课程修改</li>
		</ul>
<center>
    <div class="ht301">
        <div class="ht302"> </div>
        <div class="ht304">
            <div class="groupaction_div">
                <h4>修改</h4>
                <form method="post" action="coursesgroupaction.php?action=groupmodifyaction" class="groupaction_form rgform">
                    <input type="hidden" name="groupaddaction" value="863f907b">
                    <p>
                        <label for="group_pid">上级</label>
                        <select name="group_pid" size=1>
                            <option value="1"> - 无 -</option>
                            <?

                            $SQL = "SELECT    id ,  name        ,uid ,  pid     ,path   ,status     FROM coursesgroup  order by path     ";
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
                                if ($id == $pid) {
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
                    </p>
                    <br /><br />
                    <p>
                        <label for="group_name">名称</label>
                        <input type="text" name="group_name" value="<?print($name)?>" size="20">
                    </p>
                    <br /><br />
                    <p>
                        <input type="submit" name="forumsubmit" value="提交" class="submit button bg-main" />
                        <input type="hidden" name="fid" value="<?print($fid)?>">
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?
    }
    }else if ($action == "forumdelete"){

        $SQL = "select * from coursesgroup where pid=$fid";
        if ($dsql->query($SQL)) {
            if ($dsql->next_record()) {

                echo "<br><script>alert(\"有子单位不能删除\");window.location='coursesgrouplist.php'</script>\n";
            } else {

                $SQL = " select * from courses where coursesgroupid=$fid";
                if ($dsql->query($SQL) && $dsql->next_record()) {

                    echo "<br><script>alert(\"有课程不能删除\");window.location='coursesgrouplist.php'</script>\n";
                } else {
                    $SQL = "delete from  coursesgroup where id='$fid'";
                    $dsql->query($SQL);

                    echo "<br><script>alert(\"删除成功\");window.location='coursesgrouplist.php'</script>\n";
                }
            }
        }
    }else if (isset($groupaddaction)){
        if (strlen($group_name) > 0) {
            $SQL = "insert into coursesgroup (  name ,     id ,  pid )values('$group_name',0,$group_pid)  ";
            $dsql->query($SQL);
            //echo $SQL;

            //$SQL = "select lastinsertid() from coursesgroup where id = $group_pid";

            $SQL = "select last_insert_id()  as lastinsid,path from coursesgroup  where id = $group_pid limit 0,1";
            if (!$dsql->query($SQL)) {
                echo "";
            } else {
                $dsql->next_record();
                $lastinsid = $dsql->f("lastinsid");
                $path = $dsql->f("path");

                $SQL = "update coursesgroup set path='${path},$lastinsid' where id = $lastinsid;";


                if (!$dsql->query($SQL)) {
                    echo "";
                } else {

                    echo "<br><script>alert(\"增加${group_name}成功\");window.location='coursesgrouplist.php'</script>\n";

                }

            }
        }
    }else{
    if (strlen($SQL) < 5) {
        $SQL = "SELECT    id ,	name 	  	,uid ,	pid 	,path 	,status     FROM coursesgroup  order by path     ";
    }
    //echo $SQL . "\n";
    $dsql->query($SQL);
    ?>
   <!--  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title></title>
        <link href="images/css.css" rel="stylesheet" type="text/css"/>
    </head>
    <body> -->
 <style type="text/css">
    .coursesgrouplist {
      background: #1E8997;
    }
    .coursesgrouplist a {
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
	  <li><a href="coursesgrouplist.php">课程分组</a></li>
	  <li>添加课程分组</li>
	</ul>
            <form method="post" action="coursesgroupaction.php?action=groupaddaction&add=forum" class="form-x" style="width: 50%">
                <input type="hidden" name="groupaddaction" value="863f907b">
                <div class="form-group">
                    <div class="label">
                        <label for="group_pid">上级:</label>
                    </div>
                    <div class="field">
                        <select name="group_pid" size=1>
                            <option value="1"> - 无 -</option>
                            <?

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
                                if ($id == $pid) {
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
                        <label for="group_name">名称:</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="group_name" value="名称" size="20">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for=""></label>
                    </div>
                    <div class="field">
                        <input class="input button bg-main" type="submit" name="forumsubmit" value="提 交">
                    </div>
                </div>
              <!--   <center>
                    <table cellspacing="1" cellpadding="4" width="70%" align="center" class="tableborder">
                        <tr height="45" class="header">
                        </tr>
                        <tr height="45" class="header">
                        <tr height="45" align="center">
                            <td bgcolor="#F8F8F8" width="15%">上级:</td>
                            <td colspan="4" bgcolor="#FFFFFF" width="27%">
                                <select name="group_pid" size=1>
                                    <option value="1"> - 无 -</option>
                                    <?

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
                                        if ($id == $pid) {
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
                                </select></td>

                        </tr>
                        <tr height="45" bgcolor="#FFFFFF" align="center">
                            <td bgcolor="#F8F8F8" width="15%">名称:</td>
                            <td bgcolor="#FFFFFF" width="28%"><input type="text" name="group_name" value="名称" size="20">
                            </td>
                        </tr>
                        <tr height="45" bgcolor="#FFFFFF" align="center">
                            <td bgcolor="#F8F8F8" width="15%">
                            <td colspan="4" bgcolor="#FFFFFF" width="27%">
                                <br><br>
                                <center><input type="submit" name="forumsubmit" value="提 交">
                            </td>
                        </tr>
                    </table> -->
            </form>

    <?
    }
    ?><?
    include("footer.php");

    ?>

