<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/CheckerOfCourse.php");

$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);
$isATEACHER = CheckerOfCourse::isATEACHER($auth_pid);

$checkerOfCourse = new CheckerOfCourse();

$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
//echo $QUERY_STRING. "<br/>"; #
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
//echo $QUERY_STRING. "; QUERY_STRING_1=" . $QUERY_STRING_1 . "; QUERY_STRING_2=" . $QUERY_STRING_2;

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");
//echo ("ac = " . $ac);
$dsql = new DSQL();
$bpasswd = bpasswd($password);


if ($isAdmin) {
} else if ($isFeeTeacher || $isTeacher) {//管理导出用户
    $whereinfo = " and  creator_id=$auth_id  ";
} else if ($isStudent) {
    $whereinfo = " and id=$auth_id ";
} else {
    $whereinfo = " and id=$auth_id ";
}


if ($ac == "add") {//管理员增加用户
    if ($auth_pid != 3) {
        exit;
    }

    if ($password_confirmation == $password) {
        if ($isFeeTeacher) {
            // $SQL = "insert into users (id,nickname,email,password,university,college,phonenumber,truename,remember_token,created_at,updated_at,pwd,usertype,feeuserid)values(0,'$nickname','$email','$bpasswd','$university','$college','$phonenumber','$truename','$remember_token','$created_at','$updated_at','$password','$usertype', '$auth_id')";
        } else {//管理员才能增加用户
            $SQL = "SELECT * FROM users where email = '$email'";
            $dsql->query($SQL);
            if ($dsql->next_record()) {
                echo "<br><script>alert('邮箱已经存在');history.go(-1)</script>\n";
                exit;
            } else {
                //$SQL = "insert into users (id,nickname,email,password,university,college,phonenumber,truename,remember_token,created_at,updated_at,pwd,usertype,feeuserid)values(0,'$nickname','$email','$bpasswd','$university','$college','$phonenumber','$truename','$remember_token',now(),'$updated_at','$password','$usertype','0')";
                $SQL = "insert into users " .
                    "(isfee,   nickname,   email,          password, " .
                    "pwd,   university, university_id,  college, " .
                    "phonenumber, truename, usertype,   paytime, " .
                    "endtime,   " .
                    "creator_id, feeuserid, created_at )values(" .
                    "?, ?,  ?,  ?,
                    ?,  ?,  ?,  ?,
                    ?,  ?,  ?,  ?,
                    ?,
                    $auth_id, 0,  now())";


//"0,   '$nickname','$email','$bpasswd','$university','$college','$phonenumber','$truename','$remember_token',now(),'$updated_at','$password','$usertype','0')";
            }
        }
       // echo $SQL;// 12 个参数
        $pstmt = new DSQL();
        $query_prepare_1 = $pstmt->getPstmt($SQL);
        $query_prepare_1->bind_param("isssssissssss",
            $isfee, $nickname, $email, $bpasswd,
            $password, $university, $universityId, $college,
            $phonenumber, $truename, $usertype, $paytime,
            $endtime
        );
        $succ = $query_prepare_1->execute();
        if ($succ) {
            echo "<br><script>alert('增加成功');window.location='userlist.php'</script>\n";
        } else {
            echo "<br><script>alert('增加用户失败！');history.go(-1)</script>\n";
        }
        exit;

    } else {

        echo "<br><script>alert('二次密码不一致');window.history.go(-1)</script>\n";
    }
} else if ($ac == "admineditupdate") {//管理员修改
    if (!$isAdmin) {
        exit;
    }
    //echo "管理员修改. " . "changepwd=" . $changepwd . "<br/>";

    //$SQL = "update  users set nickname = '$nickname',email = '$email',$info university = '$university',college = '$college',phonenumber = '$phonenumber',truename = '$truename',usertype = '$usertype',isfee = '$isfee',paytime = '$paytime',endtime = '$endtime',payquantity = '$payquantity' where id = $id  ";
    if ($changepwd == 1) {
        //echo ("保留旧密码");
    } else {
        //echo("修改密码");
        $SQL = "update  users set " .
            "password = ?,          pwd = ?, " .
            "updated_at = now(),    updator_id = $auth_id  " .
            "where id = $id $whereinfo";
        $pstmt = new DSQL();
        $query_prepare_1 = $pstmt->getPstmt($SQL);
        $query_prepare_1->bind_param("ss",
            $bpasswd, $password);
        $query_prepare_1->execute();
    }
    //修改其他参数
    //echo("<br/>");
    $SQL = "update  users set " .
        "nickname = ?,  email = ?,         paytime = ?, endtime = ?, " .
        "university=?,  university_id=?,   college=?,   phonenumber=?, " .
        "truename = ?,  usertype = ?,      isfee = ?,   payquantity = ?, " .
        "" .
        "updated_at = now(),    updator_id = $auth_id  " .
        "where id = $id  $whereinfo";
    $pstmt = new DSQL();
    $query_prepare_2 = $pstmt->getPstmt($SQL);
    $query_prepare_2->bind_param("sssssisssiii",
        $nickname, $email, $paytime, $endtime,
        $university, $universityId, $college, $phonenumber,
        $truename, $usertype, $isfee, $payquantity
    );
    //echo $SQL;// 14 个参数
    $succ = $query_prepare_2->execute();
    
    /*对教师、付费教师，判断 teacher 表中是否有该老师的记录*/
    if ($usertype == CheckerOfCourse::PID_TEACHER || $usertype == CheckerOfCourse::PID_FEETEACHER) {
    	if (!$ACADEMIC_TITLE) {//没选任何职称
    		$ACADEMIC_TITLE = 0;
    	}
	    $sql = "select ID from TEACHER where ID=" . $id;
	    $dsql->query($sql);
	    if ($dsql->next_record()) {//有记录则更改
	    	$sql = "update TEACHER set 
	    		NAME='$truename', UPDATE_TIME=now(), STICKY=$STICKY, SORT_ORDER=$SORT_ORDER, ACADEMIC_TITLE=$ACADEMIC_TITLE 
	    		where ID=" . $id;
		    $dsql->query($sql);
		    //echo $sql . "<br/>";
	    } else {//无记录则插入
	    	$sql = "insert into TEACHER (
	    		ID, NAME, CREATE_TIME, UNIVERSITY_ID, UNIVERSITY_NAME, 
	    		STICKY, SORT_ORDER, ACADEMIC_TITLE) values (
	    		$id, '$truename', now(), $universityId, '$university', 
	    		$STICKY, $SORT_ORDER, $ACADEMIC_TITLE)";
		    //echo $sql . "<br/>";
		    $dsql->query($sql);
	    }
	}
    
    //echo("succ=" . $succ);
    if ($succ) {
        $QUERY_STRING_2 = str_replace("&amp;", "&", $QUERY_STRING_2);
        $url = "userlist.php?" . $QUERY_STRING_2 . "#" . $id;
        //echo("url=" . $url);
        $checkerOfCourse->jumpTo("修改成功", $url);
    } else {
        echo "<br><script>alert('修改失败！');window.history.go(-1)</script>\n";
    }
    //exit;
    //$pstmt->colsePstmt(true);/**/
} else if ($ac == "editupdate") {//修改 我的资料，不许修改密码；

    $SQL = "update users set " .
        "nickname = ?,  university = ?,     university_id=?, " .
        "college = ?,   phonenumber = ?,    truename = ?,studentno=?,class=?,".
        "updated_at = now(),    updator_id = $auth_id" .
        "  where id = $auth_id   ";
    //echo $SQL;
    $pstmt = new DSQL();
    $query_prepare_2 = $pstmt->getPstmt($SQL);
    $query_prepare_2->bind_param("ssisssss",
        $nickname, $university, $universityId,
        $college, $phonenumber, $truename, $studentno, $class
    );
    $succ = $query_prepare_2->execute();
    if ($isTeacher || $isFeeTeacher || $isStudent) {
        $_SESSION['auth_username'] = $truename;
    } else if ($isAdmin) {//管理员

        if ($id = $auth_id) {
            $_SESSION['auth_username'] = $truename;
        }
    }
    if ($succ) {
        if ($isAdmin) {
            echo "<br><script>alert('修改成功');window.history.go(-1)</script>\n";
        } else {
            echo "<br><script>alert('修改成功');window.location='myuseredit.php'</script>\n";
        }
    } else {
        echo "<br><script>alert('修改失败!');window.history.go(-1)</script>\n";
    }
    exit;

} else if ($ac == "del") {
    if ($auth_pid != 3) {
        exit;
    }
    $SQL = "update  users set status='$status' where id = $id  ";
    if (!$dsql->query($SQL)) {

        echo "<br><script>alert('修改失败！');window.history.go(-1)</script>\n";
        exit;
    } else {
        echo "<br><script>alert('修改成功');window.location='userlist.php'</script>\n";

    }
} else if ($ac == "edit") {//显示用户信息

        $SQL = "select *  from users where id = $id  ";
        //echo $SQL;
        $dsql->query($SQL);

        if ($dsql->next_record()) {
            $id = $dsql->f('id');
            $nickname = $dsql->f('nickname');
            $email = $dsql->f('email');
            $password = $dsql->f('password');
            $university = $dsql->f('university');
            $college = $dsql->f('college');
            $phonenumber = $dsql->f('phonenumber');
            $created_at = $dsql->f('created_at');
            $updated_at = $dsql->f('updated_at');
            $pwd = $dsql->f('pwd');
            $usertype = $dsql->f('usertype');
            $truename = $dsql->f('truename');
            $remember_token = $dsql->f('remeusertype');
            $feeuserid = $dsql->f('feeuserid');
            $isfee = $dsql->f('isfee');
            $paytime = $dsql->f('paytime');
            $endtime = $dsql->f('endtime');
            $payquantity = $dsql->f('payquantity');
            $used = $dsql->f('used');
            $universityId = $dsql->f('university_id');
			
			$heIsTeacher = ($usertype==CheckerOfCourse::PID_TEACHER || $usertype==CheckerOfCourse::PID_FEETEACHER);
			if ($heIsTeacher) {//如果此人是教师、付费教师
				$sql = "select * from TEACHER where id=" . $id;
				$dsql->query($sql);
				if ($dsql->next_record()) {
					$SORT_ORDER = $dsql->f('SORT_ORDER');
					$STICKY = $dsql->f('STICKY');
					$ACADEMIC_TITLE = $dsql->f('ACADEMIC_TITLE');
				}
				//echo "SORT_ORDER=" . $SORT_ORDER;
				
			}
            ?>
            <script type="text/javascript">

                function ifchangepwd() {

                    var ischange = !$("#changepwd").is(':checked');

                    if (ischange) {//如果改密码
                        $("#password").attr('disabled', false);
                        $("#password_confirmation").attr('disabled', false);
                    } else {
                        $("#password").attr('disabled', true);
                        $("#password_confirmation").attr('disabled', true);
                    }


                }
            </script>
            <script type="text/javascript" language="javascript" src="js/laydate.js">
            </script>
            <style type="text/css">
                .userlist {
                    background: #1E8997;
                }

                .userlist a {
                    color: #fff;
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
                    	<li><a href="courseslistfeeteacher.php">用户管理</a></li>
                    	<li>用户修改</li>
               	    </ul>
                    <div class="rhead">
                        <div class="rhead1">修改 <? echo $id ?> 号用户 <? echo $truename ?></div>
                        <div class="rhead2"><a href=userlist.php>用户列表</a></div>
                    </div>
                    <div class="rgwin">
                    <form action="useredit.php" method="post" class="rgform form-x"">
                        <input type="password" maxlength='32' style="display: none;">
                        <input style="display:none">
                        <?
                        if ($isFeeTeacher || $isTeacher || $isAdmin) {?>
                            <div class="form-group">
                            	<div class="label">
                            		<label for="name">邮箱<? if (!$isAdmin) {?>(不能修改)<? } ?></label>
                                </div>
                                <div class="field">
                                    <input   class="input" type="text" maxlength="100" id="email" name="email" value="<? print($email) ?>"
                                      datatype="e" errormsg="请输入邮箱" <? if (!$isAdmin) {?>readonly<? } ?>
                                                                          autocomplete="off" />
                                 </div>
                           	</div>
                          	<div class="form-group">
                           		<div class="label">
                           			<label for="name">昵称</label>
                                </div>
                                <div class="field">
                               		<input type="text"  class="input" maxlength="100" name="nickname"
                                                                    value="<? print($nickname) ?>" datatype="*"
                                                                    errormsg="请输入文字" autocomplete="off">
                                </div>
                            </div>
                            <!-- 定义大学 --><? include "page_element/universities.php" ?>
                            <div class="form-group">
                            	<div class="label">
                            		<label for="name">学院</label>
                                </div>
                                <div class="field">
                                	<input type="text"  class="input" maxlength="100" name="college"
                                                                    autocomplete="off" value="<? print($college) ?>">
                                </div>
                            </div>
                            <div class="form-group">
                            	<div class="label">
                            		<label for="name">手机</label>
                                </div>
                               	<div class="field">
                                    <input type="text" class="input" maxlength="11" name="phonenumber"
                                                                      autocomplete="off"
                                                                      value="<? print($phonenumber) ?>" datatype="m"
                                                                      errormsg="请输入11位手机号码如：13012345678" placeholder="请输入11位手机号码如：13012345678" nullmsg="请输入11位手机号码如：13012345678">
                                </div>
                            </div>

                            <div class="form-group">
                           		<div class="label">
                            		<label for="name">真实姓名</label>
                            	</div>
                                <div class="field">
                                	<input type="text" class="input" maxlength="30" name="truename"
                                                                      autocomplete="off" value="<? print($truename) ?>"
                                                                      datatype="*" errormsg="请输入文字">
                                </div>
                           	</div>
                            <?
                        }
                        if ($isAdmin) {
                            ?>
                            <!-- 管理员 -->
  							<? include "page_element/user_type.php" ?>
                            <div class="form-group" style="line-height: 35px">
                            	<div class="label">
                            		<label for="name">不修改密码</label>
                                </div>
                               
                                   	<input type="checkbox" name="changepwd"id="changepwd" 
                                                                       value="1" checked onclick="ifchangepwd()">
                               
                            </div>
                            <div class="form-group">
                            	<div class="label">
                           			<label for="name">密码</label>
                                </div>
                                <div class="field">
                                	<input type="password" class="input" maxlength='32' name="password"
                                                                    id="password" autocomplete="off"
                                                                    disabled="disabled">
                                </div>
                            </div>
                            <div class="form-group">
                            	<div class="label">
                                	<label for="name">确认密码</label>
                                </div>
                                <div class="field">   	
                                	<input type="password" class="input" maxlength='32'
                                                                      name="password_confirmation" autocomplete="off"
                                                                  id="password_confirmation" disabled="disabled">
                           		</div>
                            </div>
                            <div class="form-group" style="line-height: 35px">
                            	<div class="label">
                                	<label for="name">是否收费会员</label>
                                </div>
                               
                                <? include("page_element/isfee.php"); ?>
                              
                            </div>

                            <div class="form-group">
                           		<div class="label">
                           			<label for="name">开始时间</label>
                              	</div>
                              	<div class="field">  	
                                    <input type="text" class="input" maxlength="19" name="paytime" value="<? print($paytime) ?>"
                                       class="laydate-icon"
                                       onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
								</div>
                            </div>
                            <div class="form-group">
                            	<div class="label">
                                	<label for="name">截止时间</label>
                                </div>
                                <div class="field"> 
                                	<input type="text" class="input" maxlength="19" name="endtime" value="<? print($endtime) ?>"
                                       class="laydate-icon"
                                       onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
								</div>
                            </div>

                          <font style="display:<? if ($heIsTeacher){echo "block";} else {echo "none";} ?>" id="divSortOrder">
							<? include "page_element/lesson_sortorder.php" ?>
                            <div class="form-group" style="line-height: 30px;">
                            	<div class="label">
                                <label for="name">置顶</label>
                                </div>                              
                                <label for="name">
                                    否：  <input <? if ($STICKY){if ($STICKY==0)echo "checked"; } else {echo "checked";} ?> type="radio" id="STICKY" name="STICKY" value="00" >
                                </label>
                                <label for="name" >
                                    置顶：<input <? if ($STICKY){if ($STICKY==10)echo "checked"; } ?>  type="radio" id="STICKY" name="STICKY" value="10" >
                                </label>
                                
                            </div>
                             <div class="form-group">                         
                            <? include("page_element/academic_title.php"); ?>
							</div>

                          </font>


                            <?
                        } ?>
                        <INPUT TYPE="hidden" name='id' value="<? print($id) ?>"><INPUT TYPE="hidden" name='ac'
                                                                                       value="admineditupdate">

                        <div><input type="hidden" id="QUERY_STRING_2" name="QUERY_STRING_2"
                                    value="<? echo $QUERY_STRING_1 ?>"/>
                        <div class="form-group">
							<div class="label">
								<label for="text3"></label>
							</div>
							<div class="field"><input class="button bg-main" name="submit" value="修改" type="submit" onclick="return checkTheForm(this.form);"/>
                            </div>
                        </div>
                    </form>
              </div>
               
            <script type="text/javascript" src="js/validform.js"></script>
            <script type="text/javascript">
                function selectAUserType(elem) {
                    var usertype = $("input[name='usertype']:checked").val();
                    var divSortOrder = $("#divSortOrder");
                    if (usertype == <? echo CheckerOfCourse::PID_TEACHER ?> || usertype == <? echo CheckerOfCourse::PID_FEETEACHER ?>) {//选择了教师
                        divSortOrder.show();
                    } else {
                        divSortOrder.hide();
                    }
                }
                function checkTheForm(frm) {
                    //return false;
                    var ischange = !$("#changepwd").is(':checked');
                    if (!ischange) {
                        return true;
                    }
                    var pwd1 = $("#password");
                    var pwd2 = $("#password_confirmation");
                    //alert("修改密码=" + ischange + "; pwd1=" + pwd1.val() + "; pwd2=" + pwd2.val());
                    if (pwd1.val().length < 6) {
                        alert("必须填写6位以上密码");
                        pwd1.focus();
                        return false;
                    }
                    if (pwd2.val().length < 6) {
                        alert("必须填写6位以上确认密码");
                        pwd2.focus();
                        return false;
                    }//
                    if (pwd1.val() != pwd2.val()) {
                        alert("二次密码不一致");
                        pwd2.focus();
                        return false;
                    }
                    return true;
                }
                $(function () {
                    $(".rgform").Validform({
                        tiptype: 2,
                        label: ".label",
                        showAllError: true,
                        postonce: true
                    });
                })
            </script>

            <?
        }
} else if (isset($status) && $id > 0) {

        $SQL = "update  users set status=$status where id =$id";
        if (!$dsql->query($SQL)) {
            //echo "un  success:$SQL ";

            echo "<br><script>alert('修改用户状态失败！');window.location='userlist.php'</script>\n";
            exit;
        } else {
            echo "<br><script>alert('修改用户状态成功');window.location='userlist.php'</script>\n";
        }
}
include("footer.php");

?>


<?
include("bubbleUniversity.php");


?>