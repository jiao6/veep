/* 检测上传图片类型 */
    function checkImageType(fileOfForm) {
    	//alert('');
        var fileImage = fileOfForm.value;
        if (fileImage.length < 1) {
            alert("请选择图片上传");
            return false;
        }
        var kkk = /\.(gif|jpg|jpeg|jpe|png|GIF|JPG|PNG)$/;
        if (!kkk.test(fileImage)) {
            alert("图片类型只能是 gif、jpeg、jpg、png 中的一种！");
            return false;
        }
        return true;
    }

