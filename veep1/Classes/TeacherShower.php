<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
require_once("config/Pagination.php");
require_once("Classes/Teacher.php");
require_once("Classes/Lesson.php");

class TeacherShower{
    private $DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;
    private $DEFAULT_LESSON_IMAGE = CheckerOfCourse::DEFAULT_LESSON_IMAGE;
    private $DEFAULT_TEACHER_IMAGE =CheckerOfCourse::DEFAULT_TEACHER_IMAGE;

    private $teacherId = 0;//总记录数
    private $teacherName   = "";
    private $teacher ;//用来显示教师
    private $pagination ;//分页器
    private $lessonList ;//课堂列表

    /* 构造函数。  */
    function __construct($teacherId = 0){
        /* $teacherId 没有数值或小于等于 0，啥都不干 */
        if (!$teacherId) return;
        if ($teacherId <= 0) return;
        $this->teacherId = $teacherId;
        /*
        echo "teacherId=" . $teacher->getId() . "; teacherName=" . $teacher->getName() . "; img=" . $teacher->getImg();
        $this->name = $teacherName;
        $this->lessonList = $lessonList;
        $this->pagination= $pagination;
        */

    }
    function getTeacherInfo() {
    	if ($this->teacherId <= 0) return;
        $sql = "select * from users where id=" . $this->teacherId;
        $dsql = new DSQL();
        $dsql->query($sql);
        if (!$dsql->next_record())
            exit;
        $this->teacherName = $dsql->f('truename');
        //$teacherInfo = array($this->teacherId, $this->teacherName);
        $teacher = new Teacher();
        $teacher->setId($this->teacherId);
        $teacher->setName($this->teacherName);
        $img = $dsql->f('userimg');
        $img = MetaDataGenerator::getImage($img, $this->DEFAULT_TEACHER_IMAGE);
        //echo $img . "<br/>";
        $teacher->setImg($img);
        $teacher->setIntroduction($dsql->f('content'));
        $userType = $dsql->f('usertype');
        $teacher->setUserType($userType);

        $teacher->setUniversityName($dsql->f('university'));
        $teacher->setUniversityId($dsql->f('university_id'));

		/*取得职称*/
		if ($userType == CheckerOfCourse::PID_TEACHER || $userType == CheckerOfCourse::PID_FEETEACHER) {
	        $sql = "select * from TEACHER 
	            where ID = ". $this->teacherId ."";
			$dsql->query($sql);
	        if ($dsql->next_record()) {
            	$ACADEMIC_TITLE = $dsql->f('ACADEMIC_TITLE');
	            $teacher->setAcademicTitleId($ACADEMIC_TITLE);
	            $str = MetaDataGenerator::getAcademicTitle($ACADEMIC_TITLE);
	            //echo $ACADEMIC_TITLE ."; name=". $str . "<br/>";
	            $teacher->setAcademicTitleName($str);
	        }
        }


        /*算出学生总数
        $sql = "select sum(STUDENT_AMOUNT) as AMOUNT from LESSON where TEACHER_ID = ". $this->teacherId ." and STATUS=". MetaDataGenerator::STATUS_EFFECTIVE ." and SHOWN=". MetaDataGenerator::SHOWN_YES;
        $dsql->query($sql);
        if ($dsql->next_record())
            $studentAmount = $dsql->f('AMOUNT');*/
        //echo "teacherId=" . $teacher->getId() . "; teacherName=" . $teacher->getName() . "; img=" . $teacher->getImg() . "; sql=" . $sql;

        /*获得课堂列表，下线的课堂也要统计*/
        $studentAmount = 0;
        $lessonAmount  = 0;
        $STRING_TRUNCATE_LENGTH = 12;
        
        $oldCourseId = array();
        $lessonList = array();
        //所属课程被删除了也不行
        $sql = "select l.*, c.isshow from LESSON l, courses c  
            where l.COURSE_ID = c.id AND 
              l.TEACHER_ID = ". $this->teacherId .""
            ." and l.STATUS=". MetaDataGenerator::STATUS_EFFECTIVE 
            ." and c.status=". MetaDataGenerator::STATUS_EFFECTIVE 
            //." and SHOWN=". MetaDataGenerator::SHOWN_YES
            ." ORDER BY l.SHOWN DESC, l.ID DESC ";
        $dsql->query($sql);

        while ($dsql->next_record()) {
            $studentAmount += $dsql->f('STUDENT_AMOUNT');
            $lessonAmount++;

            $ID = $dsql->f('ID');
            $NAME = $dsql->f('NAME');
            //为了统计课程数量，先预设 oldCourseId=-1，如果 newCourseId 并不存在于 oldCourseId 中，则塞进去
            $newCourseId = $dsql->f('COURSE_ID');
            if (!in_array($newCourseId, $oldCourseId)) {
            	$oldCourseId[] = $newCourseId;
            }
            
            //$NAME = MetaDataGenerator::getShortenString($NAME, $STRING_TRUNCATE_LENGTH);
            //echo $NAME . "<br/>";
            $STUDENT_AMOUNT = $dsql->f('STUDENT_AMOUNT');
            $IMG_URL = $dsql->f('IMG_URL');
            $IMG_URL = MetaDataGenerator::getImage($IMG_URL, $this->DEFAULT_LESSON_IMAGE);
            $INTRODUCTION = $dsql->f('INTRODUCTION');

            $lesson = new Lesson();
            $lesson->setId($ID);
            $lesson->setName($NAME);
            $lesson->setStudentAmount($STUDENT_AMOUNT);
            $lesson->setImg($IMG_URL);
            //echo "IMG_URL=" . $lesson->getImg() . "<br/>";
            $lesson->setIntroduction($INTRODUCTION);

            $STATUS = $dsql->f('STATUS');
            $SHOWN  = $dsql->f('SHOWN');
            $isshow  = $dsql->f('isshow');//课程的上线状态
            //两者相乘为零，说明课程或课堂是 0/下线，相乘为 1 ，说明都出于在线状态
            $lesson->setShown($SHOWN * $isshow);

            //$lessonList[] = array($ID, $NAME, $STUDENT_AMOUNT, $IMG_URL, $INTRODUCTION, $lesson);
            $lessonList[] = $lesson;

        }
        $teacher->setStudentAmount($studentAmount);
        $teacher->setLessonAmount($lessonAmount);
        $teacher->setLessonList($lessonList);

        $courseAmount  = sizeof($oldCourseId);
        $teacher->setCourseAmount($courseAmount);

        $this->teacher = $teacher;
        $sql = self::updateTeacher($teacher);
        //print_r($oldCourseId);
        //echo "sql: " . $sql . "<br/>";
        $dsql->query($sql);
        return $this->teacher;
    }
    /* 修改教师的各个参数 */
	public static function updateTeacher($teacher) {
		if (!isset($teacher)) return;
		if (empty($teacher)) return;
		$teacherId  = $teacher->getId();
		$teacherName  = $teacher->getName();
		$universityId  = $teacher->getUniversityId();
		$universityName  = $teacher->getUniversityName();
		
		$studentAmount  = $teacher->getStudentAmount();
		$lessonAmount  = $teacher->getLessonAmount();
		$courseAmount  = $teacher->getCourseAmount();
		
		/**/
		if ($lessonAmount > 0) {
			$updateLessonAmount = ", LESSON_AMOUNT=$lessonAmount";
		}
		if ($studentAmount > 0) {
			$updateStudentAmount = ", STUDENT_AMOUNT=$studentAmount";
		}
		if ($courseAmount > 0) {
			$updateCourseAmount = ", COURSE_AMOUNT=$courseAmount";
		}
		
		$sql = "UPDATE TEACHER SET 
			NAME='$teacherName', UNIVERSITY_ID='$universityId', UNIVERSITY_NAME='$universityName', UPDATE_TIME=now()" . 
			$updateLessonAmount . $updateStudentAmount . $updateCourseAmount . 
			" where ID=" . $teacherId . ";\n\r";
		//echo $sql . "<br/>";
		return $sql;        
				
		
	}
    function getTeacherId() {
        return $this->teacherId;
    }
    function setTeacherId($teacherId) {
        $this->teacherId = $teacherId;
    }

    function getName() {
        return $this->name;
    }
    function setname($teacherName) {
        $this->name = $teacherName;
    }

	/* sticky0 普通，10 置顶；pageNo 页号，从1 开始；pageSize每页显示数量；universityId大学id，如果传入并大于零则按大学查询 */
    function getStickyTeacherList($sticky = MetaDataGenerator::STICKY_NO, $pageNo = 1, $pageSize=Pagination::DEFAULT_PAGE_SIZE_DEFAULT, $universityId=0) {
    	if (!isset($pageNo) || !$pageNo || $pageNo < 1) {//pageNo 没赋值或小于1，赋值为 1
    		$pageNo = 1;
    	}
    	if (!isset($pageSize) || !$pageSize || $pageSize < 1) {//pageSize 没赋值或小于1，赋值为 40
    		$pageSize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
    	}
    	$isSticky = $sticky == MetaDataGenerator::STICKY_YES;//判断是不是置顶老师列表
        $STRING_TRUNCATE_LENGTH = 5;
        $queryUniversity = "";
        if ($universityId && $universityId > 0) {
        	$queryUniversity = " AND u.university_id=". $universityId;
        }
        //排序值低于 0 的，负数，不显示
        $sql = "select u.id, u.truename, u.university_id, u.university, u.userimg, u.content,
            t.ACADEMIC_TITLE, t.STICKY, t.SORT_ORDER

             FROM users u LEFT JOIN TEACHER t ON u.id=t.ID
            WHERE 1=1 AND u.status=".MetaDataGenerator::STATUS_EFFECTIVE.
            " AND(u.usertype=". CheckerOfCourse::PID_TEACHER ." OR u.usertype=". CheckerOfCourse::PID_FEETEACHER .")
            AND IFNULL(t.STICKY, 0) = ". $sticky . " AND IFNULL(t.SORT_ORDER, 0) >0 " . $queryUniversity . "
             ORDER BY t.SORT_ORDER DESC, u.id";
        if (!$isSticky) {//普通教师列表需要分页器
        	$sqlcnt = MetaDataGenerator::generateCountSql($sql);//统计记录数的语句
	        $dsql = new DSQL();
	        $dsql->query($sqlcnt);
	        $dsql->next_record();
	        $recordCount = $dsql->f('allcount');
    		//echo "isSticky=" . $isSticky . "; recordCount=" . $recordCount . "; sqlcnt=" . $sqlcnt;
        	$this->pagination = new Pagination($recordCount, $pageSize, $pageNo);
        	/**/
        	$pageCount = $this->pagination->getPageCount();
        	$pageNo = $this->pagination->getPageNo();
        	$from = ($pageNo - 1) * $pageSize;
        	$sql .= " LIMIT ". $from .", " . $pageSize;//置顶教师显示全部，所以不加分页。
    	}
        //echo $sqlcnt . "<br/>";
        $teacherIds = "-1";//拼凑出教师 id 的字符串
        $teacherList = array();
        //echo $sql . "<br/>";
        $dsql = new DSQL();
        $dsql->query($sql);
        while($dsql->next_record()) {
            $ID = $dsql->f('id');
            $teacherIds .= ", '" . $ID . "'";//拼接出 teacher 的 id, 用来查询

            $NAME = $dsql->f('truename');
            $NAME = MetaDataGenerator::getShortenString($NAME, $STRING_TRUNCATE_LENGTH);
            //$STUDENT_AMOUNT = $dsql->f('STUDENT_AMOUNT');
            $IMG_URL = $dsql->f('userimg');
            $IMG_URL = MetaDataGenerator::getImage($IMG_URL, $this->DEFAULT_TEACHER_IMAGE);
            $INTRODUCTION = $dsql->f('content');
            $INTRODUCTION = MetaDataGenerator::getShortenString($INTRODUCTION, 222);
            $university_id = $dsql->f('university_id');
            $university = $dsql->f('university');
            $ACADEMIC_TITLE = $dsql->f('ACADEMIC_TITLE');

            $teacher = new Teacher();

            $teacher->setId($ID);
            $teacher->setName($NAME);
            $teacher->setImg($IMG_URL);
            $teacher->setIntroduction($INTRODUCTION);
            $teacher->setUniversityId($university_id);
            $teacher->setUniversityName($university);
            //$teacher->setStudentAmount($STUDENT_AMOUNT);
            $teacher->setAcademicTitleId($ACADEMIC_TITLE);
            $str = MetaDataGenerator::getAcademicTitle($ACADEMIC_TITLE);
            //echo $ACADEMIC_TITLE ."; name=". $str . "<br/>";
            $teacher->setAcademicTitleName($str);


            $teacherList[] = $teacher;
        }
        //$teacherIds = "";
        //echo $teacherIds . "<br/>";
        /*$sqlUpdateTeachers = ""; 拼凑出一个大量更新的 sql 语句来。*/
        /* 查询每个教师的学生数量 */
        $sqlAmount = "SELECT SUM(STUDENT_AMOUNT) AS totalAmount, l.TEACHER_ID, TEACHER_NAME
            FROM LESSON l, courses c 
            WHERE l.TEACHER_ID in (" . $teacherIds . ") AND l.COURSE_ID=c.id 
            AND l.STATUS=". MetaDataGenerator::STATUS_EFFECTIVE .
            " AND c.status=". MetaDataGenerator::STATUS_EFFECTIVE .
            " GROUP BY TEACHER_ID";
        //echo $sqlAmount . "<br/>";
        $dsql->query($sqlAmount);
        while($dsql->next_record()) {//查出这些人的学生数量
            $teacherId = $dsql->f('TEACHER_ID');
            $teacherName = $dsql->f('TEACHER_NAME');
            $totalAmount = $dsql->f('totalAmount');
            //echo $teacherName . ":" . $totalAmount ."<br/>";
            foreach($teacherList as $teacher) {
                if ($teacher->getId() == $teacherId) {
                    $teacher->setStudentAmount($totalAmount);
                    $sql = self::updateTeacher($teacher);
                    $dsql->query($sql);
                    //$sqlUpdateTeachers .= $sql;
                    //echo //$teacherId . ":" . $teacherName . ":" . $totalAmount . ";" . 
//                    $sqlUpdateTeachers . " <br/>";
                }
            }

        }
		//echo $sqlUpdateTeachers . " <br/>";
		//$dsql->query($sqlUpdateTeachers);

        return $teacherList;
    }
    function getLessonList() {
        return $this->lessonList;
    }
    function setLessonList($lessonList) {
        $this->lessonList = $lessonList;
    }


    function getPagination() {
        return $this->pagination;
    }
    function setPagination($pagination) {
        $this->pagination = $pagination;
    }


    function toString() {
    }
}
?>
