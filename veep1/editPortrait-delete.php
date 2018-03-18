<?
require_once("config/config.php");
require_once("config/dsql.php");
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
	Header("Location:login.php");
}

//echo $userimg
if ($userimg != "img/teacher.png") {//ԭ����ͷ��
	if(file_exists($userimg)) {//�ж�ԭͷ���Ƿ����
	     unlink($userimg);
	}
}
$dsql = new DSQL();
$SQL = "update  users set userimg = '' where id = $auth_id";

	$info[0] = "{\"email\":\"EMPTY\"}";
	$info[1] = "{\"nickname\":\"NULL\"}";
    if ($dsql->query($SQL)) {
			$info[0] = "{\"email\":\"SUCCESS\"}";
    } else {
    }
	//echo ('{"data":['.implode(",",$info).']}');
	echo "<script>alert('�޸���ɣ�'); document.location = './editPortrait-1.php';</script>"

?>