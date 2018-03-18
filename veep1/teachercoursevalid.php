<?

require_once("config/config.php");
require_once("config/dsql.php");

$dsql = new DSQL();


$dsql->query("SELECT payquantity-payquantityusage as quantity   FROM courses where  id='$courseid' and status=0");




if ($dsql->query($SQL)) {
    $quantity = $dsql->f("quantity");
    if($quantity>=$param){
        echo "{\"info\":\"可以填写！\",
			\"status\":\"y\" }";
    }else{
        echo "{\"info\":\"超过可用数！\",
			\"status\":\"y\" }";
    }
} else {
    echo "{\"info\":\"没有这个课程！\",
			\"status\":\"y\" }";
}


?>