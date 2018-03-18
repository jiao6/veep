<?php
session_start();
require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");

require_once("config/config.php");
require_once("config/dsql.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");
$isATEACHER = CheckerOfCourse::isATEACHER($auth_pid);
$dsql = new DSQL();

 if ($ac == "editimgupdate") {
    $SQL = "update users set content = '$content' where id = $auth_id  ";
     //echo $SQL;
    if ($dsql->query($SQL)) {
        if ($isATEACHER) {//是老师则可以改职称
	    	if (!$ACADEMIC_TITLE) {//没选任何职称
	    		$ACADEMIC_TITLE = 0;
	    	}
	    	$sql = "update TEACHER set 
	    		NAME='$auth_username', UPDATE_TIME=now(), ACADEMIC_TITLE=$ACADEMIC_TITLE 
	    		where ID=" . $auth_id;
		    $dsql->query($sql);
			    //echo $sql . "<br/>";
        }
        echo "<script>alert('修改成功！');</script>\n";
    } else {
        echo "<script>alert('修改失败！');window.history.go(-1)</script>\n";
        exit;
    }
}

    $SQL = "select *  from users where id = $auth_id  ";
    //echo $SQL;
    $dsql->query($SQL);

    if ($dsql->next_record()) {
        $id = $dsql->f('id');
        $nickname = $dsql->f('nickname');
        $email = $dsql->f('email');
        $password = $dsql->f('password');
        $university = $dsql->f('university');
        $universityId = $dsql->f('university_id');
        $phonenumber = $dsql->f('phonenumber');
        $created_at = $dsql->f('created_at');
        $updated_at = $dsql->f('updated_at');
        $pwd = $dsql->f('pwd');
        $usertype = $dsql->f('usertype');
        $truename = $dsql->f('truename');
        $feeuserid = $dsql->f('feeuserid');
        $isfee = $dsql->f('isfee');
        $paytime = $dsql->f('paytime');
        $endtime = $dsql->f('endtime');
        $content = $dsql->f('content');

        $userimg = MetaDataGenerator::getImage($dsql->f('userimg'), CheckerOfCourse::DEFAULT_TEACHER_IMAGE);


		if ($isATEACHER) {//是老师则插入 teacher
		    $sql = "select ID, IFNULL(ACADEMIC_TITLE, 0) as ACADEMIC_TITLE from TEACHER where ID=" . $auth_id;
		    $dsql->query($sql);
		    if ($dsql->next_record()) {//有记录则查询
		        $ACADEMIC_TITLE = $dsql->f('ACADEMIC_TITLE');
		    } else {//无记录则插入
		    	$sql = "insert into TEACHER (
		    		ID, NAME, CREATE_TIME, ACADEMIC_TITLE, UNIVERSITY_ID, UNIVERSITY_NAME) values (
		    		$auth_id, '$auth_username', now(), 0, $universityId, '$university')";
			    //echo $sql . "<br/>";
			    $dsql->query($sql);
		    }
		}

        ?>
    <style type="text/css">
        .myuserimgedit {
            background: #1E8997;
        }
        .myuserimgedit a{
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
        <form action="myuserimgedit.php" class="rgform form-x" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <div style="text-align: center;width: 30%;float: left;border-right: 1px solid #ccc;">
                    <a href="<? print($userimg) ?>" target="_blank"><img src="<?print($userimg)?>" width="200" ></a>
                    <br />
                    <a href="./editPortrait-1.php">
                        <input type="button" id="dd" name="dd" class="button bg-sub" value="修改头像" style="margin-top: 10px;">
                    </a>
                </div>
                <div style="width: 70%;float: right;">
                    <div class="label">
                       <label for="name">介绍</label>
                    </div>
                    <div class="field">
                        <textarea class="input" name="content" cols="80" rows="10" style="resize: none;"><?print($content)?></textarea>
                    </div>
                </div>
            </div>
            <!--div><label for="name">头像</label><input type="file" id="userimg" name="userimg"></div-->
            <? if ($isATEACHER) {//教师可以设定职称
            	include("page_element/academic_title.php"); 
        	   }
        	?>
            <input type="hidden" name='id' value="<? print($auth_id) ?>"><input type="hidden" name='ac' value="editimgupdate">
            <div class="form-group" style="margin-top: 10px;text-align: center;">
                <input type="submit" id="submit" name="submit" class="button bg-main" value="保存修改">
            </div>
        </form>
        <?
    }
include("footer.php");
?>


