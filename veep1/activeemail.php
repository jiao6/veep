<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");

require_once("config/email.class.php");
require_once("config/RandChar.php");
require_once("header.php");

$dsql = new DSQL();


$md5=md5($e.$i.$t.SMTP_PASS);

$time = time()-$t;



//echo "$md5!=$tk::$time";

if($md5!=$tk){
    $time=time();

    echo "<br><script>alert('链接不存在');window.location='index.php'</script>\n";
}else if($time >86400){

    echo "<br><script>alert('超过 24 小时，您的链接已过期！');window.location='index.php'</script>\n";
}else
if ($i>0) {
    $SQL = "SELECT * FROM users where  id= $i ";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        $email = $dsql->f('email');
        $password = $dsql->f('password');
        $university = $dsql->f('university');
        $universityId = $dsql->f('university_id');
        $college = $dsql->f('college');
        $phonenumber = $dsql->f('phonenumber');
        $created_at = $dsql->f('created_at');
        $updated_at = $dsql->f('updated_at');
        $ischeckemail = $dsql->f('ischeckemail');
        $usertype = $dsql->f('usertype');
        $truename = $dsql->f('truename');
        if($ischeckemail){
            echo "<br><script>alert('邮件已经激活!');window.location='index.php'</script>\n";
            exit;
        }

        $SQL = "update users set ischeckemail=1,email=?  where id = ?   ";
        //echo $SQL;// 6 个参数
        $pstmt = new DSQL();
        $query_prepare_2 = $pstmt->getPstmt($SQL);
        $query_prepare_2->bind_param("si",$e, $i );
        $succ = $query_prepare_2->execute();

        //echo "succ::$succ";
        if ($succ) {

            echo "<br><script>alert('激活成功');window.location='index.php'</script>\n";
            //发送验证邮件
            /* 发邮件 */
            $smtpserver = SMTP_SERVER_URL;//SMTP服务器
            $smtpserverport =SMTP_SERVER_PORT;//SMTP服务器端口
            $smtpusermail = SMTP_USER_MAIL;//SMTP服务器的用户邮箱
            $smtpuser = SMTP_USER;//SMTP服务器的用户帐号
            $smtppass = SMTP_PASS;//SMTP服务器的用户密码
            $mailtype = MAIL_TYPE;//邮件格式（HTML/TXT）,TXT为文本邮件


            $smtpemailto = $email; //$_POST['toemail'];//发送给谁 334669@qq.com
            $mailtitle = "邮箱验证通过";//邮件主题

            $date = date("Y年m月d日 H时i分", $time);

            $mailcontent = "尊敬的用户" . $truename . "：<br/>您好！<br/>您的邮箱已经验证通过，现在您将免费享受以下服务：<br/><br/>";//邮件内容
            $mailcontent .= "邮箱账号登录<br/>";
            $mailcontent .= "您可以用邮箱${email}直接登录虚拟实验工场网站<br/><br/>";
            $mailcontent .= "邮件服务<br/>";

            $mailcontent .= "个性资讯，精彩课程邮件服务。<br/><br/>";
            $mailcontent .= "账号安全保障<br/>";
            $mailcontent .= "绑定邮箱找回密码，账号更安全。<br/>";
            $mailcontent .= "<p align='right'>虚拟实验工场服务中心</p>". "<br/>";
            $mailcontent .= "<p align='right'>$date</p>". "<br/>";
            //$mailcontent .= "dddddddddddddddddddddddd". "<br/>";


            //************************ 配置信息 ****************************
            $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
            $smtp->debug = false;//是否显示发送的调试信息
            $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
            //echo "$state $smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype";



        } else {
            echo "<br><script>alert('激活失败!');window.location='index.php'</script>\n";
        }
    }


}




include("footer.php");
include("bubbleUniversity.php");
?>


