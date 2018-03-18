<div class="xcConfirm" id="pop_14762533194109678" style="display:none">
    <div class="xc_layer"></div>
    <div class="popBox">
        <div class="ttBox">
            <!--a class="clsBtn" title="关闭窗口" onclick="hideBubble()"></a-->
            <span class="tt" id="bubble_title">虚拟试验工场</span>
        </div>
        <div class="txtBox">
            <div id="icon" class="bigIcon" style="background-position: 0px 0px;"></div>
            <!-- 错误 background-position: -48px -48px;  成功 background-position: 0px -48px; -->
            <p id="bubble_content">
                提示文字，提示文字，提示文字，提示文字，提示文字，提示文字<br>ffffffffffffffffffffffffffffff
            </p>
        </div>
        <div class="btnArea">
            <div class="btnGroup">
                <!--a class="sgBtn"><input type="button" id="buttonOK" value="确定" style="" class="button_ok" title="确定"     onclick="hideBubble()"></a>
                <a class="sgBtn"><input type="button" id="buttonOK" value="取消" style="" class="button_cancel" title="取消" onclick="hideBubble()"></a-->
                <a class="sgBtn"><input type="button" id="buttonOK" value="确定" style="" class="button_ok" title="确定"     onclick="hideBubble()"></a>
            </div>
        </div>
    </div>
</div>

<style>
    .button_ok {
        border-radius:5px; width:95px; height:35px; background:red; color:white; font-size:16px; font-weight:bold;
    }
    .button_cancel {
        border-radius:5px; width:95px; height:35px; background:#546a79; color:white; font-size:16px; font-weight:bold;
    }
    /*垂直居中*/
    .verticalAlign{vertical-align:middle;display:inline-block;height:100%;margin-left:-1px;}

    .xcConfirm .xc_layer{
        position:fixed;top:0;left:0;width:100%;height:100%;background-color:#666666;opacity:0.5;z-index:2147000000;
    }
    .xcConfirm .popBox{position:fixed;left:50%;top:50%;background-color:#ffffff;z-index:2147000001;width:570px;height:300px;margin-left:-285px;margin-top:-150px;border-radius:5px;font-weight:bold;color:#535e66;}
    .xcConfirm .popBox .ttBox{height:30px;line-height:30px;padding:14px 30px;border-bottom:solid 1px #eef0f1;}
    .xcConfirm .popBox .ttBox .tt{font-size:18px;display:block;float:left;height:30px;position:relative;}
    .xcConfirm .popBox .ttBox .clsBtn{display:block;cursor:pointer;width:12px;height:12px;position:absolute;top:22px;right:30px;background:url(img/informations.png) -48px -96px no-repeat;}
    .xcConfirm .popBox .txtBox{margin:40px 100px;height:100px;overflow:hidden;}
    .xcConfirm .popBox .txtBox .bigIcon{float:left;margin-right:20px;width:48px;height:48px;background-image:url(img/informations.png);background-repeat:no-repeat;background-position:48px 0;}
    .xcConfirm .popBox .txtBox p{height:84px;margin-top:16px;line-height:26px;overflow-x:hidden;overflow-y:auto;}
    .xcConfirm .popBox .txtBox p input{width:364px;height:30px;border:solid 1px #eef0f1;font-size:18px;margin-top:6px;}
    .xcConfirm .popBox .btnArea{border-top:solid 1px #eef0f1;}
    .xcConfirm .popBox .btnGroup{float:right;}
    .xcConfirm .popBox .btnGroup .sgBtn{margin-top:14px;margin-right:10px;}
    .xcConfirm .popBox .sgBtn{display:block;cursor:pointer;float:left;width:95px;height:35px;line-height:35px;text-align:center;color:#FFFFFF;border-radius:5px;}
    .xcConfirm .popBox .sgBtn.ok{background-color:#0095d9;color:#FFFFFF;}
    .xcConfirm .popBox .sgBtn.cancel{background-color:#546a79;color:#FFFFFF;}
</style>
<script>
    /* 需要给 td 加上 id, 以获得其左上角的位置 */
    var bw = $("#pop_14762533194109678");
    function showBubble() {
        bw.show();
    }
    function hideBubble() {
        bw.hide();
    }
    /* 第2参数 10 提示文字； 20：疑问 ； 30 警告 ； 40 失败； 成功 50
		第3参数，弹出框标题
    */
    function showMessage() {
		var msg = arguments[0] ? arguments[0] : ""; //设置参数p1默认值为-1 
		var returnCode = arguments[1] ? arguments[1] : 10; //设置参数p1默认值为-1 
		var bubble_title = arguments[2] ? arguments[2] : "虚拟实验工场"; //设置参数p1默认值为-1 
    	 
        if (msg.length < 1) return;
        //alert(msg);
        $("#bubble_content").html(msg);
        $("#bubble_title").text(bubble_title);
        var icon = $("#icon");
        var buttonOK = $("#buttonOK");
        //alert(icon.css('color'));
        //$(this).find('a').css('color','#fff');
        //    <!-- 错误 background-position: -48px -48px;  成功 background-position: 0px -48px; -->
        var COLOR_DISABLED = "grey";//按钮灰掉的字颜色
        var COLOR_ENABLED = "white";//可用时的颜色
        var color = COLOR_ENABLED;
        var disabled = false;
        if (returnCode == 10) {
            icon.css("background-position",   "0px 0px");
        } else if (returnCode == 20){//正在处理中，确定 按钮灰掉
            icon.css("background-position",   "-48px 0px");
            color = COLOR_DISABLED;
            disabled = true;
            //alert(buttonOK.val());
        } else if (returnCode == 30){
            icon.css("background-position",   "0px -96px");
        } else if (returnCode == 40){
            icon.css("background-position",   "-48px -48px");
        } else if (returnCode == 50){
            icon.css("background-position",   "0px -48px");
        } else {
            icon.css("background-position",   "0px 0px");
        }
        buttonOK.css("color",     color);
        buttonOK.attr("disabled", disabled);
        showBubble();
    }
    /* 当前页面元素、最长显示的字数、 id：没用了 、完整名称 */
    function showWholeName(elem, length, theId, theName) {
        //alert(elem + "; length=" + length + "; theId=" + theId + "; theName=" + theName);
        if (theName.length <= length) return; //长度小于等于 5，不显示
        //var e = "td" + theId;
        //e = $("#" + e);
        //alert($(elem).offset().left);
        var left = $(elem).offset().left; //表格 td 的 的左侧
        var top  = $(elem).offset().top;  //表格 td 的 的左侧
        left = left + 6 * length; //图层起点在 td 的左侧
        top = top + 14;
        //alert("left = " + left + "; ");
        //alert(theId + "; name=" + theName);
        bw.text("　" + theName + "　");
        var width = bw.width();//取得弹出窗口宽度
        var right = left + width;//取得弹出窗口右侧边缘
        var docuWidth = $(document).width();//浏览器时下窗口文档对象宽度
        if (right > docuWidth) {
            left = docuWidth - width;//超出了窗口宽度，则左上点向左移动
        }
        //alert("left = " + left + "; width=" + width + "; right=" + right + "; width=" + width + "; docuWidth=" + docuWidth);

        bw.css("left", left + "px");
        bw.css("top", top + "px");
        //bw.hide();
        bw.show();
        //alert();
    }
</script>
