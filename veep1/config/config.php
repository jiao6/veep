<?
@session_start();
error_reporting(0);


$reportpath = "/www/wwwroot/veep/data/report";

foreach ($_COOKIE as $key=>$value)
{
	${$key}=dhtmlspecialchars($value);
}


foreach ($_GET as $key=>$value)
{
	${$key}=dhtmlspecialchars($value);

}
foreach ($_POST as $key=>$value)
{
	${$key}=dhtmlspecialchars($value);
}

foreach ($_REQUEST as $key=>$value)
{
	${$key}=dhtmlspecialchars($value);
}
foreach ($_SESSION as $key=>$value)
{
	${$key}=dhtmlspecialchars($value);
}

$global_vars = array(

		"DB_HOST"               =>      "139.217.13.127",
		"DB_NAME"               =>      "veep3",
		"DB_USER"               =>      "root",
		"DB_PWD"                =>      "vrlab927929",
	/* 邮件相关参数 */
		"SMTP_SERVER_URL" 	=> "183.232.125.184",//SMTP服务器
		"SMTP_SERVER_PORT" 	=> 25,//SMTP服务器端口
		"SMTP_USER_MAIL"                => "service@vrsygc.com",//SMTP服务器的用户邮箱
		"SMTP_USER" 				=> "service@vrsygc.com",//SMTP服务器的用户帐号
		"SMTP_PASS" 				=> "BITVRlab927929",//SMTP服务器的用户密码
		"MAIL_TYPE" 				=> "HTML",//邮件格式（HTML/TXT）,TXT为文本邮件

	/* URL 相关。部署之后需要修改 */
		"HOST_URL" 	=> "http://vrsygc.chinacloudapp.cn",//网站根目录 本地： http://localhost/veep3  部署： http://veep.chinacloudapp.cn
);

/*
 *
 *
  $global_vars = array(

		"DB_HOST"               =>      "127.0.0.1",
		"DB_NAME"               =>      "veep2",
		"DB_USER"               =>      "root",
		"DB_PWD"                =>      "root",
);

"DB_HOST"               =>      "139.219.4.246",
		"DB_NAME"               =>      "veep2",
		"DB_USER"               =>      "veepdb%bitveep",
		"DB_PWD"                =>      "BITVRlab927",

$global_vars = array(
"DB_HOST"               =>      "211.139.127.70:23306",
"DB_NAME"               =>      "veep2",
"DB_USER"               =>      "veep3",
"DB_PWD"                =>      "veep3veep3veep3"
);
*/
$url = "http://vrsygc.chinacloudapp.cn/token.php"; //toenurl
// Globalize everything for later use
while (list($key, $value) = each($global_vars)) {
	define($key, $value);
}
function bpasswd($pwd){
	return md5(md5($pwd).'sXz34NQR');
}
function fix_session_register(){
	function session_register(){
		$args = func_get_args();
		foreach ($args as $key){
			$_SESSION[$key]=$GLOBALS[$key];
		}
	}
	function session_is_registered($key){
		return isset($_SESSION[$key]);
	}
	function session_unregister($key){
		unset($_SESSION[$key]);
	}
}
if (!function_exists('session_register')) fix_session_register();

function dhtmlspecialchars($string, $flags = null) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val, $flags);
		}
	} else {

		$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		if(strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
		if(!get_magic_quotes_gpc()){
			$string=addslashes( $string);
		}
		//$string = str_replace("_","\_",$string);//转义掉”_”
		$string = str_replace("%","\%",$string);//转义掉”%”


	}
	return $string;
}


$keyIndex[0][0]=1;
$keyIndex[0][1]=4;
$keyIndex[0][2]=7;
$keyIndex[0][3]=8;
$keyIndex[0][4]=10;
$keyIndex[0][5]=50;
$keyIndex[0][6]=38;
$keyIndex[0][7]=34;
$keyIndex[1][0]=3;
$keyIndex[1][1]=6;
$keyIndex[1][2]=9;
$keyIndex[1][3]=25;
$keyIndex[1][4]=31;
$keyIndex[1][5]=40;
$keyIndex[1][6]=55;
$keyIndex[1][7]=60;
$keyIndex[2][0]=2;
$keyIndex[2][1]=12;
$keyIndex[2][2]=18;
$keyIndex[2][3]=22;
$keyIndex[2][4]=23;
$keyIndex[2][5]=53;
$keyIndex[2][6]=56;
$keyIndex[2][7]=62;
$keyIndex[3][0]=5;
$keyIndex[3][1]=63;
$keyIndex[3][2]=41;
$keyIndex[3][3]=32;
$keyIndex[3][4]=42;
$keyIndex[3][5]=51;
$keyIndex[3][6]=0;
$keyIndex[3][7]=11;
$keyIndex[4][0]=9;
$keyIndex[4][1]=13;
$keyIndex[4][2]=21;
$keyIndex[4][3]=33;
$keyIndex[4][4]=44;
$keyIndex[4][5]=57;
$keyIndex[4][6]=61;
$keyIndex[4][7]=14;
$keyIndex[5][0]=15;
$keyIndex[5][1]=59;
$keyIndex[5][2]=43;
$keyIndex[5][3]=45;
$keyIndex[5][4]=52;
$keyIndex[5][5]=46;
$keyIndex[5][6]=58;
$keyIndex[5][7]=16;
$keyIndex[6][0]=17;
$keyIndex[6][1]=24;
$keyIndex[6][2]=27;
$keyIndex[6][3]=35;
$keyIndex[6][4]=47;
$keyIndex[6][5]=37;
$keyIndex[6][6]=29;
$keyIndex[6][7]=19;
$keyIndex[7][0]=20;
$keyIndex[7][1]=26;
$keyIndex[7][2]=28;
$keyIndex[7][3]=36;
$keyIndex[7][4]=48;
$keyIndex[7][5]=49;
$keyIndex[7][6]=39;
$keyIndex[7][7]=30;


function mkdirs($dir, $mode = 0777, $recursive = true) {
	if( is_null($dir) || $dir === "" ){
		return FALSE;
	}
	if( is_dir($dir) || $dir === "/" ){
		return TRUE;
	}
	if( mkdirs(dirname($dir), $mode, $recursive) ){
		return mkdir($dir, $mode);
	}
	return FALSE;
}


?>
