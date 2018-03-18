<?php
require_once("config/config.php");
require_once("config/dsql.php");
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

require_once("header.php");
$dsql = new DSQL();

if ($ac == "del") {
    $SQL = "SELECT * FROM courses where id= $coursesid";
    $dsql->query($SQL);
    if ($dsql->next_record()) {

        $userid = $dsql->f('userid');
        $useremail = $dsql->f('useremail');
        $SQL = "select now()  ";
        if ($auth_pid == 3) {//管理员
            $SQL = "update  coursesuser  set status='$status' where id = $id ";
        } else if ( strtolower($useremail) == strtolower($auth_email) || $userid == $auth_id) {
            $SQL = "update  coursesuser  set status='$status' where id = $id  ";
        }
        if (!$dsql->query($SQL)) {
            echo "<br><script>alert('操作成功');history.go(-1)</script>\n";
        } else {
            echo "<br><script>alert('操作成功');history.go(-1)</script>\n";
        }
    }
}
?>