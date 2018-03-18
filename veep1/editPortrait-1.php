<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


require_once("header.php");

$dsql = new DSQL();

    $SQL = "select *  from users where id = $auth_id  ";
    //echo $SQL;
    $dsql->query($SQL);

    if ($dsql->next_record()) {
        $id = $dsql->f('id');
        $nickname = $dsql->f('nickname');
        $email = $dsql->f('email');

        $userimg = $dsql->f('userimg');
        if(!file_exists($userimg)){
            $userimg="img/teacher.png";
        }
        ?>
<style type="text/css">
    .myuserimgedit {
        background: #1E8997;
    }
    .myuserimgedit a{
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
  #dragDiv{border:1px dashed #fff; width:288px; height:288px; top:0px; left:0px; cursor:move; }


</style>

        <script type="text/javascript" language="javascript" src="js/laydate.js">
        </script>
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
          <li><a href="myuserimgedit.php">头像和简介</a></li>
          <li>修改头像</li>
      </ul>
      <form action="editPortrait-delete.php" class="rgform" id="frm2"  method="post" enctype="multipart/form-data">
        <input type="hidden" name="userimg" id="userimg" value="<? echo $userimg ?>">
        <div class="form-group">
          <div class="field" style="text-align: center;">
            <span for="name">当前头像</span><br>
            <a href="<? print($userimg) ?>" target="_blank"><img src="<?print($userimg)?>" height="200"></a><br><br>
            <input type="submit" id="dd" name="dd" class="button bg-sub button-middle" value="删除当前头像" onclick="">
          </div>
        </div>
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
            <form action="editPortrait-update.php" class="rgform" id="frm1"  method="post" enctype="multipart/form-data">
              <div>
                <input id="userimgNew" name="userimgNew" type="file" value="请选图" onchange="changeImg(this)" /><br/>
                <input type="hidden" name='id' value="<? print($id) ?>">
                <input type="hidden" name='olduserimg' value="<? echo $userimg ?>">
              </div>
              <div class="form-group" style="margin-top: 20px;">
                <input type="submit" id="submit" name="submit" class="button bg-main" value="提交" onclick="return checkImageType(this.form.userimgNew);">
              </div>
              <input id="showPosition" name="showPosition" type="hidden" value="" />
            </form>
          </td>
        </tr>
      </table>
<!--input id="idSize" type="button" value="缩小显示" />
<input id="idOpacity" type="button" value="全透明" />
<input id="idColor" type="button" value="白色背景" />
<input id="idScale" type="button" value="使用比例" />
<input id="idMin" type="button" value="设置最小尺寸" />
<input id="idView" type="button" value="缩小预览" />
<br/>
当前图片宽度：
<input id="imgWidth" name="imgWidth" value="0" readOnly size="1" />
当前图片高度：
<input id="imgHeight" name="imgHeight" value="0" readOnly size="1" /-->
<!--input id="idImg" type="button" value="换图片" />
<br/><br/-->
<!--input id="idPic" type="button" value="" /-->

<script type="text/javascript" src="js/imageCropper-2.js"></script>


<script type="text/javascript" src="js/checkImageType.js"></script>
<?
    }
include("footer.php");
?>
