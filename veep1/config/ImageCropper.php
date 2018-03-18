<?php
class ImageCropper{

		// 目标图的最大宽、高、源图路径、目标图路径、切割横、纵坐标、切割宽、高。
    function crop($WIDTH_LIMIT, $HEIGHT_LIMIT, $src_file, $dst_file, $left, $top, $width, $height) {
        //$src = imagecreatefromstring(file_get_contents($src_file));
        //echo "in the beginning. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height. "; <br/>";
        $array = getimagesize($src_file);
        //$width_ori = $array[0];//原图宽
        //$height_ori = $array[1];//原图高
        $type = $array[2]; //图片类型

        switch($type) {//读出原图
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
        $w_src = imagesx($src_img);//原图的宽
        $h_src = imagesy($src_img);//原图的高
        $r_src = $w_src/$h_src;//原图的长宽比

        $WIDTH_FRAME  = 300;  //图片框的宽度
        $HEIGHT_FRAME = 400;  //图片框的高度
        $RATIO_FRAME = $WIDTH_FRAME/$HEIGHT_FRAME; //图片框长宽比 0.75


        //图片与图片框不一样大，则原图在图片框中被压缩或放大了了，数值是错的；
        $ratio_width = $w_src/$WIDTH_FRAME;//横向缩放比例
        $ratio_height = $h_src/$HEIGHT_FRAME;//纵向缩放比例
        $ratio = $ratio_width;
        if ($r_src > $RATIO_FRAME) {//图片比图片框扁，那么缩放比是横向比例
        } else {//图片瘦长，使用纵向缩放比例
          $ratio = $ratio_height;
        }
        $left = (int)($left * $ratio);
        $top = (int)($top * $ratio);
        $width = (int)($width * $ratio);
        $height = (int)($height * $ratio);

        //echo "here. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height . "; w_src=" .$w_src . "; h_src=" .$h_src. "; <br/>";
				//避免超长超宽
				if (($left) > $w_src) {//起点超过宽度，起点设为 0
					$left = 0;
				}
				if (($width) > $w_src) {//截取宽度超过图片宽度
					$width = $w_src;
				}
				if (($left + $width) > $w_src) {//起点加宽度超过图片宽度
						$width = $w_src - $left;
				}
				
				
				if (($top) > $h_src) {//起点超过宽度，起点设为 0
					$top = 0;
				}
				if (($height) > $h_src) {//截取宽度超过图片宽度
					$height = $h_src;
				}
				if (($top + $height) > $h_src) {//起点加高度超过图片高度
						$height = $h_src - $top;
				}
        //echo "here. src_file= " . $src_file . "; dst_file=" .$dst_file . "; left=" .$left . "; top=" .$top . "; width=" .$width . "; height=" .$height . "; w_src=" .$w_src . "; h_src=" .$h_src. "; <br/>";
				
        //$dst_file = imagecreatetruecolor($width, $height);
        //定义临时图片用来缩放

				/*图片宽度超过 200 则只截取 200宽
        if ($width > $WIDTH_LIMIT)
            $width = $WIDTH_LIMIT;
        if ($height > $HEIGHT_LIMIT)
            $height = $HEIGHT_LIMIT;
				*/

        if ($WIDTH_LIMIT == $HEIGHT_LIMIT) {//最大长宽相等，则强制输出正方形，切掉多处的部分
        	$side_length = min($width, $height);
        	$width = $height = $side_length;
      	} else {// 长宽不同。例如要求 4:3，进入图片 400:200 if ($WIDTH_LIMIT > $HEIGHT_LIMIT)
      		$ratio_limit = $WIDTH_LIMIT/$HEIGHT_LIMIT;// 4/3
      		$length = (int) ($height * $ratio_limit);// 800/3 --> 266
      		$width = min ($length, $width);
      		$height = (int) ($width/$ratio_limit);// 798/4 --> 199
      	}
        $int_img = imagecreatetruecolor($width, $height);
        imagecopy($int_img, $src_img, 0, 0, $left, $top, $width, $height);
        //echo "int_img=" . $int_img . "; 宽=". imagesx($int_img) . "; 高=" . imagesy($int_img)."<br/>";

        //$new_img = imagecreatetruecolor($width, $height);//定义一个新图片
        //imagecopyresampled($src_img, $new_img, $left, $top, 0, 0, $width, $height, $width, $height);

        /*
        echo "src_img=" . $src_img . "; src_file; =". $src_file  . "; dst_file; =". $dst_file . "; 宽=". $w_src . "; 高=" . $h_src . "; ratio=" . $ratio  .
            "; left=" . $left."; top=" . $top."; width=" . $width."; height=" . $height. "<br/>";*/

        //输出图片
        switch($type) {//读出原图
            case IMAGETYPE_JPEG://统一输出为 jpg
              imagejpeg($int_img, $dst_file, 77);// 最后参数是压缩比，0 最低， 100 最高。gif, png 无此参数。
              break;
            case IMAGETYPE_PNG:
              //header("Content-Type: image/png");
              //imagejpeg($int_img);
              imagejpeg($int_img, $dst_file, 77);// 最后参数是压缩比，0 最低， 100 最高。gif, png 无此参数。
              break;
            case IMAGETYPE_GIF:
              //imagegif($src_file, $dst_file);// gif, png 无压缩比
              imagejpeg($int_img, $dst_file, 77);// 最后参数是压缩比，0 最低， 100 最高。gif, png 无此参数。
              break;
            default:
              return false;
        }/**/

        imagedestroy($src_img);
        imagedestroy($int_img);
        //imagedestroy($dst_file);
        //echo "mmmm" . "<br/>";

        //删除图片
        if(file_exists($src_file)) {//判断原头像是否存在
             unlink($src_file);//删掉旧头像
        }

        return true;
    }
}
?>