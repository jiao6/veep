<?
// require_once("header.php");
/*
require_once 'config/ValidateCode.class.php';

session_start();
$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();
$_SESSION['authnum_session'] = $_vc->getCode();//验证码保存到SESSION中

*/
//session_destroy();
session_start();
//在页首先要开启session,
//将session去掉，以每次都能取新的session值;
//用seesion 效果不错，也很方便


?>
<?
require_once("config/config.php");

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="keywords" content="虚拟实验工场,虚拟实验,实验工场,北京理工大学,虚拟仿真实验教学中心"/>
    <meta name="description" content="虚拟实验,实验工场,北京理工大学,虚拟仿真实验教学中心"/>

    <title>虚拟实验工场</title>
    <!-- <link rel="stylesheet" href="pintuer/pintuer.css"> -->
    <!-- <link rel="stylesheet" href="css/admin.css"> -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- <script src="pintuer/jquery.js"></script> -->
    <!-- <script src="pintuer/pintuer.js"></script> -->
    <script src="pintuer/respond.js"></script>
    <script src="js/admin.js"></script>
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/other.css">
    <!-- <link rel="stylesheet" type="text/css" href="css/table.css"> -->
    <link rel="icon" href="img/vew.png" type="image/x-icon">

    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            // hm.src = "//hm.baidu.com/hm.js?e63b49bba5605031e467ee9cb52fb0ea";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <link rel="icon" href="img/vew.png" type="image/x-icon">
    <style type="text/css">
            .admin {
                top: 84px;
            }
            .logo {
                padding: 7px 10px;
                border: 0;
            }
        </style>
</head>
<body style="background: #fafafa;font-family: 'Microsoft Yahei'">
<div id="top">
    <div class="logo">
        <a href="" target="_blank"><img src="img/beijing.png" alt="后台管理系统" style="width: 166px" /></a>
        <a href="" target="_blank"><img src="img/xuni.png" alt="后台管理系统" style="width: 166px;float: right;" /></a>
    </div>
</div>
<div class="contain lg" style="height:480px">
  <div class="window" style="height:470px">
    <form name="loginForm" action="login_action.php" class="rgform2" method="post">
      <div class="h1">登录</div>
      <br>
      <p>
        <input id="email" name="email" class="txt" type="text" placeholder="请输入邮箱" maxlength="30"  datatype="*" errormsg="请输入邮箱"  nullmsg="请输入邮箱" />

      </p>
      <p>

        <input id="passwd" name="passwd" class="psw" type="password" placeholder="请输入密码" maxlength="30"   />
      </p>
      <p>
        <input id="verifycode" name="verifycode" class="txt" style="width:200px;*margin-left: -20px" type="text" placeholder="请输入验证码"  nullmsg="请输入验证码" datatype="*" maxlength="4" />
        <img id="checkpic" title="点击刷新" onclick="changing();" src='captcha.php' height="30" align="middle" style="margin-top: -12px" /><a  onclick="changing();">&nbsp;&nbsp;刷新</a>
      </p>

      <p>
      <input type="submit" class="btn" value="登录"  ></p>
      <div class="form_btm">
        <div class="fb_lf"><small>忘记密码？</small><a href="findpassword.php" ><small>找回</small></a> </div>
        <div class="fb_rt"><small>还没注册？</small><a onClick="userReg();" style="cursor: pointer;"><small>注册</small></a><br/><small>学生登录?</small><a href="finduniversity.php" ><small>查学校编号</small></a></div>
      </div>
    </form>

  </div>

</div>


<script type="text/javascript" src="js/validform.js"></script>

<script type="text/javascript">
  $(function(){
    $(".rgform2").Validform({
      tiptype:1
    });
  })
</script>
<script type="text/javascript">
  function changing(){
     document.getElementById('checkpic').src="captcha.php?"+Math.random();
  }
  function userReg(){
      window.location = "register.php" ;
  }
</script>
<?
include("footer.php");
?>

