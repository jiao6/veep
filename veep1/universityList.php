<?
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
	Header("Location:login.php");
}
require_once("config/config.php");;
require_once("config/dsql.php");
$STATUS_NORMAL = 0;
$SQL = "select * from university where province_id=" . $provinceId . " and status=" . $STATUS_NORMAL;
	$dsql = new DSQL();
	$dsql->query($SQL);
    $i = 0;
	while ($dsql->next_record()) {
		$id = $dsql->f('id');
		$name = $dsql->f('name');
		$image = $dsql->f('image');
		$info[$i] = "{\"id\":\"$id\", \"name\":\"$name\", \"image\":\"$image\"}";
		$i++;
	}
//$info[0] = "{\"email\":\"EMPTY\", \"SQL\":\"$SQL\", \"nickname\":\"BLANK\"}";
echo ('{"data": ['.implode(",",$info).'] }');




?>


