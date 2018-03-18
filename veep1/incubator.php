<?php
require_once("config/config.php");
require_once("header2.php");
?>
<!--中间内容-->
	<div id="middle" style="height: 200px">
		<p style="line-height: 24px;background: rgb(255, 255, 255)">
			<span style=";font-family:仿宋;font-size:20px"><span style=";font-family:仿宋;font-size:20px">　　孵化器是为用户的个性化需求提供的接口规范。当虚拟实验工场提供的实验库不能完全覆盖您关注的课程时，您可以通过两种方式为自己的课程创建实验：<br/>
					　　一是按照孵化器的接口规范自行设计开发自己个性化的实验；<br/>
					　　二是给出需求，虚拟实验工场将为您提供全方位技术支持和服务支持。这两种方式所孵化的虚拟实验均可在虚拟实验工场发布。<br/>
			</span>
 
　　		</span>
		</p>
		<p style="line-height: 24px;background: rgb(255, 255, 255)">

			<br/>
			<br/>
			<br/>
		</p


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
		<script type="text/javascript">
			$('.tm-ul li').removeClass('zhuye').eq(4).addClass('zhuye');
		</script>
<?php
	include("footer.php");
?>