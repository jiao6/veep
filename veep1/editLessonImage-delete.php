<?

session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}

require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/CheckerOfCourse.php");
//echo "dddddddddd=" . $QUERY_STRING_2;

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课程图片

//echo $DEFAULT_COURSE_IMAGE . "; " .$COURSE_IMAGE_DIR;
//检查用户权限
$checker = new CheckerOfCourse();
$destUrl = "lessonedit.php?lessonId=". $lessonId. $checker->recoverChar($QUERY_STRING_2);
$pass = $checker->checkAuthOfLesson($auth_pid, $auth_id, $lessonId);
//echo $DEFAULT_COURSE_IMAGE . "; COURSE_IMAGE_DIR=" .$COURSE_IMAGE_DIR . "; pass=" .$pass;
if ($pass) {
} else {
    $checker->jumpTo("你无权删除本课程图片！", $destUrl);
    exit();
}
$sql = "select IMG_URL from LESSON where ID=" . $lessonId;
$dsql = new DSQL();
$dsql->query($sql);
if ($dsql->next_record()) {//存在图片
    $lessomImg = $dsql->f('IMG_URL');
    if ($lessomImg != $DEFAULT_COURSE_IMAGE) {//原来有头像
        if(file_exists($lessomImg)) {//判断原头像是否存在
             unlink($lessomImg);//删除
        }
    }
}
$dsql = new DSQL();
$SQL = "update LESSON set IMG_URL = '' where ID = $lessonId";
//echo "lessomImg=" . $lessomImg . "; lessonId=" . $lessonId . "; sql=" . $sql . "<br/>";
    /*$info[0] = "{\"email\":\"EMPTY\"}";
    $info[1] = "{\"nickname\":\"NULL\"}";*/
    if ($dsql->query($SQL)) {
        $checker->jumpTo("删除图片成功！", $destUrl);
    } else {
        $checker->jumpTo("删除图片失败！", $destUrl);
    }
    //echo ('{"data":['.implode(",",$info).']}');
?>