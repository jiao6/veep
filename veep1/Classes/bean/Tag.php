<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");

class Tag{
    //几个重要的参数
    private $id = 0;//总记录数
    private $name = "";
    private $sortOrder = 1000;//学生人次
    private $tagType   = 9999;//课堂数量
    private $attachAmount = 0;//课程数量
    private $objectType   = 300;//100用户；200课程；300课堂；400实验；
    private $status 	= 0;//状态。0正常，1删除，2自己固有的标签，4附加的

	//
    private $img  = "";
    private $introduction = "";//当前页号
    private $universityName	= "北京理工大学";//北理工
    
    private $academicTitleId= 0; //10助教
    private $academicTitleName = ""; //10助教
    
    private $lessonList = array() ;//前、后若干页

    /* 构造函数。总记录数量、每页记录数、当前页号  */
    function __construct(){
    }
    function getId() {
        return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }

    function getName() {
        return $this->name;
    }
    function setName($name) {
        $this->name = $name;
    }
    function getImg() {
        return $this->img;
    }
    function setImg($img) {
        $this->img = $img;
    }
    
    function getIntroduction() {
        return $this->introduction;
    }
    function setIntroduction($introduction) {
        $this->introduction = $introduction;
    }


    function getSortOrder() {
        return $this->sortOrder;
    }
    function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }

    function getTagType() {
        return $this->tagType;
    }
    function setTagType($tagType) {
        $this->tagType = $tagType;
    }

    function getAttachAmount() {
        return $this->attachAmount;
    }
    function setAttachAmount($attachAmount) {
        $this->attachAmount = $attachAmount;
    }

    function getObjectType() {
        return $this->objectType;
    }
    function setObjectType($objectType) {
        $this->objectType = $objectType;
    }

    function getStatus() {
        return $this->status;
    }
    function setStatus($status) {
        $this->status = $status;
    }

    function getUniversityName() {
        return $this->universityName;
    }
    function setUniversityName($universityName) {
        $this->universityName = $universityName;
    }

    function getAcademicTitleId() {
        return $this->academicTitleId;
    }
    function setAcademicTitleId($academicTitleId) {
        $this->academicTitleId = $academicTitleId;
    }

    function getAcademicTitleName() {
        return $this->academicTitleName;
    }
    function setAcademicTitleName($academicTitleName) {
        $this->academicTitleName = $academicTitleName;
    }



/*返回数组，0号id，1号name，2号学生人数，3号图片，4号介绍*/
    function getLessonList() {
        return $this->lessonList;
    }
    function setLessonList($lessonList) {
        $this->lessonList = $lessonList;
    }


    function toString() {
    }
}
?>
