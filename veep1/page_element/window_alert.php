<script type="text/javascript" >
	//http://www.17sucai.com/preview/1/2015-03-25/提示对话框/index.html
	$("#btn1").click(function(){
		var txt=  "提示文字，提示文字，提示文字，提示文字，提示文字，提示文字<br> ffffffffffffffffffffffffffffff";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.info);
	});
	
	$("#btn2").click(function(){
		var txt=  "提示文字，提示文字，提示文字，提示文字，提示文字，提示文字";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.confirm);

	});
	
	$("#btn3").click(function(){
		var txt=  "提示文字，提示文字，提示文字，提示文字，提示文字，提示文字";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.warning);
	});
	
	$("#btn4").click(function(){
		var txt=  "提示文字，提示文字，提示文字，提示文字，提示文字，提示文字";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.error);
	});
	
	$("#btn5").click(function(){
		var txt=  "提示文字，提示文字，提示文字，提示文字，提示文字，提示文字";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.success);
	});
	
	$("#btn6").click(function(){
		var txt=  "请输入";
		window.wxc.xcConfirm(txt, window.wxc.xcConfirm.typeEnum.input,{
			onOk:function(v){
				console.log(v);
			}
		});
	});
	
	$("#btn7").click(function(){
		var txt=  "自定义呀";
		var option = {
			title: "自定义",
			btn: parseInt("0011",2),
			onOk: function(){
				console.log("确认啦");
			}
		}
		window.wxc.xcConfirm(txt, "custom", option);
	});
	
	$("#btn8").click(function(){
		var txt=  "默认";
		window.wxc.xcConfirm(txt);
	});

</script>
<script type="text/javascript" >
    /*
     * 使用说明:
     * window.wxc.Pop(popHtml, [type], [options])
     * popHtml:html字符串
     * type:window.wxc.xcConfirm.typeEnum集合中的元素
     * options:扩展对象
     * 用法:
     * 1. window.wxc.xcConfirm("我是弹窗<span>lalala</span>");
     * 2. window.wxc.xcConfirm("成功","success");
     * 3. window.wxc.xcConfirm("请输入","input",{onOk:function(){}})
     * 4. window.wxc.xcConfirm("自定义",{title:"自定义"})
     */
    (function($){
        window.wxc = window.wxc || {};
        window.wxc.xcConfirm = function(popHtml, type, options) {
            var btnType = window.wxc.xcConfirm.btnEnum;
            var eventType = window.wxc.xcConfirm.eventEnum;
            var popType = {
                info: {
                    title: "信息",
                    icon: "0 0",//蓝色i
                    btn: btnType.ok
                },
                success: {
                    title: "成功",
                    icon: "0 -48px",//绿色对勾
                    btn: btnType.ok
                },
                error: {
                    title: "错误",
                    icon: "-48px -48px",//红色叉
                    btn: btnType.ok
                },
                confirm: {
                    title: "提示",
                    icon: "-48px 0",//黄色问号
                    btn: btnType.okcancel
                },
                warning: {
                    title: "警告",
                    icon: "0 -96px",//黄色叹号
                    btn: btnType.okcancel
                },
                input: {
                    title: "输入",
                    icon: "",
                    btn: btnType.ok
                },
                custom: {
                    title: "",
                    icon: "",
                    btn: btnType.ok
                }
            };
            var itype = type ? type instanceof Object ? type : popType[type] || {} : {};//格式化输入的参数:弹窗类型
            var config = $.extend(true, {
                //属性
                title: "", //自定义的标题
                icon: "", //图标
                btn: btnType.ok, //按钮,默认单按钮
                //事件
                onOk: $.noop,//点击确定的按钮回调
                onCancel: $.noop,//点击取消的按钮回调
                onClose: $.noop//弹窗关闭的回调,返回触发事件
            }, itype, options);

            var $txt = $("<p>").html(popHtml);//弹窗文本dom
            var $tt = $("<span>").addClass("tt").text(config.title);//标题
            var icon = config.icon;
            var $icon = icon ? $("<div>").addClass("bigIcon").css("backgroundPosition",icon) : "";
            var btn = config.btn;//按钮组生成参数

            var popId = creatPopId();//弹窗索引

            var $box = $("<div>").addClass("xcConfirm");//弹窗插件容器
            var $layer = $("<div>").addClass("xc_layer");//遮罩层
            var $popBox = $("<div>").addClass("popBox");//弹窗盒子
            var $ttBox = $("<div>").addClass("ttBox");//弹窗顶部区域
            var $txtBox = $("<div>").addClass("txtBox");//弹窗内容主体区
            var $btnArea = $("<div>").addClass("btnArea");//按钮区域

//            var $ok = $("<a>").addClass("sgBtn").addClass("ok").text("确定");//确定按钮
//            var $cancel = $("<a>").addClass("sgBtn").addClass("cancel").text("取消");//取消按钮
            var $ok =     $("<a>").addClass("sgBtn").html("<input type='button' id='buttonOK' value='确定' style='' class='button_ok' >  ");//确定按钮
            var $cancel = $("<a>").addClass("sgBtn").html("<input type='button' id='buttonCancel' value='取消' style='' class='button_cancel' >  ");//取消按钮
            var $hide = $("<a>").addClass("sgBtn").html("<input type='button' id='buttonCancel' value='关闭' style='' class='button_cancel' >  ");//取消按钮
            var $input = $("<input>").addClass("inputBox");//输入框
            var $clsBtn = $("<a>").addClass("clsBtn");//关闭按钮

            //建立按钮映射关系
            var btns = {
                ok: $ok,
                cancel: $cancel
            };

            init();

            function init(){
                //处理特殊类型input
                if(popType["input"] === itype){
                    $txt.append($input);
                }

                creatDom();
                bind();
            }

            function creatDom(){
                $popBox.append(
                    $ttBox.append(
                        $clsBtn
                    ).append(
                        $tt
                    )
                ).append(
                    $txtBox.append($icon).append($txt)
                ).append(
                    $btnArea.append(creatBtnGroup(btn))
                );
                $box.attr("id", popId).append($layer).append($popBox);
                $("body").append($box);
            }

            function bind(){
                //点击确认按钮
                $ok.click(doOk);

                //回车键触发确认按钮事件
                $(window).bind("keydown", function(e){
                    if(e.keyCode == 13) {
                        if($("#" + popId).length == 1){
                            doOk();
                        }
                    }
                });

                //点击取消按钮
                $cancel.click(doCancel);

                //点击关闭按钮
                $clsBtn.click(doClose);
            }

            //确认按钮事件
            function doOk(){
                var $o = $(this);
                var v = $.trim($input.val());
                if ($input.is(":visible"))
                    config.onOk(v);
                else
                    config.onOk();
                $("#" + popId).remove();
                config.onClose(eventType.ok);
            }

            //取消按钮事件
            function doCancel(){
                var $o = $(this);
                config.onCancel();
                $("#" + popId).remove();
                config.onClose(eventType.cancel);
            }

            //关闭按钮事件
            function doClose(){
                $("#" + popId).remove();
                config.onClose(eventType.close);
                $(window).unbind("keydown");
            }

            //生成按钮组
            function creatBtnGroup(tp){
                var $bgp = $("<div>").addClass("btnGroup");
                $.each(btns, function(i, n){
                    if( btnType[i] == (tp & btnType[i]) ){
                        $bgp.append(n);
                    }
                });
                return $bgp;
            }

            //重生popId,防止id重复
            function creatPopId(){
                var i = "pop_" + (new Date()).getTime()+parseInt(Math.random()*100000);//弹窗索引
                if($("#" + i).length > 0){
                    return creatPopId();
                }else{
                    return i;
                }
            }
        };

        //按钮类型
        window.wxc.xcConfirm.btnEnum = {
            ok: parseInt("0001",2), //确定按钮
            cancel: parseInt("0010",2), //取消按钮
            okcancel: parseInt("0011",2) //确定&&取消
        };

        //触发事件类型
        window.wxc.xcConfirm.eventEnum = {
            ok: 1,
            cancel: 2,
            close: 3
        };

        //弹窗类型
        window.wxc.xcConfirm.typeEnum = {
            info: "info",
            success: "success",
            error:"error",
            confirm: "confirm",
            warning: "warning",
            input: "input",
            custom: "custom"
        };

    })(jQuery);
</script>
<style type="text/css" />
	.button_ok {
		border-radius:5px; width:95px; height:35px; background:#337ab7; color:white; font-size:16px; font-weight:bold;
	}
	.button_cancel {
		border-radius:5px; width:95px; height:35px; background:#546a79; color:white; font-size:16px; font-weight:bold;
	}
    /*垂直居中*/
    .verticalAlign{vertical-align:middle;display:inline-block;height:100%;margin-left:-1px;}

    .xcConfirm .xc_layer{position:fixed;top:0;left:0;width:100%;height:100%;background-color:#666666;opacity:0.5;z-index:2147000000;}
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

