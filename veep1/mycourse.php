<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
if (!$auth) loginFalse();

function loginFalse()
{
	Header("Location:login.php");
}

require_once("header.php");
require_once("config/CheckerOfCourse.php");

$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);

$dsql = new DSQL();

if($ac=="del"){
	$SQL = "select coursesid from coursesemail where coursesid ='$lessonId' and email = '$auth_email'";
	//echo $SQL . "<br/>";
	$dsql->query($SQL);
	$SQL = "update coursesuser set status=1 where coursesid ='$lessonId' and userid='$auth_id'";
	if($dsql->next_record()){
		echo "  <script >alert('老师导入的学生课程不能退出')</script>\n";
	}else 	if(!$dsql->query($SQL)){
		//echo "un  success:$SQL ";
		echo "  <script >alert('退出课堂失败');history.go(-1)</script>\n";

	}else{
		echo "  <script >alert('退出课堂成功');history.go(-1)</script>\n";
		//echo "success:$SQL ";
	}
}
?>

<link rel="stylesheet" type="text/css" href="css/courseku.css">
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
</div>
<div class="admin">
<div class="contain2 clss">
	<div class="testtop">
		<div class="testline"></div>
		<div class="testtxt">我的课堂</div>
	</div>
	<div class="right_rt">
		<ul class="testbtm">
			<?
			$whereinfo = " ";

			if ($search) {
				$whereinfo .= " and ( 1=1  )  ";
			}
			if ($auth_pid == 2 || $auth_pid == 4) {
				$whereinfo = " and c.useremail='$auth_email'  ";
			}else if ($auth_pid == 1) {
				$whereinfo = "    and ( c.id in (select coursesid from coursesemail where (email = '$auth_email' or email = '$auth_import_email')) or   c.id  in (select coursesid from coursesuser where userid = '$auth_id' and status=0)  ) ";
			}
			$SQL = "SELECT c.*  FROM LESSON  c,  courses  cp  where    1=1  $whereinfo  and c.STATUS=0 and c.SHOWN=1  and c.COURSE_ID=cp.id and   cp.status=0 and cp.isshow=1 and  c.START_TIME < NOW( ) and  c.END_TIME > NOW( )  and  cp.starttime < NOW( ) and  cp.endtime > NOW( ) ";

			//echo $SQL . "\n";
			$dsql->query($SQL);
			$nbsp = $pernbsp = "|---------";
			$olddepth = 1;
			$i=0;
			while ($dsql->next_record()) {
				$i++;
				$lessonId = $dsql->f('ID');
				$lessonName = $dsql->f('NAME');
				$content = $dsql->f('INTRODUCTION');
				$coursesimg = $dsql->f('IMG_URL');
				/*
				$moocurl = $dsql->f('moocurl');
				$starttime = $dsql->f('starttime');
				$endtime = $dsql->f('endtime');
				$userid = $dsql->f('userid');
				$created_at = $dsql->f('created_at');
				$updated_at = $dsql->f('updated_at');
				$useremail = $dsql->f('useremail');
				$code = $dsql->f('code');
				$payquantity = $dsql->f('payquantity');
				$moocid = $dsql->f('moocid');
				$step = $dsql->f('step');*/
				if(strlen($content)>50){
					$content =mb_substr($content, 0,50, 'utf-8')."..";
				}
				if (!file_exists($coursesimg)) {
					$coursesimg = CheckerOfCourse::DEFAULT_LESSON_IMAGE; //"images/course.jpg";
				}

				?>
				<li>
					<div class="clsimg"></div>
					<img src="<? print($coursesimg) ?>" height="180px">
					<div class="clsrt">
						<p class="clsname"><a href="">
								<? print($lessonName) ?></a></p>
						<p class="clsinfo">
							<? print($content) ?>
						</p>
						<span class="selected"><a href="?ac=del&lessonId=<? print($lessonId) ?>"  onclick="return confirm('确定要退出吗')">退出课堂</a></span>
						<a href="lesson.php?lessonId=<? print($lessonId) ?>"><span class="selected">开始学习</span></a>
					</div>
				</li>
				<?
			}
			//列出已退出的课堂。cu.status=1 
			$SQL = "SELECT  c.* FROM LESSON  c,  courses  cp ,coursesuser cu  
				where  cu.coursesid = c.ID and cu.userid ='$auth_id' 
					and cu.status=1 and c.STATUS=0 and c.SHOWN=1  
					and c.COURSE_ID=cp.id and cp.status=0 and cp.isshow=1 
					and c.START_TIME < NOW( ) and  c.END_TIME > NOW( )  
					and  cp.starttime < NOW( ) and  cp.endtime > NOW()";

			//echo $SQL . "<br/>";
			$dsql->query($SQL);
			$nbsp = $pernbsp = "|---------";
			$olddepth = 1;

			while ($dsql->next_record()) {
				$i++;
				$lessonId = $dsql->f('ID');
				$lessonName = $dsql->f('NAME');
				$content = $dsql->f('INTRODUCTION');
				$coursesimg = $dsql->f('IMG_URL');
				/*
				$moocurl = $dsql->f('moocurl');
				$starttime = $dsql->f('starttime');
				$endtime = $dsql->f('endtime');
				$userid = $dsql->f('userid');
				$created_at = $dsql->f('created_at');
				$updated_at = $dsql->f('updated_at');
				$useremail = $dsql->f('useremail');
				$code = $dsql->f('code');
				$payquantity = $dsql->f('payquantity');
				$moocid = $dsql->f('moocid');
				$step = $dsql->f('step');*/
				if(strlen($content)>50){
					$content =mb_substr($content, 0,50, 'utf-8')."..";
				}
				?>
				<li>
					<div class="clsimg"></div>
					<img src="<? print($coursesimg) ?>" height="180px">
					<div class="clsrt">
						<p class="clsname">
								<? print($lessonName) ?>(已退出)</p>
						<p class="clsinfo">
							<? print($content) ?>
						</p>
					 
					</div>
				</li>
				<?
			}

			if($i==0){
				?>
				<li>
					<div  > <br/><br/><br/>
						您还没有加入课堂, 如有选课码请通过<a  style="color:#FF0000"  href="mycode.php">选课码选课</a>
					</div>
				</li>
				<?
			}
			?>
		</ul>
	</div>
	<div class="left_lt">
		<div class="tuijian">推荐课程</div>
		<ul>
			<?
			$homepage_courseid[0] = 114;
			$homepage_courseid[1] = 95;
			$homepage_courseid[2] = 98 ;

			$SQL = "SELECT  c.*,u.university ,u.truename 
				FROM courses c,users u 
				where c.useremail=u.email  and  (c.id=$homepage_courseid[0] or c.id = $homepage_courseid[1] or c.id = $homepage_courseid[2] )    ";
			//echo $SQL;
			$dsql->query($SQL);
			$homepage_course[0]="";
			while ($dsql->next_record()) {
				$id = $dsql->f('id');
				$lessonName = $dsql->f('name');
				$coursesimg = $dsql->f('coursesimg');
				$university = $dsql->f('university');
				$truename = $dsql->f('truename');
				if(!file_exists($coursesimg)){
					$coursesimg="images/course.jpg";
				}
				if($homepage_courseid[0]==$id){
					$homepage_course[0]="<li>
										<a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\">
										<span class=\"hed\">$lessonName</span>
										<span class=\"pair\">$truename</span>
										<span class=\"pair\">$university</span>
										</a>
								</li>";

				}else if($homepage_courseid[1]==$id){
					$homepage_course[1]="<li>
									<a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\">
										<span class=\"hed\">$lessonName</span>
										<span class=\"pair\">$truename</span>
										<span class=\"pair\">$university</span>
										</a>
								</li>";

				}else if($homepage_courseid[2]==$id){
					$homepage_course[2]="<li>
									<a href=\"course.php?courseid=$id\"><img src=\"$coursesimg\">
										<span class=\"hed\">$lessonName</span>
										<span class=\"pair\">$truename</span>
										<span class=\"pair\">$university</span>
										</a>
								</li>";
				}
			}
			echo implode($homepage_course);
			?>


		</ul>
	</div>
</div>
<?include("footer.php");?>
<script type="text/javascript">
	$(function(){
		$('.tm-ul li').removeClass('zhuye').eq(1).addClass('zhuye');
		var lis = $('.left ul li');
		var first = $('.left ul li:first-child');
		for (var i = 0; i < lis.length; i++) {
			$(lis[i]).click(function () {
				window.location = $(this).find('a').attr('href');
				$(this).find('a').css('color','#fff');
				$(this).siblings().find('a').css('color','#000');
				$(this).css({'background': '#1C7A80'}).siblings().css({
					'background': '#FAFAFA'
				});
			});
		}
	});
</script>
</div>
</body>
</html>