<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}



require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");




require_once("header.php");

$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
$checker = new CheckerOfCourse();
$QUERY_STRING_2 = $checker->conertChar($QUERY_STRING_1);

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课堂图片


$dsql = new DSQL();

    $SQL = "select ID, NAME, IMG_URL from LESSON where ID = $lessonId  ";
    //echo $SQL;
    $dsql->query($SQL);

    if ($dsql->next_record()) {
        $lessonName = $dsql->f('NAME');
        $shortLessonName = MetaDataGenerator::getShortenString($lessonName, MetaDataGenerator::STRING_TRUNCATE_LENGTH_OF_INFO_PAGE);
        if (strlen($shortLessonName) > 0)
        	$shortLessonName = "【". $shortLessonName ."】";
        $IMG_URL = $dsql->f('IMG_URL');
		$IMG_URL = MetaDataGenerator::getImage($IMG_URL, $DEFAULT_COURSE_IMAGE);
        ?>
<style type="text/css">
    .lessonlist {/* 左侧菜单高亮 */
        background: #1E8997;
    }
    .lessonlist a{
        color: #fff !important;
    }
</style>


<!-- 切割图片用到 imageCropper-1.js，drag.js，resize.js，imageCropper-2.js。
   imageCropper-2.js 放在后边。顺序并不能错 -->
<script type="text/javascript" src="js/imageCropper-1.js"></script>
<script type="text/javascript" src="js/drag.js"></script>
<script type="text/javascript" src="js/resize.js"></script>
<style type="text/css" >
  #rRightDown,#rLeftDown,#rLeftUp,#rRightUp,#rRight,#rLeft,#rUp,#rDown{
    position:absolute;
    background:#FFF;
    border: 1px solid #333;
    width: 6px;
    height: 6px;
    z-index:500;
    font-size:0;
    opacity: 0.5;
    filter:alpha(opacity=50);
  }

  #rLeftDown,#rRightUp{cursor:ne-resize;}
  #rRightDown,#rLeftUp{cursor:nw-resize;}
  #rRight,#rLeft{cursor:e-resize;}
  #rUp,#rDown{cursor:n-resize;}

  #rLeftDown{left:-4px;bottom:-4px;}
  #rRightUp{right:-4px;top:-4px;}
  #rRightDown{right:-4px;bottom:-4px;background-color:#00F;}
  #rLeftUp{left:-4px;top:-4px;}
  #rRight{right:-4px;top:50%;margin-top:-4px;}
  #rLeft{left:-4px;top:50%;margin-top:-4px;}
  #rUp{top:-4px;left:50%;margin-left:-4px;}
  #rDown{bottom:-4px;left:50%;margin-left:-4px;}
    /*图片显示区的尺寸，宽 300，高 400；拉伸区尺寸，左上点 50,50，宽 100，高 100，可能需要更改*/
  #bgDiv{width:300px; height:400px; border:1px solid #666666; position:relative;}
  #dragDiv{border:1px dashed #fff; width:294px; height:221px; top:0px; left:0px; cursor:move; }
</style>
<script type="text/javascript" language="javascript" src="js/laydate.js"> </script>
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
      <ul class="bread">
          <li><a href="lessonlist.php">课堂管理</a></li>
          <li>修改</li>
          <li>修改课堂图片</li>
      </ul>
      <form action="editLessonImage-delete.php" class="rgform" id="frm2"  method="post" >
        <input type="hidden" name="lessonId" id="lessonId" value="<? echo $lessonId ?>">
        <input type="hidden" name="IMG_URL" id="IMG_URL" value="<? echo $IMG_URL ?>">
        <input type="hidden" name="QUERY_STRING_2" id="QUERY_STRING_2" value="<? echo $QUERY_STRING_2 ?>">
        <div class="form-group">
          <div class="field" style="text-align: center;">
            <span for="name"><? echo $shortLessonName ?>课堂图片</span><br>
            <a href="<? print($IMG_URL) ?>" target="_blank"><img src="<?print($IMG_URL)?>" height="120"></a><br><br>
            <input type="submit" id="dd" name="dd" class="button bg-sub button-middle" value="删除课堂图片" onclick="">
          </div>
        </div>
        <div></div><br><br>
      </form>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 700px;margin: auto;margin-top: 30px;">
        <tr>
          <td style="width: 30%">
            <div id="bgDiv">
              <div id="dragDiv">
                <div id="rRightDown"> </div>
                <div id="rLeftDown"> </div>
                <div id="rRightUp"> </div>
                <div id="rLeftUp"> </div>
                <div id="rRight"> </div>
                <div id="rLeft"> </div>
                <div id="rUp"> </div>
                <div id="rDown"></div>
              </div>
            </div>
          </td>
          <td align="center" style="vertical-align: top;">
            <div id="viewDiv" style="width:300px; height:300px;"> </div>
          </td>
          <td style="vertical-align: middle;">
            <form action="editLessonImage-update.php" class="rgform" id="frm1"  method="post" enctype="multipart/form-data">
              <div>
                <input id="coursesImgNew" name="coursesImgNew" type="file" value="请选图" onchange="changeImg(this)" /><br/>
                <input type="hidden" name="lessonId" value="<? print($lessonId) ?>">
                <input type="hidden" name='coursesImgOld' value="<? echo $IMG_URL ?>">
                <input type="hidden" name="QUERY_STRING_2" id="QUERY_STRING_2" value="<? echo $QUERY_STRING_2 ?>">
              </div>
              <div class="form-group" style="margin-top: 20px;">
                <input type="submit" id="submit" name="submit" class="button bg-main" value="提交" onclick="return checkImageType(this.form.coursesImgNew)">
              </div>
              <input id="showPosition" name="showPosition" type="hidden" value="" />
          </form>
          </td>
        </tr>
      </table>
<br/>
<script type="text/javascript" src="js/imageCropper-2.js">
</script>
<script type="text/javascript" src="js/checkImageType.js"></script>
<?
}
include("footer.php");
?>
