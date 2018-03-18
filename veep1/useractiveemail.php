<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");

require_once("config/email.class.php");
require_once("config/RandChar.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");

$dsql = new DSQL();
if ($ac == "active") {

    if($rand!=$_SESSION["findpasswordrand"] ){
        exit;
    }else{

        $_SESSION["findpasswordrand"]  = "1";
    }



    $SQL = "SELECT * FROM users where   id  = $auth_id ";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        $email = $dsql->f('email');
        $truename = $dsql->f('truename');

            echo "<br><script>alert('验证邮件发送成功');window.location='index.php'</script>\n";

            //发送验证邮件
            /* 发邮件 */
            $smtpserver = SMTP_SERVER_URL;//SMTP服务器
            $smtpserverport =SMTP_SERVER_PORT;//SMTP服务器端口
            $smtpusermail = SMTP_USER_MAIL;//SMTP服务器的用户邮箱
            $smtpuser = SMTP_USER;//SMTP服务器的用户帐号
            $smtppass = SMTP_PASS;//SMTP服务器的用户密码
            $mailtype = MAIL_TYPE;//邮件格式（HTML/TXT）,TXT为文本邮件


            $smtpemailto = $email; //$_POST['toemail'];//发送给谁 334669@qq.com
            $mailtitle = "邮箱验证";//邮件主题
            $time = time();
            $date = date("Y年m月d日 H时i分", $time);

            $mailcontent = "尊敬的用户" . $truename . "：<br/>您好！<br/>为了保障您帐号的安全性，请点击下面的链接激活邮箱：<br/>";//邮件内容

            $md5=md5($auth_id.$time.SMTP_PASS);

            $mailcontent .= HOST_URL . "/" . "activeemail.php?t=${time}&i=$auth_id&tk=$md5<br/>";
            $mailcontent .= "(如果您无法点击这个链接，请将此链接复制到浏览器地址栏后访问)". "<br/>";
            $mailcontent .= "为了保证您帐号的安全性，该链接有效期为24小时，并且点击一次后将失效!". "<br/>";

            $mailcontent .= "如果您误收到此电子邮件，则可能是其他用户在尝试帐号设置时的误操作，如果您并未发起该请求，则无需再进行任何操作，并可以放心地忽略此电子邮件。". "<br/>";
            $mailcontent .= "请勿直接回复本邮件。". "<br/>";
            $mailcontent .= "感谢您使用本系统". "<br/>";
            $mailcontent .= "<p align='right'>虚拟实验工场服务中心</p>". "<br/>";
            $mailcontent .= "<p align='right'>$date</p>". "<br/>";
            //$mailcontent .= "dddddddddddddddddddddddd". "<br/>";


            //************************ 配置信息 ****************************
            $smtp = new smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
            $smtp->debug = false;//是否显示发送的调试信息
            $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
            //echo "$state $smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype";



            echo "<br><script>alert('验证邮件发送成功');window.location='index.php'</script>\n";

        } else {
            echo "<br><script>alert('验证邮件发送失败!');window.history.go(-1)</script>\n";
        }


exit;
}

$rand=rand(10000,99999);
$_SESSION["findpasswordrand"] = "$rand";//保存id


      ?>

            <script type="text/javascript" language="javascript" src="js/laydate.js">
            </script>
            <div id="admin-nav">
   			 	<div>
        			<ul class="nav admin-nav" style="height: 0;">
            			<li class="active">
                			<ul class="nav nav-inline admin-nav">
                    		<?
                   		 	include("menu.php");
                   			?>
               				</ul>
            		</li>
       			 </ul>
    			</div>
   			 	<div class="admin">
                    <div class="rhead">
                        <div class="rhead1">验证邮箱，享受更多优质服务</div>
                    </div>
                    <div class="rfg"></div>
                    <form  class="rgform form-x"  method="post">
                        <input id="rand" name="rand" class="txt button bg-main" type="hidden" value="<?print($rand)?>" maxlength="33">
                        <INPUT TYPE="hidden" name='id' value="<? print($id) ?>"><INPUT TYPE="hidden" name='ac' value="active">
                        <div><input type="submit" id="submit" name="submit" class="btn2  button bg-main" value="验证邮箱"></div>
                        <br/><br/><br/>



                        <div><input type="button" onclick="window.location='index.php'" class="btn2  button bg-main" value="跳过(不验证)"></div>
                    </form>


            <?




include("footer.php");
include("bubbleUniversity.php");
?>


