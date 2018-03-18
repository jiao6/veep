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


?>
<style type="text/css">
    .mycode {
        background: #1E8997;
    }
    .mycode a {
        color: #fff;
    }
</style>
<script type="text/javascript" language="javascript" src="js/laydate.js">
</script>
<script type="text/javascript" src="js/validform.js"></script>
<script>
        $(function(){
            //$(".registerform").Validform();  //就这一行代码！;
            $(".rgform").Validform({
                tiptype:3,
                label:".label",
                showAllError:true,
                postonce:true
            });

            // $('.hint').detach().appendTo($(this).parent());
        })
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
            <div class="rhead1">选课码选课</div>
        </div>

        <form action="mycode-finish.php" class="rgform form-x" method="post" onsubmit="return false;">
            <div class="form-group">
                 <div class="label">
                	<label for="name">选课码</label>
                </div>
                <div class="field">
                	<input type="text" name="code" id="code" value="" maxlength="11" placeholder="请输入选课码" errormsg="请输入选课码" nullmsg="请输入选课码"  datatype="n6-16" class="input">
                </div>
            </div>
            <div class="form-group">
                 <div class="label">
                	<label for="name"></label>
                </div>
                <div class="field">
            		<INPUT TYPE="hidden" name='ac' value="code">
            	</div>
            <div class="form-group">
            	<div class="label">
                <label for="name"> </label>
                </div>
                <div class="field">
                	<input type="submit" id="submit" name="submit" class="submit button button-small bg-main" value="选课" onclick="selectCourse(); return false;" >		
                 </div>
             </div>
            <br/><br/>


            <!--div><input type="button" id="" name="btn1" class="btn2"  value="打开弹窗" onclick="showMessage('臭狗屎', 10)"></div>
            <div><input type="button" id="btn1" name="btn1" class="btn2"  value="弹窗1(信息)"></div>
            <div><input type="button" id="btn2" name="btn1" class="btn2"  value="弹窗2(提示)"></div>
            <div><input type="button" id="btn3" name="btn1" class="btn2"  value="弹窗3(警告)"></div>
            <div><input type="button" id="btn4" name="btn1" class="btn2"  value="弹窗4(错误)"></div>
            <div><input type="button" id="btn5" name="btn1" class="btn2"  value="弹窗5(成功)"></div>
            <div><input type="button" id="btn6" name="btn1" class="btn2"  value="弹窗6(输入框)"></div>
            <div><input type="button" id="btn7" name="btn1" class="btn2"  value="弹窗7(自定义)"></div>
            <div><input type="button" id="btn8" name="btn1" class="btn2"  value="弹窗8(默认)"></div-->
        </form>
<script>
    function selectCourse() {
    	var code = $("#code").val();
    	if (code.length < 6) return false;
    	if(isNaN(code)) return false;
    	showMessage('正在处理，请稍候……', 20);
	    $.ajax({//先判断邮箱是否存在
	      type:"post",
	      url :"mycode-finish.php",
	      data: {"ac": "code", "code": code},
	      datatype:"json",
	      success:function(data){
	        var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
	        //alert();
	        var result = dataJson.data[0].result;
	        var message = dataJson.data[0].message;
	        //alert("result=" + result + "； message=" + message);
	        if (result == "SUCCESS") {
	          //alert("您输入的邮箱不存在！");
	          //return;
	        	showMessage(message, 50);
	        } else {//处理失败
	        	showMessage(message, 40);
	        }
	
	      }
	    })



	    //alert("密码找回邮件已经发送，请稍后到邮件查看");
	    //window.location="index.php" ;
    	return false;
    }
</script>
<?


include("footer.php");
include("page_element/bubble_alert.php");
?>


