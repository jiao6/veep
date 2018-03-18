<?
require_once("config/config.php");;
require_once("config/dsql.php");
require_once("header.php");
require_once("config/MetaDataGenerator.php");

error_reporting(0);
session_start();

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}

if (!isset($dsql)) {
    $dsql = new DSQL();
}
$COURSE_TYPE = MetaDataGenerator::COURSE_TYPE_KECHENG;
$dsql2 = new DSQL();
if ($action == "add") {
    $SQL = "SELECT c.*  FROM courses  c   where    c.id = '$coursesid'  ";


    $dsql->query($SQL);
    $dsql->next_record();
    $starttime = $dsql->f('starttime');
    $endtime = $dsql->f('endtime');

    $temp_x = explode(',', $itemId);
//echo print_r($temp_x) . "\n";
    $SQL = "";
    for ($i = 0; $i < count($temp_x); $i++) {

        if (substr($temp_x[$i], 0, 1) != 'g' && $temp_x[$i] > 0) {
            //$insert[]  = "('$coursesid','".$temp_x[$i]."',".(($i+1)*10).",now(),now()) ";
            $SQL = "select id from  coursesexperiment where coursesid='$coursesid' and COURSE_TYPE=". $COURSE_TYPE ." and experimentsid='" . $temp_x[$i] . "'";
//echo $SQL . "<br/>";
            $dsql->query($SQL);
            if ($dsql->next_record()) {

            } else {
                $SQL = "select name from  experiments where  id='" . $temp_x[$i] . "'";
                $dsql->query($SQL);
                if ($dsql->next_record()) {
                    $name = $dsql->f('name');
                }
                $SQL = "insert into coursesexperiment(
                	coursesid,experimentsid,sort,starttime,endtime,
                	score,count,scoringmode,timemode,isshowscore,
                	name, COURSE_TYPE
                	)values(
                	'$coursesid','" . $temp_x[$i] . "'," . (($i + 1) * 10) . ",'$starttime', '$endtime',
                	'10','10','	最后得分','60','显示',
                	'$name', '". MetaDataGenerator::COURSE_TYPE_KECHENG ."')";
	//			echo $SQL . "<br/>";
                $dsql->query($SQL);
            }

        }

    }
}

?>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css " media="all"/>

<script type="text/javascript" language="javascript" src="js/jquery.min.js">
</script>

<script type="text/javascript" language="javascript" src="js/laydate.js">
</script>
<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
<style type="text/css">
    .courseslistfeeteacher {
        background: #1E8997;
    }
    .courseslistfeeteacher a {
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
                <li><a href="courseslistfeeteacher.php">课程管理</a></li>
                <li>增加课程</li>
            </ul>
            <div class="step">
                <div class="step-bar complete" style="width: 25%;">
                    <span class="step-point icon-check"></span><span class="step-text">第一步</span>
                </div>
                <div class="step-bar complete" style="width: 25%;">
                    <span class="step-point icon-check"></span><span class="step-text">第二步</span>
                </div>
                <div class="step-bar active" style="width: 25%;">
                    <span class="step-point">3</span><span class="step-text">第三步</span>
                </div>
                <div class="step-bar" style="width: 25%;">
                    <span class="step-point">4</span><span class="step-text">第四步</span>
                </div>
            </div>
        <div class="xian"></div>
        <div class="p3">
            <table id="table" class="display" cellspacing="0" width="100%" class="rt_table" style="margin-bottom: 30px;left: 0">
            </table>
            <button type="button" id="batch-edit-btn" class="button bg-sub">批量编辑</button>
            <button type="button" id="batch-save-btn" class="button bg-sub">批量保存</button>
            <center>
            <div class="form-group">
                <div class="label">
                    、<label for="submit"></label>
                </div>
                <div class="field">
                    <input type="submit" id="submit" name="submit" onclick="window.location='addclass4.php?coursesid=<? print($coursesid) ?>'" value="下一步" class="submit input button bg-main">
                </div>
            </div>
                
            </center>
        </div>
<script type="text/javascript" class="init">
    var allresponse = 0;
    $(function () {
        $('#table').addClass('rt_table')
            var table = $('#table').DataTable({
                "ajax": {
                    "url": "addclassdata.php?theId=<?print($coursesid)?>&COURSE_TYPE=<?print($COURSE_TYPE)?>",
                    //"dataSrc": "data",//默认为data
                    "type": "post",
                    "error": function () {
                        alert("服务器未正常响应，请重试");
                    }
                },
                'bSort': false,
                "columns": [
                    {"data": "id", "title": "序号", "defaultContent": ""},
                    {"data": "name", "title": "名称", "defaultContent": ""},
                    {"data": "starttime", "title": "开放时间", "defaultContent": ""},
                    {"data": "endtime", "title": "关闭时间", "defaultContent": ""},
                    {"data": "score", "title": "分值", "defaultContent": ""},
                    {"data": "count", "title": "答题次数", "defaultContent": ""},
                    {"data": "scoringmode", "title": "计分方式", "defaultContent": ""},
                    {"data": "timemode", "title": "限时模式", "defaultContent": " "},
                    {"data": "isshowscore", "title": "显示反馈成绩", "defaultContent": ""},
                    {"data": "sort", "title": "排序值", "defaultContent": ""},
                    {
                        "data": null,
                        "title": "操作",
                        "defaultContent": "<nobr><button class='edit-btn' type='a' style='border:1px solid #FEB751;background: #FEB751 none repeat scroll 0 0;color: #fff;margin: 2px;padding: 2px 8px;'>编辑</button><button  class='delete-btn' style='border:1px solid #FEB751;background: #FEB751 none repeat scroll 0 0;color: #fff;margin: 2px;padding: 2px 8px;'>删除</button>"
                    }
                ],
                "language": {
                    "sProcessing": "处理中...",
                    "sLengthMenu": "显示 _MENU_ 项结果",
                    "sZeroRecords": "没有匹配结果",
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
                    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
                    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
                    "sInfoPostFix": "",
                    "sSearch": "搜索:",
                    "sUrl": "",
                    "sEmptyTable": "表中数据为空",
                    "sLoadingRecords": "载入中...",
                    "sInfoThousands": ",",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "上页",
                        "sNext": "下页",
                        "sLast": "末页"
                    },
                    "oAria": {
                        "sSortAscending": ": 以升序排列此列",
                        "sSortDescending": ": 以降序排列此列"
                    }
                }
            });

            $("#table tbody").on("click", ".edit-btn", function () {
                var tds = $(this).parents("tr").children();
                var id = $(this).parents("tr").children().eq(0).text();
                $.each(tds, function (i, val) {
                    var jqob = $(val);
                    if (i < 2 || jqob.has('button').length) {
                        return true;
                    }//跳过第1项 序号,按钮
                    if (i == 6) {//几分方式
                        var txt = jqob.text();
                        var puttxt = "<select class='selectTd' >";
                        if(txt=='平均分'){
                            puttxt += "<option value='平均分' selected >平均分</option>";
                        }else{
                            puttxt += "<option value='平均分'   >平均分</option>";
                        }
                        if(txt=='最高分'){
                            puttxt += "<option value='最高分' selected >最高分</option>";
                        }else{
                            puttxt += "<option value='最高分'   >最高分</option>";
                        }
                        if(txt=='最后得分'){
                            puttxt += "<option value='最后得分' selected >最后得分</option>";
                        }else{
                            puttxt += "<option value='最后得分'   >最后得分</option>";
                        }

                        var put = $(puttxt+"</select>");


                        //put.val(txt);
                        jqob.html(put);
                    } else if (i == 8) {//几分方式
                        var txt = jqob.text();
                        var puttxt = "<select class='selectTd' >";
                        if(txt=='显示'){
                            puttxt += "<option value='显示' selected >显示</option>";
                        }else{
                            puttxt += "<option value='显示'   >显示</option>";
                        }
                        if(txt=='不显示'){
                            puttxt += "<option value='不显示' selected >不显示</option>";
                        }else{
                            puttxt += "<option value='不显示'   >不显示</option>";
                        }
                        var put = $(puttxt+"</select>");
                        jqob.html(put);
                    } else if (i == 2 || i == 3) {
                        var txt = jqob.text();
                        var put = $("<input  class=\"laydate-icon\" onClick=\"laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})\">");
                        put.val(txt);
                        jqob.html(put);
                    }  else if (i >= 4) {
                        var txt = jqob.text();
                        var put = $("<input type='text'  size=3>");
                        put.val(txt);
                        jqob.html(put);
                    } else {
                        var txt = jqob.text();
                        var put = $("<input type='text'  size=8>");
                        put.val(txt);
                        jqob.html(put);
                    }
                });
                $(this).text("保存");
                $(this).toggleClass("edit-btn");
                $(this).toggleClass("save-btn");
            });
            $("#table tbody").on("click", ".delete-btn", function () {
                var row = table.row($(this).parents("tr"));
                var id = $(this).parents("tr").children().eq(0).text();
                //alert(id);
                if (confirm('确定要删除吗')) {

                    $.ajax({
                        "url": "addclassdataedit.php?ac=del&coursesid=<?print($coursesid)?>",
                        data: {'id': id},
                        "type": "post",
                        "error": function () {
                            alert("服务器未正常响应，请重试");
                        },
                        "success": function (response) {
                            allresponse = allresponse + 1;
                            if (allresponse == 1) {
                                alert(response);
                                window.location = "addclass3.php?coursesid=<?print($coursesid)?>";

                            }
                        }
                    });
                }
            });
            $("#table tbody").on("click", ".save-btn", function () {
            var row = table.row($(this).parents("tr"));
            var tds = $(this).parents("tr").children();
            $.each(tds, function (i, val) {
                var jqob = $(val);
                //把input变为字符串
                if (!jqob.has('button').length) {
                    if (jqob.has('select').length) {
                        var txt = jqob.children("select").val();
                        jqob.html(txt);
                        table.cell(jqob).data(txt);//修改DataTables对象的数据
                    } else {
                        var txt = jqob.children("input").val();
                        jqob.html(txt);
                        table.cell(jqob).data(txt);//修改DataTables对象的数据
                    }

                }
            });
            var data = row.data();
            $.ajax({
                "url": "addclassdataedit.php?theId=<?print($coursesid)?>&COURSE_TYPE=<? echo $COURSE_TYPE ?>",
                "data": data,
                "async": false,
                "type": "post",
                "error": function () {
                    alert("服务器未正常响应，请重试");
                },
                "success": function (response) {

                    allresponse = allresponse + 1;
                    if(  response.trim()  == '修改成功'){
                        responseok = 1;
                        $(this).html("编辑");
                        // alert(responseok);
                        //alert($(this).html());
                        $(this).toggleClass("edit-btn");
                        $(this).toggleClass("save-btn");
                    }else{
                        responseok=0;
                    }
                    if (allresponse == 1 || response != '修改成功') {
                        alert(response);
                        //window.location = "addclass3.php?coursesid=<?print($coursesid)?>";



                    }

                    if(responseok==0){

                        var id = $(this).parents("tr").children().eq(0).text();
                        $.each(tds, function (i, val) {
                            var jqob = $(val);
                            if (i < 2 || jqob.has('button').length) {
                                return true;
                            }//跳过第1项 序号,按钮
                            if (i == 6) {//几分方式
                                var txt = jqob.text();
                                var puttxt = "<select class='selectTd' >";
                                if(txt=='平均分'){
                                    puttxt += "<option value='平均分' selected >平均分</option>";
                                }else{
                                    puttxt += "<option value='平均分'   >平均分</option>";
                                }
                                if(txt=='最高分'){
                                    puttxt += "<option value='最高分' selected >最高分</option>";
                                }else{
                                    puttxt += "<option value='最高分'   >最高分</option>";
                                }
                                if(txt=='最后得分'){
                                    puttxt += "<option value='最后得分' selected >最后得分</option>";
                                }else{
                                    puttxt += "<option value='最后得分'   >最后得分</option>";
                                }

                                var put = $(puttxt+"</select>");


                                //put.val(txt);
                                jqob.html(put);
                            } else if (i == 8) {//几分方式
                                var txt = jqob.text();
                                var puttxt = "<select class='selectTd' >";
                                if(txt=='显示'){
                                    puttxt += "<option value='显示' selected >显示</option>";
                                }else{
                                    puttxt += "<option value='显示'   >显示</option>";
                                }
                                if(txt=='不显示'){
                                    puttxt += "<option value='不显示' selected >不显示</option>";
                                }else{
                                    puttxt += "<option value='不显示'   >不显示</option>";
                                }
                                var put = $(puttxt+"</select>");
                                jqob.html(put);
                            } else if (i == 2 || i == 3) {
                                var txt = jqob.text();
                                var put = $("<input  class=\"laydate-icon\" onClick=\"laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})\">");
                                put.val(txt);
                                jqob.html(put);
                            }  else if (i >= 4) {
                                var txt = jqob.text();
                                var put = $("<input type='text'  size=3>");
                                put.val(txt);
                                jqob.html(put);
                            } else {
                                var txt = jqob.text();
                                var put = $("<input type='text'  size=8>");
                                put.val(txt);
                                jqob.html(put);
                            }
                        });
                    }else{
                        $(this).html("编辑");
                        // alert(responseok);
                        //alert($(this).html());
                        $(this).toggleClass("edit-btn");
                        $(this).toggleClass("save-btn");
                    }

                }
            });
            if(responseok==1){
                $(this).html("编辑");
                // alert(responseok);
                //alert($(this).html());
                $(this).toggleClass("edit-btn");
                $(this).toggleClass("save-btn");
            }



        });

            //批量点击编辑按钮
            $("#batch-edit-btn").click(function () {
                $(".edit-btn").click();
            });
            $("#batch-save-btn").click(function () {
                $(".save-btn").click();
            });
    });
</script>
<? include "footer.php" ?>