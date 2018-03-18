<?
require_once("config/config.php");
require_once("config/dsql.php");

if(!isset($dsql)){
	$dsql = new DSQL();
}

$SQL = "select * from userexperimenttoken where token='$token' ";
//echo $SQL; 
$dsql->query($SQL);
$time = time();
if($dsql->next_record()){
	$type= $dsql->f('type');

	$tokenarray = str_split($token);
	$md5 = "";
	$md5 += $tokenarray[$keyIndex[$type][0]];
	$md5 += $tokenarray[$keyIndex[$type][1]];
	$md5 += $tokenarray[$keyIndex[$type][2]];
	$md5 += $tokenarray[$keyIndex[$type][3]];
	$md5 += $tokenarray[$keyIndex[$type][4]];
	$md5 += $tokenarray[$keyIndex[$type][5]];
	$md5 += $tokenarray[$keyIndex[$type][6]];
	$md5 += $tokenarray[$keyIndex[$type][7]];
	 
	$status=1;
	$errinfo = "正常";
 
	 
	 
}else{
	$type = 0;
	$status=2;
	$errinfo = "token不存在";
	
}

$md5 = "";
	$md5 .= $tokenarray[$keyIndex[$type][0]];
	$md5 .= $tokenarray[$keyIndex[$type][1]];
	$md5 .= $tokenarray[$keyIndex[$type][2]];
	$md5 .= $tokenarray[$keyIndex[$type][3]];
	$md5 .= $tokenarray[$keyIndex[$type][4]];
	$md5 .= $tokenarray[$keyIndex[$type][5]];
	$md5 .= $tokenarray[$keyIndex[$type][6]];
	$md5 .= $tokenarray[$keyIndex[$type][7]];

	$type  = $type+1;
	$other = $md5;
	$info = "$status:$type:$errinfo:$time:$other";
	$md5string = md5($info.$md5);
	echo "$status:$md5string:$type:$errinfo:$time:$other";
 
 
 
 
 
 
  
?>