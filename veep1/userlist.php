<?php
    require_once("config/config.php");
    require_once("config/dsql.php");
    require_once("config/MetaDataGenerator.php");
    $QUERY_STRING = $_SERVER["QUERY_STRING"];// url 挂的所有参数。 oursesid=110&ac=edit&page
    //echo $QUERY_STRING. "<br/>"; #
    $QUERY_STRING_1 = strstr($QUERY_STRING, "&");//第一个 & 和之后的参数。 &ac=edit&page

    session_start();
    if (!$auth) loginFalse();

    function loginFalse()
    {
        Header("Location:login.php");
    }
    require_once("header.php");
    require_once("config/CheckerOfCourse.php");

    $isStudent = CheckerOfCourse::isStudent($auth_pid);
    $isTeacher = CheckerOfCourse::isTeacher($auth_pid);
    $isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
    $isAdmin = CheckerOfCourse::isAdmin($auth_pid);

    $dsql = new DSQL();

    $whereinfo = " ";

    if ($search) {
        $search = trim($search);
        $whereinfo .= " and (    ( binary  nickname like '%$search%' )or(binary   university like '%$search%')or(binary   truename like '%$search%')or( binary  phonenumber like '%$search%')or(email like '%$search%'))  ";
    }
    if($isAdmin){

    }else if($isFeeTeacher||$isTeacher){//管理导出用户
        $whereinfo = " and  creator_id=$auth_id  ";
    }else if($isStudent){
        $whereinfo = " and id=$auth_id ";
    }else{
        $whereinfo = " and id=$auth_id ";
    }
    $SQL = "SELECT * from users where (1=1)  $whereinfo ";
    $sql = MetaDataGenerator::generateCountSql($SQL);

    $dsql->query($sql);
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
    $offset = ($page - 1) * $pagesize;
?>
<style type="text/css">
    .userlist {
        background: #1E8997;
    }
    .userlist a {
        color: #fff !important;
    }
    .rt_table td:nth-child(2) {
        text-align: center;
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
            <?if($isAdmin){?>
                <a href="adduser.php" class="button button-small bg-sub">新增用户</a>
            <?}?>
            <form method="post" style="float: right;margin-right: 20px;">
                <div class="form-group">
                    <div class="field">
                        <input type="text" name="search" style="height: 30px; line-height: 30px;text-indent: 10px;width: 300px;border: 1px solid #ccc;">
                        <input type="submit" id="submit" name="submit" class="submit button button-small bg-main" value="搜索">
                    </div>
                </div>
            </form>
        </div>
        <table class="rt_table" style="width: 100%">
            <tr>
                <th class="f_right">编号</th>
                <th>类型</th>
                <th class="f_left">邮箱</th>
                <!--th>昵称</th-->
                <th class="f_left">学校</th>
                <th class="f_left">姓名</th>
                <!--th>上级</th>
                <th>付费时间</th>
                <th>停止时间</th-->
                <th>创建时间</th>
                <!--th>使用数量</th>
                <th>是否付费</th>
                <th>付费数量</th>
                <th>已经使用</th-->
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?
                //group
                $MAX_EMAIL_LENGTH = 20;
                $SQL .= "limit $offset, $pagesize";
                //echo $SQL;
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
                    //$paytime = MetaDataGenerator::getTimeString($dsql->f('paytime')); //date("y年n月j日", $dsql->f('paytime'));// $dsql->f('paytime');
                    //$endtime = MetaDataGenerator::getTimeString($dsql->f('endtime'), false);// $dsql->f('endtime');
                    $created_at = MetaDataGenerator::getTimeString($dsql->f('created_at'), true, "Y年m月d日 H:i");// $dsql->f('endtime');
                    //MetaDataGenerator::getTimeString($dsql->f('endtime'), false); //
                    //date("y年n月j日", $dsql->f('endtime'));//
                    $payquantity = $dsql->f('payquantity');
                    $used = $dsql->f('used');
                    $status = $dsql->f('status');
                    $usertype = MetaDataGenerator::getUserImageFromChar($usertype);
                    /*
                    if ($usertype == 1) {
                        $usertype = "学生";
                    } else if ($usertype == 2) {
                        $usertype = "教师";
                    } else if ($usertype == 3) {
                        $usertype = "管理员";
                    } else if ($usertype == 4) {
                        $usertype = "付费教师";
                    }*/
                    echo "<tr>
                            <td align='right'>$id<a name='$id' /></td>
                            <td>$usertype</td>
                            <td onmouseout='hideBubble()' onmouseover='showWholeName(this, ". $MAX_EMAIL_LENGTH .", $id, \"$email\")' >". MetaDataGenerator::getShortenString($email, $MAX_EMAIL_LENGTH) ."</td>
                            <!--td>$nickname</td-->
                            <td>$university </td>
                            <td>$truename </td>
                            <td align='right'>$created_at</td>
                            <!--td>$payquantity</td>
                            <td>$isfee</td>
                            <td>$used</td-->
                        ";
                    $statusImage = MetaDataGenerator::getStatusImageFromChar($status);
                    if ($status == 0) {
                        //$status = "有效";
                        echo "<td><nobr>$statusImage</nobr></td><td><nobr class='nobr'><a class='xiugai' href='useredit.php?id=$id&ac=edit". $QUERY_STRING_1 ."' style='background:none'><img src='img/edit.png' width='28' height='28' alt='修改' title='修改' /></a><a href='useredit.php?ac=del&id=$id&status=1' style='background:none'><img src='img/delete.png' width='28' height='28' alt='删除' title='删除' /></a></nobr></td></tr>";
                    } else if ($status == 1) {
                        //$status = "已删除";
                        echo "<td><nobr>$statusImage</nobr></td><td><nobr class='nobr'><a href='useredit.php?ac=del&id=$id&status=0' style='background:none'><img src='img/activate.png' width='28' height='28' alt='激活' title='激活' /></a><a class='xiugai' href='useredit.php?id=$id&ac=edit". $QUERY_STRING_1 ."' style='background:none'><img src='img/edit.png' width='28' height='28' alt='修改' title='修改' /></a></nobr></td></tr>";
                    }
                }
            ?>
            </table>
            <!-- <tr> -->
                <!-- <td colspan="8"> -->
                <?
                    $url = "userlist.php";
                    $queryString = "?id=$id&search=$search&page=";
                    $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
                    $PARAM_PAGE_NO = "page";//放页号的 url 参数
                    $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                ?>
                <!-- </td> -->
            <!-- </tr> -->
        
    <!-- </div> -->
<!-- </div> -->
<?
    include("bubbleWindow.php");
    include("footer.php");
?>


