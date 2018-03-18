<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
require_once("Classes/Teacher.php");

class Lesson{
    //每页数据数量
    private $id = 0;//总记录数
    private $name = "";
    private $img  = "";
    private $introduction  = "";//
    private $studentAmount   = 0;//
    private $studentLimit   = 0;//
    private $teacherId = 0 ;//
    private $shown = "" ;//
    private $courseId = 0 ;//
    private $courseName = "" ;//
    private $startTime = "2017-01-01 00:00:00" ;//
    private $endTime = "2099-12:31 23:59:59" ;//
    private $lessonCode = "" ;//
    private $sortOrder = 1000;
    
    private $moocId = 0;
    private $moocUrl = "";

    private $teacher = "" ;//授课教师

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
/*返回数组，0号id，1号name，2号学生人数，3号图片，4号介绍*/
    function getTeacherId() {
        return $this->teacherId;
    }
    function setTeacherId($teacherId) {
        $this->teacherId = $teacherId;
    }

    function getShown() {
        return $this->shown;
    }
    function setShown($shown) {
        $this->shown = $shown;
    }
	/* 授课教师 */
    function getTeacher() {
        return $this->teacher;
    }
    function setTeacher($teacher) {
        $this->teacher = $teacher;
    }
	/* 学生上限，从 courses 表查询 */
    function getStudentLimit() {
        return $this->studentLimit;
    }
    function setStudentLimit($studentLimit) {
        $this->studentLimit = $studentLimit;
    }

    function getCourseId() {
        return $this->courseId;
    }
    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function getCourseName() {
        return $this->courseName;
    }
    function setCourseName($courseName) {
        $this->courseName = $courseName;
    }

    function getStartTime() {
        return $this->startTime;
    }
    function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    function getEndTime() {
        return $this->endTime;
    }
    function setEndTime($endTime) {
        $this->endTime = $endTime;
    }


    function getLessonCode() {
        return $this->lessonCode;
    }
    function setLessonCode($lessonCode) {
        $this->lessonCode = $lessonCode;
    }

    function getMoocId() {
        return $this->moocId;
    }
    function setMoocId($moocId) {
        $this->moocId = $moocId;
    }

    function getMoocUrl() {
        return $this->moocUrl;
    }
    function setMoocUrl($moocUrl) {
        $this->moocUrl = $moocUrl;
    }

    function getSortOrder() {
        return $this->sortOrder;
    }
    function setSortOrder($sortOrder) {
        $this->sortOrder = $sortOrder;
    }

    function toString() {
    }
}
?>
