<?
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("header.php");
error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}




if(!isset($dsql)){
    $dsql = new DSQL();
}
if(!isset($dsql2)){
    $dsql2 = new DSQL();
}
if($action=="add"){
    if ($auth_pid != 3) {
        exit;
    }
    if($_FILES["coursesimg"]["name"]){
        @mkdir("data");

        @mkdir("data/coursesimg");

        $coursesimg = "data/coursesimg/". $_FILES["coursesimg"]["name"];

        copy($_FILES["coursesimg"]["tmp_name"],$coursesimg);
    }else{
        $coursesimg = "img/course.jpg";
    }
	$SQL =  "select id from users where email = '$useremail'";//查找获得者的用户 id
    $dsql->query($SQL);
	$dsql->next_record();
	$teacherId = $dsql->f('id');
		
    $SQL =  "insert into courses (
			id,name,moocurl,starttime,endtime,
			userid,created_at,updated_at,coursesimg,content,
			useremail,code,payquantity,moocid,step,
			coursesgroupid,sort, TEACHER_ID
		)values(
			0,'$name','$moocurl','$starttime','$endtime',
			'$auth_id',now(),'$updated_at','$coursesimg','$content',
			'$useremail','$code','$payquantity','$moocid','$step',
			'$coursesgroupid','$sort', '$teacherId'
		)";
    $dsql->query($SQL);
    //echo "$SQL";
    $dsql->query("select last_insert_id() as id from courses ");
    $dsql->next_record();
    $coursesid=$dsql->f('id');
    //echo $coursesid;
}else{

}

$SQL = "SELECT id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity,moocid,step 
	FROM courses where id='$coursesid'";
//echo $SQL . "\n";
$dsql->query($SQL);
$nbsp = $pernbsp = "|---------";
$olddepth =1;
if($dsql->next_record()){
    $id = $dsql->f('id');
    $name = $dsql->f('name');
    $moocurl = $dsql->f('moocurl');
    $starttime = $dsql->f('starttime');
    $endtime = $dsql->f('endtime');
    $userid = $dsql->f('userid');
    $created_at = $dsql->f('created_at');
    $updated_at = $dsql->f('updated_at');
    $coursesimg = $dsql->f('coursesimg');
    $content = $dsql->f('content');
    $useremail = $dsql->f('useremail');
    $code = $dsql->f('code');
    $payquantity = $dsql->f('payquantity');
    $moocid = $dsql->f('moocid');
    $step = $dsql->f('step');
}

?>

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
                <div class="step-bar complete" style="width: 25%;">
                    <span class="step-point icon-check"></span><span class="step-text">第一步</span>
                </div>
                <div class="step-bar active" style="width: 25%;">
                    <span class="step-point">2</span><span class="step-text">第二步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">3</span><span class="step-text">第三步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">4</span><span class="step-text">第四步</span>
                </div>
            </div>
            <div class="form-x" style="width: 90%">
                <div class="form-group">
                    <div class="label">
                        <label for="sort">新增课程名称：</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="sort" maxlength="8" value="<?print($name)?>" disabled>
                    </div>
                </div>
                <?
                    if($moocurl!=''){
                        echo "<span>MOOC地址：$moocurl</span><br/>";
                    }
                ?>
                <div class="form-group">
                    <div class="label">
                        <label for="sort">起：</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="sort" maxlength="8" value="<?print($starttime)?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="sort">止：</label>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="sort" maxlength="8" value="<?print($endtime)?>" disabled>
                    </div>
                </div>
            </div>
            <div>
                <form name="frm" action="addclass3.php" method="get" class="select_form form-x" style="width: 100%">
                    <table border='0' style="width: 100%">
                        <tr style="border: 0;background: #fff;height: 100%">
                            <td style="border: 1px solid #ccc;height: 100%;width: 40%">
                                <select name='SrcSelect' size='16' class="ecv_autoSizeDropDown" style="height: 100%;width: 100%" multiple ondblclick="moveLeftOrRight(document.frm.SrcSelect,document.frm.ObjSelect)">
                                    <?
                                        if($auth_pid==2||$auth_pid==4){
                                            $SQL2 = "select pid from  courses where id= '$coursesid' and isclass=1 ";
                                           // echo $SQL2;
                                            $dsql2->query($SQL2);
                                            if($dsql2->next_record()){
                                                $pid =$dsql2->f('pid');
                                            }
                                            //echo "pid=" . $pid . "<br/>";
                                            $SQL2 = "SELECT id ,name FROM experiments  where  id in (SELECT    experimentsid  FROM coursesexperiment   where    coursesid =  '$pid' and experimentsid not in (select experimentsid from  coursesexperiment where coursesid= '$coursesid') )  ";
                                            //echo $SQL2;
                                            $dsql2->query($SQL2);
                                            while($dsql2->next_record()) {
                                                $id = $dsql2->f('experimentsid');
                                                $name = $dsql2->f('name');
                                                echo "<option value=\"$id\"  >$nbsp$pernbsp|$name</option>";
                                            }
                                        }else if($auth_pid==3){
                                            $SQL = "SELECT id ,name,uid ,pid,path,status     FROM experimentsgroup  order by path     ";
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
                                                    echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";

                                                }else if($newdepth>$olddepth){
                                                    $nbsp = $nbsp.$pernbsp;
                                                    echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";
                                                }else{
                                                    $depthno = $olddepth - $newdepth;
                                                    $nbsp = substr($nbsp,0,strlen($nbsp)-strlen($pernbsp)*$depthno);
                                                    echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";
                                                }
                                                $SQL2 = "SELECT    id ,name FROM experiments  where groupid = '$id'   ";
                                                //echo $SQL2;
                                                $dsql2->query($SQL2);
                                                while($dsql2->next_record()){
                                                    $id=$dsql2->f('id');
                                                    $name=$dsql2->f('name');
                                                    echo "<option value=\"$id\"  >$nbsp$pernbsp|《${name}》</option>";
                                                }

                                                $olddepth =  $newdepth;

                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                            <td style="border: 0;padding: 10px;vertical-align: middle;text-align: center;">
                                &nbsp;<input align="left" type=button value=">>" onclick="moveLeftOrRightAll(document.frm.SrcSelect,document.frm.ObjSelect)" >&nbsp;<br>
                                &nbsp;<input align="left" type=button value=">" onclick="moveLeftOrRight(document.frm.SrcSelect,document.frm.ObjSelect)" >&nbsp;<br>
                                &nbsp;<input align="left" type=button value="<" onclick="moveLeftOrRight(document.frm.ObjSelect,document.frm.SrcSelect)" >&nbsp;<br>
                                &nbsp;<input align="left" type=button value="<<" onclick="moveLeftOrRightAll(document.frm.ObjSelect,document.frm.SrcSelect)" >&nbsp;
                            </td>
                            <td style="border: 1px solid #ccc;height: 100%;width: 40%">
                                <select id="ObjSelect" name="experiments[]" size=16 class="ecv_autoSizeDropDown" style="height: 100%;width: 100%" multiple ondblclick="moveLeftOrRight(document.frm.ObjSelect,document.frm.SrcSelect)">
                                    <?
                                    $SQL = "SELECT  id ,name FROM experiments  where  id in (SELECT  experimentsid as id    FROM coursesexperiment ce  WHERE ce.coursesid =$coursesid and COURSE_TYPE=". MetaDataGenerator::COURSE_TYPE_KECHENG ." )";
                                    $dsql->query($SQL);
                                    $arrayid[0] = 0;
                                    while($dsql->next_record()){
                                        $id = $dsql->f('id');
                                        $name = $dsql->f('name');

                                        echo "<option value=\"$id\"  >$name</option>";
                                        $arrayid[] = $id;
                                    }
                                    $arrayids = implode(",",$arrayid);
                                    ?>
                                </select>
                                    <? //echo $SQL . "\n"; ?>
                            </td>
                            <td width="30px" style="border: 0;padding: 10px;vertical-align: middle;text-align: center;">
                                <input type=button value="置顶" onclick="moveToTop(document.frm.ObjSelect)" ><br>
                                <input type=button value="向上" onclick="moveUp(document.frm.ObjSelect)" ><br>
                                <input type=button value="向下" onclick="moveDown(document.frm.ObjSelect)" ><br>
                                <input type=button value="置底" onclick="moveToBottom(document.frm.ObjSelect)" >
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <input type=hidden name="itemId" id="itemId" value="">
                    <input type=hidden name="action" value="add">
                    <input type=hidden name="coursesid" value="<?print($coursesid)?>">
                    <div class="form-group" style="width: 90%">
                        <div class="label">
                            <label for="submit"></label>
                        </div>
                        <div class="field">
                            <input type="submit" class="input button bg-main" id="submit" name="submit"  value="下一步" onclick="experimentsall();" style="width: 90%">
                        </div>
                    </div>
                </form>
    <script language=javascript>
        var ids = [<?print($arrayids)?>];
        function experimentsall(){
            var bbb = "";
            for(var i = 0; i < document.getElementById("ObjSelect").length; i++){
                var aaa = document.getElementById("ObjSelect").options[i].value;
                bbb = bbb + "," + aaa;
            }
            bbb = bbb.substr(1, bbb.length-1);
            document.getElementById("itemId").value = bbb;
            return true;
        }
        function moveLeftOrRight(fromObj,toObj){
            var fromObjOptions=fromObj.options;
            for(var i=0;i<fromObjOptions.length;i++){
                if(fromObjOptions[i].selected){
                    if(fromObjOptions[i].value.substr(0,1)=='g'){
                        alert("分组名称不能选择");
                    }else if(ids.indexOf(parseInt(fromObjOptions[i].value))>-1){
                        alert("已经入库的实验不能再次操作,如需删除请在管理实验里删除");
                    }else {
                        toObj.appendChild(fromObjOptions[i]);
                        i--;
                    }

                }
            }
        }
        function moveLeftOrRightAll(fromObj,toObj){
            var fromObjOptions = fromObj.options;
            if(fromObjOptions.length>1000) {
                //if(!confirm("Are you sure to move options?")) return false;
            }
            for(var i = 0; i < fromObjOptions.length; i ++){
                i --;
                if(ids.indexOf(parseInt(fromObjOptions[i].value))>-1){
                    alert("已经入库的实验不能再次操作,如需删除请在管理实验里删除:"+ fromObjOptions[i].text );
                    //break;
                }else {
                    fromObjOptions[0].selected=true;
                    toObj.appendChild(fromObjOptions[i]);
                }
            }
        }
        function moveUp(selectObj){
            var theObjOptions=selectObj.options;
            for(var i=1;i<theObjOptions.length;i++) {
                if( theObjOptions[i].selected && !theObjOptions[i-1].selected ) {
                    swapOptionProperties(theObjOptions[i],theObjOptions[i-1]);
                }
            }
        }
        function moveDown(selectObj){
            var theObjOptions=selectObj.options;
            for(var i=theObjOptions.length-2;i>-1;i--) {
                if( theObjOptions[i].selected && !theObjOptions[i+1].selected ) {
                    swapOptionProperties(theObjOptions[i],theObjOptions[i+1]);
                }
            }
        }
        function moveToTop(selectObj){
            var theObjOptions=selectObj.options;
            var oOption=null;
            for(var i=0;i<theObjOptions.length;i++) {
                if( theObjOptions[i].selected && oOption) {
                    selectObj.insertBefore(theObjOptions[i],oOption);
                }
                else if(!oOption && !theObjOptions[i].selected) {
                    oOption=theObjOptions[i];
                }
            }
        }
        function moveToBottom(selectObj){
            var theObjOptions=selectObj.options;
            var oOption=null;
            for(var i=theObjOptions.length-1;i>-1;i--) {
                if( theObjOptions[i].selected ) {
                    if(oOption) {
                        oOption=selectObj.insertBefore(theObjOptions[i],oOption);
                    }
                    else oOption=selectObj.appendChild(theObjOptions[i]);
                }
            }
        }
        function selectAllOption(selectObj){
            var theObjOptions=selectObj.options;
            for(var i=0;i<theObjOptions.length;i++){
                theObjOptions[0].selected=true;
            }
        }
        /* private function */
        function swapOptionProperties(option1,option2){
            //option1.swapNode(option2);
            var tempStr=option1.value;
            option1.value=option2.value;
            option2.value=tempStr;
            tempStr=option1.text;
            option1.text=option2.text;
            option2.text=tempStr;
            tempStr=option1.selected;
            option1.selected=option2.selected;
            option2.selected=tempStr;
        }
    </script>
<?
include("footer.php");

?>