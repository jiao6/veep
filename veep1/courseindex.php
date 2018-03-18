<?
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("header.php");
?><?
error_reporting(0);

	if(@$auth!='login'){
					?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">


</head>
<body>
					<script>alert('请先登录');window.location="login.php"</script>
					<?
						exit;
				}
?>
	<div class="contain3 test">
		<div class="ts_top">
			<img src="img/btm1.jpg" height="200px">
			<div class="tst_info">
				<p class="tstname">大学计算机（示范课）</p>
				<p class="tstinfo">通过6个虚拟实验来揭示计算机外部结构、计算机内部原理、基于计算机求解实际问题等内容，从而实现计算思维的落地。</p>
				<p class="join"><a href="">加入我的课程</a></p>
				<style type="text/css">
					.join{
						width: 120px;
						height: 40px;
						text-align: center;
						line-height: 40px;
						background: #1C7A80;
						float: right;
						margin: 50px 120px 0 0;
						border-radius: 6px;
					}
					.join a{
						color: #fff;
						text-decoration: none;
					}
				</style>
			</div>
		</div>
		<div class="ts_btm">
			<div class="tsb_lt">
				<ul class="tab">
					<li style="margin-left: 0" value="3">虚拟实验</li>
					<li value="2">实验报告</li>
					<li value="1">实验讨论</li>
				</ul>
				<ul class="tab_tar">
					<li class="tabr3">
						<textarea class="ex_text" placeholder="说说你的想法吧..."></textarea>
						<a href="#"></a><div class="ext_div">发表评论</div>
						<div class="ext_tit"><div>评论列表</div></div>
						<ul class="ext_con">
							<div></div>
							<li>
								<div class="ext_uimg"><img src="/img/btm1.jpg"><div class="uname">六个字符以内</div></div>
								<img src="/img/4.png" class="trian">
								<div class="ext_text">计算机硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验</div>
								<div class="ext_info">
									<div>
										<span class="ext_fo"><span></span>楼</span>
										<span class="ext_time">2016-07-13</span>
										<a class="ext_re">回复<span></span></a>
									</div>									
								</div>
								<img class="trian2" src="/img/3.png">
								<div class="ext_app">
									<ul>
										<li>
											<div class="ext_re_u">
												<img src="/img/btm2.jpg">
												<a class="ext_re_n">学生1:</a>
												<span class="ext_re_txt">实与仿真计算学科方向带头人。现担任教育部高等学校“大学计算机课程”教学指导委员会副主任；全国高等院校计算机基础教育研究会副会长；</span>
											</div>
											<div class="ext_ap_info">
												<div>
													<span class="ext_ap_time">2016-07-13</span>
													<a class="ext_ap_re">回复</a>
												</div>								
											</div>
										</li>
										<li>
											<div class="ext_re_u">
												<img src="/img/btm6.png">
												<a class="ext_re_n">学生2:</a>
												<span class="ext_re_txt">汉字信息编码与转换字信息编码与转换模拟实字信息编码与转换模拟实字信息编码与转换模拟实字信息编码与转换模拟实字信息编码与转换模拟实字信息编码与转换模拟实字信息编码与转换模拟实模拟实验汉字信息编码与转换模拟实验汉字信息编码与转换模拟实验</span>
											</div>
											<div class="ext_ap_info">
												<div>
													<span class="ext_ap_time">2016-07-13</span>
													<a class="ext_ap_re">回复</a>
												</div>									
											</div>
										</li>
										<div class="app_re"></div>
									</ul>
								</div>
							</li>
							<li>
								<div class="ext_uimg"><img src="/img/btm3.jpg"><div class="uname">xxx</div></div>
								<img src="/img/4.png" class="trian">
								<div class="ext_text">验计算机硬件系统虚拟拆卸实验</div>
								<div class="ext_info">
									<div>
										<span class="ext_fo"><span></span>楼</span>
										<span class="ext_time">2016-07-13</span>
										<a class="ext_re">回复<span></span></a>
									</div>									
								</div>
								<img class="trian2" src="/img/3.png">
								<div class="ext_app">
									<ul>
										<div class="app_re"></div>
									</ul>
								</div>
							</li>
							<li>
								<div class="ext_uimg"><img src="/img/btm4.jpg"><div class="uname">x</div></div>
								<img src="/img/4.png" class="trian">
								<div class="ext_text">计算机汉字信息编码与转换模拟实验汉字信息编码与转换模拟实验硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验计算机硬件系统虚拟拆卸实验</div>
								<div class="ext_info">
									<div>
										<span class="ext_fo"><span></span>楼</span>
										<span class="ext_time">2016-07-13</span>
										<a class="ext_re">回复<span></span></a>
									</div>									
								</div>
								<img class="trian2" src="/img/3.png">
								<div class="ext_app">
									<ul>
										<div class="app_re"></div>
									</ul>
								</div>
							</li>
						</ul>
					</li>
					<li class="tabr2">
						<ul>
							<li>
								<div><h4><a href="coursereport.php?fileid=1">实验报告1 <span>计算机硬件系统虚拟拆卸实验</span></a></h4></div>
							</li>
							<li>
								<div><h4><a href="coursereport.php?fileid=2">实验报告2 <span>汉字信息编码与转换模拟实验</span></a></h4></div>
							</li>
							<li>
								<div><h4><a href="coursereport.php?fileid=3">实验报告3 <span>一条指令的执行过程实验</span></a></h4></div>
							</li>
							<li>
								<div><h4><a href="coursereport.php?fileid=4">实验报告4 <span>水箱水位的仿真计算实验</span></a></h4></div>
							</li>
							<li>
								<div><h4><a href="#">实验报告5 <span>邮件传输实验</span></a></h4></div>
							</li>
							<li>
								<div><h4><a href="#">实验报告6 <span>并行计算实验</span></a></h4></div>
							</li>
						</ul>
					</li>
					<li class="tabr1">
						<ul>
							<li>
								<div class="circle">1</div>
								<div class="chapter">
									<h4>实验一</h4>
									<p>虚拟拆机</p>
									<a class="btn btn-primary start_e" href="experimentplay.php?fileid=1&name=虚拟拆机">开始实验</a>
								</div>
							</li>
							<li>
								<div class="circle">2</div>
								<div class="chapter">
								<h4>实验二</h4>
									<p>汉字信息编码</p>
									<a class="btn btn-primary start_e" href="experimentplay.php?fileid=2&name=汉字信息编码">开始实验</a>
								</div>
							</li>
							<li>
								<div class="circle">3</div>
								<div class="chapter">
									<h4>实验三</h4>
									<p>一条指令的执行过程</p>
									<a class="btn btn-primary start_e" href="experimentplay.php?fileid=3&name=一条指令的执行过程">开始实验</a>
								</div>
							</li>
							<li>
								<div class="circle">4</div>
								<div class="chapter">
									<h4>实验四</h4>
									<p>水箱水位的仿真计算实验</p>
									<a class="btn btn-primary start_e" href="experimentplay.php?fileid=4&name=水箱水位的仿真计算实验">开始实验</a>
								</div>
							</li>
							<li>
								<div class="circle">5</div>
								<div class="chapter">
									<h4>实验五</h4>
									<p>邮件传输实验</p>
									<a class="btn btn-primary start_e" href="experimentplay.php?fileid=5&name=邮件传输实验">开始实验</a>
								</div>
							</li>
							<li>
								<div class="circle">6</div>
								<div class="chapter">
									<h4>实验六</h4>
									<p>并行计算实验</p>
									<a class="btn btn-primary start_e" href="#">开始实验</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="tsb_rt">
				<div class="tsbtit">授课老师</div>
				<img src="img/teach.png" width="84px">
				<div class="tsbname"><a href="">李凤霞</a></div>
				<div class="tsbinfo"> 北京理工大学计算机学院教授，北京市教学名师。任北京理工大学计算机学院基础教学部主任、教育部大学计算机虚拟仿真实验教学中心主任、 虚拟现实与仿真计算学科方向带头人。现担任教育部高等学校“大学计算机课程”教学指导委员会副主任；全国高等院校计算机基础教育研究会副会长；中国计算机学会虚拟现实与可视化专委会副主任。特聘为北京市教育委员会 “北京高等学校计算机与信息类专业群专家委员会委员兼教学协作委员会委员”。 目前是国家级精品课、国家级精品资源共享课程负责人、国家级优秀教学团队负责人、《C语言程序设计》和《大学计算机》MOOC在线课程主讲人。
				</div>
			</div>
		</div>
	</div>
	<div id="bottom">
		<div class="btm-btm" style="position: absolute;">
			<div class="about">
				<a href="">关于我们</a> |
				<a href="">产品中心</a> |
				<a href="">服务与支持</a> |
				<a href="">下载中心</a>
			</div>
			<div class="info">
				<p>北京理工大学</p>
				<p>地址： 北京海淀区中关村南大街5号  邮编：100081</p>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
	<script>
		$(function(){
			var heig2 = $(".tsb_rt").height();
			var reg = /\s+/;
			var tab = $(".tab li");
			var li = $(".tab_tar>li");
			
			chooseHeig();
			userre();
			Num();
			togg();		
			fooN();
			colorToggle($(tab),{"background":"#FAFAFA","color":"#1C7A80"},{"background":"#1C7A80","color":"#FAFAFA"})
			
			$(".tabr1").show().siblings().hide();
			$(tab).click(function(){
				var val = $(this).attr("value");
				$(li[val-1]).show().siblings().hide();
				chooseHeig($(li[val-1]).height());
			});

			$(".ext_div").click(function(){
				var texts = $(".ex_text").val();
				var time = getDay();
				t = texts.replace(reg,"");
				if(t == ""){
					alert("请输入字符！");
				} else {
					var html = "<li><div class='ext_uimg'><img src='/img/btm1.jpg'><div class='uname'>xxxx</div></div><img src='/img/4.png' class='trian'><div class='ext_text'>"+texts+"</div>"+"<div class='ext_info'><div><span class='ext_fo'><span></span>楼</span> <span class='ext_time'>" + time + "</span> <a class='ext_re'>回复<span></span></a></div></div>"+"<img class='trian2' src='/img/3.png'><div class='ext_app'><ul><div class='app_re'></div></ul></div>";
					$(".ext_con").prepend(html);
					$(".ex_text").val("");
					togg();
					fooN();
				}
				Num();
				chooseHeig();
			});

			function colorToggle(obj,a,b){
        var fir = $(obj).first();
        $(fir).css(a).siblings().css(b).hover(function(){
          $(this).css(a);
        },function(){
          $(this).css(b);
        });
        for(var i = 0;i < obj.length;i ++){
          $(obj[i]).click(function(){
            $(this).css(a).siblings().css(b).hover(function(){
              $(this).css(a);
            },function(){
              $(this).css(b);
            });
            $(this).hover(function(){
            	$(this).css(a);
            },function(){
            	$(this).css(a);
            });
          });
        }
      }
			function chooseHeig(){
				var heig1;
				for(var i = 0;i < li.length;i ++){
					if($(li[i]).css("display") == "list-item" || $(li[i]).css("display") == "block"){
						heig1 = $(li[i]).height();
					}
				}
				heig = heig1>heig2?heig1:heig2;
				if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion .split(";")[1].replace(/[ ]/g,"")=="MSIE7.0") {
					heig = heig - 450 + "px";
				}else{
					heig = heig + 60 + "px";
				}
				$("#bottom").css({"position":"relative","top":heig});
			}
			function Num(){
				var lis = $(".ext_con>li");
				for(var i = 0;i < lis.length;i ++){
					var app = $(lis[i]).find(".ext_re_u");
					var len = "("+ app.length +")";
					$(lis[i]).find(".ext_re>span").html(len);
				}
			}
			function fooN(){
				var foos = $(".ext_con>li");
				var foon = $(".ext_fo>span");
				for(var i = 0;i < foos.length;i ++){
					$(foon[foos.length-i-1]).html(i+1);
				}
				if(foos.length>1){
					var num = Math.ceil(foos.length/5);
					$(".tabr3 .pages").remove();
					$(".tabr3").append("<ul class='pages'><ul>");
					for(var i = 0;i < num;i ++){
						j = i +1;
						$(".pages").append("<li value='"+j+"'>"+j+"</li>");
					}
					var l = ($(".pages li").length + 1)*30 + "px";
					var pageli = $(".pages li");
					var linum = $(".ext_con>li");
					$(linum).hide();
					for(var i = 0;i < 5;i ++){
						$(linum[i]).show();
					}
					colorToggle($(pageli),{"background":"#1E8997","color":"#fff"},{"background":"#fff","color":"#1E8997"});
					for(var i = 0;i < pageli.length;i ++){
						$(pageli[i]).on("click",function(){
							var n = $(this).val()-1;
							console.log(n);
							$(linum).hide();
							for(var i = 5*n+1;i <= 5*(n+1);i ++){
								$(linum[i-1]).show();
							}
							chooseHeig();
						});
					}
					$(".pages").css("width",l);
					// chooseHeig();
				}
			}
			function togg(){
				$(".trian2").hide();
				$(".ext_app").hide();
				var ex = $(".ext_re");
				for (var i = 0;i < ex.length;i ++){
					$(ex[i]).unbind();	
					$(ex[i]).on("click",function(){
						$(this).parent().parent().siblings(".trian2").toggle();
						$(this).parent().parent().siblings(".ext_app").toggle();
						var a = $(this).parent().parent().siblings(".ext_app");
						if($(a).find(".app_re").html() == ""){
							$(a).find(".app_re").html("<textarea class='app_text'></textarea><div class='app_sub'>回复</div>");
						}
						apps();
						chooseHeig();
					});
				}
			}
			function apps(){
				var appsub = 	$(".app_sub");
				for(var i = 0;i < appsub.length;i ++){
					$(appsub[i]).unbind();
					$(appsub[i]).on("click",function(){
						var text1 = $(this).siblings(".app_text").val();
						var time1 = getDay();
						t = text1.replace(reg,"");
						if(t == ""){
							alert("请输入字符！");
						}else{
							var html1 = "<li><div class='ext_re_u'><img src='/img/btm2.jpg'><a class='ext_re_n'>学生1:</a><span class='ext_re_txt'>"+text1+"</span></div><div class='ext_ap_info'><div><span class='ext_ap_time'>"+time1+"</span> <a class='ext_ap_re'>回复</a></div></div></li>"
							$(this).parent().before(html1);
						}
						$(this).siblings(".app_text").val("");
						Num();
						userre();
						chooseHeig();
					});
				}
			}
			function userre(){
				var usersub = $(".ext_ap_re");
				for(var i = 0;i < usersub.length;i ++){
					$(usersub[i]).unbind();
					usersub.on("click",function(){
						var text2 = $(this).parent().parent().siblings(".ext_re_u").find(".ext_re_n").text();
						$(this).parent().parent().parent().siblings(".app_re").find(".app_text").val("回复"+text2);
					});
				}
			}
			function getDay(){
				var date = new Date();
				var year = date.getFullYear(),day = date.getDate();
				var mon = date.getMonth() +1;
				if(mon.toString.length <= 1){
					mon = "0" + mon;
				}
				var time = year + "-" + mon + "-" + day;
				return time;
			}
		});
	</script>
	</body>
	</html>