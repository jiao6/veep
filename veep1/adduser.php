<?php
    require_once("config/config.php");
    require_once("config/dsql.php");
    session_start();
    if (!$auth) loginFalse();

    function loginFalse()
    {
        Header("Location:login.php");
    }
    if($auth_pid!=3){

        Header("Location:login.php");
    }
    require_once("header.php");
?>
<script type="text/javascript" language="javascript" src="js/laydate.js"></script>
<style type="text/css">
    .userlist {
        background: #1E8997;
    }
    .userlist a {
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
            <li><a href="userlist.php">用户管理</a></li>
            <li>增加用户</li>
            <li style="float: right;margin-right: 10%;"><a href="userlist.php" class="button border-blue button-small">用户列表</a></li>
        </ul>
        <form action="useredit.php" class="form-x" style="width: 853px">
		<?
			$isFeeTeacher = false;
			if ($auth_pid == 4) $isFeeTeacher= true;
		?>
				<!-- 管理员 -->
                <div class="form-group">
                    <? include "page_element/user_type.php" ?>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">邮箱(永远不能改)</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input type="text" maxlength="100" id="email" name="email" value="<? print($email) ?>" datatype="e" errormsg="请输入邮箱" autocomplete="off" placeholder="邮箱" class="input">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">昵称</label>
                    </div>
                    <div class="field">
                        <input type="text" maxlength="100" name="nickname" value="<? print($nickname) ?>" class="input">
                    </div>
                </div>
                <!-- 定义大学 -->
                <? include "page_element/universities.php" ?>
                <div class="form-group">
                    <div class="label">
                        <label for="name">学院</label>
                    </div>
                    <div class="field">
                        <input type="text" maxlength="30" name="college" value="<? print($college) ?>" class="input">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">联系电话</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" maxlength="30" name="phonenumber" value="<? print($phonenumber) ?>" datatype="*" errormsg="请输入" placeholder="联系电话">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">真实姓名</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" maxlength="30" name="truename" value="<? print($truename) ?>">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">密码</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="password" maxlength="30" value="" name="password" id="password" maxlength="30" datatype="*" errormsg="请输入密码" placeholder="密码">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">确认密码</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="password" maxlength="30" value="" name="password_confirmation" id="password_confirmation" maxlength="30" datatype="*" errormsg="请输入密码" placeholder="密码">
                    </div>
                </div>
            <?
                if (!$isFeeTeacher) {//管理员
            ?>
                <div class="form-group">
                    <div class="label">
                       <label for="name">是否收费会员</label>
                    </div>
                    <div class="field" style="line-height: 35px">
                        <? include("page_element/isfee.php"); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">开始时间</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" maxlength="30" name="paytime" value="2016-07-01 00:00:00" class="laydate-icon" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">截止时间</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" maxlength="30" name="endtime" value="2022-12-31 23:59:59" class="laydate-icon" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                    </div>
                </div>
            <?
                }
            ?>
                <input type="hidden" name='ac' value="add">
                <div class="form-group">
                    <div class="label">
                        <label for="name"></label>
                    </div>
                    <div class="field">
                        <input class="input button bg-main" type="submit" id="submit" name="submit" class="btn2" value="增加用户" onclick="return checkTheForm(this.form);">
                    </div>
                </div>
        </form>
	<script type="text/javascript" src="js/validform.js"></script>
    <script type="text/javascript">
        function checkTheForm(frm) {
        	//alert();
            var pwd1 = $("#password");
            var pwd2 = $("#password_confirmation");
            //alert("修改密码=" + 7 + "; pwd1=" + pwd1.val() + "; pwd2=" + pwd2.val());
            if (pwd1.val().length < 6) {
                alert("必须填写6位以上密码");
                pwd1.focus();
                return false;
            }
            if (pwd2.val().length < 6) {
                alert("必须填写6位以上确认密码");
                pwd2.focus();
                return false;
            }
            if (pwd1.val() != pwd2.val()) {
                alert("二次密码不一致");
                pwd2.focus();
                return false;
            }
            return true;
        }
        $(function(){
            $(".rgform").Validform({
                tiptype:2,
                label:".label",
                showAllError:true,
                postonce:true
            });
        })
    </script>

<?
include("footer.php");
include("bubbleUniversity.php");
?>