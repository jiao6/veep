<?php
session_start();

require_once("config/config.php");

if (!$auth) loginFalse();

function loginFalse()
{
	Header("Location:login.php");
}


require_once("header.php");
?>
<style type="text/css">
	.changemypassword {
		background: #1E8997;
	}
	.changemypassword a {
		color: #fff !important;
	}
</style>
	<script type="text/javascript" language="javascript" src="js/laydate.js">
	</script>
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
			<div class="rhead">
				<div class="rhead1">修改【<? echo $auth_username ?>】的密码</div>
			</div>
			<form action="useredit.php" method="post" class="rgform form-x" name="form1" id="form1" style="margin-top: 20px;width: 80%;">
				<div class="form-group">
					<div class="label">
						<label for="oldPwd">旧密码</label>
						<input type="image" title="隐藏密码" src="images/openeye-2.png" width="50" style="width:40px; height:34px; float: right;" onclick="seePwd(this, oldPwd); return false;" />
					</div>
					<div class="field">
						<input class="input" id="oldPwd" name="oldPwd"   type="password" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label for="newPwd1">新密码</label>
						<input type="image" src="images/openeye-2.png" width="50" style="width:40px; height:34px; float: right;" onclick="seePwd(this, newPwd1); return false;" />
					</div>
					<div class="field">
						<input class="input" id="newPwd1" name="newPwd1" type="password" maxlength="20">
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label for="newPwd2">确认密码</label>
						<input type="image" src="images/openeye-2.png" width="50" style="width:40px; height:34px; float: right;" onclick="seePwd(this, newPwd2); return false;" />
					</div>
					<div class="field">
						<input class="input" id="newPwd2" name="newPwd2" type="password" maxlength="20">
					</div>
				</div>
				<div>
				<div class="form-group">
					<div class="label">
						<label for="submit"></label>
					</div>
					<div class="field">
						<input type="button" id="submit" name="submit" class="button bg-main button-big" value="保存" style="float: left;" onclick="subm(this.form)">
					</div>
				</div>
				</div>
			</form>
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
			if (frm.oldPwd.value.length < 1) {
				alert("旧密码必填！");
				frm.oldPwd.focus();
				return false;
			}
			if (b.test(frm.oldPwd.value)) {//
				alert("只能填写字母和数字！");
				frm.oldPwd.focus();
				return false;
			}

			if (frm.newPwd1.value.length < 6) {
				alert("新密码必填！且6位以上");
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
			if (frm.oldPwd.value == frm.newPwd1.value) {
				alert("新旧密码不能一样！");
				frm.newPwd2.focus();
				return false;
			}
			var url = "changemypassword-ok.php";
			var oldPwd = $("#oldPwd").val();
			var newPwd1 = $("#newPwd1").val();
			//alert(oldPwd);
			$.ajax({//先判断旧密码是否正确
				type:"post",
				url :url,
				data: {"oldPwd":oldPwd, "newPwd1":newPwd1},
				datatype:"json",
				success:function(data){
					var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
					var email = dataJson.data[0].email;
					var nickname = dataJson.data[1].nickname;
					//alert("nickname=" + nickname + "； email=" + email);
					if (email == "EMPTY") {
						alert("旧密码错误，不能修改密码！");
					} else if (email == "SUCCESS") {
						alert("修改成功！");
						$("#oldPwd").val('');
						$("#newPwd1").val('');
						$("#newPwd2").val('');
						//window.location.href='myuseredit.php'

					} else {
						alert("修改失败！");
					}
				}
			})


		}
	</script>
<?
include('footer.php');
?>