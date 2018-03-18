<?
session_start();
if (!$auth) loginFalse();

function loginFalse()
{
    Header("Location:login.php");
}
if (!isset($dsql)) {
    $dsql = new DSQL();
}
require_once("config/config.php");
require_once("config/dsql.php");
error_reporting(0);


header("Content-type:application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition:attachment;filename=code$coursesid.xls");
//输出内容如下：
echo   " "."\t" ."courseid"."\t" ."code"."\t" ."createtime"."\t" ."endtime"."\t" ."day"."\t\r\n";
?><?


                $SQL = "SELECT id,coursesid,code,createtime,endtime,day FROM coursescode  where coursesid = '$coursesid' order by id desc     ";
                //echo $SQL . "\n";
                $dsql->query($SQL);
                $nbsp = $pernbsp = "|---------";
                $olddepth = 1;
                while ($dsql->next_record()) {
                    $id = $dsql->f('id');
                    $coursesid = $dsql->f('coursesid');
                    $code = $dsql->f('code');
                    $createtime = $dsql->f('createtime');
                    $endtime = $dsql->f('endtime');
                    $day = $dsql->f('day');


                    echo "$id\t$coursesid\t$code\t$createtime\t$endtime\t$day\r\n";
                }

?>