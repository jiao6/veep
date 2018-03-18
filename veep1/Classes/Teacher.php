<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");

class Teacher{
    //每页数据数量
    private $id = 0;//总记录数
    private $name = "";
    private $img  = "";
    private $introduction	= "";//当前页号
    private $studentAmount	= 0;//学生人次
    private $lessonAmount	= 0;//课堂数量
    private $courseAmount	= 0;//课程数量
    private $userType 		= 1;//1学生，2教师，3管理员，4付费教师
    private $universityId 	= 10007;//北理工
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


    function getStudentAmount() {
        return $this->studentAmount;
    }
    function setStudentAmount($studentAmount) {
        $this->studentAmount = $studentAmount;
    }

    function getLessonAmount() {
        return $this->lessonAmount;
    }
    function setLessonAmount($lessonAmount) {
        $this->lessonAmount = $lessonAmount;
    }

    function getCourseAmount() {
        return $this->courseAmount;
    }
    function setCourseAmount($courseAmount) {
        $this->courseAmount = $courseAmount;
    }

    function getUserType() {
        return $this->userType;
    }
    function setUserType($userType) {
        $this->userType = $userType;
    }

    function getUniversityId() {
        return $this->universityId;
    }
    function setUniversityId($universityId) {
        $this->universityId = $universityId;
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
