<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
error_reporting(0);
session_start();

if (!isset($dsql)) {
    $dsql = new DSQL();
}
$SQL = "select u.university_id ,u.university  from  LESSON l,courses  c,users u  where l.id = '$lessonId'   and l.course_id = c.id and c.teacher_id=u.id   and (l.assigner_id='$auth_id' or 3='$auth_pid' or l.teacher_id='$auth_id')";
//echo $SQL;
$dsql->query($SQL);
if($dsql->next_record()){

    $universityId = $dsql->f('university_id');
    $university  = $dsql->f('university');

}else{
    echo "<br><script>alert('没有权限');history.back()</script>\n";
    exit;
}
?>
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
            </div>
            <div class="admin">
            <div class="rhead">
                <div class="rhead1">导入用户</div>
            </div>
            <div class="rfg"></div>
            <form action="importuser_action.php" class="rgform form-x"   method="post"  enctype="multipart/form-data">
                <div class="form-group">
                     <div class="label"><label for="text3"></label></div>
                    (1) 请您将学生的“姓名”、“学号”、“班级” 信息填入到模板中，并将此文件上传。<br/>
                    <div class="label"><label for="text3"></label></div>
                    (2) 您所在学校的学校编码为：<?print($universityId);?>，导入学生用户的初始登录名为学校编码+学号，初始密码为学号。<br/>
                    <div class="label"><label for="text3"></label></div>
                    例如导入学生“李明”的学号为“20160001”，则李明的登录名为“<?print($universityId);?>20160001”，登录密码为：20160001。初次登录请提示学生修改邮箱。<br/>
                    <div class="label"><label for="text3"></label></div>
                    <div><a href="img/demo.xls"  style="color: red">下载模板</a></div>
                </div>
                 <div class="form-group">
                 	<div class="label">
                    	<label for="name">浏览文件</label>
                    </div>
					<div class="field">
                    	<input type="file" name="user">
                    </div>
                </div>

                <div class="form-group">
					<div class="label">
						<label for="text3"></label>
					</div>
					<div class="field">
                    	<input type="button" id="button" name="button"  class="button bg-main" value="返回"  onclick="history.back();" style="margin-right:50px"> <input type="submit" id="submit" name="submit" class="button bg-main" value="提交">
                    <input type="hidden"  class="button bg-main" name='lessonId' value="<? print($lessonId) ?>"><input type="hidden" name='ac' value="import">
					</div>
                </div>
            </form>
    <script type="text/javascript">
        $('.rt').height(700);
        $('.contain').height(800);
    </script>
<?
include("footer.php");
?>