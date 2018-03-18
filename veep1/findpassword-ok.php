<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/email.class.php");
require_once("config/RandChar.php");
//echo "$formrand!=  :".$_SESSION["findpasswordrand"];
if($formrand!=$_SESSION["findpasswordrand"] ){
	exit;
}else{

	$_SESSION["findpasswordrand"]  = "1";
}


$SQL = "select id, nickname from users where id > -1 and email=?";
$info[0] = "{\"email\":\"EMPTY\", \"userid\":\"$todo\", \"nickname\":\"BLANK\"}";
//$mysqli = $pstmt->getConn();
$pstmt = new DSQL();
$query_prepare = $pstmt->getPstmt($SQL);//$mysqli->prepare($SQL);

$query_prepare->bind_param("s", $email);
$query_prepare->execute();
$query_prepare->bind_result($userid, $nickname);
$result = array();
$has = false;
if ($query_prepare->fetch()) {//邮箱存在
    $result[] = array('userid'=>$userid, 'nickname'=>$nickname);
		$info[0] = "{\"email\":\"$email\", \"userid\":\"$userid\", \"nickname\":\"$nickname\"}";
		$has = true;
}
if (!$has) {//邮箱错，返回
	//$info[0] = "{\"email\":\"EMPTY\", \"userid\":\"$todo\", \"nickname\":\"BLANK\"}";
	echo ('{"data":['.implode(",",$info).']}');
	$query_prepare->colse();//关闭 stmt
	return;
}

//$query_prepare->colse();//关闭 stmt

	/* 发邮件 */
	$smtpserver = SMTP_SERVER_URL;//SMTP服务器
	$smtpserverport =SMTP_SERVER_PORT;//SMTP服务器端口
	$smtpusermail = SMTP_USER_MAIL;//SMTP服务器的用户邮箱
	$smtpuser = SMTP_USER;//SMTP服务器的用户帐号
	$smtppass = SMTP_PASS;//SMTP服务器的用户密码
	$mailtype = MAIL_TYPE;//邮件格式（HTML/TXT）,TXT为文本邮件

	
	$smtpemailto = $email; //$_POST['toemail'];//发送给谁 334669@qq.com
	$mailtitle = "找回密码";//邮件主题
	$time = time();
	$date = date("Y年m月d日 H时i分", $time);

	$mailcontent = "尊敬的用户" . $nickname . "：<br/>您好！<br/>您在 " . $date ." 提交找回密码请求，请点击下面的链接修改用户". $email ."的密码。<br/>";//邮件内容
	$date = date("YmdHi", $time);
	$randCharObj = new RandChar();
	$str = $randCharObj->getRandChar(20);
	
	$mailcontent .= HOST_URL . "/" . "changemypassword-2.php?remember=". $str ."<br/>";
	$mailcontent .= "(如果您无法点击这个链接，请将此链接复制到浏览器地址栏后访问)". "<br/>";
	$mailcontent .= "为了保证您帐号的安全性，该链接有效期为24小时，并且点击一次后将失效!". "<br/>";
	$mailcontent .= "设置并牢记密码保护问题将更好地保障您的帐号安全。". "<br/>";
	$mailcontent .= "如果您误收到此电子邮件，则可能是其他用户在尝试帐号设置时的误操作，如果您并未发起该请求，则无需再进行任何操作，并可以放心地忽略此电子邮件。". "<br/>";
	$mailcontent .= "请勿直接回复本邮件。". "<br/>";
	$mailcontent .= "感谢您使用本系统". "<br/>";
	$mailcontent .= "<p align='right'>虚拟实验工场服务中心</p>". "<br/>";
	$mailcontent .= "<p align='right'>2016/9/5</p>". "<br/>";
	//$mailcontent .= "dddddddddddddddddddddddd". "<br/>";
	
	
	//************************ 配置信息 ****************************
	$smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug = false;//是否显示发送的调试信息
	$state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
	$info[1] = "{\"state\":\"$state\"}";
	echo ('{"data":['.implode(",",$info).']}');
	if($state == ""){//邮件发送失败，退出
		return;
	}
	//邮件发送成功则更新 user 表中的 remember_token 字段为 randCharObj
	$splictor = "!";
	$str .= $splictor . $date. $splictor. $userid;//用 惊叹号，将随机数、时间戳、用户 id 穿起来
	$SQL = "update users set remember_token = '". $str ."' where id = " . $userid;
	//echo "ddddddd=" . $SQL;
	$dsql = new DSQL();
	$dsql->query($SQL);

//	echo "<div style='width:300px; margin:36px auto;'>";
/*	if($state==""){
		echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
		echo "<a href='index.html'>点此返回</a>";
		exit();
	}
	echo "恭喜！邮件发送成功！！";
	echo "<a href='index.html'>点此返回</a>";
	echo "</div>";*/


?>