<?

error_reporting(0);
session_start();

error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
require_once("header.php");
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}


$whereinfo = " ";

if($coursesid){
    $whereinfo = " and (  coursesid = $coursesid)";
}
if($auth_pid==1){

    $whereinfo .= " and ( eu.userid='$auth_id'  )";
}
function time2second($remain){
    //计算小时数

    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;


    $time ="$hours 时$mins 分$secs 秒";

    return $time;
}



$SQL = " SELECT count(*) as allcount 
	FROM  experimentsuser eu, experiments e ,courses c,coursesexperiment ce  
	where e.id=eu.experimentsid and ce.coursesid=eu.coursesid and ce.COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." and ce.experimentsid=eu.experimentsid and c.id=eu.coursesid  $whereinfo   ";
$dsql->query($SQL);

$dsql->next_record();
$numrows = $dsql->f("allcount");

if (!isset ($pagesize))
    $pagesize = 40;
if ((!isset ($page)) or $page < 1)
    $page = 1;
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize)
    $pages++;
if ($page > $pages)
    $page = $pages;
$offset = ($page - 1) * $pagesize;
if($offset<0)$offset=0;

$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;


?>
<style type="text/css">
    .myscore {
        background: #1E8997;
    }
    .myscore a{
        color: #fff;
    }
</style>
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
                <div class="rhead1">成绩</div>


            </div>
            <div class="rfg"></div>
            <table class="rt_table">
                <tr class="r1">
                    <th>课堂名称</th>
                    <th>实验名称</th>
                    <th>评分方式 </th>
                    <th>提交时间 </th>
                    <th>耗时</th>
                    <th>答题次数</th>
                    <th>分数 </th>
                </tr>
                <?
                    $SQL = "SELECT eu.*,c.NAME as cname,e.name as ename,ce.scoringmode 
                    	FROM  experimentsuser eu, experiments e, LESSON c, coursesexperiment ce  
                    	where e.id=eu.experimentsid and ce.coursesid=eu.coursesid and ce.COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." and ce.experimentsid=eu.experimentsid 
                    	and c.ID=eu.coursesid  $whereinfo 
                    	order by eu.id desc  
                    	limit $offset, $pagesize ";
                    //echo $SQL;
                    $dsql->query($SQL);
                    $nbsp = $pernbsp = "|---------";
                    $olddepth =1;
                    $i = 0;
                    while($dsql->next_record()) {
                        $i++;
                        $id = $dsql->f('id');
                        $scoringmode = $dsql->f('scoringmode');
                        $experimentsid = $dsql->f('experimentsid');
                        $coursesid = $dsql->f('coursesid');
                        $userid = $dsql->f('userid');
                        $created_at = $dsql->f('created_at');
                        $useremail = $dsql->f('useremail');
                        $count = $dsql->f('count');
                        $consumingtime = $dsql->f('consumingtime');
                        $score = $dsql->f('score');
                        $cname = $dsql->f('cname');
                        $ename = $dsql->f('ename');
                        echo "<tr class=\"r3\">
                            <td title=".$id.">$cname</td>
                            <td>$ename</td>

                            <td>$scoringmode</td>

                            <td> $created_at</td>
                            <td> ".time2second($consumingtime)."</td>
                            <td>$count</td>
                            <td>$score</td>
                        </tr>";
                ?>
                <?
                    }
                if($i==0){
                    echo "<tr class=\"r3\">
                            <td>没有成绩</td>
                            <td></td>
                            <td></td>
                            <td></td>

                            <td> </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>";
                }
                ?>
            </table>
            <?
            echo "<br><div id=text14>共 $numrows 条 <a href='?id=$id&search=$search&page=$first'>首页</a><a href='?id=$id&search=$search&page=$prev'>上一页</a><a href='?id=$id&search=$search&&page=$next'>下一页</a><a href='?id=$id&search=$search&page=$last'>尾页</a><span>[$page/$pages]</span></div>";
            ?>
        </div>
    </div>

<?
include("footer.php");
?>