<?php
class MetaDataGenerator{
    //元数据图片所在目录，注意有 /
    const METADATA_IMAGE_DIR = "img/";
    //元数据图片扩展名，注意 有 .
    const METADATA_IMAGE_EXT = ".png";

    //截断字符串的长度
    const STRING_TRUNCATE_LENGTH = 8;

    //截断字符串的长度，在详细页中
    const STRING_TRUNCATE_LENGTH_OF_INFO_PAGE = 18;

    //截止时间
    const DEFAULT_END_DATE = "2099-12-31";
    const DEFAULT_END_DATETIME = "2099-12-31 23:59:59";

	//缺省排序
    const DEFAULT_SORT_ORDER = 1000;

    //是否收费用户
    const IS_FEE_YES = 1;
    const IS_FEE_NO  = 2;

    const STICKY_YES = 10;
    const STICKY_NO  = 0;
    /*
    */

    //课程： 0 课程 ； 1 课堂 ；
    const COURSE_TYPE_KECHENG = 0;
    const COURSE_TYPE_KETANG  = 1;

    const TAG_COLUMN_SIZE  = 4;//标签每行 4 个
    const TAG_ROW_SIZE  = 3;//标签共3行 
    
    const TAG_STATUS_SELF  = 2;//自己固有的标签 
    const TAG_STATUS_OTHER = 4;//附加的

    const TAG_TYPE_NAME  = 1010;//标签类型，名字
    const TAG_TYPE_TEACHER_NAME  = 1020;//标签类型，教师或主管人名字
    const TAG_TYPE_UNIVERSITY_NAME  = 1030;//标签类型，所属大学的名字
    const TAG_TYPE_MISCELLANEOUS  = 9999;//标签类型，杂项

    const OBJECT_TYPE_USER  = 100;//数据类型，课堂 
    const OBJECT_TYPE_COURSE  = 200;//数据类型，课堂 
    const OBJECT_TYPE_LESSON  = 300;//数据类型，课堂 
    const OBJECT_TYPE_EXPERIMENT = 400;//数据类型，课堂 

    public static function getCourseTypeImage($intInput){
        $width = 40;
            //echo "input ". $intInput;
        $title = "课程";
        if ($intInput == 0) {//strcmp($intInput , "简单")
            $title = "课程";
            $imgName = "course_type_kecheng";
        } else if ($intInput == 1) {
            $title = "课堂";
            $imgName = "course_type_ketang";
        } else  {
        }
        $str = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $intInput = "<img src='". $str ."' width='". $width ."' alt='".$title."' title='".$title."' />";
        return $intInput;
    }

    /* 简单，一般，难。目前直接写文字，将来可能要放 id，为此定义一个 6 个元素的数组 */
    const DIFFICULTY_NAME_SIMPLE = "简单";
    const DIFFICULTY_NAME_COMMON = "一般";
    const DIFFICULTY_NAME_DIFFICULT = "难";

    const DIFFICULTY_CODE_SIMPLE = 10;
    const DIFFICULTY_CODE_COMMON = 20;
    const DIFFICULTY_CODE_DIFFICULT = 30;

    public static function getDifficultyImageFromChar($charInput){
        $width = 30;
            //echo "input ". $charInput;
        $title = $charInput;
        if ($charInput == "简单") {//strcmp($charInput , "简单")
            $imgName = "deficulty_level_easy";
        } else if ($charInput == "一般") {
            $imgName = "deficulty_level_common";
        } else if ($charInput == "难") {
            $imgName = "deficulty_level_difficult";
        }
        $charInput = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $charInput = "<img src='". $charInput ."' width='". $width ."' alt='".$title."' title='".$title."' />";
        return $charInput;
    }
    //试验类型： 演示、 验证 、设计
    const EXPERIENCE_NAME_YANSHI =   "演示型";
    const EXPERIENCE_NAME_YANZHENG = "验证型";
    const EXPERIENCE_NAME_SHEJI =    "设计型";
    public static function getExperienceImageFromChar($charInput){
        $width = 30;
            //echo "input ". $charInput;
        $title = $charInput;
        if ($charInput == "演示型") {//strcmp($charInput , "简单")
            $imgName = "expericence_type_yanshi";
        } else if ($charInput == "验证型") {
            $imgName = "expericence_type_yanzheng";
        } else if ($charInput == "设计型") {
            $imgName = "expericence_type_sheji";
        }
        $charInput = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $charInput = "<img src='". $charInput ."' width='". $width ."' alt='".$title."' title='".$title."' />";
        return $charInput;
    }
    const SHOWN_YES = 1; //在线
    const SHOWN_NO  = 0; //下线


    const STATUS_EFFECTIVE = 0;
    const STATUS_DELETED =   1;
    
    //试验类型： 已删除 、 有效
    public static function getStatusImageFromChar($number){
        $width = 30;
            //echo "input ". $charInput;
        if ($number == "0") {//strcmp($number , "简单")
            $title = "有效";
            $imgName = "status_effective";
        } else if ($number == "1") {
            $title = "已删除";
            $imgName = "status_deleted";
        } else if ($number == "2") {
            //$imgName = "expericence_type_sheji";
        }
        $charInput = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $charInput = "<img src='". $charInput ."' width='". $width ."' alt='".$title."' title='".$title."' />";
        return $charInput;
    }

    //用户类型： 1 学生 、 2 教师 、3 管理员 、 4 付费教师
    public static function getUserImageFromChar($number){
        $width = 60;
        $height = 20;
            //echo "input ". $charInput;
        if ($number == 1) {//strcmp($number , "简单")
            $title = "学生";
            $imgName = "user_type_student";
        } else if ($number == 2) {
            $title = "教师";
            $imgName = "user_type_common_teacher";
        } else if ($number == 3) {
            $title = "管理员";
            $imgName = "user_type_admin";
        } else if ($number == 4) {
            $title = "付费教师";
            $imgName = "user_type_fee_teacher";
        } else {
            $title = "其他";
            $imgName = "user_type_other";
        }
        $charInput = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $charInput = "<img src='". $charInput ."' width='". $width ."' height='". $height ."' alt='".$title."' title='".$title."' />";
        return $charInput;
    }
    /* $userId 当前用户，$creatorId 课堂建立者。
    自己创建分配别人的课堂，别人创建分配给我的课堂 */
    public static function getClassTypeFrom ($userId, $creatorId) {
        $width = 30;
        $height = 30;
        if ($userId == $creatorId) {
            $imgName = "ketang_toother";
            $title = "为别人创建的课堂";
        } else {
            $imgName = "ketang_fromother";
            $title = "别人为我创建的课堂";
        }
        $charInput = self::METADATA_IMAGE_DIR . $imgName . self::METADATA_IMAGE_EXT;
        $charInput = "<img src='". $charInput ."' width='". $width ."' height='". $height ."' alt='".$title."' title='".$title."' />";
        return $charInput;
    }
    const ACADEMIC_TITLE_ASSISTANT = 10;
    const ACADEMIC_TITLE_LECTURER =  20;
    const ACADEMIC_TITLE_ASSOCIATE_PROFESSOR = 30;
    const ACADEMIC_TITLE_PROFESSOR = 40;

    const ACADEMIC_TITLE_NAME_ASSISTANT = "助教";
    const ACADEMIC_TITLE_NAME_LECTURER =  "讲师";
    const ACADEMIC_TITLE_NAME_ASSOCIATE_PROFESSOR = "副教授";
    const ACADEMIC_TITLE_NAME_PROFESSOR = "教授";
	public static function getAcademicTitle($id = 0) {
		if ($id <= 0) return "";
		if ($id == self::ACADEMIC_TITLE_ASSISTANT)
			return self::ACADEMIC_TITLE_NAME_ASSISTANT;
		if ($id == self::ACADEMIC_TITLE_LECTURER)
			return self::ACADEMIC_TITLE_NAME_LECTURER;
		if ($id == self::ACADEMIC_TITLE_ASSOCIATE_PROFESSOR) {
			//echo "id=" . $id . "<br/>";
			return self::ACADEMIC_TITLE_NAME_ASSOCIATE_PROFESSOR;
		}
		if ($id == self::ACADEMIC_TITLE_PROFESSOR)
			return self::ACADEMIC_TITLE_NAME_PROFESSOR;
		
		return "";
	}
    
    public static function getShortenString($strInput, $length=self::STRING_TRUNCATE_LENGTH) {
        //$length = strlen($strInput);
        //echo "strInput=" . $strInput ."<br/>";
        if(empty($strInput)){
            return "";
        }
        $length2 = self::abslength($strInput);
        $str = $strInput;
        if ($length2 > $length) {
            $str = self::utf8_substr($strInput, 0, $length) . "…";
        }
        return $str;
        //return $length;
    }

    /**
    * 可以统计中文字符串长度的函数
    * @param $str 要计算长度的字符串
    * @param $type 计算长度类型，0(默认)表示一个中文算一个字符，1表示一个中文算两个字符
    *
    */
    public static function abslength($str) {
        //echo "abslength=" . $str ."<br/>";
        if(empty($str)){
            return 0;
        }
        if(function_exists('mb_strlen')){
            return mb_strlen($str,'utf-8');
        } else {
            preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }/**/
    }
    /*
    utf-8编码下截取中文字符串,参数可以参照substr函数
    @param $str 要进行截取的字符串
    @param $start 要进行截取的开始位置，负数为反向截取
    @param $end 要进行截取的长度
    */
    function utf8_substr($str, $start=0) {
        //echo "str=" . $str ."; length=" . $length ."; STRING_TRUNCATE_LENGTH=" . self::STRING_TRUNCATE_LENGTH . "<br/>";
        //echo "str=" . $str .";" . self::STRING_TRUNCATE_LENGTH . "<br/>";
        if(empty($str)){
            return "";
        }
        if (function_exists('mb_substr')){
            if(func_num_args() >= 3) {
                $end = func_get_arg(2);
                return mb_substr($str,$start,$end,'utf-8');
            }
            else {
                mb_internal_encoding("UTF-8");
                return mb_substr($str,$start);
            }

        } else {
            $null = "";
            preg_match_all("/./u", $str, $ar);
            if(func_num_args() >= 3) {//3个参数或更多
                $end = func_get_arg(2);
                return join($null, array_slice($ar[0],$start,$end));
            } else {//2 个参数
                return join($null, array_slice($ar[0], $start));
            }
        }
    }
    /* 将时间戳变成日期
        Y - 年，四位数字; 如: "1999"
        y - 年，二位数字; 如: "99"
        z - 一年中的第几天; 如: "0" 至 "365"
        F - 月份，英文全名; 如: "January"
        m - 月份，二位数字，若不足二位则在前面补零; 如: "01" 至 "12"
        n - 月份，二位数字，若不足二位则不补零; 如: "1" 至 "12"
        M - 月份，三个英文字母; 如: "Jan"
        t - 指定月份的天数; 如: "28" 至 "31"
        d - 几日，二位数字，若不足二位则前面补零; 如: "01" 至 "31"
        j - 几日，二位数字，若不足二位不补零; 如: "1" 至 "31"
        h - 12 小时制的小时; 如: "01" 至 "12"
        H - 24 小时制的小时; 如: "00" 至 "23"
        g - 12 小时制的小时，不足二位不补零; 如: "1" 至 12"
        G - 24 小时制的小时，不足二位不补零; 如: "0" 至 "23"
        i - 分钟; 如: "00" 至 "59"
        s - 秒; 如: "00" 至 "59"
        S - 字尾加英文序数，二个英文字母; 如: "th"，"nd"
        U - 总秒数
        D - 星期几，三个英文字母; 如: "Fri"
        l - 星期几，英文全名; 如: "Friday"
        w - 数字型的星期几，如: "0" (星期日) 至 "6" (星期六)
        a - "am" 或是 "pm"
        A - "AM" 或是 "PM"
     */
    function getTimeString($str, $from=true, $format="y年m月d日") {
        if(empty($str)){return "洪荒";}
        if ($str == "0000-00-00 00:00:00") {
            if ($from)
                return "";
            else
                return "永远";
        }
        $s = strtotime($str);//date("y年n月j日",  $str);
        $s = date($format,  $s);
        return $s;
    }

	public static function time2second($remain){
		if ($remain <=0) return "$remain 秒";
	    //计算小时数
	    $hours = intval($remain/3600);
	    //计算分钟数
	    $remain = $remain%3600;
	    $mins = intval($remain/60);
	    //计算秒数
	    $secs = $remain%60;
	    $time ="$secs 秒";
		if ($mins > 0) 
	    	$time ="$mins 分 " . $time;
		if ($hours > 0) 
	    	$time ="$hours 小时 " . $time;
			
	    return $time;
	}
	public static function generateCountSql($sql){
		$leng = strlen ($sql);
		if ($leng < 5) return "";
		$tmp2 = stripos($sql, " From ");
		$tmp3 = stripos($sql, " order ");
		if (!$tmp3) {// sql 中没有 order 
			$tmp3 = $leng;
		} 
		//echo "from = " . $tmp2 . "; order = " . $tmp3 ."<br/>";
		$str1 = substr($sql, $tmp2, $tmp3 - $tmp2);
		return "select count(*) as allcount " . $str1;
	}
	/* 判断图片是否存在，不存在则提供缺省图片 */
	public static function getImage($img, $defaultImg){
		$leng = strlen ($img);
		if ($leng < 5) return $defaultImg;
	   	if ($img == $defaultImg) {//判断是否缺省头像
    		//echo "存在";
    	} else {//数据库里不是缺省头像，判断头像是否存在
    		//echo "不存在" . $img;
    		if(file_exists($img)) {
    			//echo "存在";
    		} else {//头像不存在，使用缺省头像
    			//echo "不存在" . $defaultImg . "<br/>";
    			$img = $defaultImg;
    		}
    	}
        return $img;
	}

}
?>
