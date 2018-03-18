    // 定义一个图片切割
  var ic = new ImgCropper("bgDiv", "dragDiv", "", {
    Width: 300, Height: 400, Color: "#000",
    Resize: true,
    Right: "rRight", Left: "rLeft", Up: "rUp", Down: "rDown",
    RightDown: "rRightDown", LeftDown: "rLeftDown", RightUp: "rRightUp", LeftUp: "rLeftUp",
    Preview: "viewDiv", viewWidth: 300, viewHeight: 300
  })




  $("idSize").onclick = function(){
    if(ic.Height == 200){
      ic.Height = 400;
      this.value = "缩小显示";
    }else{
      ic.Height = 200;
      this.value = "还原显示";
    }
    ic.Init();
  }

  $("idOpacity").onclick = function(){
    if(ic.Opacity == 0){
      ic.Opacity = 50;
      this.value = "全透明";
    }else{
      ic.Opacity = 0;
      this.value = "半透明";
    }
    ic.Init();
  }

  $("idColor").onclick = function(){
    if(ic.Color == "#000"){
      ic.Color = "#fff";
      this.value = "白色背景";
    }else{
      ic.Color = "#000";
      this.value = "黑色背景";
    }
    ic.Init();
  }

  $("idScale").onclick = function(){
    if(ic.Scale){
      ic.Scale = false;
      this.value = "使用比例";
    }else{
      ic.Scale = true;
      this.value = "取消比例";
    }
    ic.Init();
  }

  $("idMin").onclick = function(){
    if(ic.Min){
      ic.Min = false;
      this.value = "设置最小尺寸";
    }else{
      ic.Min = true;
      this.value = "取消最小尺寸";
    }
    ic.Init();
  }

  $("idView").onclick = function(){
    if(ic.viewWidth == 200){
      ic.viewWidth = 300;
      this.value = "缩小预览";
    }else{
      ic.viewWidth = 200;
      this.value = "扩大预览";
    }
    ic.Init();
  }









  function changeImg(fl) {
    var url = getFileUrl(fl.id);
    //alert('fl= ' + url);
    if(fl.value.length > 0){
      ic.Url = url;
    }
    ic.Init();
    //alert(url);
    //$("imgWidth").value= ic._tempImg.width;
    //$("imgHeight").value= ic._tempImg.height;

  }

  function getFileUrl(sourceId) {
    var url;
    //alert('yyy, ' + sourceId);
    if (navigator.userAgent.indexOf("MSIE")>=1) { // IE
      url = document.getElementById(sourceId).value;
    } else if(navigator.userAgent.indexOf("Firefox")>0) { // Firefox
      url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0));
    } else if(navigator.userAgent.indexOf("Chrome")>0) { // Chrome
      url = window.URL.createObjectURL(document.getElementById(sourceId).files.item(0));
    }
    return url;
  }
