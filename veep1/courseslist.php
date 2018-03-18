<?

error_reporting(0);
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
if (!isset($dsql)) {
    $dsql = new DSQL();
}
$whereinfo = " ";
if ($search) {
    $whereinfo .= " and  (   c.name like '%$search%' or   c.content  like '%$search%'  or  c.userid in (select id from users a where     (  a.truename like '%$search%')or(a.nickname like '%$search%')or(   a.university like '%$search%')or(   a.truename like '%$search%')or(   a.phonenumber like '%$search%'))  )";
}
if($isclass=='0'||$isclass=='1'){
    $whereinfo .= " and (isclass='$isclass') ";
}
if($auth_pid==2){//普通教师
    $whereinfo .= " and (c.useremail='$auth_email') ";
    // $whereinfo = " and (userid='$auth_id')";
}else  if($auth_pid==4){//付费教师。显示他分配给别人和别人分配给他的课堂
    //$whereinfo = " and (useremail='$auth_email')";
    $whereinfo .= " and (c.userid='$auth_id' or c.teacher_id=$auth_id)";
}else if ($auth_pid != 3) {
    exit;
}
$SQL = "SELECT count(*) as allcount FROM courses  c where c.status=0   $whereinfo  order by c.id desc     ";
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
<style type="text/css">
    .courseslist {
        background: #1E8997;
    }
    .courseslist a {
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
            <div class="rhead">
                <?
                    if($auth_pid==3){//管理员
                        // <div class=\"rhead1\">我的课程</div>
                        echo "<a href=\"addclass1.php\" class=\"button bg-sub button-small\">增加课程</a>";
                         // style=\"float: right\"
                    }else{
                        // echo "<div class=\"rhead1\">我的课堂</div>";
                    }
                ?>
                <form method="post" style="float: right;margin-right: 20px;">
                    <div class="form-group">
                        <div class="field">
                            <input type="text" name="search" style="height: 30px; line-height: 30px;text-indent: 10px;width: 300px;border: 1px solid #ccc;">
                            <input type="hidden" name="isclass" <?print($isclass)?>>
                            <input type="submit" id="submit" name="submit" class="submit button button-small bg-main" value="搜索">
                        </div>
                    </div>
            </form>
            </div>
            <table class="rt_table" style="width: 100%">
                <tr>
                    <th class="f_right">编号</th>
                    <th class="f_left">课堂名称</th>
                    <th class="f_left">创建者</th>
                    <th class="f_right">
                        <font title="本课堂已选课人数">已选课人数 </font>/<br/>
                        <font title="本课程可选课人数上限">可选课人数 </font>
                    </th>
                    <th class="f_left">类型</th>
                    <th class="f_right">选课码</th>
                    <th class="f_right">课堂期限</th>
                    <th class="f_right">排序</th>
                    <th>操作</th>
                </tr>
                <?
                    if ($offset < 0) $offset = 0;
                    $SQL = "SELECT c.*,u.university, 
                      kecheng.payquantity as maxquantity, kecheng.trueusage as allTrueusage, 
                      creator.truename as creator_name
                      FROM courses c, courses kecheng, users u, users creator 
                      where  c.pid=kecheng.id and c.useremail=u.email and c.userid=creator.id
                      and c.status=0 $whereinfo
                      order by c.id desc limit $offset, $pagesize    ";
                    //echo $SQL . "<br/>";
                    $dsql->query($SQL);
                    $nbsp = $pernbsp = "|---------";
                    $olddepth = 1;
                    $MAX_EMAIL_LENGTH = 18;
                    while ($dsql->next_record()) {
                        $id = $dsql->f('id');
                        $name = $dsql->f('name');
                        $moocurl = $dsql->f('moocurl');
                        //$starttime = $dsql->f('starttime');
                        $starttime = MetaDataGenerator::getTimeString($dsql->f('starttime'));
                        $endtime =   MetaDataGenerator::getTimeString($dsql->f('endtime'));//$dsql->f('endtime');
                        $userid = $dsql->f('userid');
                        $created_at = $dsql->f('created_at');
                        $updated_at = $dsql->f('updated_at');
                        $coursesimg = $dsql->f('coursesimg');
                        $content = $dsql->f('content');
                        $useremail = $dsql->f('useremail');
                        $university = $dsql->f('university');
                        $code = $dsql->f('code');
                        $payquantity = $dsql->f('payquantity');
                        $payquantityusage = $dsql->f('payquantityusage');
                        $trueusage = $dsql->f('trueusage');
                        $moocid = $dsql->f('moocid');
                        $step = $dsql->f('step');
                        $isshow = $dsql->f('isshow');
                        $db_isclass = $dsql->f('isclass');
                        $status = $dsql->f('status');
                        $sort = $dsql->f('sort');
                        $maxquantity = $dsql->f('maxquantity');
                        $allTrueusage = $dsql->f('allTrueusage');
                        $creator_name  = $dsql->f('creator_name');
                        $pid = $dsql->f('pid');//通过课堂查询课程 id
    					$sum = $allTrueusage;
                        /*
                        $sql = "select sum(trueusage) as sum from courses where pid=$pid";//查出该课堂所在课程所有人数。
                        //echo $sum . "<br/>";
                        $dsql2 = new DSQL();
                        $dsql2->query($sql);
                        if ($dsql2->next_record()){
                        	$sum = $dsql2->f('sum');
                        	//echo $sum. "<br/>";
                        }
                        */
                        $db_isclass = MetaDataGenerator::getCourseTypeImage($db_isclass);
                ?>
                <tr>
                    <td align="right">
                        <? print($id) ?><a name="<? print($id) ?>"></a>
                    </td>
                    <td>
                        <? //print( $university."<br/>".$name) ?>
                        <table>
                            <tr style="background:none; padding:0px 0px; ">
                                <td style="padding:0px 0px; " onmouseout="hideBubble()" onmouseover="showWholeName(this, <? print $MAX_EMAIL_LENGTH ?> , <? print $id ?>, '<? print $university ?>')" >
                                    <? echo (MetaDataGenerator::getShortenString($university, $MAX_EMAIL_LENGTH) ) ?>
                                </td>
                            </tr>
                            <tr style="background:none">
                                <td style="padding:0px 0px; " onmouseout="hideBubble()" onmouseover="showWholeName(this, <? print $MAX_EMAIL_LENGTH ?> , <? print $id ?>, '<? print $name ?>')" >
                                    <? echo (MetaDataGenerator::getShortenString($name, $MAX_EMAIL_LENGTH) ) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td><? print($creator_name) ?></td>
                    <td align="right"><? print("$trueusage/".($maxquantity-$sum)) ?></td><!--$payquantity-$payquantityusage-->
                    <td>
                    	<? echo MetaDataGenerator::getClassTypeFrom($userid, $auth_id) ?>
                    </td>
                    <td align="right"><? print($code) ?></td>
                    <td align="right"><? print($starttime . "<br/>" . $endtime) ?></td>
                    <td align="right"><? print($sort) ?></td>

                    <td>
                        <ul class="caozuo_xiala">
                            <?
                            if($isshow==1){
                                //$show =  "<li><a href=\"coursesedit.php?coursesid=$id&ac=blank\" >下线</a></li>";
                                $show =  "<li><img src='img/offline.png' onclick=\"course_show($id,'blank');\"  style='display:block'  id='offlineimg$id' height='30' alt='下线' title='下线' /></li> ";
                                $show .=  "<img src='img/publish.png' onclick=\"course_show($id,'show');\" id='publishimg$id' style='display:none' width='30' height='30' alt='发布' title='发布' />";

                            }else{
                                //$show =  "<li><a href=\"coursesedit.php?coursesid=$id&ac=show\" >发布</a></li>";
                                $show =  " <li><img src='img/offline.png'    onclick=\"course_show($id,'blank');\"  style='display:none'  id='offlineimg$id' height='30' alt='下线' title='下线' /></li> ";
                                $show .=  "<li><img src='img/publish.png'  onclick=\"course_show($id,'show');\" id='publishimg$id' style='display:block' width='30' height='30' alt='发布' title='发布' /></li>";
                            }
                            if($status==1){
                                $status= "<li> <a href=\"coursesedit.php?ac=del&coursesid=$id&status=0\">激活</a></li>";
                            }else{
                                $status= "<li> <a href=\"coursesedit.php?ac=del&coursesid=$id&status=1\" onclick=\"return confirm('确定要删除吗')\">删除</a></li>";
                            }
                            if($auth_pid==2 ){//普通老师

                                echo $show;
                                ?>
                                <!--li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit">修改</a></li>
                                <li class='gengduo'><a>更多</a-->
                                <li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                <li class='gengduo' >
                                    <li><img src='img/more.png' width='30' height='30' alt='更多' title='更多' /></li>
                                    <ul class="caozuo">
                                        <li><a href="coursesaddclass2.php?coursesid=<? print($id) ?>">增加实验</a></li>
                                        <li><a href="coursesaddclass3.php?coursesid=<? print($id) ?>">管理实验</a></li>

                                        <li><a href="coursesstudentscore.php?coursesid=<? print($id) ?>">查看成绩</a></li>
                                        <li><a href="coursesscoreexp_allstudents.php?coursesid=<? print($id) ?>">导出学生</a></li>


                                    </ul>
                                </li>
                                <?
                            }else if( $auth_pid==4){//收费老师
                                echo $show;
                                                 ?>
                                <!--li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit">修改</a></li>
                                <li class='gengduo'><a>更多</a-->
                                <li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                    <ul class="caozuo">
                                        <li><a href="coursesaddclass2.php?coursesid=<? print($id) ?>">增加实验</a></li>
                                        <li><a href="coursesaddclass3.php?coursesid=<? print($id) ?>">管理实验</a></li>

                                        <li><a href="coursesstudentscore.php?coursesid=<? print($id) ?>">查看成绩</a></li>
                                        <li><a href="coursesscoreexp_allstudents.php?coursesid=<? print($id) ?>">导出学生</a></li>
                                        <? if($userid==$auth_id){ echo $status; } ?>
                                    </ul>
                                </li>
                                <?
                            }else if($auth_pid==3) {//管理员
                                echo $show;
                                ;
                                ?>
                                <!--li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit">修改</a></li-->
                                <li><a href="coursesedit.php?coursesid=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                    <ul class="caozuo">
                                        <li><a href="coursesaddclass2.php?coursesid=<? print($id) ?>">增加实验</a></li>
                                        <li><a href="coursesaddclass3.php?coursesid=<? print($id) ?>">管理实验</a></li>
                                        <li><a href="coursesimportuser.php?coursesid=<? print($id) ?>">导入学生账号</a></li>
                                        <li><a href="importuser.php?coursesid=<? print($id) ?>">导入学生用户</a></li>
                                        <li><a href="coursescreatekey.php?coursesid=<? print($id) ?>">生成注册码</a></li>
                                        <li><a href="coursesdownloadkey.php?coursesid=<? print($id) ?>">下载注册码</a></li>
                                        <? echo $status ?>
                                    </ul>
                                </li>
                                <?
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <?
                }
                ?>
            </table>
            <br/><br/>
            <?

            $url = "courseslist.php";
            $queryString = "?courseid=$courseid&isclass=$isclass&search=$search&page=";
            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
            $PARAM_PAGE_NO = "page";//放页号的 url 参数

            $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);

            ?>
<script>
    function course_show(id,ac) {
        //alert(id);
        var isok = false;
        if (confirm('请确认操作')) {
            $.ajax({
                "url": "coursesedit.php",
                data: {'coursesid': id,'ac':ac},
                "type": "post",
                "async":false,
                "error": function () {
                    alert("服务器未正常响应，请重试");
                },
                "success": function (response) {
                    //alert('已经处理')
                    isok=true;
                }
            });
            if(isok){
                if(ac=="blank"){//下线
                    document.getElementById('publishimg'+id).style='display:block';
                    document.getElementById('offlineimg'+id).style='display:none';
                }
                if(ac=="show"){//下线
                    document.getElementById('publishimg'+id).style='display:none';
                    document.getElementById('offlineimg'+id).style='display:block';
                }


            }
        }

    }
</script>
<?
include("bubbleWindow.php");
include("footer.php");
?>