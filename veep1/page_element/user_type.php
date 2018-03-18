<div class="form-group" style="line-height: 30px">
<div class="label">
    <label for="name">请选择身份</label>
</div>
<?
$ELEMENT_NAME  = "usertype";
// radio 之间的间距
$ELEMENT_DISTANCE  = 133;

require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");
$PID_STUDENT     = CheckerOfCourse::PID_STUDENT;
$PID_TEACHER     = CheckerOfCourse::PID_TEACHER;
$PID_ADMIN       = CheckerOfCourse::PID_ADMIN;
$PID_FEETEACHER  = CheckerOfCourse::PID_FEETEACHER;

$P_NAME_STUDENT     = CheckerOfCourse::P_NAME_STUDENT;
$P_NAME_TEACHER     = CheckerOfCourse::P_NAME_TEACHER;
$P_NAME_ADMIN       = CheckerOfCourse::P_NAME_ADMIN;
$P_NAME_FEETEACHER  = CheckerOfCourse::P_NAME_FEETEACHER;

$arrayDataCode = array($PID_STUDENT, $PID_TEACHER, $PID_FEETEACHER, $PID_ADMIN);//()
$arrayDataName = array($P_NAME_STUDENT, $P_NAME_TEACHER, $P_NAME_FEETEACHER, $P_NAME_ADMIN);//()

?>
<?
//是否 付费教师
$isFeeTeacher= CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin= CheckerOfCourse::isAdmin($auth_pid);

$cnt = 1;
if (isset($auth_pid)) {//用户已登录，管理员、付费教师可以增加、修改用户
	/*
	if ($isFeeTeacher) {//付费教师只能添加教师
		$arrayDataCode = array($PID_TEACHER);//()
		$arrayDataName = array($P_NAME_TEACHER);//()
	} else {//系统管理员能添加全部
	}*/
	if (isAdmin) {
		if ($usertype == $PID_ADMIN) {//当前用户是管理员，被修改的也是管理员，则出现 4 个点
		} else {//被改的不是管理员，只显示3个点
			$arrayDataCode = array($PID_STUDENT,    $PID_TEACHER, $PID_FEETEACHER);//()
			$arrayDataName = array($P_NAME_STUDENT, $P_NAME_TEACHER, $P_NAME_FEETEACHER);//()
		}
	}
} else {//用户未登录，注册，只能注册学生和教师
	$arrayDataCode = array($PID_STUDENT,    $PID_TEACHER);//()
	$arrayDataName = array($P_NAME_STUDENT, $P_NAME_TEACHER);//()
	$ELEMENT_DISTANCE  = 260;
}
	foreach ($arrayDataCode as $data){
	    $left = 133 + $ELEMENT_DISTANCE * ($cnt - 1);
	    $name = MetaDataGenerator::getUserImageFromChar($data);
	    $str =  '<label for="name" >' . $name;
	    $checked = "";
	    if (isset($usertype)) {//修改用户时，data 数据有数值， 哪个选中哪个打点
	        if($data==$usertype) {
	            $checked = "checked";
	        }
	    } else {//添加用户时，data 数据是空的，学生 上打点
	        if($data==$PID_STUDENT) {
	            $checked = "checked";
	        }
	    }
	/**/
	    $str .= '<input '. $checked .' type="radio" id="'. $ELEMENT_NAME.'" name="'.$ELEMENT_NAME.'" value="'. $data .
	    '" onclick="selectAUserType(this)" style="margin-right: 40px;margin-left: 10px ;"></label>';
	    echo $str;
	    $cnt++;
	}
?>
</div>
