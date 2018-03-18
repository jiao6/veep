<?php
// 找回密码需要的元素
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
function loginFalse($alert) {
	echo "<script>alert('". $alert ."');document.location='login.php'</script>";
	//Header("Location:login.php");
}

if (!$remember) {
	 loginFalse('链接不存在！');//没有 remeber 字符串，退出，
} else {
	$remember = trim($remember);
	$LENGTH = 20;
	//$domain = strstr($remember, '\'');
	$length = strlen($remember);
	if ($length != $LENGTH) {
		//loginFalse('串号格式错误！');//长度不对，退出
	}
	$SQL = "select id, email, nickname, remember_token from users where remember_token like '". $remember ."%'";	
	//echo "remember=". $remember . "; length = " . $length . "; SQL = " . $SQL;
	$dsql = new DSQL();
	$dsql->query($SQL);
	$has = false;
	if($dsql->next_record()){
		$userid = $dsql->f('id');
		$email = $dsql->f('email');
		$remember_token = $dsql->f('remember_token');
		$has = true;
	}
	//echo "userid=". $userid . "; has = " . $has  . "; remember_token = " . $remember_token . "; SQL = " . $SQL . <br/>;
	if (!$has) {//串号不存在，退出。
		loginFalse("链接不存在！");//长度不对，退出
		return;
	}
	$strs = explode("!", $remember_token);//用惊叹号分为数组
	$timestamp = $strs[1];
	$userid2 = $strs[2];
	//echo "userid=". $userid . "; has = " . $has  . "; userid2 = " . $userid2 . "; timestamp = " . $timestamp;
	if ($userid != $userid) {
		loginFalse("用户 id 不符！");//长度不对，退出
	}
	$time = time();
	$now = date("YmdHi", $time);
	$interval = $now - $timestamp;
	//echo "userid=". $userid . "; interval = " . $interval  . "; userid2 = " . $userid2 . "; timestamp = " . $timestamp . "; now = " . $now;
	$INTERVAL = 1440;//一整天
	if ($interval < $INTERVAL &&  $interval > 0) {//少于 1 整天；并大于零，不允许
	} else {
		loginFalse("超过 24 小时，您的链接已过期！");//长度不对，退出
	}
	//$remember = $email;
}
?>

            <script type="text/javascript" language="javascript" src="js/laydate.js">
            </script>
            <div class="contain mc mc1">
                <div class="lt">
                    <ul>
                        <li class="gn">功能列表</li>
                        <?
                        include("menu.php");
                        ?>
                    </ul>
                </div>
                <div class="rt">
                    <div class="rhead">
                        <div class="rhead1">修改【<? echo $email ?>】的密码</div>
                    </div>
                    <div class="rfg"></div>

                    <form action="useredit.php" method="post" class="rgform" align="center" name="form1" id="form1">

                    	<script>
                    		function seePwd(img1, pwd) {
                    			var imgName = img1.src;
                    			//alert(pwd.value);
                    			if (imgName.indexOf("openeye-1") > 0) {
                    				img1.src = "images/openeye-2.png";
                    				pwd.type  = 'password';
                    				img1.title = "隐藏密码";
                    			} else {
                    				img1.src = "images/openeye-1.png";
                    				pwd.type  = 'text';
                    				img1.title = "显示密码";
                    			}
                    			return;
                    		}
                    		function subm(frm) {
                    			var b = /[\u4E00-\u9FA5]/i;
                    			
                    			if (frm.newPwd1.value.length < 1) {
                    				alert("新密码必填！");
                    				frm.newPwd1.focus();
                    				return false;
                    			}
                    			if (b.test(frm.newPwd1.value)) {//
                    				alert("只能填写字母和数字！");
                    				frm.newPwd1.focus();
                    				return false;
                    			}
                    			if (frm.newPwd2.value.length < 1) {
                    				alert("请再次输入新密码！");
                    				frm.newPwd2.focus();
                    				return false;
                    			}
                    			if (b.test(frm.newPwd2.value)) {//
                    				alert("只能填写字母和数字！");
                    				frm.newPwd2.focus();
                    				return false;
                    			}
                    			if (frm.newPwd2.value != frm.newPwd1.value) {
                    				alert("两个新密码不符！");
                    				frm.newPwd2.focus();
                    				return false;
                    			}
                    			var url = "findpassword-finish.php";
        									var userid = $("#userid").val();
        									var newPwd1 = $("#newPwd1").val();
											var remember =  $("#remember").val();
        									//alert(userid);
        									$.ajax({//先判断旧密码是否正确
								            type:"post",
								            url :url,
								            data: {"userid":userid, "newPwd1":newPwd1,"remember":remember},
								            datatype:"json",
								            success:function(data){
								            	var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式   
															var email = dataJson.data[0].email;
															var nickname = "";
															//alert("nickname=" + nickname + "； email=" + email);
															if (email == "EMPTY") {
																alert("旧密码错误，不能修改密码！");
															} else if (email == "SUCCESS") {
																alert("修改成功！");
																$("#newPwd1").val('');
																$("#newPwd2").val('');
																window.location="login.php"

															} else {
																alert("修改失败！");
															}
								            }
        									})


                    		}
                    	</script>


							<form action="useredit.php" method="post" class="rgform" align="center" name="form1" id="form1">

								<div>
									<label for="newPwd1">新密码</label>
									<input id="newPwd1" name="newPwd1" type="password" maxlength="20">
									<input type="image" src="images/openeye-2.png" width="50" style="width:40px; height:34px; float: left;left: 590px" onclick="seePwd(this, newPwd1); return false;" />
								</div>
								<div>
									<label for="newPwd2">确认密码</label>
									<input id="newPwd2" name="newPwd2" type="password" maxlength="20">
									<input type="image" src="images/openeye-2.png" width="50" style="width:40px; height:34px; float: left;left: 590px" onclick="seePwd(this, newPwd2); return false;" />
								</div>
								<div>
									<input type="hidden" name="userid" id="userid" value="<? echo $userid ?>">
									<input type="hidden" name="email" id="email" value="<? echo $email ?>">
									<input type="hidden" name="remember" id="remember" value="<? echo $remember ?>">


									<input type="button" id="submit" name="submit" class="btn2" value="修改" style="float: left;" onclick="subm(this.form)">
								</div>
							</form>


                </div>
            </div>
</body>
</html>
 
