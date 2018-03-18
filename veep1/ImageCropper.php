<?php
class ImageCropper{

		// Ŀ��ͼ�������ߡ�Դͼ·����Ŀ��ͼ·�����и�ᡢ�����ꡢ�и���ߡ�
    function crop($WIDTH_LIMIT, $HEIGHT_LIMIT, $src_file, $dst_file, $left, $top, $width, $height) {
        //$src = imagecreatefromstring(file_get_contents($src_file));
        //echo "in the beginning. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height. "; <br/>";
        $array = getimagesize($src_file);
        //$width_ori = $array[0];//ԭͼ��
        //$height_ori = $array[1];//ԭͼ��
        $type = $array[2]; //ͼƬ����

        switch($type) {//����ԭͼ
            case IMAGETYPE_JPEG:
              $src_img = imagecreatefromjpeg($src_file);
              break;
            case IMAGETYPE_PNG:
              $src_img = imagecreatefrompng($src_file);
              break;
            case IMAGETYPE_GIF:
              $src_img = imagecreatefromgif($src_file);
              break;
            default:
              exit();
        }
        $w_src = imagesx($src_img);//ԭͼ�Ŀ�
        $h_src = imagesy($src_img);//ԭͼ�ĸ�
        $r_src = $w_src/$h_src;//ԭͼ�ĳ����

        $WIDTH_FRAME  = 300;  //ͼƬ��Ŀ��
        $HEIGHT_FRAME = 400;  //ͼƬ��ĸ߶�
        $RATIO_FRAME = $WIDTH_FRAME/$HEIGHT_FRAME; //ͼƬ�򳤿�� 0.75


        //ͼƬ��ͼƬ��һ������ԭͼ��ͼƬ���б�ѹ����Ŵ����ˣ���ֵ�Ǵ�ģ�
        $ratio_width = $w_src/$WIDTH_FRAME;//�������ű���
        $ratio_height = $h_src/$HEIGHT_FRAME;//�������ű���
        $ratio = $ratio_width;
        if ($r_src > $RATIO_FRAME) {//ͼƬ��ͼƬ��⣬��ô���ű��Ǻ������
        } else {//ͼƬ�ݳ���ʹ���������ű���
          $ratio = $ratio_height;
        }
        $left = (int)($left * $ratio);
        $top = (int)($top * $ratio);
        $width = (int)($width * $ratio);
        $height = (int)($height * $ratio);

        //echo "here. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height . "; w_src=" .$w_src . "; h_src=" .$h_src. "; <br/>";
				//���ⳬ������
				if (($left) > $w_src) {//��㳬����ȣ������Ϊ 0
					$left = 0;
				}
				if (($width) > $w_src) {//��ȡ��ȳ���ͼƬ���
					$width = $w_src;
				}
				if (($left + $width) > $w_src) {//���ӿ�ȳ���ͼƬ���
						$width = $w_src - $left;
				}
				
				
				if (($top) > $h_src) {//��㳬����ȣ������Ϊ 0
					$top = 0;
				}
				if (($height) > $h_src) {//��ȡ��ȳ���ͼƬ���
					$height = $h_src;
				}
				if (($top + $height) > $h_src) {//���Ӹ߶ȳ���ͼƬ�߶�
						$height = $h_src - $top;
				}
        //echo "here. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height . "; w_src=" .$w_src . "; h_src=" .$h_src. "; <br/>";
				
        //$dst_file = imagecreatetruecolor($width, $height);
        //������ʱͼƬ��������

				/*ͼƬ��ȳ��� 200 ��ֻ��ȡ 200��
        if ($width > $WIDTH_LIMIT)
            $width = $WIDTH_LIMIT;
        if ($height > $HEIGHT_LIMIT)
            $height = $HEIGHT_LIMIT;
				*/

        if ($WIDTH_LIMIT == $HEIGHT_LIMIT) {//��󳤿���ȣ���ǿ����������Σ��е��ദ�Ĳ���
        	$side_length = min($width, $height);
        	$width = $height = $side_length;
      	} else {// ����ͬ������Ҫ�� 4:3������ͼƬ 400:200 if ($WIDTH_LIMIT > $HEIGHT_LIMIT)
      		$ratio_limit = $WIDTH_LIMIT/$HEIGHT_LIMIT;// 4/3
      		$length = (int) ($height * $ratio_limit);// 800/3 --> 266
      		$width = min ($length, $width);
      		$height = (int) ($width/$ratio_limit);// 798/4 --> 199
      	}
        $int_img = imagecreatetruecolor($width, $height);
        imagecopy($int_img, $src_img, 0, 0, $left, $top, $width, $height);
        //echo "int_img=" . $int_img . "; ��=". imagesx($int_img) . "; ��=" . imagesy($int_img)."<br/>";

        //$new_img = imagecreatetruecolor($width, $height);//����һ����ͼƬ
        //imagecopyresampled($src_img, $new_img, $left, $top, 0, 0, $width, $height, $width, $height);

        /*
        echo "src_img=" . $src_img . "; src_file; =". $src_file  . "; dst_file; =". $dst_file . "; ��=". $w_src . "; ��=" . $h_src . "; ratio=" . $ratio  .
            "; left=" . $left."; top=" . $top."; width=" . $width."; height=" . $height. "<br/>";*/

        //���ͼƬ
        switch($type) {//����ԭͼ
            case IMAGETYPE_JPEG://ͳһ���Ϊ jpg
              imagejpeg($int_img, $dst_file, 77);// ��������ѹ���ȣ�0 ��ͣ� 100 ��ߡ�gif, png �޴˲�����
              break;
            case IMAGETYPE_PNG:
              //header("Content-Type: image/png");
              //imagejpeg($int_img);
              imagejpeg($int_img, $dst_file, 77);// ��������ѹ���ȣ�0 ��ͣ� 100 ��ߡ�gif, png �޴˲�����
              break;
            case IMAGETYPE_GIF:
              //imagegif($src_file, $dst_file);// gif, png ��ѹ����
              imagejpeg($int_img, $dst_file, 77);// ��������ѹ���ȣ�0 ��ͣ� 100 ��ߡ�gif, png �޴˲�����
              break;
            default:
              return false;
        }/**/

        imagedestroy($src_img);
        imagedestroy($int_img);
        imagedestroy($dst_file);
        //echo "mmmm" . "<br/>";

        //ɾ��ͼƬ
        if(file_exists($src_file)) {//�ж�ԭͷ���Ƿ����
             unlink($src_file);//ɾ����ͷ��
        }

        return true;
    }
}
?>