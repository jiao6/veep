<SCRIPT>
    function prepareToSelect(elem) {
        //alert(btnSelector.value);
        var infoDiv = $("#InfoDivSelectedTags");
        var left = $(elem).offset().left -450; //表格 td 的 的左侧
        var top  = $(elem).offset().top - 150;  //表格 td 的 的左侧
        //left = left + 6 * length; //图层起点在 td 的左侧
        //top = top + 14;
        //alert("left = " + left + "; top=" + top);
        var width = infoDiv.width();//取得弹出窗口宽度
        var right = left + width;//取得弹出窗口右侧边缘
        var docuWidth = $(document).width();//浏览器时下窗口文档对象宽度
        //alert(docuWidth + ", left=" + left + ", width=" + width + ", right=" + right);
        if (right > docuWidth) {
            left = docuWidth - width - 20;//超出了窗口宽度，则左上点向左移动
        }
        //alert(docuWidth + ", left=" + left + ", width=" + width + ", right=" + right);
        infoDiv.css("left", left + "px");
        infoDiv.css("top", top + "px");
        infoDiv.show();

    }
    function closeWindow1() {
        var infoDiv = $("#InfoDivSelectedTags");
        infoDiv.hide();
    }
    function closeWindow2() {
        var InfoDivSimiliarTags = $("#InfoDivSimiliarTags");
        InfoDivSimiliarTags.hide();
    }

    function selectATag(elem) {
        var tagId = elem.id.substr(6);//从按钮获得 标签 id
        var tagNm = $.trim(elem.value);
        var selectedTagIds = $("#selectedTagIds");
        var str01 = selectedTagIds.val();
        //alert(tagId + "; " + tagNm);
        var txtTag = $(".txtTag");
        //先判断是否已经选择了这个标签
        var hasSelected = false;
        txtTag.each(function (index, domEle){
            var itsValue = $.trim($(this).val());
            if (itsValue != "" && itsValue==tagNm) {
                hasSelected = true;
                showMessage("您已经选择了【"+itsValue+"】，不能重复选择！", 30);
                return false;
            }
        });
        if(hasSelected) {
        	return;//已经选择了该标签则退出
        } else {
	    	if (prevFocusText == undefined) {//直接点击按钮
	        	var hasEmpty = false;//还有空的文本框
	            txtTag.each(function (index, domEle){
	                var itsValue = $(this).val();
	                if (itsValue == "") {
	                    $(this).val(tagNm);
	                    //str01 += "; " + tagId;
	                    hasEmpty = true;
	                    return false;
	                }
	            });
	            if (!hasEmpty) {
	            	showMessage("已经没有可供添加的文本框了！", 30);
	            }
	
	    	} else {
	    		if(!prevFocusText.disabled) {
	    			//alert(tagNm);
	    			prevFocusText.value=tagNm;
	    		}
	    	}
        }

    	//alert(prevFocusText);
    	
        //selectedTagIds.val(str01);
    }
    /* 根据用户按键给予提示 */
    function showTip(elem) {
        //InfoDivSimiliarTags.show();
        var txt = $.trim(elem.value);
        var length33 = txt.length;
        if (length33 == length22) {
        	return;
        }
        //alert(length22 + "; " + length33);
        //输入内容或缩短了
        length22 = length33;
        if (length33 < 1) return;
        /**/
	    $.ajax({
	      type:"post",
	      url :"lessonedit.php",
	      data: {"todo": "getSimiliarTags", "txt": txt},
	      datatype:"json",
	      success:function(data){
	        //alert(1);
	        var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
            var univList = dataJson.data;
            var leng = univList.length;
            tagSize = leng;
            if (leng < 1) return;
            var unversityListByProvince = $("#unversityListByProvince");
            var content = "";
            unversityListByProvince.text("");
	        //alert("length=" + leng + "； message=" + leng);
            for (var i = 0; i < leng; i++) {
                var aUniver = univList[i];
                var univerId = aUniver.id;
                var univerName = aUniver.name;
                var ATTACH_AMOUNT = aUniver.ATTACH_AMOUNT;
                var style1 = "";
                //content += '<a title="'+ univerName +'；学校编码：'+ univerId +'" style="'+ style1 +'" id="univer" univerId="' +univerId + '" univerName="'+ univerName +'" class="universityClass" onclick="selectUniver(this, '+ univerId +', \''+ univerName +'\')"> '+ univerName +'  </a>';
                content += '<input id="btnTag'+univerId+'"  type="button" value="'+univerName+'" class="btnTag" onclick="selectATag(this)" title="'+univerName+'。已选次数：'+ATTACH_AMOUNT+'">&nbsp;';
                if (i > 0 && (i + 1) % 4 == 0)//每 4 个换行
                	content += "<br/>";
                //alert("univerId=" + univerId + "; univerName=" + univerName + "; univerImage=" + univerImage);
            }
            //alert(content);
            unversityListByProvince.html(content);
			showSimiliarTags(elem);
	      }
	    })
    }
    //重新设置
    function resetLength(elem) {
        var txt = $.trim(elem.value);
        var length33 = txt.length;
        length22 = length33;
        prevFocusText = undefined;
    }
    var prevFocusText;
    var length22 = 0;
    function getPrevElement(elem) {
    	prevFocusText = elem;
    }
    // 显示近似标签列表
    function showSimiliarTags(elem) {
    	var leftLimit = 817;
        var infoDiv = $("#InfoDivSimiliarTags");
        //alert();
        var left = $(elem).offset().left +154; //表格 td 的 的左侧
        if (left > leftLimit)left = leftLimit;
        var top  = $(elem).offset().top - 30;  //表格 td 的 的左侧
        //left = left + 6 * length; //图层起点在 td 的左侧
        //top = top + 14;
        //alert("left = " + left + "; top=" + top);
        var width = infoDiv.width();//取得弹出窗口宽度
        var right = left + width;//取得弹出窗口右侧边缘
        var docuWidth = $(document).width();//浏览器时下窗口文档对象宽度
        //alert(docuWidth + ", left=" + left + ", width=" + width + ", right=" + right);
        if (right > docuWidth) {
            left = docuWidth - width - 20;//超出了窗口宽度，则左上点向左移动
        }
        //alert(docuWidth + ", left=" + left + ", width=" + width + ", right=" + right);
        infoDiv.css("left", left + "px");
        infoDiv.css("top", top + "px");
        infoDiv.show();

    }
</SCRIPT>
<SCRIPT>
    $("#txtTag7").keydown(function(){
     	//alert("keydown");
     });
	$('input').bind('keyup', function () {
		//alert("keydown");
	});
	function subm() {
		var spliter = "||";
		var txtTag = $(".txtTag");
		//alert(txtTag.length);
		var correct = true;
		var tagNames = "";
        txtTag.each(function (index, domEle){
            var itsValue = $.trim($(this).val());
            var k = itsValue.indexOf("|");
            if (itsValue != "" && k >=0) {
                $(this).focus();
                showMessage("不能输入“|” !", 30);
                return false;
                correct = false;
            }
            if (itsValue != "") {
            	tagNames += itsValue + spliter;
            }
            //alert(tagNames);
        });
        if (!correct) return false;
        tagNames = tagNames.substr(0, tagNames.length-2);//砍掉最后两个竖线
        $("#selectedTagIds").val(tagNames);
        if (tagNames == "") {
        	if (confirm("您确定要清空所有标签吗？")) {
        		//alert("yes");
        		//closeWindow1();
        	} else {
        		return false;
        	}
        }
    	showMessage('正在处理，请稍候……', 20);
	    $.ajax({//
	      type:"post",
	      url :"lessonedit.php",
	      data: {"todo": "updateTags", "lessonId": <? echo $lessonId ?>, "tagNames": tagNames},
	      datatype:"json",
	      success:function(data){
	        //alert(1);
	        var dataJson = JSON.parse(data); // 使用json2.js中的parse方法将data转换成json格式
	        //alert(2);
	        var result = dataJson.data[0].result;
	        var message = dataJson.data[0].message;
	        //alert("result=" + result + "； message=" + message);
	        if (result == "SUCCESS") {
	          //alert("您输入的邮箱不存在！");
				showMessage(message, 50);
	          //return;
	        } else {//处理失败
	        	showMessage(message, 40);
	        }
	      }
	    })
	    var selTagList = $("#selTagList");//上次载入的数据标签列表
	    var arrayTagNames = tagNames.split(spliter);
        //alert("处理【"+tagNames+"】；" + arrayTagNames);
	    selTagList.empty();
	    for (var i = 0; i < arrayTagNames.length; i++) {
	    	selTagList.append('<OPTION value="">'+arrayTagNames[i]+'</OPTION>');
	    }
	    closeWindow1();
	    closeWindow2();
	    //alert("修改成功！");
	}
</SCRIPT>
<style>
    .txtTag{
        width:100px;
        text-indent:3px;
    }
    .btnTag{
        width:100px;
        padding: 0;
    }
</style>

<div id="InfoDivSelectedTags" style="display:none; border:2px black solid; background: white; color:black; position:absolute; left:100px; top:100px; z-index: 9999">
    <table width="440">
        <tr align="center">
            <td colspan="3" style="background:green; color:white" onclick="showSimiliarTags(this)"> 已选标签<input type="hidden" id="selectedTagIds" size="10"></td>
            <td width="115" title="关闭" onclick="closeWindow1();closeWindow2();">X</td>
        </tr>
      <form>
        <tr style="height:30px">
            <td colspan="4" align="center">
        	<? $cnt = 1;
        	if (sizeof($dataTagList) >0) {
        		foreach($dataTagList as $tag) {
        			$tagType = $tag->getStatus();//
        			$readOnly  = "";
        			if ($tagType == 2) $readOnly = "disabled title=\"默认标签，不可修改和删除！\"";//低于2000 是不可删除的标签  
					$txt = '<input id="txtTag'.$cnt.'" type="text" size="11" maxlength="20" value="'.$tag->getName().'" class="txtTag" title="'.$tag->getName().'。已选次数：'.$tag->getAttachAmount().'" style="" '.$readOnly.' onkeyup="showTip(this)" oninput="showTip(this)" onfocus="resetLength(this)" onblur="getPrevElement(this)">&nbsp;';
					echo $txt;
		     		$cnt++; 
		       } 
		 	} ?>
		       <!--input id="txtTag5" type="text" size="11" maxlength="20" value="" class="txtTag" style=""-->
		     </td>
        </tr>
        <tr align="center" style="height:30px">
            <td colspan="3" > <input type="button" value="提    交    修    改" title="修改标签" style="width:222px; cursor:hand" onclick="subm()" class="button bg-main button-small"></td>
            <td colspan="1" > <input type="reset" class="button bg-sub button-small" value="重置" title="显示原有的标签" style="width:100px; cursor:hand" onclick=""> </td>
        </tr>
      </form>
        <tr align="center" style="height:30px">
            <td colspan="4" style="background:#999; color:white;line-height: 30px;margin: 3px 0;"> 热门标签</td>
        </tr>
        <tr style="height:30px">
            <td colspan="4" align="center"><?
            $cnt = 1;
            $size = sizeof($tagList);
            foreach($tagList as $tag) { ?>
            <input id="btnTag<? echo $tag->getId() ?>" type="button" value="<? echo $tag->getName() ?>" class="btnTag button border-blue " onclick="selectATag(this)" title="<? echo $tag->getName() . '。已选次数：' .$tag->getAttachAmount() ?>">&nbsp;
            <?
                $cnt++;
            } ?>
            </td>
        </tr>
    </table>
</div>
<div id="InfoDivSimiliarTags" style="display:none; border:2px black solid; background: white; color:black; position:absolute; left:100px; top:100px; z-index: 111111">
    <table width="420">
        <tr align="center">
            <td colspan="3" style="background:green; color:white"> 相似标签</td>
            <td title="关闭" onclick="closeWindow2()" class="hover">X</td>
        </tr>
        <tr style="height:30px">
            <TD colspan="4" id="unversityListByProvince">
            </TD>
        </tr>
    </table>
</div>
