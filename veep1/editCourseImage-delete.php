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

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//ȱʡ�γ�ͼƬ
$COURSE_IMAGE_DIR     = CheckerOfCourse::COURSE_IMAGE_DIR;//ͼƬ���λ��

//echo $DEFAULT_COURSE_IMAGE . "; " .$COURSE_IMAGE_DIR;
//����û�Ȩ��
$checker = new CheckerOfCourse();
$destUrl = "coursesedit.php?coursesid=". $courseId. $checker->recoverChar($QUERY_STRING_2);
$pass = $checker->checkAuth($auth_pid, $auth_email, $courseId);
//echo $DEFAULT_COURSE_IMAGE . "; COURSE_IMAGE_DIR=" .$COURSE_IMAGE_DIR . "; pass=" .$pass;
if ($pass) {
} else {
	$checker->jumpTo("����Ȩɾ�����γ�ͼƬ��", $destUrl);
	exit();
}


if ($coursesImg != $DEFAULT_COURSE_IMAGE) {//ԭ����ͷ��
    if(file_exists($coursesImg)) {//�ж�ԭͷ���Ƿ����
         unlink($coursesImg);//ɾ��
    }
}

$dsql = new DSQL();
$SQL = "update courses set coursesimg = '' where id = $courseId";
//echo "coursesImg=" . $coursesImg . "; courseId=" . $courseId . "; SQL=" . $SQL . "<br/>";
    /*$info[0] = "{\"email\":\"EMPTY\"}";
    $info[1] = "{\"nickname\":\"NULL\"}";*/
    if ($dsql->query($SQL)) {
		$checker->jumpTo("ɾ��ͼƬ�ɹ���", $destUrl);
    } else {
		$checker->jumpTo("ɾ��ͼƬʧ�ܣ�", $destUrl);
    }
    //echo ('{"data":['.implode(",",$info).']}');
?>