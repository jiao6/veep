<?
@session_start();
error_reporting(0);
foreach ($_COOKIE as $key=>$value)
{
		 if(!is_array($value)){
			${$key}=htmlspecialchars($value);
		}else{
			${$key}= $value ;
		}
        
//      if( strpos( $value�� '\'' ) ) exit ( "$value is an invalid value for variety!" ); 
}


foreach ($_GET as $key=>$value)
{
         if(!is_array($value)){
			${$key}=htmlspecialchars($value);
		}else{
			${$key}= $value ;
		}
//      if( strpos( $value�� '\'' ) ) exit( "$value is an invalid value for variety!" ); 

}
foreach ($_POST as $key=>$value)
{
         if(!is_array($value)){
			${$key}=htmlspecialchars($value);
		}else{
			${$key}= $value ;
		}
//      if( strpos( $value�� '\'' ) ) exit( "$value is an invalid value for variety!" ); 
}

foreach ($_REQUEST as $key=>$value)
{
         if(!is_array($value)){
			${$key}=htmlspecialchars($value);
		}else{
			${$key}= $value ;
		}
//      if( strpos( $value�� '\'' ) ) exit( "$value is an invalid value for variety!" ); 
}
foreach ($_SESSION as $key=>$value)
{
       if(!is_array($value)){
			${$key}=htmlspecialchars($value);
		}else{
			${$key}= $value ;
		}
		 
//      if( strpos( $value�� '\'' ) ) exit( "$value is an invalid value for variety!" ); 
}

$global_vars = array(

"DB_HOST"               =>      "veepdb.mysqldb.chinacloudapi.cn",
"DB_NAME"               =>      "veep2",
"DB_USER"               =>      "veepdb%bitveep",
"DB_PWD"                =>      "BITVRlab927",

	/* 邮件相关参数 */
	"SMTP_SERVER_URL" 	=> "smtp.ym.163.com",//SMTP服务器
	"SMTP_SERVER_PORT" 	=> 25,//SMTP服务器端口
	"SMTP_USER_MAIL" 		=> "veep@bynor.com",//SMTP服务器的用户邮箱
	"SMTP_USER" 				=> "veep@bynor.com",//SMTP服务器的用户帐号
	"SMTP_PASS" 				=> "veepbeijing",//SMTP服务器的用户密码
	"MAIL_TYPE" 				=> "HTML",//邮件格式（HTML/TXT）,TXT为文本邮件

	/* URL 相关。部署之后需要修改 */
	"HOST_URL" 	=> "http://veep.chinacloudapp.cn",//网站根目录 本地： http://localhost/veep3  部署： http://veep.chinacloudapp.cn
	
);

/*
$global_vars = array(

"DB_HOST"               =>      "127.0.0.1",
"DB_NAME"               =>      "veep2",
"DB_USER"               =>      "root",
"DB_PWD"                =>      "root",
);






$global_vars = array(
"DB_HOST"               =>      "211.139.127.70:23306",
"DB_NAME"               =>      "veep2",
"DB_USER"               =>      "veep3",
"DB_PWD"                =>      "veep3veep3veep3"
);
*/
$url = "http://veep.chinacloudapp.cn/token.php"; //toenurl
$reportpath = "data/report/";
// Globalize everything for later use
while (list($key, $value) = each($global_vars)) {
  define($key, $value);
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

?>