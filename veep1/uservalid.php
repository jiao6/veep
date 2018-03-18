<?
require_once("config/config.php");
require_once("config/dsql.php");


$dsql = new DSQL();



    if ($param) {
        $SQL = "select id from users where email = '$param' and id !='$auth_id'";


        $dsql->query($SQL);

        if ($dsql->next_record()) {
            echo "{\"info\":\"邮箱已经存在！\",
			\"status\":\"n\" }";
        }else{
            echo "{\"info\":\"可以使用！\",
			\"status\":\"y\" }";
        }

    }

?>