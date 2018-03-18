<?php
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}


$dsql = new DSQL();

if ($auth_pid != 3) {
    exit;
}
$whereinfo = " ";

if ($search) {
    $whereinfo .= " and (    (binary   a.name like '%$search%')or(a.mobile like '%$search%')or(binary  a.content like '%$search%'))  ";
}


$SQL = "SELECT count(*) as allcount  FROM    experiments  a  where ( 1=1)  $whereinfo   ";
$dsql->query($SQL);


$dsql->next_record();
$numrows = $dsql->f("allcount");

require_once("config/Pagination.php");
if (!isset ($pagesize))
    $pagesize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
if ((!isset ($page)) or $page < 1)
    $page = 1;

$pagination = new Pagination($numrows, $pagesize, $page);
$pages = $pagination->getPageCount();
$page  = $pagination->getPageNo();
//echo "pageCount=" . $pages  . "; pageNo=" . $page . "; isFirst=" . $pagination->isFirst() . "; hasPrev=" . $pagination->hasPrev() .  "; isLast=" . $pagination->isLast().  "; hasNext=" . $pagination->hasNext().  "; getPrev=" . $pagination->getPrev().  "; getNext=" . $pagination->getNext().  "; hasRecord=" . $pagination->hasRecord().  "; getPageList=" . ($pagination->getPageList())."<br/>";

$offset = ($page - 1) * $pagesize;


require_once("config/MetaDataGenerator.php");
?>

<style>
    .experimentslist {
        background: #1E8997;
    }
    .experimentslist a {
        color: #fff !important;
    }
    .rt_table td {
        padding-bottom: 5px;
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
            <div class="rhead">
                <!-- <div class="rhead1">实验管理</div> -->
                <div class="rhead2"><a href="experiment_add.php"  class="button bg-sub button-small">新增实验</a></div>
            </div>
            <div class="rfg"></div>
            <table class="rt_table" style="width: 100%">
                <tr>
                    <th class="f_right">编号</th>
                    <th class="f_left">名称</th>
                    <th class="f_left">类型</th>
                    <th class="f_left">难度</th>
                    <th class="f_left">排序</th>
                    <th class="f_left">状态</th>
                    <th class="f_left">操作</th>
                </tr>
                <?
                    if ($offset < 0) $offset = 0;
                    //group
                    $SQL = "SELECT  *  from experiments where (1=1)  $whereinfo  order by id desc limit $offset,$pagesize";
                    $dsql->query($SQL);
                    $i = $offset;
                    while ($dsql->next_record()) {
                        $i++;
                        $id = $dsql->f('id');
                        $name = $dsql->f('name');
                        $content = $dsql->f('content');
                        $softfile = $dsql->f('softfile');
                        $sort = $dsql->f('sort');
                        $reportfile = $dsql->f('reportfile');
                        $userid = $dsql->f('userid');
                        $status = $dsql->f('status');
                        $groupid = $dsql->f('groupid');
                        $img = $dsql->f('img');
                        $type = $dsql->f('type');
                        $difficulty = $dsql->f('difficulty');
                        echo "<tr>
                            <td align='right'>$id<a name='$id'></a></td>
                            <td onmouseout='hideBubble()' onmouseover='showWholeName(this, ". MetaDataGenerator::STRING_TRUNCATE_LENGTH .", $id, \"$name\")' >" . MetaDataGenerator::getShortenString($name) ."</td>
                            <td>". MetaDataGenerator::getExperienceImageFromChar($type) ."</td>
                            <td>". MetaDataGenerator::getDifficultyImageFromChar($difficulty) ."</td>
                            <td>$sort </td> ";
                        //MetaDataGenerator::getDifficultyImageFromChar($difficulty)

                        //有效性图片
                        echo "<td><nobr>". MetaDataGenerator::getStatusImageFromChar($status) ."</td>";//MetaDataGenerator::getStatusImageFromChar($status)
                        if ($status == "0") {
                            $status = "有效";
                            //实验报告
                            echo "<td>  <a TARGET=_blank  href=reportDesign/daxe.html?file=../data/report/xml/test$id.xml&config=config/ExpReport_config.xml&save=../experimentsreportsave.php><img src='img/expreport.png' width='30' height='30' alt='实验报告' title='实验报告' /></a>   ";
                            //修改
                            echo "<a href=experimentsedit.php?id=$id&ac=edit><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a>";
                            //删除
                            echo "<a href='experimentsedit.php?ac=del&id=$id&status=1' onclick=\"return confirm('确定要删除吗')\"><img src='img/delete.png' width='30' height='30' alt='删除' title='删除' /></a><a href=experimentsedit.php?id=$id&ac=edit></a></td></tr>";
                        } else if ($status == "1") {
                            $status = "已删除";
                            //echo "<td><nobr>". ($status) ."</td>";//MetaDataGenerator::getStatusImageFromChar($status)
                            //激活
                            echo "<td><a href=experimentsedit.php?ac=del&id=$id&status=0><img src='img/activate.png' width='30' height='30' alt='激活' title='激活' /></a>";
                            echo "<a TARGET=_blank href=reportDesign/daxe.html?file=../data/report/xml/test$id.xml&config=config/ExpReport_config.xml&save=../experimentsreportsave.php><img src='img/expreport.png' width='30' height='30' alt='实验报告' title='实验报告' /></a></td></tr>";
                        }
                    }
                ?>
            </table>
            <br>
            <?
                $url = "experimentslist.php";
                $queryString = "?id=$id&search=$search&page=";
                $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
                $PARAM_PAGE_NO = "page";//放页号的 url 参数

                $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
            ?>

<?
include("bubbleWindow.php");

include("footer.php");
?>
