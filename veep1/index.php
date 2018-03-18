<?php
require_once("config/config.php");
require_once("config/dsql.php");
$dsql = new DSQL();
require_once ("config/MetaDataGenerator.php");
require_once ("config/Pagination.php");
require_once ("Classes/TeacherShower.php");
$teacherShower = new TeacherShower(0);
$STRING_MAX = 6;
require_once("header2.php");
?>
<!--中间内容-->
	<div id="middle">
		<div class="mid sykc" style="height: 395px">
			<div class="midtop">
				<span style="font-size: 20px;letter-spacing: 2px;">热门课程</span><span class="gengduo"><a href="courses.php">更多课程</a>></span>
			</div>
			<div class="midbtm midbtm1" style="height:290px">
				<ul>
					<?
					$homepage_courseid[0] = 114;
					$homepage_courseid[1] = 95;
					$homepage_courseid[2] = 98 ;

					$SQL = "SELECT  c.*,u.university ,u.truename  FROM courses c,users u  where c.useremail=u.email  and  (c.id=$homepage_courseid[0] or c.id = $homepage_courseid[1] or c.id = $homepage_courseid[2] )    ";
					//echo $SQL;
					$dsql->query($SQL);
					$homepage_course[0]="";
					while ($dsql->next_record()) {
						$id = $dsql->f('id');
						$name = $dsql->f('name');
						$coursesimg = $dsql->f('coursesimg');
						$university = $dsql->f('university');
						$truename = $dsql->f('truename');
						if(!file_exists($coursesimg)){
							$coursesimg="images/course.jpg";
						}
						if($homepage_courseid[0]==$id){
							$homepage_course[0]="<li class=\"marleft\">
									<div class=\"md_img\"><a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\"></a></div>
									<div class=\"midinfo\">
										<p class=\"infoname\"><a href=\"course.php?courseid=$id\">$name</a></p>
										<p><a href=\"course.php?courseid=$id\">$truename</a></p>
										<p><a href=\"course.php?courseid=$id\">$university</a></p>
									</div>
								</li>";

						}else if($homepage_courseid[1]==$id){
							$homepage_course[1]="<li>
									<div class=\"md_img\"><a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\"></a></div>
									<div class=\"midinfo\">
										<p class=\"infoname\"><a href=\"course.php?courseid=$id\">$name</a></p>
										<p><a href=\"course.php?courseid=$id\">$truename</a></p>
										<p><a href=\"course.php?courseid=$id\">$university</a></p>
									</div>
								</li>";

						}else if($homepage_courseid[2]==$id){
							$homepage_course[2]="<li class=\"marright\">
									<div class=\"md_img\"><a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\"></a></div>
									<div class=\"midinfo\">
										<p class=\"infoname\"><a href=\"course.php?courseid=$id\">$name</a></p>
										<p><a href=\"course.php?courseid=$id\">$truename</a></p>
										<p><a href=\"course.php?courseid=$id\">$university</a></p>
									</div>
								</li>";
						}
					}
					echo implode($homepage_course);
					?>
				</ul>
			</div>
		</div>
		<div class="mid sykc" style="height: 300px;">
			<div class="midtop">
				<span style="font-size: 20px;letter-spacing: 2px;">热门实验</span>
				<span class="gengduo"><a href="experiments.php">更多实验</a>></span>
			</div>
			<div class="midbtm midbtm3">
				<ul>
					<?
					$SQL = "SELECT  *  from experiments where  status=0   and  id< 5 ";
					//echo $SQL;
					$dsql->query($SQL);
					$i = 0;
					while ($dsql->next_record()) {
						$i++;
						$id = $dsql->f('id');
						$name = $dsql->f('name');
						$content = $dsql->f('content');
						$softfile = $dsql->f('softfile');
						$reportfile = $dsql->f('reportfile');
						$userid = $dsql->f('userid');
						$status = $dsql->f('status');
						$groupid = $dsql->f('groupid');
						$img = $dsql->f('img');
						$type = $dsql->f('type');
						$difficulty = $dsql->f('difficulty');

						if($i==1){
							$liclass = "class=\"marleft\"";


						}else if($i==4){
							$liclass = "class=\"marright\"";

						}else{
							$liclass="";
						}

						echo "<li $liclass >
								<div class=\"md_img\"><a href=\"experiments.php#$id\"><img src=\"$img\"></a></div>
								<div class=\"midinfo\">
									<p class=\"infoname\"><a href=\"experiments.php#$id\">$name</a></p>
								</div>
							</li>";


					}
					?>
				</ul>
			</div>
		</div>

				 <?
                $stickyTeacherList = $teacherShower->getStickyTeacherList(
                    MetaDataGenerator::STICKY_YES, 0, 6, 0);
                //echo "size=" . sizeof($stickyTeacherList);
                    if (sizeof($stickyTeacherList) > 0) {//置顶老师存在
                ?>
		<div class="" style="height: 300px;">
			<div class="midtop">
				<span style="font-size: 20px;letter-spacing: 2px;">热门老师</span>
				<span class="gengduo"><a href="teacherinfolist.php">更多老师</a>></span>
			</div>
			<div>
				<ul class="ul_lists">
                  <?
				  	$i = 0;
				  foreach($stickyTeacherList as $teacher){
					  $i++;
					  if($i==1){
						  $liclass = "class=\"marleft\"";


					  }else if($i==4){
						  $liclass = "class=\"marright\"";

					  }else{
						  $liclass="";
					  }
					  if($i>4){break;}
					  ?>
					  <li <?print($liclass)?>>
						  <div class="md_img">
							  <a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><img src="<? echo $teacher->getImg() ?>" alt="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" title="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" ></a>
						  </div>
						  <div class="midinfo">
							  <p class="infoname" title="<? echo $teacher->getName() ?>"><a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><? echo MetaDataGenerator::getShortenString($teacher->getName(), $STRING_MAX) ?></a></p>
							  <p title="<? echo $teacher->getAcademicTitleName() ?>"><? echo $teacher->getAcademicTitleName() ?></p>
							  <p class="school_name" title="<? echo $teacher->getUniversityName() ?>"><a href="teacherinfolist.php?universityId=<? echo $teacher->getUniversityId() ?>&pageSize=<? echo $pageSIze ?>&pageNo=<? echo $pageNo ?>"><? echo MetaDataGenerator::getShortenString($teacher->getUniversityName(), $STRING_MAX) ?></a></p>

						  </div>
					  </li>


                  <? } ?>
				</ul>
			</div>
		</div>
               <?  }

                 ?>



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
			'width': '327px',
			'height': '185px',
			'margin-left': '-10px',
			'cursor': 'pointer'
		},300)
	},function(){
		$(this).animate({
			'width': '297px',
			'height': '168px',
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
		$('.tm-ul li').removeClass('zhuye').eq(0).addClass('zhuye');
	</script>
<?php
	include("footer.php");
?>