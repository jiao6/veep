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
if ($ac == "editupdate") {

    if($rand!=$_SESSION["findpasswordrand"] ){
        exit;
    }else{

        $_SESSION["findpasswordrand"]  = "1";
    }



    $SQL = "SELECT * FROM users where email = '$email' and id != $auth_id ";
    $dsql->query($SQL);
    if ($dsql->next_record()) {
        echo "<br><script>alert('邮箱已经存在');history.go(-1)</script>\n";
        exit;
    } else {

        $SQL = "update users set " .
            "updated_at = now(),    updator_id = $auth_id" .
            "  where id = $auth_id   ";
        //echo $SQL;// 6 个参数
        $pstmt = new DSQL();
        $query_prepare_2 = $pstmt->getPstmt($SQL);
        //$query_prepare_2->bind_param("s",$email );
        $succ = $query_prepare_2->execute();

        if ($succ) {
            echo "<br><script>alert('修改成功,请注意查收邮件');window.location='studentuseredit.php'</script>\n";

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

            $md5=md5($email.$auth_id.$time.SMTP_PASS);

            $mailcontent .= HOST_URL . "/" . "activeemail.php?t=${time}&i=$auth_id&e=$email&tk=$md5<br/>";
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





        } else {
            echo "<br><script>alert('修改失败!');window.history.go(-1)</script>\n";
        }
    }


}
        $SQL = "select * from users where id = $auth_id  ";
        //echo $SQL;
        $dsql->query($SQL);

        if ($dsql->next_record()) {
            $id = $dsql->f('id');
            $nickname = $dsql->f('nickname');
            $email = $dsql->f('email');
            $password = $dsql->f('password');
            $university = $dsql->f('university');
            $universityId = $dsql->f('university_id');
            $college = $dsql->f('college');
            $phonenumber = $dsql->f('phonenumber');
            $created_at = $dsql->f('created_at');
            $updated_at = $dsql->f('updated_at');
            $pwd = $dsql->f('pwd');
            $usertype = $dsql->f('usertype');
            $truename = $dsql->f('truename');
            $remember_token = $dsql->f('remeusertype');
            $feeuserid = $dsql->f('feeuserid');
            $isfee = $dsql->f('isfee');
            $paytime = $dsql->f('paytime');
            $endtime = $dsql->f('endtime');
            $payquantity = $dsql->f('payquantity');
            $used = $dsql->f('used');
            $rand=rand(10000,99999);
            $_SESSION["findpasswordrand"] = "$rand";//保存id
            ?>
<div class="contain lg" style="height:480px">
  <div class="window" style="height:470px">
    <form name="loginForm" action="studentuseredit.php" class="rgform2" method="post">
      <div class="h1">修改电子邮箱并验证邮箱</div>
      <br>
        <p>
            <br/>
        </p>
      <p>
        <input id="email" name="email" class="txt" type="text" maxlength="30"    value="<? print($email) ?>" placeholder="请输入常用电子邮箱。请慎重填写" ajaxurl="uservalid.php?ac=ajax" datatype="e" errormsg="请输入正确邮箱" nullmsg="请输入常用邮箱" />

      </p>

        <p>
           <br/>
        </p>

      <p>
        <INPUT TYPE="hidden" name='id' value="<? print($id) ?>"><INPUT TYPE="hidden" name='ac' value="editupdate">
                        <input id="rand" name="rand" class="txt" type="hidden" placeholder="邮箱" value="<?print($rand)?>" maxlength="33">
      <input type="submit" class="btn" value="修改邮箱并验证邮箱"  ></p>

    </form>

  </div>

</div>



            <script type="text/javascript" src="js/validform.js"></script>
            <script type="text/javascript">
                $(function(){
                    $(".rgform2").Validform({
                        tiptype:3,

                        showAllError:true,
                        postonce:true

                    });
                })
            </script>

            <?
        }



include("footer.php");

?>


