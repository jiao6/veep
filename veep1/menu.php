<?
@session_start();
$url = $_SERVER['PHP_SELF'];
//require_once("config/CheckerOfCourse.php");
//截取文件名称
$phpselffilename= substr($url ,strrpos($url ,'/')+1,-4);

$menu['addteacher']=1;
$menu['changemypassword']=1;
$menu['coursesgrouplist']=1;
$menu['courseslist']=1;
$menu['courseslistfeeteacher']=1;
$menu['experimentslist']=1;
$menu['grouplist']=1;
$menu['mycode']=1;
$menu['mycourse']=1;
$menu['myscore']=1;
$menu['myuseredit']=1;
$menu['myuserimgedit']=1;
$menu['studentscore']=1;
$menu['studentslist']=1;
$menu['userlist']=1;
$menu['studentuseredit']=1;
$menu['lessonlist']=1;


$sessionfilename = $_SESSION['sessionfilename'];
if($menu["$phpselffilename"]==1){
    $_SESSION['sessionfilename'] = $phpselffilename;
    //setcookie("cookiefilename",$phpselffilename);
echo "<style type=\"text/css\">
                .$phpselffilename {
                    background: #1E8997;
                }
                .$phpselffilename a {
                    color: #fff;
                }
            </style>";
}else{
    // $phpselffilename = $HTTP_COOKIE_VARS["cookiefilename"];
    echo "<style type=\"text/css\">
    .$sessionfilename {
        background: #1E8997;
    }
    .$sessionfilename a {
        color: #fff;
    }
    </style>";
}
?>
<?
//echo "roleRightList=" . print_r($roleRightList) . "<br/>";

if(($auth_pid == 1)) {
    if(strpos($auth_email,'@')== false){
        echo "<li class=\"studentuseredit\"><a href=\"studentuseredit.php\">修改邮箱</a></li>";
    }else {
    }
?>
    <li class="mycourse"><a href="mycourse.php">我的课堂 </a></li>
    <li class="mycode"><a href="mycode.php">选课码选课 </a></li>
    <li class="myscore"><a href="myscore.php">我的成绩 </a></li>
    <li class="myuseredit"><a href="myuseredit.php">我的资料</a></li>
    <li class="myuserimgedit"><a href="myuserimgedit.php">头像和简介</a></li>
    <?
} else if (($auth_pid==2)) {
        ?>
        <li class="courseslist"><a href="lessonlist.php">课堂管理</a></li>
        <li class="studentslist"><a href="studentslist.php">学生管理</a></li>

        <li class="studentscore"><a href="studentscore.php">学生成绩</a></li>
        <li class="myuseredit"><a href="myuseredit.php">我的资料</a></li>
        <li class="myuserimgedit"><a href="myuserimgedit.php">头像和简介</a></li>
        <?
} else if ($auth_pid==3) {//admin 身份登录      $auth_pid == 3
    ?>
    <li class="userlist"><a href="userlist.php">用户管理</a></li>
    <!--课程、课堂管理分开
    <li class="courseslist"><a href="courseslist.php">课程管理</a></li>-->
    <li class="courseslistfeeteacher"><a href="courseslistfeeteacher.php">课程管理</a></li>
    <li class="lessonlist"><a href="lessonlist.php">课堂管理</a></li>
    <li class="experimentslist"><a href="experimentslist.php">实验管理</a></li>
    <li class="grouplist"><a href="grouplist.php">实验分组</a></li>
    <li class="coursesgrouplist"><a href="coursesgrouplist.php">课程分组</a></li>
    <li class="myuseredit"><a href="myuseredit.php">我的资料</a></li>
    <li class="myuserimgedit"><a href="myuserimgedit.php">头像和简介</a></li>
    <?
} else if ($auth_pid==4) {
    /*foreach ($roleRightList as $roleRight) {
        echo '<li class="'. $roleRight[7] .'"><a href="'. $roleRight[6] .'">'. $roleRight[1] .'</a></li>';
    }*/
    ?>
    <li class="addteacher"><a href="addteacher.php">课堂分配</a></li>
    <li class="courseslistfeeteacher"><a href="courseslistfeeteacher.php">课程管理</a></li>
    <li class="lessonlist"><a href="lessonlist.php">课堂管理</a></li>
    <li class="studentslist"><a href="studentslist.php">学生管理</a></li>
    <li class="userlist"><a href="userlist.php">用户管理</a></li>
    <li class="studentscore"><a href="studentscore.php">学生成绩</a></li>
    <li class="myuseredit"><a href="myuseredit.php">我的资料</a></li>
    <li class="myuserimgedit"><a href="myuserimgedit.php">头像和简介</a></li>
    <?
}
/*
$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);
$rightChecker = new CheckerOfCourse();
$roleRightList = $rightChecker->getRoleRightList($auth_id, $auth_pid);*/
?>
        <li class="changemypassword"><a href="changemypassword.php">我的密码</a></li>
