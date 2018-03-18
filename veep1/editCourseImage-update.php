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

//$aaaa = $showPosition . "; coursesImgOld=[" . $coursesImgOld . "]; coursesImgNew=[" . $coursesImgNew . "]; courseId=" . $courseId  . "; pageNo=" . $pageNo. "; <br/>";
//echo "<script>alert('".$aaaa."')</script>";

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//ȱʡ�γ�ͼƬ
$COURSE_IMAGE_DIR     = CheckerOfCourse::COURSE_IMAGE_DIR;//ͼƬ���λ��
$DATA_DIR             = CheckerOfCourse::DATA_DIR;//ͼƬĿ¼����һ��

$WIDTH_LIMIT  = CheckerOfCourse::IMAGE_WIDTH;   //ͼƬ�����
$HEIGHT_LIMIT = CheckerOfCourse::IMAGE_HEIGHT;  //ͼƬ���߶�

$support_type = array(IMAGETYPE_GIF, IMAGETYPE_JPEG , IMAGETYPE_PNG);

    //����û�Ȩ��
$checker = new CheckerOfCourse();
$destUrl = "coursesedit.php?coursesid=". $courseId. $checker->recoverChar($QUERY_STRING_2);

    $pass = $checker->checkAuth($auth_pid, $auth_email, $courseId);
    //echo $DEFAULT_COURSE_IMAGE . "; COURSE_IMAGE_DIR=" .$COURSE_IMAGE_DIR . "; pass=" .$pass;
    if ($pass) {
    } else {
        $checker->jumpTo("����Ȩ�޸ı��γ�ͼƬ��", $destUrl);
        exit();
    }
    //echo $aaaa;
    $array = getimagesize($coursesImgNew);
    //echo "0000" . "<br/>";
    $type = $array[2]; //ͼƬ����
    if(in_array($type, $support_type, true)) {//�ж��ǲ���ͼƬ��ֻ֧�� gif, jpg, png
    } else {
       //$checker->jumpTo("���ϴ���[". $aaaa ."]����ͼƬ��������". $type ."���޸�ʧ�ܣ�", $destUrl);
       //exit();
    }
    //�������޸�ͷ��
    //echo "1111" . "<br/>";
     @mkdir($DATA_DIR);//�������Ŀ¼
     @mkdir($COURSE_IMAGE_DIR);

    //echo "type= " . $type . "<br/>";
    if ($coursesImgOld != $DEFAULT_COURSE_IMAGE) {//ԭ����ͷ��
        if(file_exists($coursesImgOld)) {//�ж�ԭͷ���Ƿ����
             unlink($coursesImgOld);//ɾ����ͷ��
        }
    }

    //echo "2222" + "<br/>";
    $arrName = explode(".",$_FILES["coursesImgNew"]["name"]);
    $intName = sizeof($arrName);
    $extName = strtolower($arrName[$intName-1]); //����ȡ���ϴ��ļ��ĺ�׺��.

    $userimg1 = $COURSE_IMAGE_DIR . "/" .$courseId. '.1.'.$extName;
    $userimg2 = $COURSE_IMAGE_DIR . "/" .$courseId. CheckerOfCourse::UPLOADED_IMAGE_TYPE;//��չ��ͳһ��Ϊ jpg
    //echo $showPosition . "; userimg1=" . $userimg1 . "<br/>";
    $array = explode(";", $showPosition);
    $left = $array[0];
    $top = $array[1];
    $width = $array[2];
    $height = $array[3];
    @copy($_FILES["coursesImgNew"]["tmp_name"], $userimg1);
    if($_FILES["coursesImgNew"]["name"]){
        $SQL = "update courses set coursesimg = '$userimg2' where id = $courseId ";
        //echo $showPosition . "; " . $SQL . "<br/> ". $left . "; " . $top . "; " . $width. "; ". $height. "<br/>";
    }


    //����Դͼ��ʵ��
        //echo "��ʼ��ͼ����" . $showPosition . "; userimg1=" . $userimg1 . "; userimg2=" . $userimg2. "<br/>". "<br/>";
        $imageCropper = new ImageCropper();
        $succ = $imageCropper->crop ($WIDTH_LIMIT, $HEIGHT_LIMIT, $userimg1, $userimg2, $left, $top, $width, $height);
        //echo "��ͼ��������" . "<br/>";


    $dsql = new DSQL();
    if ($dsql->query($SQL)) {
       $checker->jumpTo("�޸ĳɹ���", $destUrl);
    } else {
       $checker->jumpTo("�޸�ʧ�ܣ�", $destUrl);
    }
    exit();
?>