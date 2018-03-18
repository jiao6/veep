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

$dsql = new DSQL();
if ($ac == "editupdate") {

}

$SQL = "select * from users where id = $auth_id  ";
//echo $SQL;
$dsql->query($SQL);

if ($dsql->next_record()) {
    $id = $dsql->f('id');
    $nickname = $dsql->f('nickname');
    $email = $dsql->f('email');
    $password = $dsql->f('password');
    $university = $dsql->f('university');
    $universityId = $dsql->f('university_id');
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
    $studentno = $dsql->f('studentno');
    $class = $dsql->f('class');

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
        .myuseredit {
		        background: #1E8997;
		    }
		    .myuseredit a {
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
	    <? /* 定义大学 */
	    	$universityId = '<div class="form-group">' .
	        '<div class="label"><label for="name">学校</label><span class="hint">*</span></div>'.
	        '<div class="field"><input class="input" type="text" maxlength="30" id="university" name="university" autocomplete="off" value="' . $university .'" readOnly style="" onclick="showProvince(this, 100, '. $universityId .', \''. $university .'\')" ></div>'.
	        '<input type="hidden" id="universityId" name="universityId" value="'.$universityId.'" />'
	        . '</div>';
	    ?>
	    <form action="useredit.php" class="rgform form-x"  method="post">
        <div class="form-group">
        	<div class="label">
		        <label for="name">邮箱(不能修改)</label>
        	</div>
        	<div class="field">
        		<input class="input" type="text" id="email" name="email"  maxlength="50" disabled="disabled"  value="<? print($email) ?>">
        	</div>
        </div>
        <div class="form-group">
					<div class="label">
        		<label for="name">昵称</label><span class='hint'>*</span>
					</div>
					<div class="field">
        		<input class="input" type="text" name="nickname"  value="<? print($nickname) ?>"  maxlength="18" datatype="s3-18" errormsg="至少3个字符,最多18个字符" >
        	</div>
        </div>
	    <? /* 定义大学 */
	    echo $universityId ;
	    ?>
	    <div class="form-group">
	    	<div class="label">
			    <label for="name">学院</label>
			  </div>
			  <div class="field">
			    <input class="input" type="text" name="college" value="<? print($college) ?>"  maxlength="48" >
			  </div>
	    </div>
	    <div class="form-group">
	    	<div class="label">
		    	<label for="name">联系电话</label><span class='hint'>*</span>
		    </div>
		    <div class="field">
		    	<input class="input" type="text" name="phonenumber" value="<? print($phonenumber) ?>"  maxlength="30"  errormsg="请输入电话或手机号"   datatype="/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/">
		    </div>
	    </div>
	    <div class="form-group">
	    	<div class="label">
		    	<label for="name">真实姓名</label><span class='hint'>*</span>
		    </div>
		    <div class="field">
		    	<input class="input" type="text" name="truename" value="<? print($truename) ?>"   datatype="s1-25" errormsg="注意输入1-25个字符"  maxlength="25">
		    </div>
	    </div>
     	<?if($auth_pid==1){?>
        <div class="form-group">
	        <div class="label">
	        	<label for="name">学号</label>
	        </div>
	        <div class="field">
        		<input class="input" type="text" name="studentno" value="<? print($studentno) ?>" maxlength="25">
        	</div>
        </div>
        <div class="form-group">
	        <div class="label">
	        	<label for="name">班级</label>
	        </div>
	        <div class="field">
        		<input class="input" type="text" name="class" value="<? print($class) ?>" maxlength="25">
        	</div>
        </div>
    	<?}?>
      <input class="input" type="hidden" name='id' value="<? print($id) ?>"><input class="input" type="hidden" name='ac' value="editupdate">
      <div class="form-group">
        <div class="label">
        		<label for="submit"></label>
					</div>
					<div class="field">
        		<input class="input button bg-main" type="submit" id="submit" name="submit" value="修改">
        	</div>
      </div>
    </form>


    <script type="text/javascript" src="js/validform.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".rgform").Validform({
                tiptype:3,

                showAllError:true,
                postonce:true

            });
        })
    </script>
    <script language="javascript">
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
            //$('#university').after($xialaDIV);
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
                //$xialaDIV.css('background-position', ' 0% -16px');
                //$xialaSELECT.show();
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
        }
    </script>
    <?
}



include("footer.php");
include("bubbleUniversity.php");
?>


