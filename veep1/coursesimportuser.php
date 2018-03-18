<?

error_reporting(0);
session_start();
require_once("config/config.php");
require_once("config/dsql.php");

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

require_once("header.php");


if (!isset($dsql)) {
    $dsql = new DSQL();
}

?>
<style type="text/css">
    .lessonlist {
        background: #1E8997;
    }
    .lessonlist a{
        color: #fff !important;
    }
</style>
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
              <li>导入白名单</li>
        </ul>
            <form action="lessonedit.php" class="rgform">
                <div class="form-group">
                    <label for="name">账号列表（请将账号复制到文本框，一行一个账号）,提交之后将覆盖原有的账号：</label>
                    <div class="field">
                        <textarea name="email" style="left: 10px;float: none;width: 300px;height: 200px"  placeholder="名称" datatype="*"  placeholder="学生账号" datatype="*" errormsg="请输入学生账号" >
                            <?
                                $SQL = "SELECT   DISTINCT  email FROM coursesemail where coursesid = '$lessonId' order by id asc ";
                                $dsql->query($SQL);
                                while ($dsql->next_record()) {
                                    $email = $dsql->f('email');
                                    echo "$email\n";
                                }
                            ?>
                        </textarea>
                    </div>
                </div>
                <div class="form-group">
                    <input type="button" id="button" name="button" class="button bg-sub"  value="返回"  onclick="history.back();" style="left: 10px;">
                    <input type="submit" id="submit" name="submit" class="button bg-main" value="提交" style="left: 10px;">
                    <input type="hidden" name='lessonId' value="<? print($lessonId) ?>">
                    <input type="hidden" name='ac' value="import">
                </div>
            </form>
            <script type="text/javascript" src="js/validform.js"></script>
            <script type="text/javascript">
                $(function () {
                    $(".rgform").Validform({
                        tiptype: 3,
                        label: ".label",
                        showAllError: true

                    });
                })
            </script>
    <script type="text/javascript">
        $('.rt').height(700);
        $('.contain').height(800);
    </script>
<?
include("footer.php");
?>