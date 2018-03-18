<?
$ELEMENT_TITLE = "实验类型";
$ELEMENT_NAME  = "type";

require_once("config/MetaDataGenerator.php");
$SIMPLE =     MetaDataGenerator::EXPERIENCE_NAME_YANSHI;
$COMMON =     MetaDataGenerator::EXPERIENCE_NAME_YANZHENG;
$DIFFICULT  = MetaDataGenerator::EXPERIENCE_NAME_SHEJI;
$arrayData = array($SIMPLE, $COMMON, $DIFFICULT);//()
?>
<div class="form-group" style="line-height: 30px">
<div class="label">
    <label for="name"><? echo $ELEMENT_TITLE ?></label>
</div>
<?
$cnt = 1;
foreach ($arrayData as $data){
    $left = 133 + 211 * ($cnt - 1);
    $name = MetaDataGenerator::getExperienceImageFromChar($data);
    $str =  '<label for="name">' . $name;
    $checked = "";
    if (isset($type)) {//修改用户时，data 数据有数值， 哪个选中哪个打点
        if($data==$type) {
            $checked = "checked";
        }
    } else {//添加用户时，data 数据是空的， 验证 上打点
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
