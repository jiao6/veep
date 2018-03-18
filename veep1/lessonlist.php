<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");
require_once("config/CheckerOfCourse.php");
error_reporting(0);
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

$isStudent = CheckerOfCourse::isStudent($auth_pid);
$isTeacher = CheckerOfCourse::isTeacher($auth_pid);
$isFeeTeacher = CheckerOfCourse::isFeeTeacher($auth_pid);
$isAdmin = CheckerOfCourse::isAdmin($auth_pid);

if (!isset($dsql)) {
    $dsql = new DSQL();
}
$whereinfo = " ";
if ($search) {
    $whereinfo .= " and  (   c.NAME like '%$search%'  or c.TEACHER_ID in (select id from users a where     (  a.truename like '%$search%') or(   a.university like '%$search%') or(   a.phonenumber like '%$search%'))  )";
}
/*
if($isclass=='0'||$isclass=='1'){
    $whereinfo .= " and (isclass='$isclass') ";
}*/
$queryCondition = "";
if (isset($courseId) && $courseId > 0) {//根据课程查找课堂列表
    $whereinfo .= " and (c.COURSE_ID='$courseId') ";
    $queryCondition = "课程编号为【". $courseId ."】的";
}
if($isTeacher){//普通教师
    $whereinfo .= " and (c.teacher_id='$auth_id') ";
    // $whereinfo = " and (assigner_id='$auth_id')";
}else  if($isFeeTeacher){//付费教师。显示他分配给别人和别人分配给他的课堂
    //$whereinfo = " and (teacher_id='$auth_email')";
    $whereinfo .= " and (c.TEACHER_ID='$auth_id' or c.assigner_id=$auth_id)";
}else if ($isAdmin) {
}else {
    exit;
}
$SQL = "SELECT count(*) as allcount FROM LESSON c where c.status=0 $whereinfo order by c.id desc     ";
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
    .lessonlist {
        background: #1E8997;
    }
    .lessonlist a {
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
    			<!-- 我的<? echo $queryCondition ?>课堂 -->
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
                    <th>编号</th>
                    <th>课堂名称</th>
                    <th>创建者</th>
                    <th>
                        <font title="本课堂已选课人数">已选课人数 </font>/<br/>
                        <font title="本课程可选课人数上限">可选课人数 </font>
                    </th>
                    <th>类型</th>
                    <th>选课码</th>
                    <th>课堂期限</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                <?
                if ($offset < 0) $offset = 0;

                $SQL = "SELECT c.*,u.university,
                  kecheng.payquantity as maxquantity, kecheng.trueusage as allTrueusage, kecheng.name as course_name, 
                  creator.truename as creator_name, u.truename as teacher_name 
                  FROM LESSON c, courses kecheng, users u, users creator
                  where  c.COURSE_ID=kecheng.id and c.TEACHER_ID=u.id and c.ASSIGNER_ID=creator.id
                  and c.status=0 $whereinfo
                  order by c.id desc limit $offset, $pagesize ";
                //echo $SQL . "<br/>";
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 0;
                $MAX_EMAIL_LENGTH = 18;
                while ($dsql->next_record()) {
                    $id = $dsql->f('ID');
                    $name = $dsql->f('NAME');
                    //$start_time = $dsql->f('start_time');
                    $start_time = MetaDataGenerator::getTimeString($dsql->f('START_TIME'));
                    $end_time =   MetaDataGenerator::getTimeString($dsql->f('END_TIME'));//$dsql->f('end_time');
                    $assigner_id = $dsql->f('ASSIGNER_ID');

                    $university = $dsql->f('university');

                    $creator_name  = $dsql->f('creator_name');
                    $teacher_name  = $dsql->f('teacher_name');
                    $code = $dsql->f('CODE');
                    $SHOWN = $dsql->f('SHOWN');

                    $maxquantity = $dsql->f('maxquantity');
                    $trueusage = $dsql->f('STUDENT_AMOUNT');
                    $course_name = $dsql->f('course_name');
                    $allTrueusage = $dsql->f('allTrueusage');
                    $sum = $allTrueusage;
                    $sort = $dsql->f('SORT_ORDER');

                    $olddepth++;
                    /*
                    $payquantity = $dsql->f('payquantity');
                    $payquantityusage = $dsql->f('payquantityusage');
                    $db_isclass = $dsql->f('isclass');
                    $status = $dsql->f('status');
                    $assigner_id = $dsql->f('assigner_id');//通过课堂查询课程 id
                    */
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

                    //$db_isclass = MetaDataGenerator::getCourseTypeImage($db_isclass);

                    ?>

                    <tr>
                        <td align="right">
                            <? print($id) ?><a name="<? print($id) ?>"></a>
                        </td>
                        <td onmouseover="showInfoDiv(this, <? print($id) ?>)" onmouseout="hideInfoDiv(this, <? print($id) ?>)">
                            <? //print( $university."<br/>".$name) ?>
                            <table>
                                <tr style="background:none; padding:0px 0px; ">
                                    <td style="padding:0px 0px; ">
                                        <? echo (MetaDataGenerator::getShortenString($university, $MAX_EMAIL_LENGTH) ) ?>
                                    </td>
                                </tr>
                                <tr style="background:none">
                                    <td style="padding:0px 0px; " >
                                        <a href="lesson.php?lessonId=<? print($id) ?>" title="" ><? echo (MetaDataGenerator::getShortenString($name, $MAX_EMAIL_LENGTH) ) ?></a>
                                    </td>
                                </tr>
                            </table>
<div id="InfoDiv<? print($id) ?>" style="display:none; border:2px black solid; background: white; color:black; position:absolute; left:100px; top:<?echo 0+$olddepth*100 ?>px">
    <table>
    	<caption style="background:green; color:white"><? echo $university. " - " . $name ?></caption>
        <tr>
            <td>
                课程名称
            </td>
            <td colspan="3">
                <? echo $course_name ?>
            </td>
        </tr>
        <tr>
            <td> 起始时间 </td>
            <td><? echo $start_time ?></td>
            <td> 终止时间 </td>
            <td><? echo $end_time ?></td>
        </tr>
        <tr>
            <td> 分配者 </td>
            <td><? echo $creator_name ?></td>
            <td> 获得者 </td>
            <td><? echo $teacher_name ?></td>
        </tr>
        <tr>
            <td> 选课码 </td>
            <td><? echo $code ?></td>
            <td>  </td>
            <td><? echo "" ?></td>
        </tr>
    </table>
</div>
                        </td>
                        <td><? print($creator_name) ?></td>
                        <td align="right"><? print("$trueusage/".($maxquantity-$sum)) ?></td><!--$payquantity-$payquantityusage-->
                        <td>
                            <? echo MetaDataGenerator::getClassTypeFrom($assigner_id, $auth_id) ?>
                        </td>
                        <td align="right"><? print($code) ?></td>
                        <td align="right"><? print($start_time . "<br/>" . $end_time) ?></td>
                        <td align="right"><? print($sort) ?></td>

                        <td>
                            <ul class="caozuo_xiala">
                                <?
                                if($SHOWN == 1){//在线
                                    $show =  " <img src='img/offline.png'  onclick=\"course_show($id,'blank');\" id='publishimg$id' height='30' alt='下线' title='下线' /> ";
                                }else{
                                    $show =  " <img src='img/publish.png'  onclick=\"course_show($id,'show');\"  id='publishimg$id' height='30' alt='发布' title='发布' /> ";
                                }
                                if($status==1){
                                    $status= "<li> <a href=\"lessonedit.php?ac=del&lessonId=$id&status=0\">激活</a></li>";
                                }else{
                                    $status= "<li> <a href=\"lessonedit.php?ac=del&lessonId=$id&status=1\" onclick=\"return confirm('确定要删除吗')\">删除</a></li>";
                                }
                                if($isTeacher){//普通老师

                                    echo $show;
                                    ?>
                                    <!--li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit">修改</a></li>
                                    <li class='gengduo'><a>更多</a-->
                                    <li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                    <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                        <ul class="caozuo">
                                            <li><a href="coursesaddclass2.php?lessonId=<? print($id) ?>">增加实验</a></li>
                                            <li><a href="coursesaddclass3.php?lessonId=<? print($id) ?>">管理实验</a></li>
                                            <li><a href="coursesimportuser.php?lessonId=<? print($id) ?>">导入白名单</a></li>
                                            <li><a href="lessonstudentscore.php?lessonId=<? print($id) ?>">查看成绩</a></li>
                                            <li><a href="coursesscoreexp_allstudents.php?lessonId=<? print($id) ?>">导出学生</a></li>


                                        </ul>
                                    </li>
                                    <?
                                } else if ($isFeeTeacher){//收费老师
                                    echo $show;
                                                     ?>
                                    <!--li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit">修改</a></li>
                                    <li class='gengduo'><a>更多</a-->
                                    <li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                    <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                        <ul class="caozuo">
                                            <li><a href="coursesaddclass2.php?lessonId=<? print($id) ?>">增加实验</a></li>
                                            <li><a href="coursesaddclass3.php?lessonId=<? print($id) ?>">管理实验</a></li>
                                            <li><a href="coursesimportuser.php?lessonId=<? print($id) ?>">导入白名单</a></li>
                                            <li><a href="lessonstudentscore.php?lessonId=<? print($id) ?>">查看成绩</a></li>
                                            <li><a href="coursesscoreexp_allstudents.php?lessonId=<? print($id) ?>">导出学生</a></li>
                                            <li><a href="importuser.php?lessonId=<? print($id) ?>">导入学生用户</a></li>
                                            <li><a href="userlist.php">用户管理</a></li>


                                            <? if($assigner_id==$auth_id){ echo $status; } ?>
                                        </ul>
                                    </li>
                                    <?
                                }else if($isAdmin) {//管理员
                                    echo $show;
                                    ;
                                    ?>
                                    <!--li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit">修改</a></li-->
                                    <li><a href="lessonedit.php?lessonId=<? print($id) ?>&ac=edit&page=<? echo $page ?>" style="background:none; padding:0px 0px; margin:0px"><img src='img/edit.png' width='30' height='30' alt='修改' title='修改' /></a></li>
                                    <li class='gengduo' ><img src='img/more.png' width='30' height='30' alt='更多' title='更多' />
                                        <ul class="caozuo">
                                            <li><a href="coursesaddclass2.php?lessonId=<? print($id) ?>">增加实验</a></li>
                                            <li><a href="coursesaddclass3.php?lessonId=<? print($id) ?>">管理实验</a></li>
                                            <li><a href="coursesimportuser.php?lessonId=<? print($id) ?>">导入白名单</a></li>
                                            <li><a href="coursescreatekey.php?lessonId=<? print($id) ?>">生成注册码</a></li>
                                            <li><a href="coursesdownloadkey.php?lessonId=<? print($id) ?>">下载注册码</a></li>
                                            <li><a href="importuser.php?lessonId=<? print($id) ?>">导入学生用户</a></li>
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

            $url = "lessonlist.php";
            $queryString = "?courseId=$courseId&search=$search&page=";//courseid=$courseid&
            $PARAM_PAGE_SIZE = "pagesize";//放每页记录数的 url 参数
            $PARAM_PAGE_NO = "page";//放页号的 url 参数
            $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);

            ?>
<script>
    function course_show(id, ac) {
        //alert(id);
        var isok = false;
        if (confirm('请确认操作')) {
            $.ajax({
                "url": "lessonedit.php",
                data: {'lessonId': id, 'ac':ac},
                "type": "post",
                "async":false,
                "error": function () {
                    alert("服务器未正常响应，请重试");
                },
                "success": function (response) {
                    alert("修改完成");
                    isok=true;
                }
            });
            if(isok){
                
                var imgPublish = $("#publishimg" + id);
                if(ac=="blank"){//下线
                    //alert(imgPublish.attr("src"));
                    imgPublish.attr("src","img/publish.png");
                    imgPublish.attr("title","发布");
                    var func = "course_show("+ id +",'show')";
                    imgPublish.attr("onclick", func);
                } else if(ac=="show"){//上线
                    imgPublish.attr("src","img/offline.png");
                    imgPublish.attr("title","下线");
                    var func = "course_show("+ id +",'blank')";
                    imgPublish.attr("onclick", func);
                }


            }
        }

    }
</script>
<script>
    function showInfoDiv(elem, lessonId) {
        //alert(lessonId);
        var left = $(elem).offset().left -200; //表格 td 的 的左侧
        var top  = $(elem).offset().top -50;  //表格 td 的 的左侧
        //left = left + 6 * length; //图层起点在 td 的左侧
        //top = top + 14;
        //alert("left = " + left + "; top=" + top);
		var infoDiv = $("#InfoDiv" + lessonId);
        var width = infoDiv.width();//取得弹出窗口宽度
        var right = left + width;//取得弹出窗口右侧边缘
        var docuWidth = $(document).width();//浏览器时下窗口文档对象宽度
        if (right > docuWidth) {
        	left = docuWidth - width;//超出了窗口宽度，则左上点向左移动
        }
        infoDiv.css("left", left + "px");
        infoDiv.css("top", top + "px");
		infoDiv.show();
    }
    function hideInfoDiv(elem, lessonId) {
		var infoDiv = $("#InfoDiv" + lessonId);
		infoDiv.hide();
    }
</script>
<?
include("bubbleWindow.php");
include("footer.php");
?>