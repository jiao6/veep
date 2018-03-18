<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
require_once("header.php");

?>
    <script type="text/javascript" language="javascript" src="js/laydate.js">
    </script>
<style type="text/css">
    .lessonlist {
        background: #1E8997;
    }
    .lessonlist a{
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
              <li><a href="lessonlist.php">课堂管理</a></li>
              <li>生成注册码</li>
        </ul>
            <div class="rhead">
                <div class="rhead1 f_center">为【<? echo $lessonId ?>】号课堂生成秘钥</div>
                <div class="rhead2"><a href=lessonlist.php class="button border-blue icon-angle-double-right">课堂列表</a></div>
            </div>
            <div class="rfg"></div>

            <form action="handleData.php" class="rgform form-x" onsubmit="" >
                <div class="form-group">
                    <div class="label">
                        <label for="name">数量</label><span class="hint">*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="quantity" value="1" maxlength="2" datatype="n"  placeholder="数量" errormsg="请输入数量">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">使用天数</label><span class="hint">*</span>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="day" value="99" maxlength="5" datatype="n"  placeholder="使用天数" errormsg="请输入使用天数">
                    </div>
                </div>
                <div class="form-group">
                    <div class="label">
                        <label for="name">截止日期</label><span class="hint">*</span>
                    </div>
                    <div class="field">
                        <input type="text" name="endtime" value="<? echo MetaDataGenerator::DEFAULT_END_DATE ?>" class="input" onClick="laydate({istime: false, format: 'YYYY-MM-DD'})" placeholder="截止日期" errormsg="请输入截止日期">
                    </div>
                </div>
                <input type="hidden" name='lessonId' value="<? print($lessonId) ?>">
                <input type="hidden" name='ac' value="createLessonKey">
                <div class="form-group">
                    <div class="label">
                        <label for="submit"></label>
                    </div>
                    <div class="field">
                        <input type="submit" id="submit" name="submit" class="button bg-main" value="增加">
                    </div>
                </div>
            </form>
            <script type="text/javascript" src="js/validform.js"></script>
            <script type="text/javascript">
            	var bubble_title = "生成密钥";
                function subm(frm) {
                    //alert(frm);
                    return false;
                }
                $(function () {
                    $(".rgform").Validform({
                        tiptype: 3,
                        label: ".label",
                        showAllError: true,
                        //tipSweep:true,
                        ajaxPost:true,
                        callback:function(data){
                            $("#Validform_msg").hide();//隐藏自动弹出的窗口
                            //alert(data);
                            //var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
                            //alert(dataJson);
                            var info = data.info;
                            var status = data.status;
                            var message = data.message;
                            showMessage(message, 50, bubble_title);
                            //alert("info=" + info + "； message=" + message);
                            if (info == "SUCCESS") {
                                //showMessage(message, 50);
                            } else {//处理失败
                                //showMessage(message, 40);
                            }
                        },
                        beforeSubmit:function(curform){
                            //在验证成功后，表单提交前执行的函数，curform参数是当前表单对象。
                            //这里明确return false的话表单将不会提交;
                            showMessage('正在处理，请稍候……', 20, bubble_title);
                            //return false;
                        }/**/


                    });
                })
            </script>
<?

include("footer.php");
include("page_element/bubble_alert.php");
?>