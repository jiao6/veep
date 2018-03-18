<?



error_reporting(0);
session_start();
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("header.php");
require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");

error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);

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
        $coursesimg = CheckerOfCourse::DEFAULT_COURSE_IMAGE;
    }

    $SQL =  "insert into LESSON
     (id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code,payquantity,moocid,step,coursesgroupid,sort)values(0,'$name','$moocurl','$starttime','$endtime','$auth_id',now(),'$updated_at','$coursesimg','$content','$useremail','$code','$payquantity','$moocid','$step','$coursesgroupid','$sort')";
    $dsql->query($SQL);
    //echo "$SQL";
    $dsql->query("select last_insert_id() as id from LESSON ");
    $dsql->next_record();
    $lessonId=$dsql->f('id');
    //echo $lessonId;
}else{

}
$theId = $lessonId;
$theObj = "课堂";
$isLesson = true; //课堂管理
$COURSE_TYPE = MetaDataGenerator::COURSE_TYPE_KETANG;
if (!isset($lessonId) || $lessonId <= 0) {//没有 lessonId 或小于等于 0，使用 courseId
    $theId = $courseId;
    $isLesson = false;
    $theObj = "课程";
	$COURSE_TYPE = MetaDataGenerator::COURSE_TYPE_KECHENG;
}
//拿到课程或课堂名称
if ($isLesson) {
    $SQL = "SELECT ID, NAME, COURSE_ID
     FROM LESSON
     where id='$lessonId'";
    //echo $SQL . "\n";
    $dsql->query($SQL);
    if($dsql->next_record()){
        $id = $dsql->f('ID');
        $name = $dsql->f('NAME');
        $name = MetaDataGenerator::getShortenString($name, MetaDataGenerator::STRING_TRUNCATE_LENGTH_OF_INFO_PAGE);
        $COURSE_ID = $dsql->f('COURSE_ID');
    }
} else {//课程
    $SQL = "SELECT ID, NAME
     FROM courses
     where id='$courseId'";
    //echo $SQL . "\n";
    $dsql->query($SQL);
    $nbsp = $pernbsp = "|---------";
    if($dsql->next_record()){
        $id = $dsql->f('ID');
        $name = $dsql->f('NAME');
        $name = MetaDataGenerator::getShortenString($name, MetaDataGenerator::STRING_TRUNCATE_LENGTH_OF_INFO_PAGE);
        $COURSE_ID = $courseId;
    }
}
$olddepth =1;
$nbsp = $pernbsp = "|---------";

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
                <li>实验管理</li>
            </ul>
            <div class="rhead">
                <div class="rhead1 f_center">为【<? echo $id . "号". $theObj ."——" .$name ?>】增加实验</div>
            </div>
            <div class="rfg"></div>
            <div>
                <form name="frm" action="coursesaddclass3.php" method="get" class="select_form" style="width: 100%">
                    <table border='0' style="width: 100%">
                        <tr style="border: 0;background: #fff;height: 100%">
                            <td style="border: 1px solid #ccc;height: 100%;width: 40%">
                                <!-- 获得所有实验列表 -->
                                <select name='SrcSelect' size='16' class="ecv_autoSizeDropDown" style="eight: 100%;width: 100%"s multiple ondblclick="moveLeftOrRight(document.frm.SrcSelect,document.frm.ObjSelect)">
                                    <?
                                    if($isTeacher || $isFeeTeacher){
	                                    if ($isLesson) {
	                                        $SQL2 = "SELECT id, name FROM experiments
	                                            where  id in
	                                            (SELECT experimentsid FROM coursesexperiment WHERE coursesid = '$COURSE_ID' AND COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KECHENG." and experimentsid not in
	                                            (SELECT experimentsid FROM coursesexperiment WHERE coursesid = '$lessonId' AND COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG.") )  ";
	                                	} else {//课程级别的维护实验，只能管理员做。付费教师无权维护课程的实验
	                                	}
                                        //echo "SQL2=" . $SQL2;
                                        $dsql2->query($SQL2);
                                        while($dsql2->next_record()) {
                                            $id = $dsql2->f('id');
                                            $name = $dsql2->f('name');
                                            echo "<option value=\"$id\"  >$nbsp$pernbsp|$name</option>";
                                        }
                                    } else if($isAdmin) {//管理员，显示所有实验
                                        $SQL = "SELECT id, name, uid, pid, path, status
                                            FROM experimentsgroup order by path  ";
                                        //echo $SQL . "\n";
                                        $dsql->query($SQL);
                                        $nbsp = $pernbsp = "|---------";
                                        $olddepth =1;
                                        while($dsql->next_record()){
                                            $id=$dsql->f('id');
                                            $group_name=$dsql->f('name');
                                            //$insertdate=$dsql->f('insertdate');
                                            //$userid=$dsql->f('userid');
                                            $group_pid=$dsql->f('pid');
                                            $path =$dsql->f('path');
                                            $newdepth =  substr_count($path,',');
                                            $select = "";
                                            if ($id==$COURSE_ID){
                                                $select = "selected='selected' ";
                                            }
                                            if ($newdepth==$olddepth){
                                                //$nbsp = "|--";
                                                echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";

                                            } else if($newdepth>$olddepth){
                                                $nbsp = $nbsp.$pernbsp;
                                                echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";
                                            } else {
                                                $depthno = $olddepth - $newdepth;
                                                $nbsp = substr($nbsp,0,strlen($nbsp)-strlen($pernbsp)*$depthno);
                                                echo "<option value=\"g$id\" $select>$nbsp|$group_name</option>";
                                            }
                                            $SQL2 = "SELECT  id ,name FROM experiments  where groupid = '$id'   ";
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
                                    /* 查找所有该课堂的实验。课程、课堂的实验添加修改公用本程序，所以根据传入参数判断是课程还是课堂 */
                                   	$COURSE_TYPE_CONDITION = " and ce.COURSE_TYPE=" . $COURSE_TYPE;
                                    $SQL = "SELECT e.id ,e.name
                                        FROM experiments e, coursesexperiment ce
                                        WHERE e.id=ce.experimentsid and ce.coursesid = ". $theId . " " . $COURSE_TYPE_CONDITION . " " .
                                        "ORDER BY ce.sort asc";
                                    //echo $SQL;
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
                            </td>
<?                                     //echo $SQL . "<br/>";  ?>
                            <td width="30px" style="border: 0;padding: 10px;vertical-align: middle;text-align: center;">
                                <input type=button value="置顶" onclick="moveToTop(document.frm.ObjSelect)" ><br>
                                <input type=button value="向上" onclick="moveUp(document.frm.ObjSelect)" ><br>
                                <input type=button value="向下" onclick="moveDown(document.frm.ObjSelect)" ><br>
                                <input type=button value="置底" onclick="moveToBottom(document.frm.ObjSelect)" >
                            </td>
                        </tr>
                    </table>
                    <br/>
                    <p>
                        <input type=hidden name="itemId" id="itemId" value="">
                        <input type=hidden name="action" value="add">
                        <input type=hidden name="lessonId" value="<?print($lessonId)?>">
                        <input type=hidden name="courseId" value="<?print($courseId)?>">
                        <div style="text-align: center;width: 90%;">
                            <input type="button" id="button" name="button" onclick="history.back();" value="上一步" class="submit button bg-main">
                            <input type="submit" id="submit" name="submit" onclick="return experimentsall();" value="下一步" class="submit button bg-main" >
                        </div>
                    </p>
                </form>
            </div>
    <script language=javascript>
        var ids = [<?print($arrayids)?>];
        function experimentsall(){
            var selectRight = document.getElementById("ObjSelect");
            var leng = selectRight.options.length;
            if (leng < 1) {
                alert("请从左侧实验列表中，至少选择 1 个实验！");
                return false;
            }
            var bbb="";
            for(var i=0; i < leng; i++){
                var aaa=selectRight.options[i].value;
                bbb=bbb+","+aaa;
            }
            bbb = bbb.substr(1, bbb.length-1);
            document.getElementById("itemId").value = bbb;
            return true;
        }
        function moveLeftOrRight(fromObj,toObj){
            var fromObjOptions=fromObj.options;
            for(var i=0;i<fromObjOptions.length;i++){
                if(fromObjOptions[i].selected){
            		var leftValue= fromObjOptions[i].value;
            		var leftText = fromObjOptions[i].text;
            		var kk = leftText.lastIndexOf("|") + 1;//加上竖线本身的长度
            		//alert(kk);
            		leftText = leftText.substr(kk, leftText.length - kk);//去掉竖线之前的部分
            		leftText = leftText.replace("《", "").replace("》", "");//去掉书名号
            		leftText = "【" + leftText + "】";
            		//alert(leftValue + ":" + leftText);
                    if(leftValue.substr(0,1)=='g'){
                        alert("分组名称不能选择");
                    }else if(ids.indexOf(parseInt(leftValue))>-1){
                        alert("实验"+ leftText + "已经入库，不能再次操作，如需删除请在管理实验里删除");
                    }else {
                        toObj.appendChild(fromObjOptions[i]);
                        i--;
                    }
                }
            }
        }

        function moveLeftOrRightAll(fromObj,toObj){
            var fromObjOptions=fromObj.options;
            if(fromObjOptions.length>1000) {
                //if(!confirm("Are you sure to move options?")) return false;
            }
            for(var i=0;i<fromObjOptions.length;i++){
               if(ids.indexOf(parseInt(fromObjOptions[i].value))>-1){
                    alert("已经入库的实验不能再次操作,如需删除请在管理实验里删除:"+ fromObjOptions[i+1].text );
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