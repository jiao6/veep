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


$whereinfo = "  ";

if ($search) {
    $whereinfo .= " and (    (binary   a.nickname like '%$search%')or(a.mobile like '%$search%')or(binary  a.university like '%$search%')or(binary  a.truename like '%$search%')or( binary a.phonenumber like '%$search%'))  ";
}


$SQL = "SELECT count(*) as allcount  FROM    users  a  where ( 1=1)  $whereinfo  ";
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


$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;

?>
<style>
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
         <ul class="bread">
         	<li><a href="courseslistfeeteacher.php">用户管理</a></li>
            <li>用户修改</li>
         </ul>
        <div class="rfg"></div>
        <table class="rt_table" style="width: 100%">
            <tr>
                <td class="f_right">编号</td>
                <td class="f_left">类型</td>
                <td class="f_left">邮箱</td>
                <!--td>昵称</td-->
                <td class="f_left">学校</td>


                <td class="f_left">姓名</td>
                <!--td>上级</td-->
                <td class="f_left">付费时间</td>
                <td>停止时间</td>
                <!--td>使用数量</td>
                <td>是否付费</td>

                <td>付费数量</td>
                <td>已经使用</td-->
                <td class="f_left">状态</td>
                <td class="f_left">操作</td>
            </tr>
            <?
            if ($offset < 0) $offset = 0;
            //group
            $SQL = "SELECT  *  from users where (1=1)  $whereinfo limit $offset,$pagesize";
            $dsql->query($SQL);
            $i = $offset;
            while ($dsql->next_record()) {
                $i++;
                $id = $dsql->f('id');
                $nickname = $dsql->f('nickname');
                $email = $dsql->f('email');
                $password = $dsql->f('password');
                $university = $dsql->f('university');
                $college = $dsql->f('college');
                //$phonenumber = $dsql->f('phonenumber');
                $created_at = $dsql->f('created_at');
                $updated_at = $dsql->f('updated_at');
                $pwd = $dsql->f('pwd');
                $usertype = $dsql->f('usertype');
                $truename = $dsql->f('truename');
                $remember_token = $dsql->f('remeusertype');
                $feeuserid = $dsql->f('feeuserid');
                $isfee = $dsql->f('isfee');
                $paytime = $dsql->f('paytime');
                $endtime = $dsql->f('endtime');
                $payquantity = $dsql->f('payquantity');
                $used = $dsql->f('used');
                $status = $dsql->f('status');


                if ($usertype == 1) {

                    $usertype = "学生";
                } else if ($usertype == 2) {
                    $usertype = "教师";

                } else if ($usertype == 3) {
                    $usertype = "管理员";

                } else if ($usertype == 4) {
                    $usertype = "付费教师";

                }
                echo "<tr><td>$id</td>
											<td>$usertype</td>
											<td >$email </td>
											<!--td>$nickname</td-->
											<td>$university </td>


											<td>$truename </td>

											<td>$paytime</td>
											<td>$endtime</td>
											<!--td>$payquantity</td>
											<td>$isfee</td>
											
											<td>$used</td-->
											 ";

                if ($status == 0) {
                    $status = "有效";
                    echo "<td><nobr><img src='img/status_effective.png' width='30' height='30' alt='有效' title='有效' /><td><nobr><a href=useredit.php?ac=del&id=$id&status=1><img src='img/delete.png' width='30' height='30' alt='删除' title='删除' /></a>|<a href=useredit.php?id=$id&ac=edit><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></td></tr>";
                } else if ($status == 1) {
                    $status = "无效";
                    echo "<td><nobr><img src='img/status_deleted.png' width='30' height='30' alt='已删' title='已删' /><td><nobr><a href=useredit.php?ac=del&id=$id&status=0><img src='img/activate.png' width='30' height='30' alt='激活' title='激活' /></a>|<a href=useredit.php?id=$id&ac=edit><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></td></tr>";
                }
            }
            ?>
        </table>
        <?

        echo "<br><div id=text14><a href=?id=$id&search=$search&page=$first>首页</a><a href=?id=$id&search=$search&page=$prev>上一页</a><a href=?id=$id&search=$search&&page=$next>下一页</a><a href=?id=$id&search=$search&page=$last>尾页</a>一共$numrows 条 $pages 页 ($page/$pages)</div>";
        ?>


<?

include("footer.php");
?>


