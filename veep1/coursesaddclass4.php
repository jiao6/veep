<?

error_reporting(0);
session_start();
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("header.php");
if (!$auth) loginFalse();
function loginFalse()
{
	Header("Location:login.php");
}

if(!isset($dsql)){
	$dsql = new DSQL();
}
?>
<style type="text/css">
    .courseslistfeeteacher {
        background: #1E8997;
    }
    .courseslistfeeteacher a {
        color: #fff !important;
    }
</style>
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
        	<ul class="bread">
              <li><a href="courseslistfeeteacher.php">课程管理</a></li>
              <li>实验管理</li>
          </ul>
			<div class="fabusy">		 	 
				<dt>课程网址
					<dd><?print($name)?>: <a href=""><?print($_SERVER['PHP_SELF']."/?myclass.php?eid=$lessonId</dd>" )?></a></dd>
				</dt>
				<dt>实验网址
					<?
						$SQL = "SELECT ce . *, e.name as  ename FROM coursesexperiment ce, experiments e WHERE ce.coursesid ='$theId' and COURSE_TYPE=$COURSE_TYPE AND e.id =ce.experimentsid ORDER BY ce.id ASC";
            //echo $SQL . "\n";
            $dsql->query($SQL);
          	while($dsql->next_record()){
             	$id = $dsql->f('id');
					   	$name = $dsql->f('name');
				   		$ename = $dsql->f('ename');

					   	if(strlen($name)<2){
								$name = $ename;
					   	}	
							$starttime = $dsql->f('starttime');
							$endtime = $dsql->f('endtime');
							$score = $dsql->f('score');
							$count = $dsql->f('count');
							$scoringmode = $dsql->f('scoringmode');
							$timemode = $dsql->f('timemode');
							$isshowscore = $dsql->f('isshowscore');
							$userid = $dsql->f('userid');
							$coursesid = $dsql->f('coursesid');
							$experimentsid = $dsql->f('experimentsid');
							$sort = $dsql->f('sort');
							echo "<dd>$name: ".$_SERVER['PHP_SELF']."/?class5.php?eid=$id</dd>"; 
						}
						if($auth_pid==4){
							echo "<input type=\"submit\" id=\"submit\" name=\"submit\" onclick=\"window.location='courseslistfeeteacher.php'\" value=\"课程管理\" class=\"submit button bg-main\">
								<input type=\"submit\" id=\"submit\" name=\"submit\" onclick=\"window.location='lessonlist.php'\" value=\"课堂管理\" class=\"submit button bg-main\">";
						} else if($auth_pid==3){
							echo "<input type=\"submit\" id=\"submit\" name=\"submit\" onclick=\"window.location='courseslistfeeteacher.php'\" value=\"课程管理\" class=\"submit button bg-main\">";
						}
					?>
				</dt>
			</div>
<?
include("footer.php");
?>