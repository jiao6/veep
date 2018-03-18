<?
require_once("header.php");
?>
<style type="text/css">
    .cba {
        width: 150px;
        height: 18px;
    }
    .selectlala {
        text-align: left;
        line-height: 20px;
        padding-left: 5px;
        width: 147px;
        font-family:  Arial,verdana,tahoma;
        height: 20px;
    }
    .passwordStrength{

    }
    .passwordStrength b{
        font-weight:normal;
    }
    .passwordStrength b,.passwordStrength span{
        display:inline-block;
        vertical-align:middle;
        line-height:16px;
        line-height:18px\9;
        height:16px;
    }
    .passwordStrength span.pass_str{
        width:45px;
        text-align:center;
        background-color:#d0d0d0;
        border-right:1px solid #fff;
    }
    .passwordStrength .last{
        border-right:none;
    }
    .passwordStrength .bgStrength{
        color:#fff;
        background-color:#71b83d;
    }
    #demo1 .passwordStrength{
        margin-left:8px;
    }
</style>
<div class="rgwin">
  <div class="rg_head">注册</div>
  <form action="register_action.php" class="rgform">
    <input class="form-control" id="email" name="action" value="add" style="display: none;">
    <? include "page_element/user_type.php" ?>
    <div>
        <label for="name">邮箱(登录账号)</label>
        <input type="text" id="email" name="email" class="form-control"  maxlength="50" placeholder="请输入常用邮箱。请慎重填写，以后不可修改" ajaxurl="uservalid.php?ac=ajax" datatype="e" errormsg="请输入正确邮箱" nullmsg="请输入常用邮箱">
        <span class='hint'>*</span>
    </div>
    <div>
        <label for="name">昵称</label>
        <input type="text" name="nickname"  class="form-control" maxlength="18" datatype="s3-18" errormsg="至少3个字符,最多18个字符"  nullmsg="请输入昵称">
        <span class='hint'>*</span>
    </div>
    <!-- 定义大学 --><? include "page_element/universities.php" ?>
    <div>
        <label for="name">学院</label>
        <input type="text" name="college"  class="form-control"   maxlength="48" >
    </div>
    <div>
        <label for="name">手机</label>
        <input type="text" name="phonenumber" placeholder="13012345678" class="form-control" placeholder="请输入11位手机号码" maxlength="11"  errormsg="请输入11位手机号码"   nullmsg="请输入11位手机号码"  datatype="m" >
        <span class='hint'>*</span>
    </div>
    <div>
        <label for="name">真实姓名</label>
        <input type="text" name="truename"  class="form-control" datatype="s1-25" errormsg="注意输入1-25个字符"  maxlength="25"  nullmsg="请输入真实姓名" >
        <span class='hint'>*</span>
    </div>
    <div>
        <label for="name">密码</label>
        <input type="password" name="password"  class="form-control" plugin="passwordStrength" maxlength="18" datatype="*6-18" errormsg="至少6个字符"  nullmsg="请输入密码">
        <span class='hint'>*</span>
    </div>
    <div class="passwordStrength">密码强度
        <span class="pass_str" style="margin-left:53px;">弱</span>
        <span class="pass_str">中</span>
        <span class="last pass_str">强</span>
    </div>
    <div>
        <label for="name">确认密码</label>
        <input type="password" name="password_confirmation"   class="form-control"   maxlength="18" placeholder="确认密码" datatype="*"  recheck="password" errormsg="两次输入密码不一致！"   nullmsg="请输入确认密码">
        <span class='hint'>*</span>
    </div>
      <div>

        <input type="checkbox"  name="accept" value="1" datatype="*"  style="height: 22px;width: 18px;" eerrormsg="必须同意用户协议"  nullmsg="请阅读并接受用户协议"> <span  style="margin-left: 150px ">阅读并接受<a href="protocal.php"  target="_blank"  style="color:#FF0000">《用户协议》</a></span>
      </div>

    <div>

          <input type="submit" id="submit" name="submit" class="btn2" value="注册"  >
    </div>

  </form>
</div>
<script type="text/javascript" src="js/passwordStrength-min.js"></script>
<script type="text/javascript" src="js/validform.js"></script>
<script language="javascript">
    /*
    var $xialaSELECT;
    $(document).ready(function(){
        initXialaSelect();
        initSearch();
    });
    var temptimeout=null;
    var query="";
    function searchDev(key){
        //if(key == "")return;
        query=key;
        clearTimeout(temptimeout);
        temptimeout= setTimeout(findUnSaved, 500);
    }

    function findUnSaved()  {
        //alert("dd");
        //if(1==1)return;
        $.ajax({
            type: "post",
            data:{'query':query},
            url:  "useruniversity.php",
            success: function(data) {
                 xiala(data);
            },
            error: function(data) {
                alert("加载失败，请重试！");

            }
        });
    }
    function initSearch()  {
        //定义一个下拉按钮层，并配置样式(位置，定位点坐标，大小，背景图片，Z轴)，追加到文本框后面
        $xialaDIV = $('<div></div>').css('position', 'absolute').css('left', $('#university').position().left + $('#university').width() - 15  + 'px').css('top',
        $('#university').position().top + 4 + 'px').css('background', 'transparent url(../images/lala.gif) no-repeat top left').css('height', '16px').css('width',
            '15px').css('z-index', '100');
        $('#university').after($xialaDIV);
        //鼠标进入修改背景图位置
        $xialaDIV.mouseover(function(){
            $xialaDIV.css('background-position', ' 0% -16px');
        });
        //鼠标移出修改背景图位置
        $xialaDIV.mouseout(function(){
            $xialaDIV.css('background-position', ' 0% -0px');
        });

        //鼠标按下修改背景图位置
        $xialaDIV.mousedown(function(){

            $xialaDIV.css('background-position', ' 0% -32px');
        });

        //鼠标释放修改背景图位置
        $xialaDIV.mouseup(function(){
            $xialaDIV.css('background-position', ' 0% -16px');
            if($xialaSELECT)
            $xialaSELECT.show();
        });

        $('#university').mouseup(function(){
            $xialaDIV.css('background-position', ' 0% -16px');
            $xialaSELECT.show();
        });
    }
    var firstTimeYes=1;
    //文本框的下拉框div
    function xiala(data){
        //first time
        if($xialaSELECT)
            {
                $xialaSELECT.empty();
            }
        //定义一个下拉框层，并配置样式(位置，定位点坐标，宽度，Z轴)，先将其隐藏
        //定义五个选项层，并配置样式(宽度，Z轴一定要比下拉框层高)，添加name、value属性，加入下拉框层

        $xialaSELECT.append(data);
        if(firstTimeYes == 1) {
            firstTimeYes =firstTimeYes+1;
        }else{
            $xialaSELECT.show();
        }
    }
    function initXialaSelect() {
        $xialaSELECT = $('<div></div>').css('position', 'absolute').css('overflow-y','scroll').css('overflow-x','hidden').css('border', '1px solid #809DB9').css('border-top','none').css('left', $('#university').position().left-15+ 'px').css
        ('top', $('#university').position().top + $('#university').height() - 8 + 'px').css('width','500px').css('z-index', '101').css('background','#fff').css('height','200px').css('max-height','600px');
        $('#university').after($xialaSELECT);
        //选项层的鼠标移入移出样式
        $xialaSELECT.mouseover(function(event){
            if ($(event.target).attr('name') == 'option') {
                //移入时背景色变深，字色变白
                    $(event.target).css('background-color', '#000077').css('color', 'white');
                $(event.target).mouseout(function(){
                //移出是背景色变白，字色变黑
                    $(event.target).css('background-color', '#FFFFFF').css('color', '#000000');
                });
            }
        });

        //通过点击位置，判断弹出的显示
        $xialaSELECT.mouseup(function(event){
            //如果是下拉按钮层或下拉框层，则依然显示下拉框层
            if (event.target == $xialaSELECT.get(0) || event.target == $xialaDIV.get(0)) {
                $xialaSELECT.show();
            } else {
                //如果是选项层，则改变文本框的值
                if ($(event.target).attr('name') == 'option') {
                    //弹出value观察
                    $('#nce').val($(event.target).html());
                    $('#d').val($(event.target).attr("d"));

                    //if seleced host then hidden the dev type
                    if($(event.target).attr("ass") == 3305) {
                            $("#ype").hide();
                            $("#ost").val(1);
                    }else{
                        $("#ype").show();
                        $("#ost").val(-1);
                    }
                }
                //如果是其他位置，则将下拉框层
                if ($xialaSELECT.css('display') == 'block') {
                    $xialaSELECT.hide();
                }
            }
        });

        $xialaSELECT.hide();
    }
    var k = 1;
    document.onclick = clicks;
    function clicks() {
        if(k ==2){
            k = 1;
            if($xialaSELECT)
                {
                if ($xialaSELECT.css('display') == 'block') {
                    $xialaSELECT.hide();
                }
                }
        }else{
            k = 2;
        }
    } */
</script>
<script type="text/javascript">
    $(function(){
        $(".rgform").Validform({
          tiptype:3,
            label:".label",
            showAllError:true,
            postonce:true,
            usePlugin:{
                passwordstrength:{
                    minLen:6,
                    maxLen:18
                }
            }

        });
    })
</script>

<br>
<?
include("footer.php");
include("bubbleUniversity.php");
?>