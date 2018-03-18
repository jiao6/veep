<?
error_reporting(0);
include_once ("header.php");
require_once ("config/MetaDataGenerator.php");
require_once ("config/Pagination.php");
require_once ("Classes/LessonController.php");
//echo "search=" . $search ;
$lessonController = new LessonController();

?>
<style type="text/css">
    .clss{
        width: 950px;
        margin: auto;
        background: #fff;
        margin-top: 10px;
        padding-bottom: 20px;
    }
.ul_lists li {
    float: left;
    width: 200px;
    background: #fff;
    margin: 5px 10px;
    box-shadow: 0 0 3px #ccc;
}
.ul_lists .md_img {
    width: 200px;
    height: 150px;
}
.ul_lists .md_img img {
    width: 200px;
    height: 150px;
}

    .ul_lists li.marleft {
        margin-left: 0;
    }
    .ul_lists li.marright {
        margin-right: 0;
    }
    .ul_lists .midinfo {
        margin-left: 05px;
        /*float: right;*/
    }
    .ul_lists .zhiding {
        width: 950px;
        /*height: 300px;*/
        border-bottom: 1px solid #ccc;
        border-top: 1px solid #ccc;
    }
    .school_name {
        overflow:hidden;
        white-space:nowrap;
        text-overflow:ellipsis;
        -o-text-overflow:ellipsis
    }
    #selPageSize, #selGoto {
        float: none;
        text-indent: 0;
    }
</style>
    <div class="clss">
        <div>
            <ul class="ul_lists" style="width: 950px">
                <? if (!$pageNo || $pageNo <= 1) {// 第一页才有置顶老师
                $stickyObjectList = $lessonController->getShownLessonList(
                    MetaDataGenerator::STICKY_YES, $pageNo, $pageSize, $universityId);
                //echo "size=" . sizeof($stickyObjectList);
                    if (sizeof($stickyObjectList) > 0) {//置顶老师存在
                ?>
                <div class="zhiding">
                <!--div class="zdhead" style="width: 950px; height: 30px; font-size: 20px; color: rgb(28, 122, 128); padding-left: 10px; padding-right: 98px; border-left: 11px solid rgb(120, 168, 163); margin-left: 13px;"-->
                <div class="testtxt" style="top:-20px" >
                    推荐课堂
                </div>
                  <? foreach($stickyObjectList as $object){ 
					include("page_element/lesson_block.php");
                     } ?>
                </div>
               <?  }
                 }
                 /**/
$commonObjectList = $lessonController->getShownLessonList(//普通教师的列表
    MetaDataGenerator::STICKY_NO, $pageNo, $pageSize, $universityId);
                foreach($commonObjectList as $object){
					include("page_element/lesson_block.php");
                } ?>
            </ul>
                <? $pagination = $lessonController->getPagination() ;
                $url = "";
                $queryString = "?search=$search&universityId=$universityId&pageNo=";
                $PARAM_PAGE_SIZE = "pageSize";//放每页记录数的 url 参数
                $PARAM_PAGE_NO = "pageNo";//放页号的 url 参数
                $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                ?>
        </div>

    </div>
<script>
    function showInfoDiv(elem, lessonId) {
        //alert(lessonId);
        var dataDiv = $(".dataDiv");
        dataDiv.each(function (index,domEle){
			$(this).hide();
		});
        //alert(dataDiv.length);
        //for (int i )
        var left = $(elem).offset().left +150; //表格 td 的 的左侧
        var top  = $(elem).offset().top -00;  //表格 td 的 的左侧
        //left = left + 6 * length; //图层起点在 td 的左侧
        //top = top + 14;
        //alert("left = " + left + "; top=" + top);
		var infoDiv = $("#InfoDiv" + lessonId);
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
    function hideInfoDiv(elem, lessonId) {
		var infoDiv = $("#InfoDiv" + lessonId);
		infoDiv.hide();
    }
</script>
    <!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
        <script type="text/javascript">
            var height1 = 242;
            var height2 = 20;
            var rowCount = 4;
        	//var cnt1 = Math.floor.(($('.zhiding li').length - 1)/4) + 1;
        	//var cnt2 = Math.floor.(($('.ul_lists>li').length - 1)/4) + 1;
        	//alert();
        $(function(){
        	var heightSticky = Math.floor(($('.zhiding li').length-1)/rowCount)*height1 + height1 + 60;
        	var heightCommon = Math.floor (($('.ul_lists>li').length-1)/rowCount)*height1 + height1 + 60;
            $('.zhiding').height(heightSticky);
            $('.ul_lists').height(heightCommon + heightSticky);
            // console.log(Math.ceil($('.ul_lists li').length));
        });

    	</script>
    <?
include("footer.php");
?>

