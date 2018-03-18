<?
require_once("header.php");

$rand=rand(10000,99999);
$_SESSION["findpasswordrand"] = "$rand";//保存id
?>
<div class="contain lg">
  <div class="window">
    <form name="loginForm" action="findpassword-ok.php" method = "post">
      <div class="h1">找回密码</div>
      <br>
      <p>
        <input id="email" name="email" class="txt" type="text" placeholder="邮箱" maxlength="33">
        <input id="rand" name="rand" class="txt" type="hidden" placeholder="邮箱" value="<?print($rand)?>" maxlength="33">
      </p>

      <p><input type="button" class="btn" id="subm" value="提交" onClick="userLogin();" ></p>
      <div class="form_btm">
        <div class="fb_lf"><a href="login.php" ><small>登录</small></a></div>
        <div class="fb_rt"><small>还没注册?</small><a onClick="userReg();" style="cursor: pointer;"><small>注册</small></a></div>
      </div>
    </form>
  </div>
</div>

  <script type="text/javascript" language="javascript" src="js/jquery-1.9.1.min.js">
                      </script>
  <script type="text/javascript">
  var actionid=0;
  function checkForm(){

    var eml = document.getElementById("email");

    var email=eml.value;
    if(email.length==0){
      //showAlertMsg("请输入邮箱！");
      eml.focus();
      return false;
    }
    var b =  /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
        //邮箱正则表达式对象
    if (!b.test(email)) {//
      alert("请输入正确邮箱！");
      eml.focus();
      return false;
    }
    
  	var subm= document.getElementById("subm");
  	subm.value = "正在处理，请稍候……";
  	subm.disabled = true;

  	
    $.ajax({//先判断邮箱是否存在
      type:"post",
      url :"findpassword-ok.php",
      data: {"email":email, "todo": "queryemail","formrand":<?print($rand)?>},
      datatype:"json",
      success:function(data){
        var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
        //var email = dataJson.data[0].email;
        var userid = dataJson.data[0].userid;
        var nickname = dataJson.data[0].nickname;
        //alert("email=" + email + "； userid=" + userid + "； nickname=" + nickname);
        if (nickname == "BLANK") {
          alert("您输入的邮箱不存在！");
          return;
        } else if (email == "SUCCESS") {
          //alert("修改成功！");
        } else {
          var state = dataJson.data[1].state;
          if (state == "") {
            alert("邮件发送失败！");
          } else {
            alert("密码在邮件中，请查收邮件！");
          }
        }

      }
    })
    alert("密码找回邮件已经发送，请稍后到邮件查看");
    window.location="index.php" ;
  	subm.value = "提交";
  	subm.disabled = false;

    //return true;
  }
  //用户登录
  function userLogin(){
    if(checkForm()){
      document.loginForm.submit();
    }
  }
  function userReg(){
      window.location = "register.php" ;
  }
  </script>
<?
include("footer.php");
?>