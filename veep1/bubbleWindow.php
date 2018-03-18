<div id="bubbleWindow" name="bubbleWindow" class="div-bubble-window" onmouseover="javascript:bw.show();" style="">
    <div align="">&nbsp; 未知 &nbsp;</div>
</div>
<style>
    .div-bubble-window {
        position:absolute;
        left:550px; top:1300px; /* 左上角位置 */
        border:2px black solid; /* 边框粗细和颜色 */
        background: black; color:white; /*背景色、前景色*/
        display:none; /*初始状态不显示*/
        filter:alpha(Opacity=60); -moz-opacity:0.6; opacity: 0.6;/* 背景60% 透明 */
    }
</style>
<script>
    /* 需要给 td 加上 id, 以获得其左上角的位置 */
    var bw = $("#bubbleWindow");
    function hideBubble() {
        bw.hide();
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
