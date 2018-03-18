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
    <link rel="stylesheet" href="pintuer/pintuer.css">
    <link rel="stylesheet" href="css/admin.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- <script src="pintuer/jquery.js"></script> -->
    <script src="pintuer/pintuer.js"></script>
    <script src="pintuer/respond.js"></script>
    <script src="js/admin.js"></script>
    <link rel="stylesheet" type="text/css" href="css/latest.css">
    <!-- <link rel="stylesheet" type="text/css" href="css/header.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="css/other.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="css/table.css"> -->
    <link rel="icon" href="img/vew.png" type="image/x-icon">

    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
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
<!--头部到幻灯片-->
<!-- <div id="top"> -->
    <div class="logo">
        <a href="" target="_blank"><img src="img/beijing.png" alt="后台管理系统" style="width: 166px" /></a>
        <a href="" target="_blank"><img src="img/xuni.png" alt="后台管理系统" style="width: 166px;float: right;" /></a>
    </div>
    <div class="righter nav-navicon" id="admin-nav" style="padding-top: 0"><!-- padding-top: 24px; -->
            <div class="mainer">
                <div class="admin-navbar" style="border: 0;">
                    <span class="float-right" style="margin-top: 5px;">
                    <a class="button button-little bg-main" href="index.php" target="_blank">前台首页</a>
                    <a class="button button-little bg-yellow" href="logout.php">注销登录</a>
                </span>
                    </ul>
                </div>
                <div class="admin-bread" style="border: 0;">
                    <?php
                        if($auth=='login' ){
                            $username = $auth_username;
                            if(strlen($auth_username)>9){
                                $username =mb_substr($auth_username, 0, 3, 'utf-8')."..";
                            }
                            if(strlen($auth_username)<6){
                                $username ="　　".$auth_username;
                            }

                            ?><span>您好，<?echo $username?>，欢迎您的光临。</span>
                            <?php
                        }else{
                            ?>
                            <div class="tm-rt-d">
                                <a href="login.php">登录</a>
                                <span class='shu'>|</span>
                                <a href="register.php">注册</a>
                            </div>
                            <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    <div class="top_mid">
        <div class="top_midin">
            <?php
            if ($auth == 'login') {
                $username = $auth_username;
                if (strlen($auth_username) > 9) {
                    $username = mb_substr($auth_username, 0, 3, 'utf-8') . "..";
                }
                if (strlen($auth_username) < 6) {
                    $username = "　　" . $auth_username;
                }
}
                ?>
                
           
        </div>
    </div>
<!-- </div> -->
<script type="text/javascript">
    $('.user1').click(function (e) {
        $('.user1_ul').toggle();
        $(this).toggleClass('btm');
        e.stopPropagation();
    });
    $('body').click(function () {
        $('.user1_ul').css('display', 'none');
    });
</script>