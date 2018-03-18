<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/CheckerOfCourse.php");

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");

$QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
$QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page
$checker = new CheckerOfCourse();
$QUERY_STRING_2 = $checker->conertChar($QUERY_STRING_1);

$DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;//缺省课程图片
$COURSE_IMAGE_DIR     = CheckerOfCourse::COURSE_IMAGE_DIR;//图片存放位置


$dsql = new DSQL();

    $SQL = "select id, name, coursesimg from courses where id = $courseId  ";
    //echo $SQL;
    $dsql->query($SQL);

    if ($dsql->next_record()) {
        $courseName = $dsql->f('name');
        $coursesImg = $dsql->f('coursesimg');

        if($coursesImg && file_exists($coursesImg)){
        } else {
            $coursesImg = $DEFAULT_COURSE_IMAGE;
        }
        ?>
<style type="text/css">
    .courseslist {/* 左侧菜单高亮 */
        background: #1E8997;
    }
    .courseslist a{
        color: #fff;
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
  #dragDiv{border:1px dashed #fff; width:288px; height:216px; top:0px; left:0px; cursor:move; }


</style>

        <script type="text/javascript" language="javascript" src="js/laydate.js">
        </script>
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
                    <div class="rhead1">课程图片</div>
                </div>
                <div class="rfg"></div>
                <div valign="middle"><label for="name">当前课程图片</label>
                    <center><a href="<? print($coursesImg) ?>" target="_blank"><img src="<?print($coursesImg)?>" height="120"></a></center>
                </div>

                <form action="editCourseImage-delete.php" class="rgform" id="frm2"  method="post" >
                  <input type="hidden" name="courseId" id="courseId" value="<? echo $courseId ?>">
                  <input type="hidden" name="coursesImg" id="coursesImg" value="<? echo $coursesImg ?>">
                  <input type="hidden" name="QUERY_STRING_2" id="QUERY_STRING_2" value="<? echo $QUERY_STRING_2 ?>">
                  <div><input type="submit" id="dd" name="dd" class="btn2" value="删除课程图片" onclick=""></div><br><br>
                </form>

<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="300"><div id="bgDiv">
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
      </div></td>
    <td align="center"><div id="viewDiv" style="width:300px; height:300px;"> </div></td>
  </tr>
</table>
<br/>

<script type="text/javascript" src="js/imageCropper-2.js">
</script>

                <form action="editCourseImage-update.php" class="rgform" id="frm1"  method="post" enctype="multipart/form-data">
                    <div><label for="name">课程图片</label>
                    <input id="coursesImgNew" name="coursesImgNew" type="file" value="请选图" onchange="changeImg(this)" /><br/>
                    <input type="hidden" name="courseId" value="<? print($courseId) ?>">
                    <input type="hidden" name='coursesImgOld' value="<? echo $coursesImg ?>">
                    <input type="hidden" name="QUERY_STRING_2" id="QUERY_STRING_2" value="<? echo $QUERY_STRING_2 ?>">
                    <div><input type="submit" id="submit" name="submit" class="btn2" value="修改课程图片" onclick="return checkImageType(this.form.coursesImgNew)"></div>
                    <input id="showPosition" name="showPosition" type="hidden" value="" />
                </form>
                <script type="text/javascript" src="js/checkImageType.js"></script>
            </div>
        </div>
        <?
    }
include("footer.php");
?>