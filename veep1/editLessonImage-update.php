<?

session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}

require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/ImageCropper.php");
require_once("config/CheckerOfCourse.php");

//$aaaa = $showPosition . "; coursesImgOld=[" . $coursesImgOld . "]; coursesImgNew=[" . $coursesImgNew . "]; lessonId=" . $lessonId  . "; pageNo=" . $pageNo. "; <br/>";
//echo "<script>alert('".$aaaa."')</script>";

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课程图片
$LESSON_IMAGE_DIR     = CheckerOfCourse::LESSON_IMAGE_DIR;//图片存放位置
$DATA_DIR             = CheckerOfCourse::DATA_DIR;//图片目录的上一级

$WIDTH_LIMIT  = CheckerOfCourse::IMAGE_WIDTH;   //图片最大宽度
$HEIGHT_LIMIT = CheckerOfCourse::IMAGE_HEIGHT;  //图片最大高度

$support_type = array(IMAGETYPE_GIF, IMAGETYPE_JPEG , IMAGETYPE_PNG);

    //检查用户权限
$checker = new CheckerOfCourse();
$destUrl = "lessonedit.php?lessonId=". $lessonId. $checker->recoverChar($QUERY_STRING_2);

    $pass = $checker->checkAuthOfLesson($auth_pid, $auth_id, $lessonId);
    if ($pass) {
    } else {
        $checker->jumpTo("你无权修改本课程图片！", $destUrl);
        exit();
    }
    //echo $aaaa;
    $array = getimagesize($coursesImgNew);
    //echo "0000" . "<br/>";
    $type = $array[2]; //图片类型
    if(in_array($type, $support_type, true)) {//判断是不是图片。只支持 gif, jpg, png
    } else {
       ////$checker->jumpTo("您上传的[". $aaaa ."]不是图片，类型是". $type ."，修改失败！", $destUrl);
       //exit();
    }
    //新增或修改头像
    //echo "1111" . "<br/>";
     @mkdir($DATA_DIR);//创建存放目录
     @mkdir($LESSON_IMAGE_DIR);

    //echo "type= " . $type . "<br/>";
    if ($coursesImgOld != $DEFAULT_COURSE_IMAGE) {//原来有头像
        if(file_exists($coursesImgOld)) {//判断原头像是否存在
             unlink($coursesImgOld);//删掉旧头像
        }
    }

    //echo "2222" + "<br/>";
    $arrName = explode(".",$_FILES["coursesImgNew"]["name"]);
    $intName = sizeof($arrName);
    $extName = strtolower($arrName[$intName-1]); //作用取得上传文件的后缀名.

    $userimg1 = $LESSON_IMAGE_DIR . "/" .$lessonId. '.1.'.$extName;
    $userimg2 = $LESSON_IMAGE_DIR . "/" .$lessonId. CheckerOfCourse::UPLOADED_IMAGE_TYPE;//扩展名统一改为 jpg
    //echo $showPosition . "; userimg1=" . $userimg1 . "<br/>";
    $array = explode(";", $showPosition);
    $left = $array[0];
    $top = $array[1];
    $width = $array[2];
    $height = $array[3];
    @copy($_FILES["coursesImgNew"]["tmp_name"], $userimg1);
    if($_FILES["coursesImgNew"]["name"]){
        $SQL = "update LESSON set IMG_URL = '$userimg2' where ID = $lessonId ";
        //echo $showPosition . "; " . $SQL . "<br/> ". $left . "; " . $top . "; " . $width. "; ". $height. "<br/>";
    }


    //创建源图的实例
        //echo "开始截图……" . $showPosition . "; userimg1=" . $userimg1 . "; userimg2=" . $userimg2. "<br/>". "<br/>";
        $imageCropper = new ImageCropper();
        $succ = $imageCropper->crop ($WIDTH_LIMIT, $HEIGHT_LIMIT, $userimg1, $userimg2, $left, $top, $width, $height);
        //echo "截图结束……" . "<br/>";


    $dsql = new DSQL();
    if ($dsql->query($SQL)) {
		$checker->jumpTo("OK ", $destUrl);
    } else {
       //$checker->jumpTo("修改失败！", $destUrl);
    }
    exit();
?>