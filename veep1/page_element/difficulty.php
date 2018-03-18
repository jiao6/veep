<div class="form-group" style="line-height: 30px">
<div class="label">
    <label for="name">难度系数</label>
</div>
<?
$ELEMENT_NAME = "difficulty";

require_once("config/MetaDataGenerator.php");
$SIMPLE =     MetaDataGenerator::DIFFICULTY_NAME_SIMPLE;
$COMMON =     MetaDataGenerator::DIFFICULTY_NAME_COMMON;
$DIFFICULT  = MetaDataGenerator::DIFFICULTY_NAME_DIFFICULT;
$arrayData = array($SIMPLE, $COMMON, $DIFFICULT);//()

$cnt = 1;
foreach ($arrayData as $data){
    $left = 133 + 211 * ($cnt - 1);
    $name = MetaDataGenerator::getDifficultyImageFromChar($data);
    $str =  '<label for="name">' . $name;
    // style="left:' .$left .'px;"
    $checked = "";
    if (isset($difficulty)) {//修改用户时，data 数据有数值， 哪个选中哪个打点
        if($data==$difficulty) {
            $checked = "checked";
        }
    } else {//添加用户时，data 数据是空的， 否 上打点
        if($data==$SIMPLE) {
            $checked = "checked";
        }
    }
/**/
    $str .= '<input '. $checked .' type="radio" id="'. $ELEMENT_NAME.'" name="'.$ELEMENT_NAME.'" value="'. $data .'" style="margin-right: 40px;margin-left: 10px;"></label>';
    echo $str;
    $cnt++;
}

?>
</div>
