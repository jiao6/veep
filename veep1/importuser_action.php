<?

error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}
set_time_limit(300000);

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


?>

    <div class="contain mc mc1">
    <div class="lt">
        <ul>
            <li class="gn">功能</li>
            <?
            include("menu.php");
            ?>
        </ul>
    </div>
    <div class="rt">
        <div class="rhead">
            <div class="rhead1">导入用户</div>
        </div>
        <div class="rfg"></div>
<?
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

if($step!=2) {


    @mkdir("excel");
    $time = time();
    $user_name =  "$time$auth_id.xls";
    @copy($_FILES['user']['tmp_name'], "excel/$user_name");
}
$user_name =  "$time$auth_id.xls";
$inputFileName = "excel/$user_name";
if (!file_exists($inputFileName)) {  exit("文件".$inputFileName."不存在");   }

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'Classes/PHPExcel/Calculation/DateTime.php';
require_once 'Classes/PHPExcel/Autoloader.php';
$inputFileType = 'Excel2003XML';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);




$sheetCount = $objPHPExcel->getSheetCount();


$sheetSelected = 0;

$objPHPExcel->setActiveSheetIndex(0);

//u getSheetByName('Worksheet 1');
$rowArray = $objPHPExcel->getActiveSheet()->getRowDimensions();

$cellArray = $objPHPExcel->getActiveSheet()->getColumnDimensions();

$rowCount = count($rowArray)+10000;

$cellCount = count($cellArray)+6;




$rowIndex = 2;

$cellIndex = 0;

$rowData = NULL;


$dataArr = array();

if($step!=2){//预览
    echo "<input type=\"button\" id=\"button\" name=\"button\"   onclick=\"history.back();\" value=\"上一步\" class=\"submit\"><br/><input type=\"submit\" id=\"submit\" name=\"submit\"
                       onclick=\"window.location='importuser_action.php?lessonId=$lessonId&step=2&time=$time'\" value=\"确认导入\"
                       class=\"submit\">
                       <table width='80%' border='1' style='border:2px'><tr  border='1'><td>姓名<td>学号<td>班级</tr>";
    $cellIndex = 0;
    $rowData = $objPHPExcel->getActiveSheet();

    if(trim($rowData->getCellByColumnAndRow(0,1)->getValue()) =='姓名' && trim($rowData->getCellByColumnAndRow(1, 1)->getValue())=='学号' && trim($rowData->getCellByColumnAndRow(2,1 )->getValue())=='班级') {

    }else{
        echo trim($rowData->getCellByColumnAndRow(0,1)->getValue());

        echo trim($rowData->getCellByColumnAndRow(1,1)->getValue());
        echo trim($rowData->getCellByColumnAndRow(2,1)->getValue());
        echo "导入文件模板有误，请重新导入 <input type=\"button\" id=\"button\" name=\"button\"   onclick=\"history.back();\" value=\"重新导入\" class=\"submit\"><br/>";
        exit;
    }

    while ($rowIndex < $rowCount) {
        $truename = trim($rowData->getCellByColumnAndRow(0, $rowIndex)->getValue()) ;
        $studentno =  trim($rowData->getCellByColumnAndRow(1, $rowIndex)->getValue()) ;
        $class  = trim($rowData->getCellByColumnAndRow(2, $rowIndex)->getValue());
       // $college  = trim($rowData->getCellByColumnAndRow(3, $rowIndex)->getValue());
        $status = trim($rowData->getCellByColumnAndRow(4, $rowIndex)->getValue());
        if($truename=='姓名' ){

            continue;
        }
        if( $truename==''){
            break;
        }

        if (preg_match("/[\x7f-\xff]/", $studentno)) {  //判断字符串中是否有中文
            echo "<tr  border='1'><td>";
            echo $truename;
            echo "<td><font color=red> ";
            echo $studentno;
            echo "</font><td> ";
            echo $class;

            echo "</tr>";

        } else {

        }
        if (preg_match("/[\x7f-\xff]/", $class)) {  //判断字符串中是否有中文
            echo "<tr  border='1'><td>";
            echo $truename;
            echo "<td> ";
            echo $studentno;
            echo "<td><font color=red> ";
            echo $class;

            echo "</tr>";

        } else {

        }
        if($truename!=''&&$truename!='姓名') {
            echo "<tr  border='1'><td>";
            echo $truename;
            echo "<td> ";
            echo $studentno;
            echo "<td> ";
            echo $class;

            echo "</tr>";
        }
        $rowIndex++;

    }
    echo "</table><input type=\"button\" id=\"button\" name=\"button\"   onclick=\"history.back();\" value=\"上一步\" class=\"submit\"><br/><input type=\"submit\" id=\"submit\" name=\"submit\"
                       onclick=\"window.location='importuser_action.php?lessonId=$lessonId&step=2&time=$time'\" value=\"确认导入\"
                       class=\"submit\">";
}


if($step==2){//入库
    $SQL = "select u.university_id ,u.university  from  LESSON l,courses  c,users u  where l.id = '$lessonId'   and l.course_id = c.id and c.teacher_id=u.id   and (l.assigner_id='$auth_id' or 3='$auth_pid' or l.teacher_id='$auth_id')";
    $dsql->query($SQL);
    if($dsql->next_record()){

        $universityId = $dsql->f('university_id');
        $university  = $dsql->f('university');

    }else{
        exit;
    }

    $insert = 0;
    $update = 0;
    $rowData = $objPHPExcel->getActiveSheet();
    while ($rowIndex < $rowCount) {

        $cellIndex = 0;


    //姓名	学号	班级	专业


        $truename = trim($rowData->getCellByColumnAndRow(0, $rowIndex)->getValue()) ;
        $studentno =  trim($rowData->getCellByColumnAndRow(1, $rowIndex)->getValue()) ;
        $class  = trim($rowData->getCellByColumnAndRow(2, $rowIndex)->getValue());
      //  $college  = trim($rowData->getCellByColumnAndRow(3, $rowIndex)->getValue());
        $status = trim($rowData->getCellByColumnAndRow(4, $rowIndex)->getValue());

    //号码	部门	姓名	员工编号	系统工号	调查类型
        $nickname = $studentno;
        $email = $universityId.$studentno;
        $bpasswd = bpasswd($studentno);
        $password = $studentno;
        $phonenumber = '13012345678';
        $usertype =1;
        $userimg = "";
        $feeuserid  = $auth_id;
            /*
             * 学生邮箱：学生初始登录账户设置为“学校代码”+“学号”的格式
    学校代码：课堂所属学校的代码
    学校名称：课堂所属学校的名称
    所选课堂：导入学生账号对应的课堂
    学生初次登录默认密码：学号
    昵称初始默认：学号
    手机初始默认：13012345678

             */
        if($truename!=''&&$truename!='姓名'){

            $SQL = "select *   from users where studentno = '$studentno' and university_id='$universityId'  ";
            $dsql->query($SQL);
            if($dsql->next_record()){
                $update++;
                $SQL = "update users set  status=0  where   university_id='$universityId'  and studentno ='$studentno' ";

                $dsql->query($SQL);
                echo " $truename $studentno $class 已经存在 <br>";
                echo "<br/>";

            }else{
                $insert++;
                $SQL = "insert into users (id,nickname,email,password,university,phonenumber,truename,university_Id,created_at,updated_at,pwd,usertype,feeuserid,userimg,studentno,class,creator_id)values
    (0,'$nickname','$email','$bpasswd','$university', '$phonenumber','$truename','$universityId',now(),now(),'$password','$usertype','$feeuserid','$userimg','$studentno','$class','$auth_id')";
                if($dsql->query($SQL)){
                    echo " $truename $studentno $class 增加成功 <br>";
                }
            }
            $SQL = "replace into coursesemail (coursesid,email)values('$lessonId','$email')";
            $dsql->query($SQL);
            echo "<div> 　 　更新:${update} 学生的选课记录　　增加用户:$insert  条</div>";


        }else{
            //echo " $truename $studentno $class 姓名不能为空 <br>";
        }
        $rowIndex++;
    }
}


include("footer.php");
?>