<?
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("header.php");
error_reporting(0);
session_start();
if(!isset($dsql)){
	$dsql = new DSQL();
}
if ($auth_pid != 3) {
    exit;
}
?>
<script type="text/javascript" src="js/validform.js"></script>
<script type="text/javascript" language="javascript" src="js/laydate.js"></script>
<script type="text/javascript" language="javascript" >
    function show(s)
    {
        //alert(s);
        if(s==1){
    		$("#payquantity").show();
    		$("#code").show();
    		$("#moocurl").hide();

        }else{
        	$("#moocurl").show();
        	$("#payquantity") .hide();
    		$("#code") .hide();

        }
    }
</script>
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
                <li>增加课程</li>
            </ul>
            <div class="step">
                <div class="step-bar active" style="width: 25%;">
                    <span class="step-point">1</span><span class="step-text">第一步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">2</span><span class="step-text">第二步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">3</span><span class="step-text">第三步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">4</span><span class="step-text">第四步</span>
                </div>
            </div>
			<form action="addclass2.php" method="get"  class="form-x rgform3" enctype="multipart/form-data">
				<div class="form-group">
                    <div class="label">
					   <label for="name">课程名称：</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="name" maxlength="100" placeholder="课程名称" datatype="*" errormsg="请输入名称" nullmsg="请输入名称">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
					   <label for="text1">选择分类：</label><span class='hint'>*</span>
                    </div>
                    <select name="coursesgroupid" size=1 >
                        <option value="1"> - 无 - </option>
                        <?
                            $SQL = "SELECT    id ,	name 	  	,uid ,	pid 	,path 	,status     FROM coursesgroup  order by path     ";
                            //echo $SQL . "\n";
                            $dsql->query($SQL);
                            $nbsp = $pernbsp = "|---------";
                            $olddepth =1;
                            while($dsql->next_record()){
                                $id=$dsql->f('id');
                                $group_name=$dsql->f('name');
                                $insertdate=$dsql->f('insertdate');
                                $userid=$dsql->f('userid');
                                $group_pid=$dsql->f('pid');
                                $path =$dsql->f('path');
                                $newdepth =  substr_count($path,',');
                                $select = "";
                                if($id==$pid){
                                    $select = "selected='selected' ";
                                }
                                if($newdepth==$olddepth){
                                    //$nbsp = "|--";
                                    echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";

                                }else if($newdepth>$olddepth){
                                    $nbsp = $nbsp.$pernbsp;
                                    echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";
                                }else{
                                    $depthno = $olddepth - $newdepth;
                                    $nbsp = substr($nbsp,0,strlen($nbsp)-strlen($pernbsp)*$depthno);
                                    echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";
                                }
                                $olddepth =  $newdepth;
                            }
                        ?>
                    </select>
                </div>
                <!-- <label for="text1"></label><input class="input" type="file" name="coursesimg" > -->
                <div class="form-group">
                    <div class="label">
                        <label for="upfile">
                            课程图片：
                        </label>
                    </div>
                    <div class="field">
                        <!-- <a class="button input-file"> -->
                            <!-- + 请选择上传文件 -->
                            <input size="100" type="file" name="coursesimg">
                        <!-- </a> -->
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
					   <label for="content">课程简介：</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="content" maxlength="100" placeholder="简介">
				    </div>
				</div>
                <div class="form-group">
                    <div class="label">
    					<label for="text1">负责人邮箱：</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="useremail" maxlength="100"  id="useremail"   onkeyup="searchDev(this.value)" >
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="text1">慕课课程：</label><span class='hint'>*</span>
                    </div>
                    <select name="moocid" size=1 onchange="show(this.options[this.options.selectedIndex].value)">
						<?
    						$SQL = "SELECT    id ,	name 	    FROM mooc  order by id desc     ";
    						$dsql->query($SQL);
    						$nbsp = $pernbsp = "|---------";
    						$olddepth =1;
    						while($dsql->next_record()){
    							$id=$dsql->f('id');
    							$name=$dsql->f('name');
                                if($id==1){
                                    echo "<option value=\"$id\"  selected=\"selected\">$name</option>";
                                }else{
                                    echo "<option value=\"$id\">$name</option>";
                                }
    						}
						?>
                    </select>
                </div>                
				<p id="payquantity">
                    <div class="form-group">
                        <div class="label">
				            <label for="text1">使用人次：</label><span class='hint'>*</span>
                        </div>
                        <div class="field">
                            <input class="input" type="text" name="payquantity" maxlength="5" placeholder="人次" datatype="n" errormsg="请输入正确数字" nullmsg="请输入数字">
                        </div>
                    </div>
                </p>
				<p id="moocurl" style="display:none">
                    <div class="form-group">
                        <div class="label">
					       <label for="text1">MOOC地址：</label><span class='hint'>*</span>
                        </div>
                        <div class="field">
                            <input class="input" type="text" name="moocurl" placeholder="MOOC地址">
                        </div>
                    </div>
                </p>
                <div class="form-group">
                    <div class="label">
			            <label for="text3">使用期限：</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="starttime"  class="laydate-icon" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})" value="<?print( date("Y-m-d H:i:s"))?>" >
                    </div>
                </div>
				<div class="form-group">
                    <div class="label">
					   <label for="text3">至：</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="endtime" class="laydate-icon" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"  value="<?print( date("Y-m-d H:i:s",time()+180*24*3600))?>" >
                    </div>
				</div>
				<div class="form-group">
                    <div class="label">
					   <label for="text1">排序值：</label><span class='hint'>*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="sort" maxlength="8" value="500"  placeholder="排序值" datatype="*" errormsg="请输入排序值" nullmsg="请输入排序值">
				    </div>
                </div>
                <div class="form-group">
                    <div class="label">		
					   <label for="submit"> &nbsp;</label>
                    </div>
                    <div class="field">
                        <input type="submit" class="input button bg-main" id="submit" name="submit"  value="下一步">
				    </div>
                </div>
				<input type=hidden name="action" value="add">
            </form>
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
        temptimeout= setTimeout(findUnSaved, 0);
    }  
      
    function findUnSaved()  
    {  
        //alert("dd");  
        //if(1==1)return;  
        $.ajax({  
            type: "post",     
            data:{'query':query},  
            url:  "useremail.php",          
            success: function(data) {             
                 xiala(data);  
            },  
            error: function(data) {  
                alert("加载失败，请重试！");  
                  
            }  
        });   
    }  
    function initSearch()  
    {  
        //定义一个下拉按钮层，并配置样式(位置，定位点坐标，大小，背景图片，Z轴)，追加到文本框后面   
        $xialaDIV = $('<div></div>').css('position', 'absolute').css('left', $('#useremail').position().left + $('#useremail').width() - 15  + 'px').css('top',   
        $('#useremail').position().top + 4 + 'px').css('background', 'transparent url(../images/lala.gif) no-repeat top left').css('height', '16px').css('width',   
            '15px').css('z-index', '100');   
        $('#useremail').after($xialaDIV);   
          
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
        $('#useremail').mouseup(function(){    
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
        if(firstTimeYes == 1)  
        {     
        firstTimeYes =firstTimeYes+1;  
        }else{  
            $xialaSELECT.show();   
        }         
    }  
    function initXialaSelect()  
    {  
          
        $xialaSELECT = $('<div></div>').css('position', 'absolute').css('overflow-y','scroll').css('overflow-x','hidden').css('border', '1px solid #809DB9').css('border-top','none').css('left', $('#useremail').position().left+ 'px').css
        ('top', $('#useremail').position().top + $('#useremail').height()  + 'px').css('width',$('#useremail').width()+300).css('z-index', '101').css('background','#fff').css('height','200px').css('max-height','600px');
        $('#useremail').after($xialaSELECT);   
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
            }   
            else {   
                //如果是选项层，则改变文本框的值   
                if ($(event.target).attr('name') == 'option') {   
                    //弹出value观察   
                    $('#nce').val($(event.target).html());   
                    $('#d').val($(event.target).attr("d"));   
                      
                    //if seleced host then hidden the dev type  
                    if($(event.target).attr("ass") == 3305)  
                    {  
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
    function clicks()  
    {  
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
<script type="text/javascript">
    $(function(){
    	//$(".registerform").Validform();  //就这一行代码！;
    	$(".rgform3").Validform({
          tiptype:3,
      		label:".label",
      		showAllError:true,
    		postonce:true
    	});
    })
</script>
<?
include("footer.php");
?>