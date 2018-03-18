<?
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/ImageCropper.php");

    
    $support_type=array(IMAGETYPE_GIF, IMAGETYPE_JPEG , IMAGETYPE_PNG);
    //echo $showPosition . "; olduserimg2= ; " . $olduserimg . "; userimgNew2=" . $userimgNew . "; <br/>";
    $array = getimagesize($userimgNew);
    //echo "0000" . "<br/>";
    $type = $array[2]; //ͼƬ����
	//$aaaa = $showPosition . "; olduserimg=[" . $olduserimg . "]; userimgNew=[" . $userimgNew  . "]; type=[" . $type. "]; <br/>";
	//echo "<script>alert('".$aaaa."')</script>";

    if(in_array($type, $support_type, true)) {//�ж��ǲ���ͼƬ��ֻ֧�� gif, jpg, png
    } else {
       //goBack("ʧ��");
    }
//�������޸�ͷ��
    //echo "1111" . "<br/>";
     @mkdir("data");//�������Ŀ¼
     @mkdir("data/userimg");

    //echo "type= " . $type . "<br/>";

      if ($olduserimg != "img/teacher.png") {//ԭ����ͷ��
        if(file_exists($olduserimg)) {//�ж�ԭͷ���Ƿ����
             unlink($olduserimg);//ɾ����ͷ��
        }
      }

    //echo "2222" + "<br/>";
     $arrName = explode(".",$_FILES["userimgNew"]["name"]);
     $intName = sizeof($arrName);
     $extName = strtolower($arrName[$intName-1]); //����ȡ���ϴ��ļ��ĺ�׺��.

     $userimg1 = "data/userimg/".$auth_id. '.1.'.$extName;
     $userimg2 = "data/userimg/".$auth_id.'.'. "jpg";//��չ��ͳһ��Ϊ jpg
    //echo $showPosition . "; userimg1=" . $userimg1 . "<br/>";
    $array = explode(";", $showPosition);
    $left = $array[0];
    $top = $array[1];
    $width = $array[2];
    $height = $array[3];
    @copy($_FILES["userimgNew"]["tmp_name"], $userimg1);
    if($_FILES["userimgNew"]["name"]){
        $SQL = "update users set userimg = '$userimg2' where id = $auth_id  ";
        //echo $showPosition . "; " . $SQL . "<br/> ". $left . "; " . $top . "; " . $width. "; ". $height. "<br/>";
    }


    //����Դͼ��ʵ��
		//echo "��ʼ��ͼ����" . $showPosition . "; userimg1=" . $userimg1 . "; userimg2=" . $userimg2. "<br/>". "<br/>";
		$WIDTH_LIMIT  = 200;	//ͼƬ�����
		$HEIGHT_LIMIT = 200;	//ͼƬ���߶�
		
		$imageCropper = new ImageCropper();
		$succ = $imageCropper->crop ($WIDTH_LIMIT, $HEIGHT_LIMIT, $userimg1, $userimg2, $left, $top, $width, $height);
		//echo "��ͼ��������" . "<br/>";

		
    $dsql = new DSQL();
    if ($dsql->query($SQL)) {
       goBack("�ɹ�");
        //exit;	�� 
    } else {
       goBack("ʧ��");
    }
    
		function goBack($result) {
        echo "<script>alert('�޸�ͷ��". $result ."'); document.location='myuserimgedit.php';</script>\n";  //
		}

?>