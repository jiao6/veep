<?


require_once("config/config.php");;

require_once("config/dsql.php");
if(!isset($dsql)){
    $dsql = new DSQL();
}
require_once("header.php");

if($pid>0){

}else{
    $pid=1;
}
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
$SQL = "SELECT  *   FROM experimentsgroup   where  id=$pid     limit 0,1   ";


@$dsql->query($SQL);
if($dsql->next_record()){
    $group_name = $dsql->f('group_name');
    $id = $dsql->f('id');

}else{
    $group_name = "实验课";
}
//echo $SQL . "\n";

/* 用来分页 */
$SQL = "SELECT  count(*) as allcount FROM experimentsgroup  where pid=$pid ";
$dsql->query($SQL);
$dsql->next_record();
$numrows = $dsql->f("allcount");
require_once("config/Pagination.php");
if (!isset ($pagesize))
    $pagesize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
if ((!isset ($page)) or $page < 1)
    $page = 1;
require_once("config/Pagination.php");
$pagination = new Pagination($numrows, $pagesize, $page);
$offset = ($page - 1) * $pagesize;

/*#用来分页#*/


$SQL = "SELECT    id ,  name        ,uid,   pid     ,path   ,status    FROM experimentsgroup  where pid=$pid   limit $offset, $pagesize   ";
if(!isset($dsql)){
    $dsql = new DSQL();
}
$dsql->query($SQL);

// echo $SQL . "\n";

?>
  <style type="text/css">
    .table{
        width: 90%;
        border: 1px;
        text-align: center;
        margin:30px 0 0 40px;
    }
    .table tr{
        height: 35px;
    }
    .grouplist {
        background: #1E8997;
    }
    .grouplist a {
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
</div>
<div class="admin">
    <div class="rhead">
        <!-- <div class="rhead1">实验分组管理</div> -->
        <div class="rhead2"><a href="groupaction.php?pid=<?print($pid)?>" class="button button-small bg-main">增加实验课分组</a></div>
    </div>
    <div class="ht301">
    <div class="ht304">
        <table class="rt_table" width="100%">
            <tr>
                <th class="f_right">编号</th>
                <th class="f_left">组名</th>
                <th class="f_left">操作</th>
            </tr>
                <?
                    $info = "";
                    $phonelist = "";
                    $type=1 ;
                    $olddepth = 0;
                    $i =0;
                    while($dsql->next_record()){
                        $i++;
                        $id=$dsql->f('id');
                        $group_name=$dsql->f('name');
                        $insertdate=$dsql->f('insertdate');
                        $userid=$dsql->f('userid');
                        $group_pid=$dsql->f('pid');
                        $path =$dsql->f('path');
                        $newdepth =  substr_count($path,',');
                        //echo "";
                        ?>
                        <tr align="">
                        <td align="right"><?print($id)?>&nbsp;&nbsp;&nbsp;&nbsp;<a name='<?print($id)?>'></a></td>
                        <td align=""><a href=grouplist.php?pid=<?print($id)?>><?print($group_name)?></a></td>
                        <td>
                                <a href="groupaction.php?action=forumdetail&fid=<?print($id)?>" title="修改本分组设置"><img src='img/edit.png' width='28' height='28' alt='修改' title='修改' /></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="groupaction.php?action=forumdelete&fid=<?print($id)?>" title="删除本分组"><img src='img/delete.png' width='28' height='28' alt='删除' title='删除' /></a>
                        </td>
                      </tr>
                        <?
                    }
                    if($i==0){
                        ?>
                        <tr align="center">
                        <td></td>
                        <td>无记录</td>
                        <td> </td>
                      </tr>
                        <?
                    }
                ?>
            <tr>
                <td colspan="3">
                <?
                    $url = "grouplist.php";
                    $queryString = "?id=$id&search=$search&page=";
                    $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
                    $PARAM_PAGE_NO = "page";//放页号的 url 参数
                    $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                ?>
                </td>
            </tr>
        </table>
    </div>
</div>
<?
include("footer.php");

?>