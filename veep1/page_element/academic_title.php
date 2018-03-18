<div class="form-group" style="line-height: 30px">
<div class="label">
    <label for="name">职称</label>
</div>
<?
$ELEMENT_NAME  = "ACADEMIC_TITLE";
// radio 之间的间距
$ELEMENT_DISTANCE  = 133;

require_once("config/CheckerOfCourse.php");
require_once("config/MetaDataGenerator.php");
$IDEN_1 = MetaDataGenerator::ACADEMIC_TITLE_ASSISTANT;    
$IDEN_2 = MetaDataGenerator::ACADEMIC_TITLE_LECTURER ;    
$IDEN_3 = MetaDataGenerator::ACADEMIC_TITLE_ASSOCIATE_PROFESSOR;
$IDEN_4 = MetaDataGenerator::ACADEMIC_TITLE_PROFESSOR    ;

$NAME_1 = MetaDataGenerator::ACADEMIC_TITLE_NAME_ASSISTANT;  
$NAME_2 = MetaDataGenerator::ACADEMIC_TITLE_NAME_LECTURER;  
$NAME_3 = MetaDataGenerator::ACADEMIC_TITLE_NAME_ASSOCIATE_PROFESSOR;
$NAME_4 = MetaDataGenerator::ACADEMIC_TITLE_NAME_PROFESSOR;  

$arrayDataCode = array($IDEN_1, $IDEN_2, $IDEN_3, $IDEN_4);//()
$arrayDataName = array($NAME_1, $NAME_2, $NAME_3, $NAME_4);//()

$cnt = 1;
foreach ($arrayDataCode as $data){
    $left = 133 + $ELEMENT_DISTANCE * ($cnt - 1);
    $name = $arrayDataName[$cnt - 1];
    $str =  '<label for="name">' . $name;
    $checked = "";
    if (isset($ACADEMIC_TITLE)) {//修改用户时，data 数据有数值， 哪个选中哪个打点
        if($data==$ACADEMIC_TITLE) {
            $checked = "checked";
        }
    } else {//添加用户时，data 数据是空的，学生 上打点
        if($data==$IDEN_1) {
            $checked = "checked";
        }
    }
/**/
    $str .= '：<input '. $checked .' type="radio" id="'. $ELEMENT_NAME.'" name="'.$ELEMENT_NAME.'" value="'. $data .'" style="margin-right: 40px;margin-left: 10px;" onclick="selectASTICKY(this)"></label>';
    echo $str;
    $cnt++;
}
?>
</div>
