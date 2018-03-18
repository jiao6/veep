<?
$windowWidth=635;
?>
<div id="div-bubble-window-01" name="div-bubble-window-01" class="div-bubble-window-01" style="">
    <div class="_citys1" style="width:<? echo $windowWidth ?>px">
        <div class="_citys0" style="width:<? echo $windowWidth-100 ?>px" >省市列表</div><div class="_citys0" style="width:<? echo 100 ?>px; background:grey; color:white; " onclick="hideBubble()">X</div><br/>
            <a style="background-color: #fff000">北上广津重</a>
            &nbsp; <a data-level="0" provinceName="北京" class="AreaS" provinceId="10"> 北京  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="广东" class="AreaS" provinceId="20"> 广东  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="上海" class="AreaS" provinceId="21"> 上海  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="天津" class="AreaS" provinceId="22"> 天津  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="重庆" class="AreaS" provinceId="23"> 重庆  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">A-G</a>
            &nbsp; <a data-level="0" provinceName="安徽" class="AreaS" provinceId="551"> 安徽  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="福建" class="AreaS" provinceId="591"> 福建  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="甘肃" class="AreaS" provinceId="931"> 甘肃  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="广西" class="AreaS" provinceId="771"> 广西  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="贵州" class="AreaS" provinceId="851"> 贵州  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">H-K</a>
            &nbsp; <a data-level="0" provinceName="海南  " class="AreaS" provinceId="898"> 海南  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="黑龙江" class="AreaS" provinceId="451"> 黑龙江</a>&nbsp
            &nbsp; <a data-level="0" provinceName="河南  " class="AreaS" provinceId="371"> 河南  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="河北  " class="AreaS" provinceId="311"> 河北  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="湖南  " class="AreaS" provinceId="731"> 湖南  </a>&nbsp
            &nbsp; <a href="#" style="background:white; width:22">   </a>&nbsp
            &nbsp; <a data-level="0" provinceName="湖北" class="AreaS" provinceId="27"> 湖北  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">J-L</a>
            &nbsp; <a data-level="0" provinceName="江苏" class="AreaS" provinceId="025"> 江苏  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="江西" class="AreaS" provinceId="791"> 江西  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="吉林" class="AreaS" provinceId="431"> 吉林  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="辽宁" class="AreaS" provinceId="024"> 辽宁  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">N-S</a>
            &nbsp; <a data-level="0" provinceName="内蒙古" class="AreaS" provinceId="471"> 内蒙古</a>&nbsp
            &nbsp; <a data-level="0" provinceName="宁夏  " class="AreaS" provinceId="951"> 宁夏  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="青海  " class="AreaS" provinceId="971"> 青海  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="山东  " class="AreaS" provinceId="531"> 山东  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="山西  " class="AreaS" provinceId="351"> 山西  </a>&nbsp
            &nbsp; <a href="#" style="background:white; width:22">   </a>&nbsp
            &nbsp; <a data-level="0" provinceName="陕西" class="AreaS" provinceId="29"> 陕西  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="四川" class="AreaS" provinceId="28"> 四川  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">T-Z</a>
            &nbsp; <a data-level="0" provinceName="新疆" class="AreaS" provinceId="991"> 新疆  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="西藏" class="AreaS" provinceId="891"> 西藏  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="云南" class="AreaS" provinceId="871"> 云南  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="浙江" class="AreaS" provinceId="571"> 浙江  </a>&nbsp
            <br/>
            <a style="background-color: #fff000">港澳台海外</a>
            &nbsp; <a data-level="0" provinceName="香港" class="AreaS" provinceId="852"> 香港  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="澳门" class="AreaS" provinceId="853"> 澳门  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="台湾" class="AreaS" provinceId="886"> 台湾  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="海外" class="AreaS" provinceId="999"> 海外  </a>&nbsp
            &nbsp; <a data-level="0" provinceName="其他" class="AreaS" provinceId="9999"> 其他  </a>&nbsp
            <br/><!--!-- background:#f0ad4e; color:white; -->
        <div class="_citys0" onclick="hideBubble()" style="background:#d9534f; color:white; ">关闭</div>
    </div>
</div>

<div id="div-bubble-window-11" name="div-bubble-window-11" class="div-bubble-window-11" style="">
    <div class="_citys1" style="width:<? echo $windowWidth ?>px">
        <div class="_citys0" id="provinceSelected" style="width:<? echo $windowWidth/2 -50 ?>px">大学列表</div><div class="_citys0"  style="width:<? echo $windowWidth/2 -50 ?>px; background:grey; color:white" onclick="bw11.hide(); bw01.show();">返回省市列表</div><div class="_citys0" style="width:<? echo 100 ?>px; background:black; color:white; " onclick="bw11.hide()">X</div>
        	<div id="unversityListByProvince" style="background:white">
        	</div>
        <div class="_citys0" onclick="bw11.hide()" style="background:#d9534f; color:white; ">关闭</div>
    </div>
</div>

<style>
    .div-bubble-window-01 {
        position:absolute;
        left:550px; top:300px; /* 左上角位置 */
        border:2px black solid; /* 边框粗细和颜色 */
        background: white; color:blue; /*背景色、前景色*/
        display: none; /*初始状态不显示 none block*/
        /* filter:alpha(Opacity=60); -moz-opacity:0.6; opacity: 0.6;背景60% 透明 */
    }
    .div-bubble-window-11 {
        position:absolute;
        left:590px; top:350px; /* 左上角位置 */
        border:2px black solid; /* 边框粗细和颜色 */
        background: white; color:blue; /*背景色、前景色*/
        display: none; /*初始状态不显示 none block*/
        /* filter:alpha(Opacity=60); -moz-opacity:0.6; opacity: 0.6;背景60% 透明 */
    }

    ._citys0 {
        background: #56b4f8;
        display: inline-block;
        line-height: 34px;
        font-size: 15px;
        color: #fff;
        width: <? echo $windowWidth ?>px;
        text-align: center;
        cursor: pointer;
    }
    ._citys1 a {
        width: 83px;
        height: 35px;
        display: inline-block;
        background-color: #f5f5f5;
        color: #666;
        margin-left: 6px;
        margin-top: 3px;
        line-height: 35px;
        text-align: center;
        cursor: pointer;
        font-size: 13px;
        overflow: hidden;
    }
</style>
<script>
    $(".AreaS").click(function () {//敲击一个省份
        //alert("provinceId=" + $(this).data("provinceId"));
        //alert($(this).text());
        var provinces = $(".AreaS");
        var i = 0;
        provinces.each( function(){//清掉着色
            $(this).css("background", "#f5f5f5");
            $(this).css("color", "#666");
            i++;
        });
        var provinceId   = $(this).attr("provinceId");
        var provinceName = $(this).attr("provinceName");
        //alert("provinceId=" + provinceId + "; provinceName=" + provinceName + "; " + provinces.length);

        $(this).css("background","#f0ad4e");
        $(this).css("color","white");

        $("#provinceSelected").text(provinceName + "大学列表");


        $.ajax({//返回大学列表
            //alert(provinceId),
            type: "post",
            url : "universityList.php",
            data: {"provinceId": provinceId, "newPwd1": "newPwd1"},
            datatype:"json",
            success:function(data){
                var dataJson = JSON.parse(data);
                var univList = dataJson.data;
                var leng = univList.length;
                var unversityListByProvince = $("#unversityListByProvince");
                var content = "";
    			var universityId = $("#universityId").val(); // 原来的学校 id
				//alert(universityId);
                unversityListByProvince.text("");
                for (var i = 0; i < leng; i++) {
                    var aUniver = univList[i];
                    var univerId = aUniver.id;
                    var univerName = aUniver.name;
                    var univerImage = aUniver.image;
                    var style1 = "";
                    if (univerId == universityId) {// 选好的学校高亮
        				style1 = "background: #f0ad4e; color:white"; 
                    }
                    content += '<a title="'+ univerName +'；学校编码：'+ univerId +'" style="'+ style1 +'" id="univer" univerId="' +univerId + '" univerName="'+ univerName +'" class="universityClass" onclick="selectUniver(this, '+ univerId +', \''+ univerName +'\')"> '+ univerName +'  </a>';
                    if (i > 0 && (i + 1) % 7 == 0)//每 6 个换行
                    	content += "<br/>";
                    //alert("univerId=" + univerId + "; univerName=" + univerName + "; univerImage=" + univerImage);
                }
                unversityListByProvince.html(content);
                /*

                */
            }
        })
        bw01.hide();
        bw11.show();
    })
    function selectUniver(aUniv, univerId, univerName) {
    	var university = $("#university");
    	var universityId = $("#universityId");
		//alert(univerId + "; univerName=" + univerName + "; universityId=" + universityId.val());
        $(aUniv).css("background","#f0ad4e");
        $(aUniv).css("color","white");
        var i = 0;
		var univerList = $(aUniv).siblings("a");
        univerList.each( function(){
            $(this).css("background", "#f5f5f5");
            $(this).css("color", "#666");
            i++;
        });
        universityId.val(univerId);
        university.val(univerName);
        // 选完了关闭
        bw11.hide();

    }
    $(".universityClass").click(function () {
    	//var univListList = $(this).children("a");
    	//var leng = univListList.length;
    	/*
        var provinceId   = $(this).attr("provinceId");
        var provinceName = $(this).attr("provinceName");
        var provinces = $(".AreaS");
        var i = 0;
        provinces.each( function(){
            $(this).css("background", "#f5f5f5");
            $(this).css("color", "#666");
            i++;
        });
        //alert("provinceId=" + provinceId + "; provinceName=" + provinceName + "; " + provinces.length);

        $(this).css("background","#f0ad4e");
        $(this).css("color","white");

        $("#provinceSelected").text(provinceName + "大学列表");*/
    })

    var bw01 = $("#div-bubble-window-01");
    var bw11 = $("#div-bubble-window-11");


    function hideBubble() {
        bw01.hide();
    }
    /* 当前页面元素、最长显示的字数、 id：没用了 、完整名称 */
    function showProvince(elem, length, theId, theName) {
        //alert(elem + "; length=" + length + "; theId=" + theId + "; theName=" + theName);

        var left = $(elem).offset().left; //表格 td 的 的左侧
        var top  = $(elem).offset().top;  //表格 td 的 的左侧
        left = left + length; //图层起点在 td 的左侧
        top = top + 0;
        //alert("left = " + left + "; top=" + top + theId + "; name=" + theName);
        //bw01.text("　" + theName + "　");
        var width = bw01.width();//取得弹出窗口宽度
        var right = left + width;//取得弹出窗口右侧边缘
        var docuWidth = $(document).width();//浏览器时下窗口文档对象宽度
        if (right > docuWidth) {
            left = docuWidth - width;//超出了窗口宽度，则左上点向左移动
        }
        //alert("left = " + left + "; width=" + width + "; right=" + right + "; width=" + width + "; docuWidth=" + docuWidth);

        bw01.css("left", left + "px");
        bw01.css("top", top + "px");
        bw01.show();/**/
       // alert("bw11=" + bw11);
        bw11.css("left", left + "px");
        bw11.css("top", top + 0 + "px");
        bw11.hide();
    }
</script>

