<?
require_once("config/MetaDataGenerator.php");
$YES = MetaDataGenerator::IS_FEE_YES;
$NO  = MetaDataGenerator::IS_FEE_NO;
$arrayIsFee = array($YES, $NO);//(1/是, 2/否)

$cnt = 1;
foreach ($arrayIsFee as $isFee){
    $left = 133 + 233 * ($cnt - 1);
    $name = "是";
    if ($cnt == 2)$name = "否";
    $str =  '<label for="isfee">' . $name;
    $checked = "";
    if (isset($isfee)) {//修改用户时，isFee 数据有数值， 哪个选中哪个打点
        if($isFee==$isfee) {
            $checked = "checked";
        }
    } else {//添加用户时，isFee 数据是空的， 否 上打点
        if($isFee==$NO) {
            $checked = "checked";
        }
    }

    $str .= '：<input '. $checked .' type="radio" id="isfee" name="isfee" value="'. $isFee .'"></label>';/**/
    echo $str;
    $cnt++;
}
?>
