<?
require_once("config/config.php");
require_once("config/dsql.php");


$SQL = "update users set pwd=? ,password=?,remember_token='' where    remember_token like '". $remember ."%'  and id = ". $userid; //修改密码
//echo $SQL;
$pstmt = new DSQL();
$bpasswd = bpasswd($newPwd1);
$query_prepare_2 = $pstmt->getPstmt($SQL);//$mysqli->prepare($SQL);
		//$info[1] = "{\"nickname\":\""+query_prepare_2+"\"}";
$query_prepare_2->bind_param("ss", $newPwd1,$bpasswd);
if ($query_prepare_2->execute()) {
	$info[0] = "{\"email\":\"SUCCESS\"}";//更新则返回 SUCCESS
} else {
	$info[0] = "{\"email\":\"FAIL\"}";//修改失败则返回 FAIL
}
$pstmt->colsePstmt(true);/**/
echo ('{"data":['.implode(",",$info).']}');

//$mysqli->close();

?>