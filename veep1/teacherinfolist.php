<?
error_reporting(0);

session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
    exit;
}

include_once ("header.php");
require_once ("config/MetaDataGenerator.php");
require_once ("config/Pagination.php");
require_once ("Classes/TeacherShower.php");
$teacherShower = new TeacherShower(0);
$STRING_MAX = 6;

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
        margin: 09px 18px;
    }
    .ul_lists .md_img {
        width: 100px;
        height: 100px;
        border-radius: 100px;
        float: left;
    }
    .ul_lists .md_img img {
        width: 100px;
        height: 100px;
        border-radius: 100px;
    }
    .ul_lists li.marleft {
        margin-left: 0;
    }
    .ul_lists li.marright {
        margin-right: 0;
    }
    .ul_lists .midinfo {
        margin-left: 125px;
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
                $stickyTeacherList = $teacherShower->getStickyTeacherList(
                    MetaDataGenerator::STICKY_YES, $pageNo, $pageSize, $universityId);
                //echo "size=" . sizeof($stickyTeacherList);
                    if (sizeof($stickyTeacherList) > 0) {//置顶老师存在
                ?>
                <div class="zhiding">
                <!--div class="zdhead" style="width: 950px; height: 30px; font-size: 20px; color: rgb(28, 122, 128); padding-left: 10px; padding-right: 98px; border-left: 11px solid rgb(120, 168, 163); margin-left: 13px;"-->
                <div class="testtxt" style="top:-20px" >
                    推荐教师
                </div>
                <SCRIPT></SCRIPT>
                  <? foreach($stickyTeacherList as $teacher){ ?>
                    <li>
                        <div class="md_img">
                            <a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><img src="<? echo $teacher->getImg() ?>" alt="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" title="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" ></a>
                        </div>
                        <div class="midinfo">
                            <p class="infoname" title="<? echo $teacher->getName() ?>"><a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><? echo MetaDataGenerator::getShortenString($teacher->getName(), $STRING_MAX) ?></a></p>
                            <p title="<? echo $teacher->getAcademicTitleName() ?>"><? echo $teacher->getAcademicTitleName() ?></p>
                            <p class="school_name" title="<? echo $teacher->getUniversityName() ?>"><a href="teacherinfolist.php?universityId=<? echo $teacher->getUniversityId() ?>&pageSize=<? echo $pageSIze ?>&pageNo=<? echo $pageNo ?>"><? echo MetaDataGenerator::getShortenString($teacher->getUniversityName(), $STRING_MAX) ?></a></p>
                            <p title="选课人次：<? echo $teacher->getStudentAmount() ?>">人次：<? echo $teacher->getStudentAmount() ?></p>
                        </div>
                    </li>
                  <? } ?>
                </div>
               <?  }
                 }
$commonTeacherList = $teacherShower->getStickyTeacherList(//普通教师的列表
    MetaDataGenerator::STICKY_NO, $pageNo, $pageSize, $universityId);
                foreach($commonTeacherList as $teacher){
                ?>
                    <li>
                        <div class="md_img">
                            <a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><img src="<? echo $teacher->getImg() ?>" alt="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" title="<? echo $teacher->getName().$teacher->getAcademicTitleName() ?>" ></a>
                        </div>
                        <div class="midinfo">
                            <p class="infoname" title="<? echo $teacher->getName() ?>"><a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>"><? echo MetaDataGenerator::getShortenString($teacher->getName(), $STRING_MAX) ?></a></p>
                            <p title="<? echo $teacher->getAcademicTitleName() ?>"><? echo $teacher->getAcademicTitleName() ?></p>
                            <p class="school_name" title="<? echo $teacher->getUniversityName() ?>"><a href="teacherinfolist.php?universityId=<? echo $teacher->getUniversityId() ?>&pageSize=<? echo $pageSIze ?>&pageNo=<? echo $pageNo ?>"><? echo MetaDataGenerator::getShortenString($teacher->getUniversityName(), $STRING_MAX) ?></a></p>
                            <p title="选课人次：<? echo $teacher->getStudentAmount() ?>">人次：<? echo $teacher->getStudentAmount() ?></p>
                        </div>
                    </li>
                <? } ?>
            </ul>
                <? $pagination = $teacherShower->getPagination() ;
                $url = "teacherinfolist.php";
                $queryString = "?universityId=$universityId&pageNo=";
                $PARAM_PAGE_SIZE = "pageSize";//放每页记录数的 url 参数
                $PARAM_PAGE_NO = "pageNo";//放页号的 url 参数
                $pagination->toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO);
                ?>
        </div>

    </div>
    <!-- <script type="text/javascript" src="js/jquery.min.js"></script> -->
        <script type="text/javascript">
            var height1 = 120;
            var height2 = 60;
            var rowCount = 4;
        	//var cnt1 = Math.floor.(($('.zhiding li').length - 1)/4) + 1;
        	//var cnt2 = Math.floor.(($('.ul_lists>li').length - 1)/4) + 1;
        	//alert();
        $(function(){
        	var heightSticky = Math.floor(($('.zhiding li').length - 1)/rowCount)*height1 + height1 + height2;
        	var heightCommon = Math.floor(($('.ul_lists>li').length -1)/rowCount)*height1 + height1 + height2;
            $('.zhiding').height(heightSticky);
            $('.ul_lists').height(heightCommon + heightSticky);
            // console.log(Math.ceil($('.ul_lists li').length));
        });

    	</script>
    <?
include("footer.php");
?>

