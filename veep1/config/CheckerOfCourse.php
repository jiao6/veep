<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/RoleRight.php");
require_once("config/MetaDataGenerator.php");

class CheckerOfCourse{
    /* 用户级别有关的参数 */
    //管理员的用户组 pid， 3
    const PID_STUDENT = 1;
    const PID_TEACHER = 2;
    const PID_ADMIN =   3;
    const PID_FEETEACHER = 4;

    const P_NAME_STUDENT = "学生";
    const P_NAME_TEACHER = "教师";
    const P_NAME_ADMIN =   "管理员";
    const P_NAME_FEETEACHER = "付费教师";

    //缺省课程图片
    const DEFAULT_COURSE_IMAGE =  "img/course.png";
    //缺省课堂图片
    const DEFAULT_LESSON_IMAGE =  "img/default-lesson.png";
    //缺省教师图片
    const DEFAULT_TEACHER_IMAGE = "img/teacher.png";

    //图片目录的上一级
    const DATA_DIR = "data";
    //图片存放位置
    const COURSE_IMAGE_DIR = "data/coursesimg";

    //图片存放位置
    const LESSON_IMAGE_DIR = "data/lessonimg";

    // 支持的图片文件格式
    //const SUPPORTED_IMAGE_TYPE = array(IMAGETYPE_GIF, IMAGETYPE_JPEG , IMAGETYPE_PNG);

    // 上传图片的统一扩展名
    const UPLOADED_IMAGE_TYPE = ".jpg";

    //课程图片宽
    const IMAGE_WIDTH  = 400;   //图片最大宽度
    //课程图片高
    const IMAGE_HEIGHT = 300;   //图片最大高度


    private $STATUS_EFFECTIVE = MetaDataGenerator::STATUS_EFFECTIVE;
    public static function isAdmin($authPid){
        return ($authPid == self::PID_ADMIN);
    }
    public static function isFeeTeacher($authPid){
        return ($authPid == self::PID_FEETEACHER);
    }
    public static function isTeacher($authPid){
        return ($authPid == self::PID_TEACHER);
    }
    public static function isStudent($authPid){
        return ($authPid == self::PID_STUDENT);
    }
	/* 判断是不是老师、付费教师，或的关系 */
    public static function isATeacher($authPid){
        return ($authPid == self::PID_TEACHER || $authPid == self::PID_FEETEACHER);
    }
    /* 用户 pid, 用户的 email/username, 课程的 id  */
    function checkAuth($authPid, $authEmail, $courseId){
        if ($authPid == self::PID_ADMIN) {//当前用户是管理员
            return true;
        }
//      echo "PID_ADMIN=" . self::PID_ADMIN . "; authPid=" . $authPid . "; authEmail=" . $authEmail  . "; courseId=" . $courseId . "; SQL=" . $SQL . "<br/>";
        $SQL = "select id, name, coursesimg, useremail from courses where id =$courseId and useremail = '$authEmail' ";
    //  echo "coursesImg=" . $coursesImg . "; courseId=" . $courseId  . "; auth_pid=" . $auth_pid . "; SQL=" . $SQL . "<br/>";
        $dsql = new DSQL();//非管理员只能改自己的课程
        $dsql->query($SQL);
        if($dsql->next_record()){
        } else {
            return false;
        }
        return true;
    }
    function checkAuthOfLesson($authPid, $authId, $lessonId){
        if ($authPid == self::PID_ADMIN) {//当前用户是管理员
            return true;
        }
//      echo "PID_ADMIN=" . self::PID_ADMIN . "; authPid=" . $authPid . "; authEmail=" . $authEmail  . "; lessonId=" . $lessonId . "; SQL=" . $SQL . "<br/>";
        $SQL = "select id, name from LESSON where ID =$lessonId and ASSIGNER_ID = '$authId' ";
    //  echo "coursesImg=" . $coursesImg . "; lessonId=" . $lessonId  . "; auth_pid=" . $auth_pid . "; SQL=" . $SQL . "<br/>";
        $dsql = new DSQL();//非管理员只能改自己的课程
        $dsql->query($SQL);
        if($dsql->next_record()){
        } else {
            return false;
        }
        return true;
    }
    const ROLE_RIGHT_TYPE_MAIN_MENU = 10;

    //获得所有权力的列表。 $roleId 角色 id, $userId 用户 id
    function getRoleRightList($userId, $roleId) {
        $SQL = "select rr.*, r.NAME as ROLE_NAME, o.NAME as OPERATION_NAME, a.NAME as ACTIVITY_NAME, a.MENU_URL, a.STYLE
          from ROLE_RIGHT rr, OPERATION o, ACTIVITY a , ROLE r
            where rr.OPERATION_ID=o.id and rr.ACTIVITY_ID=a.id and rr.ROLE_ID=r.id
                and rr.TYPE=". self::ROLE_RIGHT_TYPE_MAIN_MENU ." and rr.ROLE_ID=" . $roleId . " and rr.status=" . $this->STATUS_EFFECTIVE . "
            order by rr.SORT_ORDER desc";
        //echo "coursesImg=" . $coursesImg . "; userId=" . $userId  . "; roleId=" . $roleId . "; SQL=" . $SQL . "<br/>";
        $roleRightList = array();
        $dsql = new DSQL();//非管理员只能改自己的课程
        $dsql->query($SQL);
        while($dsql->next_record()){
            $id = $dsql->f('ID');
            $name = $dsql->f('NAME');
            $roleId = $dsql->f('ROLE_ID');
            $activityId = $dsql->f('ACTIVITY_ID');
            $operationId = $dsql->f('OPERATION_ID');

            $ACTIVITY_NAME = $dsql->f('ACTIVITY_NAME');
            $MENU_URL = $dsql->f('MENU_URL');
            $STYLE = $dsql->f('STYLE');
            //$roleRight = new RoleRight($id,  $name, $roleId, $activityId, $operationId);
            $roleRight = array($id,  $name, $roleId, $activityId, $operationId, $ACTIVITY_NAME, $MENU_URL, $STYLE);
            //$id = $dsql->f('ID');
            //echo "bbb=" . $id . "<br/>";
            //$roleRightList[$i] = $id;
            //$roleRightList[$i] = $roleRight;
            $roleRightList[] = $roleRight;
            //$roleRightList = array($roleRight);
            //echo "ccc=" . $roleRight->getOperationId() . "<br/>";
        }
        return $roleRightList;
    }
    /* 提示语，跳转位置 */
    public static function jumpTo($message, $url) {
        echo "<script>alert('". $message ."'); </script>";
        //if ($url) {
            echo "<script>document.location = '$url';</script>\n";
            //echo "url = " . $url;
        //}
    }
    //由于服务器端做了转译，所以提前 将 & 转为 ||
    function conertChar($src) {
        $trg = str_replace("&", "||", $src);//由于传输时对 & 转译成了 &amp; 所以替换为 ||
        return $trg;
    }
    //conertChar 的反向
    function recoverChar($src) {
        $trg = str_replace("||", "&", $src);//
        return $trg;
    }
}
?>
