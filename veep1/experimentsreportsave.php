<?php
session_start();
error_reporting(0);

if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
$dsql = new DSQL();

require_once("config/config.php");
require_once("config/dsql.php");

if ($chemin) {

    @mkdir("data");
    @mkdir("$reportpath");
    @mkdir("$reportpath/xml/");
    @mkdir("$reportpath/xmlphp/");


    $cheminfile = basename($chemin, ".xml");
    $id = int($cheminfile);

    //filename="../$reportpath/xml/test1.xml"

    $reportxmlfile = "$reportpath/xml/test$id.xml";
    $reportxmlphpfile = "$reportpath/xmlphp/test$id.php";
    copy($_FILES["contenu"]["tmp_name"], $reportxmlfile);
    $xmlFilepath = "../xml/test1.xml";
    //$result = anysxml("$reportxmlfile", "tet.php?testID=" . $id, 0);//生成实验报告表单，提交php为“course_45_report.php”
 
    //echo '<div style="margin:auto auto;height: 40px;line-height:40px;text-align: center;width:75%;"><div style="width:25%;float:left"><label>邮箱:</label><label>'.$stuEmail.'</label></div><div style="width:25%;float:left"><label>姓名:</label><label>'.$stuName.'</label></div><div style="width:25%;float:left"><label>实验日期:</label><label>'.date("Y年m月d日h:i").'</label></div><div style="width:25%;float:left"><label>成绩：</label><label><font color="red"><b></b></font></label></div></div>';
    //echo $result[0];


    file_put_contents($reportxmlphpfile, $result[0]);

    $SQL = "update  experiments set `reportfile`='$reportxmlphpfile' where id = $id  ";
    //echo $SQL;

    if (!$dsql->query($SQL)) {
        //echo $SQL;
        echo "修改未成功";
        exit;
    } else {
        echo "修改成功";

    }
}


//半角全角转换函数
// 第一个参数：传入要转换的字符串
// 第二个参数：取0，半角转全角；取1，全角到半角
function SBC_DBC($str, $args2)
{
    $DBC = Array(
        '０', '１', '２', '３', '４',
        '５', '６', '７', '８', '９',
        'Ａ', 'Ｂ', 'Ｃ', 'Ｄ', 'Ｅ',
        'Ｆ', 'Ｇ', 'Ｈ', 'Ｉ', 'Ｊ',
        'Ｋ', 'Ｌ', 'Ｍ', 'Ｎ', 'Ｏ',
        'Ｐ', 'Ｑ', 'Ｒ', 'Ｓ', 'Ｔ',
        'Ｕ', 'Ｖ', 'Ｗ', 'Ｘ', 'Ｙ',
        'Ｚ', 'ａ', 'ｂ', 'ｃ', 'ｄ',
        'ｅ', 'ｆ', 'ｇ', 'ｈ', 'ｉ',
        'ｊ', 'ｋ', 'ｌ', 'ｍ', 'ｎ',
        'ｏ', 'ｐ', 'ｑ', 'ｒ', 'ｓ',
        'ｔ', 'ｕ', 'ｖ', 'ｗ', 'ｘ',
        'ｙ', 'ｚ', '－', '　', '：',
        '．', '，', '／', '％', '＃',
        '！', '＠', '＆', '（', '）',
        '＜', '＞', '＂', '＇', '？',
        '［', '］', '｛', '｝', '＼',
        '｜', '＋', '＝', '＿', '＾',
        '￥', '￣', '｀'
    );
    $SBC = Array( // 半角
        '0', '1', '2', '3', '4',
        '5', '6', '7', '8', '9',
        'A', 'B', 'C', 'D', 'E',
        'F', 'G', 'H', 'I', 'J',
        'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T',
        'U', 'V', 'W', 'X', 'Y',
        'Z', 'a', 'b', 'c', 'd',
        'e', 'f', 'g', 'h', 'i',
        'j', 'k', 'l', 'm', 'n',
        'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x',
        'y', 'z', '-', ' ', ':',
        '.', ',', '/', '%', '#',
        '!', '@', '&', '(', ')',
        '<', '>', '"', '\'', '?',
        '[', ']', '{', '}', '\\',
        '|', '+', '=', '_', '^',
        '$', '~', '`'
    );
    if ($args2 == 0) {
        return str_replace($SBC, $DBC, $str); // 半角到全角
    } else if ($args2 == 1) {
        return str_replace($DBC, $SBC, $str); // 全角到半角
    } else {
        return false;
    }
}

function trimall($str)
{
    $qian = array(" ", "　", "\t", "\n", "\r");
    return str_replace($qian, '', $str);
}

function int($s)
{
    return (int)preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $s);
}

?>