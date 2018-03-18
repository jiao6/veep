<?
require_once("header.php");
error_reporting(0);
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
?>

    <div class="contain mc mc1">
        <div class="lt">
            <ul>
                <li class="gn">功能列表</li>
                <?
                include("menu.php");
                ?>

            </ul>
        </div>
        <div class="rt">
            <div class="rhead">
                <div class="rhead1">我的课程</div>

            </div>
            <div class="rfg"></div>
            <table class="rt_table">
                <tr>
                    <th>编号</th>
                    <th>课程名称</th>
                    <th>课程期限</th>
                    <th>图</th>
                    <th>操作</th>
                </tr><?

                $SQL = "SELECT u.* FROM courses c,coursesuser u  where u.userid='$auth_id' and u.coursesid = c.id order by id desc     ";
                //echo $SQL . "\n";
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 1;
                while($dsql->next_record()){
                    $id = $dsql->f('id');
                    $name = $dsql->f('name');
                    $moocurl = $dsql->f('moocurl');
                    $starttime = $dsql->f('starttime');
                    $endtime = $dsql->f('endtime');
                    $userid = $dsql->f('userid');
                    $created_at = $dsql->f('created_at');
                    $updated_at = $dsql->f('updated_at');
                    $coursesimg = $dsql->f('coursesimg');
                    $content = $dsql->f('content');
                    $useremail = $dsql->f('useremail');
                    $code = $dsql->f('code');
                    $payquantity = $dsql->f('payquantity');
                    $moocid = $dsql->f('moocid');
                    $step = $dsql->f('step');

                    ?>

                    <tr>
                        <td><? print($id) ?></td>
                        <td><? print($name) ?></td>
                        <td><? print($starttime . "-" . $endtime) ?></td>
                        <td><a href="<? print($moocurl) ?>"><img src="<? print($coursesimg) ?>" height="40"></a></td>
                        <td><a href="addclass2.php?coursesid=<? print($id) ?>">增加实验</a>/<a
                                href="addclass3.php?coursesid=<? print($id) ?>">管理实验</a></td>
                    </tr>
                    <?
                }
                ?>
            </table>
        </div>
    </div>

<?
include("footer.php");

?>