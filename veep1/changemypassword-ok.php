<?
require_once("config/config.php");
require_once("config/dsql.php");

$pstmt = new DSQL();

$SQL = "select email, nickname from users where id = $auth_id and password=?";
$info[0] = "{\"email\":\"EMPTY\"}";
$info[1] = "{\"nickname\":\"NULL\"}";
//$mysqli = $pstmt->getConn();
$query_prepare = $pstmt->getPstmt($SQL);//$mysqli->prepare($SQL);
$oldPwd = bpasswd($oldPwd);
$query_prepare->bind_param("s", $oldPwd);
$query_prepare->execute();
$query_prepare->bind_result($email, $nickname);
$result = array();
$has = false;
if ($query_prepare->fetch()) {//旧密码正确
    $result[] = array('email'=>$email, 'nickname'=>$nickname);
		$info[0] = "{\"email\":\"$email\"}";
		$info[1] = "{\"nickname\":\"$nickname\"}";
		$has = true;
}
if (!$has) {//旧密码错，返回
	$info[0] = "{\"email\":\"EMPTY\"}";
	$info[1] = "{\"nickname\":\"NULL\"}";
	echo ('{"data":['.implode(",",$info).']}');
	$query_prepare->colse();//关闭 stmt
	return;
}
//旧密码正确，修改密码
// 先关掉原来的 stmt
$SQL = "update users set password=?,pwd=? where id = $auth_id"; //修改密码
$pstmt = new DSQL();
$query_prepare_2 = $pstmt->getPstmt($SQL);//$mysqli->prepare($SQL);
		//$info[1] = "{\"nickname\":\""+query_prepare_2+"\"}";
$bpasswd= bpasswd($newPwd1);
$query_prepare_2->bind_param("ss", $bpasswd,$newPwd1);
if ($query_prepare_2->execute()) {
	$info[0] = "{\"email\":\"SUCCESS\"}";//更新则返回 SUCCESS
} else {
	$info[0] = "{\"email\":\"FAIL\"}";//修改失败则返回 FAIL
}
$pstmt->colsePstmt(true);/**/
echo ('{"data":['.implode(",",$info).']}');

//$mysqli->close();

/*
$dsql->query($SQL);
if ($dsql->next_record()) {

    $SQL = "select email from users where id = $auth_id and pwd='$oldPwd'";
    $email = $dsql->f('email');
    $info[0] = "{\"email\":\"$email\"}";


    $SQL = "update users set pwd='$newPwd1' where id = $auth_id"; //修改密
    if ($dsql->query($SQL)) {
      $info[0] = "{\"email\":\"SUCCESS\"}";//更新则返回 SUCCESS
    } else {
      $info[0] = "{\"email\":\"FAIL\"}";//修改失败则返回 
    }

} else {//旧密码错误
    $info[0] = "{\"email\":\"EMPTY\"}";//没查到则返回 EMPTY
}

*/
?>