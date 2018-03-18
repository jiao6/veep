<?php
require_once("config/config.php");
 
?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<meta name="keywords" content="虚拟实验工场，虚拟实验，实验工场，北京理工大学，虚拟仿真实验教学中心" />
	<meta name="description" content="虚拟实验，实验工场，北京理工大学，虚拟仿真实验教学中心" />

	<title>虚拟实验工场</title>

	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="icon" href="img/vew.png" type="image/x-icon">
	
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
        .tm-rt-d ul li ul {
            background: #1C7A80;
            position: absolute;
            top: 50px;
            width: 100px;
            z-index: 1000;
            left: -10px;
            text-indent: 20px;
        }
        .tm-rt-d ul li ul li {
            transition: .3s;
            height: 40px;
            line-height: 40px;
            z-index: 1000;
        }
        .tm-rt-d ul li ul li:hover {
            background: #1E8997;
        }
        .user1 {
            background: url('img/1.png') no-repeat right;
            padding-right: 10px;
        }
        .user1:hover {
            background: url('img/2.png') no-repeat right;
            padding-right: 10px;
        }
    </style>
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
					<li>素材库</li>
					<li>孵化器</li>
					<li><a href="mooc.php">mooc课程</a></li>
				</ul>
				<?php
				if($auth=='login' ){
					?>
					<div class="tm-rt-d">
						<ul>
							<li class="user1"><?echo $auth_username?>
								<ul class="user1_ul">
									<?php
									include("menu.php")
									?>
									<li><a href="logout.php"> 退出 </a></li>
								</ul>
							</li>
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
					<form action="courses.php">
						<input type="text" name="search" value=''>
					</form>	
				</div>
			</div>	
		</div>
		<div class="top_btm">
			<div id="banner">	
				<div id="banner_bg"></div>
				<a href="#" id="banner_info" style="display: none;"></a>
				<ul id="list"></ul>
				<div id="banner_list">
					<a href="#"><img src="img/banner_1.png" width="1200px"/></a>
					<a href="#"><img src="img/banner_2.png" width="1200px"/></a>
					<a href="#"><img src="img/banner_3.png" width="1200px"/></a>
					<a href="#"><img src="img/banner_4.png" width="1200px"/></a>
				</div>
			</div>
		</div>
	</div>
<!--中间内容-->
	<div id="middle" style="height: 800px"><br><br>

		虚拟实验运行环境需求：<br>
		Windows XP/Vista/7/8/10 Internet Explorer、Firefox、Safari、Opera浏览器。<br><br>
		实验运行需浏览器插件支持（<a href="http://veep.chinacloudapp.cn/pub/UnityWebPlayer.exe">下载地址1</a>，<a href="http://vrlab-other.stor.sinaapp.com/UnityWebPlayer.exe" >下载地址2</a>），安装需关闭浏览器，只需安装一次，之后均可使用。
		 


<!--虚拟课程展示以下-->
<script src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript"> 
// $(function(){
// 	new SwapTab(".SwapTab","span",".tab-content","ul","fb");
// 	new SwapTab(".SwapTab","span",".tab-content1","ul","fb");
// })
$(function(){
	$('.midbtm1 ul li img').hover(function(){
		$(this).animate({
			'width': '415px',
			'height': '233px',
			'margin-left': '-10px',
			'cursor': 'pointer'
		},300)
	},function(){
		$(this).animate({
			'width': '382px',
			'height': '214px',
			'margin-left': '0'
		},300)
	});
});
</script>
<script type="text/javascript">
	function addEvent(obj,evtType,func,cap){
		cap=cap||false;
		if(obj.addEventListener){
		 	obj.addEventListener(evtType,func,cap);
		 	return true;
		}else if(obj.attachEvent){
			if(cap){
			 	obj.setCapture();
		 		return true;
		 	}else{
				return obj.attachEvent("on" + evtType,func);
		 	}
		}else{
	 		return false;
		}
	}
	function getPageScroll(){
		var xScroll,yScroll;
		if (self.pageXOffset) {
			 xScroll = self.pageXOffset;
		} else if (document.documentElement && document.documentElement.scrollLeft){
			 xScroll = document.documentElement.scrollLeft;
		} else if (document.body) {
			 xScroll = document.body.scrollLeft;
		}
		if (self.pageYOffset) {
			 yScroll = self.pageYOffset;
		} else if (document.documentElement && document.documentElement.scrollTop){
			 yScroll = document.documentElement.scrollTop;
		} else if (document.body) {
			 yScroll = document.body.scrollTop;
		}
		arrayPageScroll = new Array(xScroll,yScroll);
		return arrayPageScroll;
	}
	function GetPageSize(){
		var xScroll, yScroll;
		if (window.innerHeight && window.scrollMaxY) { 
				xScroll = document.body.scrollWidth;
				yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){
				xScroll = document.body.scrollWidth;
				yScroll = document.body.scrollHeight;
		} else {
				xScroll = document.body.offsetWidth;
				yScroll = document.body.offsetHeight;
		}
		var windowWidth, windowHeight;
		if (self.innerHeight) {
				windowWidth = self.innerWidth;
				windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) {
				windowWidth = document.documentElement.clientWidth;
				windowHeight = document.documentElement.clientHeight;
		} else if (document.body) {
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
		} 
		if(yScroll < windowHeight){
				pageHeight = windowHeight;
		} else { 
				pageHeight = yScroll;
		}
		if(xScroll < windowWidth){ 
				pageWidth = windowWidth;
		} else {
				pageWidth = xScroll;
		}
		arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
		return arrayPageSize;
	}

	var AdMoveConfig=new Object();
	AdMoveConfig.IsInitialized=false;
	AdMoveConfig.ScrollX=0;
	AdMoveConfig.ScrollY=0;
	AdMoveConfig.MoveWidth=0;
	AdMoveConfig.MoveHeight=0;
	AdMoveConfig.Resize=function(){
			var winsize=GetPageSize();
			AdMoveConfig.MoveWidth=winsize[2];
			AdMoveConfig.MoveHeight=winsize[3];
			AdMoveConfig.Scroll();
	}
	AdMoveConfig.Scroll=function(){
			var winscroll=getPageScroll();
			AdMoveConfig.ScrollX=winscroll[0];
			AdMoveConfig.ScrollY=winscroll[1];
	}
	addEvent(window,"resize",AdMoveConfig.Resize);
	addEvent(window,"scroll",AdMoveConfig.Scroll);
	function AdMove(id){
		if(!AdMoveConfig.IsInitialized){
				AdMoveConfig.Resize();
				AdMoveConfig.IsInitialized=true;
		}
		var obj=document.getElementById(id);
		obj.style.position="absolute";
		var W=AdMoveConfig.MoveWidth-obj.offsetWidth;
		var H=AdMoveConfig.MoveHeight-obj.offsetHeight;
		var x = W*Math.random(),y = H*Math.random();
		var rad=(Math.random()+1)*Math.PI/6;
		var kx=Math.sin(rad),ky=Math.cos(rad);
		var dirx = (Math.random()<0.5?1:-1), diry = (Math.random()<0.5?1:-1);
		var step = 1;
		var interval;
		this.SetLocation=function(vx,vy){x=vx;y=vy;}
		this.SetDirection=function(vx,vy){dirx=vx;diry=vy;}
		obj.CustomMethod=function(){
				obj.style.left = (x + AdMoveConfig.ScrollX) + "px";
				obj.style.top = (y + AdMoveConfig.ScrollY) + "px";
				rad=(Math.random()+1)*Math.PI/6;
				W=AdMoveConfig.MoveWidth-obj.offsetWidth;
				H=AdMoveConfig.MoveHeight-obj.offsetHeight;
				x = x + step*kx*dirx;
				if (x < 0){dirx = 1;x = 0;kx=Math.sin(rad);ky=Math.cos(rad);} 
				if (x > W){dirx = -1;x = W;kx=Math.sin(rad);ky=Math.cos(rad);}
				y = y + step*ky*diry;
				if (y < 0){diry = 1;y = 0;kx=Math.sin(rad);ky=Math.cos(rad);} 
				if (y > H){diry = -1;y = H;kx=Math.sin(rad);ky=Math.cos(rad);}
		}
		this.Run=function(){
				var delay = 10;
				interval=setInterval(obj.CustomMethod,delay);
				obj.onmouseover=function(){clearInterval(interval);}
				obj.onmouseout=function(){interval=setInterval(obj.CustomMethod, delay);}
		}
	}
	function hideDiv(ads){
		var d = document.getElementById(ads);
		d.style.display = "none";
	}
</script>
<?php
	include("footer.php");
?>