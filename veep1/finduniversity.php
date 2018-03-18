<?
require_once("header.php");
/*
require_once 'config/ValidateCode.class.php';

session_start();
$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();
$_SESSION['authnum_session'] = $_vc->getCode();//验证码保存到SESSION中

*/
//session_destroy();
session_start();
//在页首先要开启session,
//将session去掉，以每次都能取新的session值;
//用seesion 效果不错，也很方便


?>


<div class="contain lg" style="height:480px">
  <div class="window" style="height:470px">

      <div class="h1">学校查询</div>
      <br>

    <p>
      <?
      $universityId =
          '　<label for="name">学校查询:</label>'.
          '<input type="text" maxlength="30"  class="txt"  id="university" name="university"   value="' . $university .'" readOnly style="" onclick="showProvince(this, 100, '. 0 .', \''. $university .'\')" >'.
          '<br/>　<label for="name">学校编号:</label><input type="text"  class="txt"  id="universityId" name="universityId" value="'.$universityId.'" />'
      ;
      echo $universityId;

      ?>
    </p>

      <input type="submit" class="btn" value="返回"  onclick="history.back()" ></p>


  </div>

</div>


<?
include("footer.php");
include("bubbleUniversity.php");
?>

