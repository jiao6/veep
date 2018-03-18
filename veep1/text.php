<?php
//header('Content-Type:text/html;charset=utf-8');
session_start();
// 第一个参数：传入要转换的字符串
// 第二个参数：取0，半角转全角；取1，全角到半角
function SBC_DBC($str, $args2) {
$DBC = Array(
'０' , '１' , '２' , '３' , '４' ,
'５' , '６' , '７' , '８' , '９' ,
'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' ,
'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' ,
'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' ,
'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' ,
'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' ,
'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
'ｙ' , 'ｚ' , '－' , '　' , '：' ,
'．' , '，' , '／' , '％' , '＃' ,
'！' , '＠' , '＆' , '（' , '）' ,
'＜' , '＞' , '＂' , '＇' , '？' ,
'［' , '］' , '｛' , '｝' , '＼' ,
'｜' , '＋' , '＝' , '＿' , '＾' ,
'￥' , '￣' , '｀'
);
$SBC = Array( // 半角
'0', '1', '2', '3', '4',
'5', '6', '7', '8', '9',
'A', 'B', 'C', 'D', 'E',
'F', 'G', 'H', 'I', 'J',
'K', 'L', 'M', 'N', 'O',
'P', 'Q', 'R', 'S', 'T',
'U', 'V', 'W', 'X', 'Y',
'Z', 'a', 'b', 'c', 'd',
'e', 'f', 'g', 'h', 'i',
'j', 'k', 'l', 'm', 'n',
'o', 'p', 'q', 'r', 's',
't', 'u', 'v', 'w', 'x',
'y', 'z', '-', ' ', ':',
'.', ',', '/', '%', '#',
'!', '@', '&', '(', ')',
'<', '>', '"', '\'','?',
'[', ']', '{', '}', '\\',
'|', '+', '=', '_', '^',
'$', '~', '`'
);
if ($args2 == 0) {
return str_replace($SBC, $DBC, $str); // 半角到全角
} else if ($args2 == 1) {
return str_replace($DBC, $SBC, $str); // 全角到半角
} else {
return false;
}
}

echo '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';

$paraID = array();
$paraAnswer = array();
$paraID[] = "stuName";
$paraAnswer[] =  SBC_DBC($_POST["stuName"],1);
unset($_POST["stuName"]);

//$paraID[] = "stuNO";
//$paraAnswer[] =  SBC_DBC($_POST["stuNO"],1);
//unset($_POST["stuNO"]);

$paraID[] = "email";
$paraAnswer[] =  SBC_DBC($_POST["email"],1);
unset($_POST["email"]);

//$paraID[] = "sysID";
//$paraAnswer[] =  SBC_DBC($_POST["sysID"],1);
//unset($_POST["sysID"]);

$paraID[] = "reportDate";
$paraAnswer[] =  SBC_DBC(date("Ymdhi"),1);

$xmlfilename = $_SESSION['xmlfilename'];
unset($_SESSION['xmlfilename']);

$idtr = $_SESSION["$xmlfilename"];
unset($_SESSION["$xmlfilename"]);

for($i = 1; $i<sizeof($idtr) ;$i++){
		$Id = $idtr[$i];
		$paraID[] = $Id;
		$paraAnswer[] = SBC_DBC($_POST["$Id"],1);
}
$xmlfilepath="../xml/".$xmlfilename;
$returnfile=judgment("$xmlfilepath",$paraID,$paraAnswer);
//$anysreturn = anysxml($returnfile,0);
//echo '<html>';
//echo $anysreturn[0];
//echo '</html>';
//echo $returnfile[0];
//echo $returnfile[1];
$url="http://veep.chinacloudapp.cn/onlinejudge.php?filename=".$returnfile[0];
Header("Location: $url");
//session_destroy();
//session_unset();
