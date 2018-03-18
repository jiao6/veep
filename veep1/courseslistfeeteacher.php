<?
    require_once("config/config.php");
    require_once("config/dsql.php");
    require_once("config/MetaDataGenerator.php");
    error_reporting(0);
    session_start();
    if (!$auth) loginFalse();

    function loginFalse()
    {
        Header("Location:login.php");
        exit;
    }
    require_once("config/config.php");
    require_once("config/dsql.php");
    require_once("header.php");
    require_once("config/CheckerOfCourse.php");
    require_once("config/MetaDataGenerator.php");

    $isStudent = CheckerOfCourse::isStudent($auth_pid);
    $isTeacher = CheckerOfCourse::isTeacher($auth_pid);
    $isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
    $isAdmin = CheckerOfCourse::isAdmin($auth_pid);

    if (!isset($dsql)) {
        $dsql = new DSQL();
    }
    $whereinfo = " and isclass=0 ";
    if ($search) {
        $whereinfo .= " and  (c.name like '%$search%' or   c.content  like '%$search%'  or  c.userid in (select id from users a where     (  a.truename like '%$search%')or(a.nickname like '%$search%')or(   a.university like '%$search%')or(   a.truename like '%$search%')or(   a.phonenumber like '%$search%'))  )";
    }
    /* 只显示课程
    if($isclass=='0' || $isclass=='1'){
        //$whereinfo .= " and (isclass='$isclass') ";
    }*/
    if($isTeacher || $isFeeTeacher){
        $whereinfo .= " and (c.TEACHER_ID='$auth_id') ";
        // $whereinfo = " and (userid='$auth_id')";
    } else if (!$isAdmin) {
        exit;
    }
    $SQL = "SELECT count(*) as allcount FROM courses  c where c.status=0   $whereinfo  order by c.id desc     ";
    //echo $SQL . "<br/>";
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
    $offset = ($page - 1) * $pagesize;

?>
    <style type="text/css">
        .courseslistfeeteacher {
            background: #1E8997;
        }
        .courseslistfeeteacher a {
            color: #fff !important;
        }
    </style>
    <div class="contain mc mc1">
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
                    if($auth_pid==3){
                        echo  "<a href=\"addclass1.php\"  class=\"button button-small bg-sub\">增加课程</a>";
                    }else{
                        echo "<div class=\"rhead1\">我的课程</div>";
                    }
                ?>
                <form method="get" style="float: right;margin-right: 20px;">
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
                    <th>编号</th>
                    <th>名称</th>
                    <th>创建时间</th>
                    <th><font title="所有课堂已选学生总数">已选课人数</font>/<br/><font title="本课程最大选课学生人数">限制人数</font></th>
                    <!--th>类型</th>
                    <th>选课码</th-->
                    <th>期限</th>
                    <th>排序</th>
                    <th style="width: 150px;">操作</th>
                </tr>
                <?
                    if ($offset < 0) $offset = 0;

                    $SQL = "SELECT c.*,u.university FROM courses c,users u where c.TEACHER_ID=u.id and c.status=0 $whereinfo  order by c.id desc limit $offset,$pagesize    ";
                    //echo $SQL . "\n";
                    $dsql->query($SQL);
                    $nbsp = $pernbsp = "|---------";
                    $MAX_EMAIL_LENGTH = 18;
                    while ($dsql->next_record()) {
                        $id = $dsql->f('id');
                        $name = $dsql->f('name');
                        $moocurl = $dsql->f('moocurl');
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
                        $trueusage = $dsql->f('trueusage');
                        $moocid = $dsql->f('moocid');
                        $step = $dsql->f('step');
                        $isshow = $dsql->f('isshow');
                        $db_isclass = $dsql->f('isclass');
                        $status = $dsql->f('status');
                        $sort = $dsql->f('sort');
                        $db_isclass = MetaDataGenerator::getCourseTypeImage($db_isclass);
                ?>

                <tr>
                    <td align="right"><? print($id) ?>&nbsp;<a name="<? print($id) ?>"></a></td>
                    <td>
                        <? print(MetaDataGenerator::getShortenString($university, $MAX_EMAIL_LENGTH))?>
                    	<br/>
                    	<a href="lessonlist.php?courseId=<? print($id) ?>" title="查看课堂"><? print(MetaDataGenerator::getShortenString($name, $MAX_EMAIL_LENGTH)) ?></a>
                    </td>
                    <td><? print(MetaDataGenerator::getTimeString($created_at, true))?></td>
                    <td align="right"><? print(($trueusage)."/".$payquantity) ?> &nbsp;&nbsp; </td>
                    <!--td><? print($db_isclass) ?></td>
                    <td><? print($code) ?></td-->
                    <td><? print($starttime . "<br/>" . $endtime) ?></td>
                    <td align="right"><? print($sort) ?>&nbsp;</td>

                    <td style="">
                        <ul class="caozuo_xiala">
                            <?
                            if($isshow==1){
                                //$show =  "<li><a href=\"coursesedit.php?coursesid=$id&ac=blank\" >下线</a></li>";
                                $show =  " <li><img src='img/offline.png' onclick=\"course_show($id,'blank');\"  style='display:block'  id='offlineimg$id' height='30' alt='下线' title='下线' /></li> ";
                                $show .=  "<li><img src='img/publish.png' onclick=\"course_show($id,'show');\" id='publishimg$id' style='display:none' width='30' height='30' alt='发布' title='发布' /></li>";

                            }else{
                                //$show =  "<li><a href=\"coursesedit.php?coursesid=$id&ac=show\" >发布</a></li>";
                                $show =  " <li><img src='img/offline.png' onclick=\"course_show($id,'blank');\"  style='display:none'  id='offlineimg$id' height='30' alt='下线' title='下线' /></li> ";
                                $show .=  "<li><img src='img/publish.png' onclick=\"course_show($id,'show');\" id='publishimg$id' style='display:block' width='30' height='30' alt='发布' title='发布' /></li>";
                            }
                            if($status==1){
                                $status= "<li> <a href=\"coursesfeeteacheredit.php?ac=del&coursesid=$id&status=0\">激活</a></li>";
                            }else{
                                $status= "<li> <a href=\"coursesfeeteacheredit.php?ac=del&coursesid=$id&status=1\" onclick=\"return confirm('确定要删除吗')\">删除</a></li>";
                            }

                                echo $show;
                                ?>
                                <!--li><a href="coursesfeeteacheredit.php?coursesid=<? print($id) ?>&ac=edit">修改</a></li>
                                <li class='gengduo'><a>更多</a-->
                                <li><a href="coursesfeeteacheredit.php?coursesid=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                    <ul class="caozuo">
                                    	<? if ($isAdmin) {?><!--课程级别的维护实验，只能管理员做-->
                                        <li><a href="coursesaddclass2.php?courseId=<? print($id) ?>">增加实验</a></li>
                                        <li><a href="coursesaddclass3.php?courseId=<? print($id) ?>">管理实验</a></li>
                                        <? } ?>
                                        <li><a href="coursesstudentscore.php?courseId=<? print($id) ?>">查看成绩</a></li>
                                        <li><a href="coursesscoreexp_allstudents.php?coursesid=<? print($id) ?>">导出学生</a></li>
                                        <? if($userid==$auth_id){ echo $status; } ?>
                                    </ul>
                                </li>
                                <?

                            ?>
                        </ul>
                    </td>
                </tr>
                <?
                    }
                ?>
                <tr>
                    <td colspan="6">
                        <?
                            $url = "courseslistfeeteacher.php";
                            $queryString = "?courseid=$courseid&isclass=$isclass&search=$search&page=";
                            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
                            $PARAM_PAGE_NO = "page";//放页号的 url 参数
                            $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                         ?>
                    </td>
                </tr>
            </table>
            <br/><br/>
    <script>
        function course_show(id,ac) {
            //alert(id);
            var isok = false;
            if (confirm('请确认操作')) {
                $.ajax({
                    "url": "coursesfeeteacheredit.php",
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
include("footer.php");
?>