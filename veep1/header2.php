<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="keywords" content="虚拟实验工场，虚拟实验，实验工场，北京理工大学，虚拟仿真实验教学中心" />
    <meta name="description" content="虚拟实验，实验工场，北京理工大学，虚拟仿真实验教学中心" />
    <title>虚拟实验工场</title>
    <link rel="stylesheet" type="text/css" href="css/header.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="icon" href="img/vew.png" type="image/x-icon">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script src="js/babyzone.js"></script>
    <script type="text/javascript" language="javascript">
        window.onload = function(){
            babyzone.scroll(4,"banner_list","list","banner_info");
        }
    </script>
    <style type="text/css">
        .user1_ul {
            display: none;
            position: relative;
            z-index: 100;
        }
        .user1:hover .user1_ul {
            display: block;
        }
        .user1_ul {
            background: #1C7A80;
            position: absolute;
            top: 50px;
            width: 100px;
            z-index: 1000;
            /*left: -10px;*/
            text-indent: 20px;
            right: -10px;
        }
        .user1_ul li {
            transition: .3s;
            height: 40px;
            line-height: 40px;
            z-index: 1000;
        }
        .user1_ul li:hover {
            background: #1E8997;
        }
        .user1 {
            padding-right: 10px;
            background: url('img/1.png') no-repeat right;
        }
        .user1:hover {
            cursor: pointer;
            padding-right: 10px;
        }
        .btm {
            background: url('img/2.png') no-repeat right;
        }
        #top,.top_midin,#banner,#banner_list img,.top_btm,.top_head,#middle,.midtop,.midbtm,.sykc,.footer{
            width: 950px;
            margin: auto;
        }
        .padleft{
            padding: 0 15px !important;
        }
        .tm-rt-d{
            margin-right: 58px;
        }
        .midbtm1 ul li{
            width: 298px;
        }
        .midbtm1 .md_img{
            width: 298px;
            height: 167px;
        }
        .midbtm1 .md_img img{
            width: 298px;
            height: 167px;
        }
        .midbtm3 ul li{
            width: 218px;
        }
        .midbtm3 .md_img{
            width: 218px;
            height: 122px;
        }
        .midbtm3 .md_img img{
            width: 218px !important;
            height: 122px;
        }
        .infoname a{
            font-size: 13px;
        }
        #list{
            top: 170px;
            margin-left: 420px !important;
        }
        .btnmit{
            width: 30px;
            height: 27px;
            background: url("img/search.png") #fff no-repeat 3px 1px;
            position: relative;
            z-index: 1 !important;
            margin-top: 12px;
            border: 0;
            *height: 30px;
            *float: left;
            *margin-top: 10px;
        }
		/*161028*/
        .ul_lists li {
            float: left;
            width: 218px;
            background: #fff;
            margin: 9px 13px;
        }
        .ul_lists .md_img {
            width: 100px;
            height: 100px;
            border-radius: 100px;
            float: left;
            box-shadow: none;
        }
        .ul_lists .md_img img {
            width: 100px;
            height: 100px;
            border-radius: 100px;
        }
        .ul_lists li.marleft {
            margin-left: 0;
        }
        .ul_lists li.marright {
            margin-right: 0;
        }
        .ul_lists .midinfo {
            margin-left: 110px;
        }
        .ul_lists .zhiding {
            width: 950px;
            border-bottom: 1px solid #ccc;
            border-top: 1px solid #ccc;
        }
        .school_name {
            overflow:hidden;
            white-space:nowrap;
            text-overflow:ellipsis;
            -o-text-overflow:ellipsis
        }
    </style>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            // hm.src = "//hm.baidu.com/hm.js?e63b49bba5605031e467ee9cb52fb0ea";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

</head>
<body>
<div class="contain"></div>
<!--头部到幻灯片-->
<div id="top">
    <div class="top_head">
        <div class="beijing">
            <img src="img/beijing.png" height="40px">
        </div>
        <div class="xuni">
            <img src="img/xuni.png" width="160px">
        </div>
    </div>
    <div class="top_mid">
        <div class="top_midin">
            <ul class="tm-ul">
                <li class="zhuye marleft padleft"><a href="index.php">首页</a></li>
                <li><a href="courses.php">实验课程</a></li>
                <li><a href="experiments.php">实验库</a></li>
                <li><a href="scc.php">素材库</a></li>
                <li> <a href="incubator.php">孵化器</a></li>
                <li><a href="mooc.php">mooc课程</a></li>
            </ul>

            <?php
            if($auth=='login' ){
                $username = $auth_username;
                if(strlen($auth_username)>9){
                    $username =mb_substr($auth_username, 0, 3, 'utf-8')."..";
                }
                if(strlen($auth_username)<6){
                    $username ="　　".$auth_username;
                }

                ?>
                <div class="tm-rt-d" style="margin-right: 10px">
                    <span class="user1"><?echo $username?></span>
                            <ul class="user1_ul">
                                <?php
                                include("menu.php")
                                ?>
                                <li><a href="logout.php"> 退出 </a></li>
                            </ul>
                </div>
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


            <div class="search">
                <button type="button" name="submit" class="btnmit"  onclick="window.location='courses.php?search='+document.getElementById('header_search_id').value;return false;"></button>
                <input type="text" name="search" id="header_search_id" maxlength="20" onkeypress="if(event.keyCode==13) {window.location='courses.php?search='+document.getElementById('header_search_id').value;return false;}" value='' style="*float: right;">

            </div>
        </div>
    </div>
    <div class="top_btm">
        <div id="banner">
            <div id="banner_bg"></div>
            <a href="#" id="banner_info" style="display: none;"></a>
            <ul id="list"></ul>
            <div id="banner_list">
                <a href="#"><img src="img/banner_1.png"></a>
                <a href="#"><img src="img/banner_2.png"></a>
                <a href="#"><img src="img/banner_3.png"/></a>
                <a href="#"><img src="img/banner_4.png"/></a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.user1').click(function(e) {
        $('.user1_ul').toggle();
        $(this).toggleClass('btm');
        e.stopPropagation();
    });
    $('body').click(function(){
        $('.user1_ul').css('display','none');
    });
</script>