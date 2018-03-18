<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");

$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);

error_reporting(0);
session_start();
//echo "QUERY_STRING_1=" . $QUERY_STRING_1 . "<br/>";

if (!isset($dsql)) {
    $dsql = new DSQL();
}

$whereinfo = " ";

if ($search) {
    $whereinfo .= " and (    (binary   a.nickname like '%$search%')or(a.mobile like '%$search%')or(binary  a.university like '%$search%')or(binary  a.truename like '%$search%')or( binary a.phonenumber like '%$search%'))  ";
}
if($isTeacher||$isFeeTeacher){
    $whereinfo = " and useremail='$auth_email'  ";
}else{
    exit;
}
?>
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
                <div class="rhead1">为 <? echo $email ?> 分配课堂</div>
            </div>
            <div class="rfg"></div>

            <form action="teachercourseaction.php" class="rgform" method="post" id="frm1" name="frm1">
               
<table class="rt_table">
    <tr style="height:47px">
        <td style="width:99px;" align="left">编号 </td>
        <td align="left">名称 </td>
        <td style="width:99px;" align="left">剩余人次 </td>
        <td align="left">分配 </td>
    </tr>
<?
$SQL = "SELECT trueusage,id,name,moocurl,starttime,endtime,userid,created_at,updated_at,coursesimg,content,useremail,code, payquantity-ifnull(trueusage,0) as quantity ,moocid,step FROM courses where status=". MetaDataGenerator::STATUS_EFFECTIVE ." and isclass=". MetaDataGenerator::COURSE_TYPE_KECHENG ." $whereinfo  order by id desc ";
//echo $SQL . "\n";
$dsql->query($SQL);
$nbsp = $pernbsp = "|---------";
$olddepth = 1;
$selected = "";
while ($dsql->next_record()) {
    if ($olddepth == 1) {$selected = " selected";} else {$selected = "";}//选中第 1 个。
    $id = $dsql->f('id');
    $name = $dsql->f('name');
    $quantity = $dsql->f('quantity');//剩余名额 payquantity-trueusage as quantity

?>
<tr style="height:55px" align="left">
    <td><? echo $id ?>&nbsp;&nbsp;</td>
    <td align="left"><? echo $name ?></td>
    <td><? echo $quantity ?>&nbsp;&nbsp</td>
    <td>
        <? if ($quantity > 0) { ?>
        <input type="radio" id="course_id" name="course_id" value="<? echo $id ?>" style="width:34px; position:relative; left:2px">
        <? } ?>
    </td>
</tr>
<?
    $olddepth++;
}
?>

</table>
           
                <!--div><label for="name">人次</label>
                    <input type="text" id="payquantity" name="payquantity" value="200" datatype="n" errormsg="请输入数字" nullmsg="请输入数字"></div-->
                <input type="hidden" name="ac"  value="selectcourseid">
                <input type="hidden" id="courseid" name="courseid">
                <input type="hidden" id="payquantity" name="payquantity" value="0">
                <input type="hidden" name='email' value="<? print($email) ?>">
                <input type="hidden" name='teacher_id' value="<? print($userid) ?>">
                <input type="hidden" name='QUERY_STRING_1' value="<? print($QUERY_STRING_1) ?>">
                <div><input type="submit" id="submit" name="submit" class="btn2 button bg-main" value="分配" onclick="return assignCourses(this.form);"></div>
            </form>
    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script type="text/javascript" src="js/validform.js"></script>

    <script type="text/javascript">
        function assignCourses(frm) {
            var frm1 = $("#frm1");
            var chenked=$("input[type='radio']:checked");
            var ids = "";
            for(var i=0;i<chenked.length;i++){
                ids += chenked[i].value + "";// 连起来用 ; 连接
            }
            //alert("ids=" + ids);
            $("#courseid").val(ids);
            if (ids.length < 1) {
                alert("请至少选择 1 个课堂");
                return false;
            }
            return true;
            //frm1.submit();
        }
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
<?
include("footer.php");

?>
