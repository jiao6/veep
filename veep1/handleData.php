<?php
session_start();

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
require_once("config/config.php");
require_once("config/dsql.php");
//echo "ac=" . $ac;
$dsql = new DSQL();
if ($ac == "createLessonKey") {
    for($i=0;$i<$quantity;$i++){
        $code =rand(10000,99999).date("m")."-".rand(10000,99999).date("d")."-".rand(10000,99999)."-".rand(10000,99999).date("His");
        $SQL = "insert into coursescode(coursesid, code, createtime, endtime, day)values('$lessonId', '$code', now(), '$endtime', '$day')  ";
        //echo $SQL;
        $dsql->query($SQL);
    }
    $message = "恭喜您，".$auth_username."，创建了 ". $quantity ." 个秘钥。";
    //$info[0] = "{\"result\":\"SUCCESS\", \"message\":\"". $message  ."\"}";//更新则返回 SUCCESS
    echo '{"info": "' . "创建成功" . '", "status": "y", "message": "'.$message.'"}';
    exit;
}
exit;
?>
