<?php
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");
require_once("config/CheckerOfCourse.php");
require_once("config/Pagination.php");
require_once("Classes/TeacherShower.php");
require_once("Classes/Teacher.php");
require_once("Classes/Lesson.php");
require_once("Classes/bean/Tag.php");

class LessonController{
    private $DEFAULT_COURSE_IMAGE = CheckerOfCourse::DEFAULT_COURSE_IMAGE;
    private $DEFAULT_TEACHER_IMAGE =CheckerOfCourse::DEFAULT_TEACHER_IMAGE;
    private $DEFAULT_LESSON_IMAGE = CheckerOfCourse::DEFAULT_LESSON_IMAGE;

    private $teacherId = 0;//总记录数
    private $teacherName   = "";
    private $teacher ;//用来显示教师
    private $pagination ;//分页器
    private $lessonList = array();//课堂列表
    private $tagList = array();//课堂列表

    /* 构造函数。  */
    function __construct(){
        /* $teacherId 没有数值或小于等于 0，啥都不干 */
        //print_r(getallheaders());
        //echo $_REQUEST['lessonId'];//能得到 request 中的参数
        //echo "auth_pid=" . $auth_pid; 得不到sesson 的参数
    }
    function setParameters($auth_id, $auth_pid) {
    	return;
    }
	function load($lessonId) {
		if (empty($lessonId) || $lessonId <=0) return;
        $SQL = "SELECT  l.*, u.email  from LESSON l, users u where l.id =$lessonId and l.TEACHER_ID=u.id ";
        //echo $SQL . "<br/>";
		$lesson = new Lesson();
		//$teacher = new Teacher();
        $dsql = new DSQL();
        $dsql->query($SQL);
        if (!$dsql->next_record()) return;//记录不存在则退出。
        $lesson->setId($lessonId);
        $lesson->setName($dsql->f('NAME'));
        $lesson->setMoocId($dsql->f('MOOC_ID'));
        $lesson->setMoocUrl($dsql->f('MOOC_URL'));
        
        $lesson->setStartTime($dsql->f('START_TIME'));
        $lesson->setEndTime($dsql->f('END_TIME'));
        $IMG_URL = MetaDataGenerator::getImage($dsql->f('IMG_URL'), $this->DEFAULT_LESSON_IMAGE);
        $lesson->setImg($IMG_URL);
        $lesson->setIntroduction($dsql->f('INTRODUCTION'));
        $lesson->setLessonCode($dsql->f('CODE'));
        $lesson->setSortOrder($dsql->f('SORT_ORDER'));
        $lesson->setTeacherId($dsql->f('TEACHER_ID'));
		/*
        $STUDENT_LIMIT = $dsql->f('STUDENT_LIMIT');
        $ASSIGNER_ID = $dsql->f('ASSIGNER_ID');
        $CREATE_TIME = $dsql->f('CREATE_TIME');
        $UPDATE_TIME = $dsql->f('UPDATE_TIME');
        */
        $lesson->setCourseName($dsql->f('email'));//课程名称放email
		
		return $lesson;
	}
	function updateTags($objectId, $tagNames, $objectType=MetaDataGenerator::OBJECT_TYPE_LESSON) {
		if (empty($objectId) || $objectId <=0) return;
		//echo "objectType=" . $objectType . "; objectId=" . $objectId . "; tagNames=" . $tagNames;
		$tagNameList = explode("||", $tagNames);
		$tagNameList2 = array();//用来存放去掉缺省标签之外的其他标签
		$default_tagNames = array();
		//print_r($tagNameList) . "<br/>";

		if ($objectType == MetaDataGenerator::OBJECT_TYPE_LESSON) {
			$lesson = $this->load($objectId);
			$name = $lesson->getName();
			$teacherId = $lesson->getTeacherId();
			//echo "teacherId=" . $lesson->getTeacherId();
			$teacherShower = new TeacherShower($teacherId);
			$teacher = $teacherShower->getTeacherInfo();
			$teacherName = $teacher->getName();
			$universityName = $teacher->getUniversityName();
			/* 拿到 课堂名称、教师名字、所属大学 3 个属性 */
			$tag = self::saveOrUpdateDataTag($name, MetaDataGenerator::TAG_TYPE_NAME, $objectId, MetaDataGenerator::OBJECT_TYPE_LESSON, MetaDataGenerator::TAG_STATUS_SELF);
			$dtId_1 = $tag->getSortOrder();
			$tag = self::saveOrUpdateDataTag($teacherName, MetaDataGenerator::TAG_TYPE_TEACHER_NAME, $objectId, MetaDataGenerator::OBJECT_TYPE_LESSON, MetaDataGenerator::TAG_STATUS_SELF);
			$dtId_2 = $tag->getSortOrder();
			$tag = self::saveOrUpdateDataTag($universityName, MetaDataGenerator::TAG_TYPE_UNIVERSITY_NAME, $objectId, MetaDataGenerator::OBJECT_TYPE_LESSON, MetaDataGenerator::TAG_STATUS_SELF);
			$dtId_3 = $tag->getSortOrder();
			$default_dtIds = $dtId_1 . ", " . $dtId_2 . ", " . $dtId_3;
			$default_tagNames = array($name, $teacherName, $universityName);
			//print_r($default_tagNames) . "<br/>";
			//echo "teacherName=" . $teacherName . "; universityName=" . $universityName . "; name=" . $name . "; default_dtIds=" . $default_dtIds;
		}
		//删除缺省标签之外的全部标签
		$sql = "delete from TAG_OF_DATA where OBJECT_ID=$objectId and OBJECT_TYPE=$objectType and ID not in ($default_dtIds)";
		//echo "sql=" . $sql;
		$dsql = new DSQL();
        $dsql->query($sql);
		$cnt = 0;
		//从 tagNameList 中去掉入库的缺省标签
		foreach($tagNameList as $elem) {
			if (in_array($elem, $default_tagNames)){
			} else {//
				$tagNameList2[] = $elem;
			}
		}
			//print_r($tagNameList2) . "<br/>";
		/* 假定该数据除了缺省标签之外，还有 2 个标签，a, c，上传 a, b, d。上传之后，删除 a, c 的记录，b, d 是新增的 */
        foreach($tagNameList2 as $elem) {
        	$sortOrder = MetaDataGenerator::DEFAULT_SORT_ORDER - $cnt * 10;
        	self::saveOrUpdateDataTag($elem, MetaDataGenerator::TAG_TYPE_MISCELLANEOUS, 
        		$objectId, $objectType, 
        		$dataTagStatus=MetaDataGenerator::STATUS_EFFECTIVE, $sortOrder);
        	$cnt++;
        }
        $info[0] = "{\"result\":\"SUCCESS\", \"message\":\" 恭喜您，修改课堂【".$objectId. " 号课堂 " .$name."】的标签成功！  \"}";//更新则返回 SUCCESS
        echo ('{"data":['.implode(",",$info).']}');
	}
	public static function saveOrUpdateDataTag($tagName, $tagType=MetaDataGenerator::TAG_TYPE_NAME, 
		$objectId, $objectType=MetaDataGenerator::OBJECT_TYPE_LESSON, 
		$dataTagStatus=MetaDataGenerator::STATUS_EFFECTIVE, $sortOrder=MetaDataGenerator::DEFAULT_SORT_ORDER) {
		if (empty($tagName)) return;
		$tagName = trim($tagName);
		if (strlen($tagName) < 1) return;
		$tag = new Tag();
		$sql = "select * from TAGS where NAME='$tagName'"; //STATUS = MetaDataGenerator::STATUS_EFFECTIVE		暂时不考虑状态
		//echo "sql=" . $sql;
		$tagExisted = true;
		$dsql = new DSQL();
        $dsql->query($sql);
        if ($dsql->next_record()) {
        	//echo "yes"."<br/>";
        	$tag->setId($dsql->f('ID'));
			$tag->setName($dsql->f('NAME'));
			$tag->setAttachAmount($dsql->f('ATTACH_AMOUNT'));
			$tag->setTagType($dsql->f('TYPE'));
    	} else {
    		$tagExisted = false;
    		//只要添加标签，添加量就有了 1 次
    		$sql = "INSERT INTO TAGS (NAME, TYPE, CREATE_TIME, ATTACH_AMOUNT, STATUS) VALUES('$tagName', $tagType, now(), 1, $dataTagStatus)";
        	//echo $sql . "<br/>";
    		$dsql->query($sql);
    		$dsql->query("select last_insert_id() as ID from TAGS");
    		$dsql->next_record();
        	$tag->setId($dsql->f('ID'));
			$tag->setName($tagName);
			//$tag->setAttachAmount($dsql->f('ATTACH_AMOUNT'));
			$tag->setTagType($tagType);
    	}
    	/* 确定该标签是否添加在数据对象上 */
    	$tagId = $tag->getId();
		$sql = "select ID from TAG_OF_DATA where OBJECT_ID=$objectId AND OBJECT_TYPE=$objectType AND TAG_ID=$tagId"; //STATUS = 0		暂时不考虑状态
        $dsql->query($sql);
        $dtId = 0;//获得 TAG_OF_DATA 的 id
        if ($dsql->next_record()) {//确定该数据对象是否添加了标签，更新状态
        	$dtId = $dsql->f('ID');
        	/*
			$sql = "update TAG_OF_DATA set STATUS=".MetaDataGenerator::STATUS_EFFECTIVE." where ID=$dtId"; //STATUS = 0		暂时不考虑状态
			$dsql->query($sql);*/
    	} else {//未添加数据标签则添加之，
    		$sql = "insert into TAG_OF_DATA (
    			OBJECT_ID, OBJECT_TYPE, TAG_ID, TAG_NAME, CREATE_TIME, SORT_ORDER) values(
    			$objectId, $objectType, $tagId, '$tagName', now(), $sortOrder)";
    		$dsql->query($sql);
    		$dsql->query("select last_insert_id() as ID from TAG_OF_DATA");
    		$dsql->next_record();
        	$dtId = $dsql->f('ID');
    	}
    	/* 旧标签修改粘贴次数。新标签刚创建，已经给了 1 次，不用再改了 */
    	if ($tagExisted) {//
    		$sql = "UPDATE TAGS t set t.ATTACH_AMOUNT = (select count(TAG_ID) from TAG_OF_DATA td where td.TAG_ID=t.ID) where t.ID=$tagId";
        //echo $sql . "<br/>";
    		$dsql->query($sql);
    	}
    	//TAG_OF_DATA 的 id 放进 SORT_ORDER 属性暂存
    	$tag->setSortOrder($dtId);
		return $tag;
	}
	/* 根据输入的文本，选择相似标签 */
	function getSimiliarTags($txt) {
		if (empty($txt)) return;
		$txt = trim($txt);
		if (strlen($txt) < 1) return;
		$sql = "select * from TAGS where NAME like '%".$txt."%' ORDER BY SORT_ORDER DESC, ATTACH_AMOUNT DESC, ID limit 0, " . Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
		//echo $sql . "<br/>";
        $dsql = new DSQL();
        $dsql->query($sql);
        $info = array();
        while ($dsql->next_record()) {
			$id = $dsql->f('ID');
			$name = $dsql->f('NAME');
			$image = $dsql->f('ATTACH_AMOUNT');
        	$info[] = "{\"id\":\"$id\", \"name\":\"$name\", \"ATTACH_AMOUNT\":\"$image\"}";
        }
        echo ('{"data": ['.implode(",",$info).'] }');
		
	}
    private $TAG_COLUMN_SIZE  = MetaDataGenerator::TAG_COLUMN_SIZE;//标签每行 4 个
    private $TAG_ROW_SIZE  = MetaDataGenerator::TAG_ROW_SIZE;//标签3行
	/* 100用户；200课程； 300课堂； 400实验 */
	function getDataTagList($objectId, $objectType = MetaDataGenerator::OBJECT_TYPE_LESSON) {
		if ($objectId <= 0 || $objectType <=0) return;
		$sql = "select td.*, t.TYPE, t.ATTACH_AMOUNT from TAG_OF_DATA td, TAGS t 
			where td.TAG_ID=t.ID AND td.OBJECT_ID=$objectId AND td.OBJECT_TYPE=$objectType 
			ORDER BY td.SORT_ORDER DESC;";
		$tagList = array();
        $dsql = new DSQL();
        $dsql->query($sql);
        while ($dsql->next_record()) {//记录不存在则退出。
        	$tag = new Tag();
			$tag->setId($dsql->f('ID'));
			$tag->setName($dsql->f('TAG_NAME'));
			$tag->setAttachAmount($dsql->f('ATTACH_AMOUNT'));
			$tag->setTagType($dsql->f('TYPE'));
			$tag->setObjectType($dsql->f('OBJECT_TYPE'));
			$tag->setStatus($dsql->f('STATUS'));
			$tagList[] = $tag;
		}
		$size = sizeof($tagList);
		$total = $this->TAG_COLUMN_SIZE * $this->TAG_ROW_SIZE;
		if ($size < $total) {//已有的标签少于 12 个，则填空
			for($i=0; $i<($total-$size); $i++) {
				$tagList[] = new Tag();
			}
			
		}
		return $tagList;
		
	}
	/* 读取推荐标签，按排序值、已加数量排序 */
	/* 获得数据对象的标签 。type：1010名字；1020任课教师；1030学校；
		2010难度；2050语言；2100设备；
		9999其他
		*/
	function getRecommendTagList() {//获得推荐标签列表
		$sql = "select * from TAGS where STATUS = 0 ORDER BY SORT_ORDER DESC, ATTACH_AMOUNT DESC, ID limit 0, " . Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
		//echo $sql . "<br/>";
		$tagList = array();
        $dsql = new DSQL();
        $dsql->query($sql);
        while ($dsql->next_record()) {//记录不存在则退出。
        	$tag = new Tag();
			$tag->setId($dsql->f('ID'));
			$tag->setName($dsql->f('NAME'));
			$tag->setAttachAmount($dsql->f('ATTACH_AMOUNT'));
			$tag->setTagType($dsql->f('TYPE'));
			$tagList[] = $tag;
		}
		return $tagList;
	}

    function getTeacherId() {
        return $this->teacherId;
    }
    function setTeacherId($teacherId) {
        $this->teacherId = $teacherId;
    }

	/* sticky0 普通，10 置顶；pageNo 页号，从1 开始；pageSize每页显示数量；universityId大学id，如果传入并大于零则按大学查询 */
    function getShownLessonList($sticky = MetaDataGenerator::STICKY_NO, $pageNo = 1, $pageSize=Pagination::DEFAULT_PAGE_SIZE_DEFAULT, $universityId=0) {
    	if (!isset($pageNo) || !$pageNo || $pageNo < 1) {//pageNo 没赋值或小于1，赋值为 1
    		$pageNo = 1;
    	}
    	if (!isset($pageSize) || !$pageSize || $pageSize < 1) {//pageSize 没赋值或小于1，赋值为 40
    		$pageSize = Pagination::DEFAULT_PAGE_SIZE_DEFAULT;
    	}
    	$isSticky = $sticky == MetaDataGenerator::STICKY_YES;//判断是不是置顶列表
        $STRING_TRUNCATE_LENGTH = 14;
        $queryUniversity = "";
        if ($universityId && $universityId > 0) {
        	$queryUniversity = " AND u.university_id=". $universityId;
        }
        $universityCondition = "";
        if (isset($universityId) && $universityId > 0) {
        	$universityCondition = " AND u.university_id=" . $universityId . " ";
        }
        //排序值低于 0 的，负数，不显示
        $from = 0;
        $sql = "select l.ID, l.NAME, COURSE_ID, IMG_URL, INTRODUCTION, 
        		START_TIME, END_TIME, ASSIGNER_ID, l.TEACHER_ID, l.CREATE_TIME, 
        		STUDENT_LIMIT, STUDENT_AMOUNT, SORT_ORDER, l.SHOWN, l.TEACHER_NAME, 
        		l.COURSE_NAME, 
        		u.university_id, u.university, c.payquantity, c.isshow 
        		 FROM LESSON l, users u, courses c  
        		WHERE l.TEACHER_ID=u.id AND l.COURSE_ID=c.id " .$universityCondition.
        			" AND l.STATUS=" . MetaDataGenerator::STATUS_EFFECTIVE . " AND c.status=". MetaDataGenerator::STATUS_EFFECTIVE .
        			" AND l.STICKY=".$sticky." AND IFNULL(l.SORT_ORDER, 1) > 0 " . 
        		" ORDER BY IFNULL(l.SORT_ORDER, 1) DESC, l.ID DESC";
        //为了测试使用，将来要删掉
        //$sql .= " LIMIT ". $from .", " . $pageSize;
        if (!$isSticky) {//普通教师列表需要分页器
        	$sqlcnt = MetaDataGenerator::generateCountSql($sql);//统计记录数的语句
	        $dsql = new DSQL();
	        $dsql->query($sqlcnt);
	        $dsql->next_record();
	        $recordCount = $dsql->f('allcount');
    		//echo "isSticky=" . $isSticky . "; recordCount=" . $recordCount . "; sqlcnt=" . $sqlcnt . "<br/>";
        	$this->pagination = new Pagination($recordCount, $pageSize, $pageNo);
        	/**/
        	$pageCount = $this->pagination->getPageCount();
        	$pageNo = $this->pagination->getPageNo();
        	$from = ($pageNo - 1) * $pageSize;
        	$sql .= " LIMIT ". $from .", " . $pageSize;//置顶教师显示全部，所以不加分页。
    	}
        //echo $sqlcnt . "<br/>";
        $objectIds = "-1";//拼凑出教师 id 的字符串
        $objectList = array();
        //echo $sql . "<br/>";
        $dsql = new DSQL();
        $dsql->query($sql);
        while($dsql->next_record()) {
            $ID = $dsql->f('ID');
            $objectIds .= ", '" . $ID . "'";//拼接出 teacher 的 id, 用来查询
            $NAME = $dsql->f('NAME');
            //$NAME = MetaDataGenerator::getShortenString($NAME, $STRING_TRUNCATE_LENGTH);
            $STUDENT_AMOUNT = $dsql->f('STUDENT_AMOUNT');
            $IMG_URL = $dsql->f('IMG_URL');
            $IMG_URL = MetaDataGenerator::getImage($IMG_URL, $this->DEFAULT_LESSON_IMAGE);
            $INTRODUCTION = $dsql->f('INTRODUCTION');
            $INTRODUCTION = MetaDataGenerator::getShortenString($INTRODUCTION, 222);

            $COURSE_ID = $dsql->f('COURSE_ID');
            $COURSE_NAME = $dsql->f('COURSE_NAME');
            $START_TIME = $dsql->f('START_TIME');
            $END_TIME = $dsql->f('END_TIME');
			$START_TIME = MetaDataGenerator::getTimeString($START_TIME);
			$END_TIME =   MetaDataGenerator::getTimeString($END_TIME);

            $shown = $dsql->f('SHOWN');
            $isshow = $dsql->f('isshow');
            $shown *= $isshow;//任何一个是 0 ，则乘积是 0，说明下线了。
			//echo 			$shown . ";" . $isshow;

            $studentLimit = $dsql->f('payquantity');//来自 courses 表
            
            $university_id = $dsql->f('university_id');
            $university = $dsql->f('university');
            $TEACHER_ID = $dsql->f('TEACHER_ID');
            $TEACHER_NAME = $dsql->f('TEACHER_NAME');
            $ACADEMIC_TITLE = $dsql->f('ACADEMIC_TITLE');
	
            $teacher = new Teacher();
            $lesson = new Lesson();

            $lesson->setId($ID);
            $lesson->setName($NAME);
            $lesson->setImg($IMG_URL);
            $lesson->setIntroduction($INTRODUCTION);
            $lesson->setStudentAmount($STUDENT_AMOUNT);
            $lesson->setStudentLimit($studentLimit);
            $lesson->setShown($shown);
            $lesson->setCourseId($COURSE_ID);
            $lesson->setCourseName($COURSE_NAME);
            $lesson->setStartTime($START_TIME);
            $lesson->setEndTime($END_TIME);
            
            
            $teacher->setId($TEACHER_ID);
            $teacher->setName($TEACHER_NAME);
            $teacher->setUniversityId($university_id);
            $teacher->setUniversityName($university);
            //$teacher->setStudentAmount($STUDENT_AMOUNT);
            $teacher->setAcademicTitleId($ACADEMIC_TITLE);
            //echo "aaa"."<br/>";
            $str = MetaDataGenerator::getAcademicTitle($ACADEMIC_TITLE);
            //echo $ACADEMIC_TITLE ."; name=". $str . "<br/>";
            $teacher->setAcademicTitleName($str);

			$lesson->setTeacher($teacher);
            $objectList[] = $lesson;
        }
        return $objectList;
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
