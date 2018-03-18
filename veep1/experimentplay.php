<?
require_once("config/config.php");
require_once("config/dsql.php");
require_once("config/MetaDataGenerator.php");

$dsql = new DSQL();
if(@$auth != 'login'){
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<body>
<script>alert('请先登录');
    window.location = "login.php"</script>
<?
exit;
}
if($auth_pid!=3&$courseid>10){

$SQL = "SELECT L.ID,L.STUDENT_LIMIT as payquantity FROM LESSON  L,  courses  cp  where  L.ID = '$courseid' and L.STATUS=0 AND L.SHOWN=1  AND L.COURSE_ID=cp.id and   cp.status=0 and cp.isshow=1  and  L.START_TIME < NOW( ) AND  L.END_TIME > NOW( )  and  cp.starttime < NOW( ) and  cp.endtime > NOW( ) ";


//echo $SQL . "\n";
$dsql->query($SQL);

if(!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<body>
<script>alert('课程 不存在 或者 课程不再开放时间');
    window.location = "lesson.php?lessonId=<?print($courseid)?>"</script>
<?
exit;

}

$SQL = "SELECT id FROM coursesexperiment  
	where coursesid = '$courseid' and COURSE_TYPE=".MetaDataGenerator::COURSE_TYPE_KETANG." and experimentsid='$id' 
	and  starttime < NOW( ) and  endtime > NOW( )     ";
//echo $SQL . "\n";
$dsql->query($SQL);

if(!$dsql->next_record()){

?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">


</head>
<body>
<script>alert('实验不存在 或者 课程不再开放时间');
    window.location = "lesson.php?lessonId=<?print($courseid)?>"</script>
<?
exit;

}


    $SQL = "SELECT id FROM coursesuser  where status=0 and  coursesid = '$courseid' and userid='$auth_id' ";
    //echo $SQL . "\n";
    $dsql->query($SQL);

    if(!$dsql->next_record()){

    ?><!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    <script>alert('请加入课程再进入实验');
        window.location = "lesson.php?lessonId=<?print($courseid)?>"</script>
    <?
    exit;

    }
}
$SQL = "SELECT  *  from experiments where id = '$id'     ";
$dsql->query($SQL);
$i = $offset;
$dsql->next_record();
$i++;

$name = $dsql->f('name');
$content = $dsql->f('content');
$softfile = $dsql->f('softfile');
$reportfile = $dsql->f('reportfile');
$userid = $dsql->f('userid');
$status = $dsql->f('status');
$groupid = $dsql->f('groupid');
$img = $dsql->f('img');
$type = $dsql->f('type');
$difficulty = $dsql->f('difficulty');
if ($softfile == '') {
    $softfile = "u3d/${id}.unity3d";

}

if($fileid){
	$softfile = "u3d/$fileid.unity3d";
}
//echo $softfile;

//生成唯一标识符    
//sha1()函数， "安全散列算法（SHA1）"    
function create_unique()
{
    $data = "tokljfawreqoidld" . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']
        . time() . rand();
    return sha1($data) . md5(time() . $data);
//return md5(time().$data);    
//return $data;    
}

$token = create_unique();
// echo strlen($token);


$keyIndex[0][0] = 1;
$keyIndex[0][1] = 4;
$keyIndex[0][2] = 7;
$keyIndex[0][3] = 8;
$keyIndex[0][4] = 10;
$keyIndex[0][5] = 50;
$keyIndex[0][6] = 38;
$keyIndex[0][7] = 34;
$keyIndex[1][0] = 3;
$keyIndex[1][1] = 6;
$keyIndex[1][2] = 9;
$keyIndex[1][3] = 25;
$keyIndex[1][4] = 31;
$keyIndex[1][5] = 40;
$keyIndex[1][6] = 55;
$keyIndex[1][7] = 60;
$keyIndex[2][0] = 2;
$keyIndex[2][1] = 12;
$keyIndex[2][2] = 18;
$keyIndex[2][3] = 22;
$keyIndex[2][4] = 23;
$keyIndex[2][5] = 53;
$keyIndex[2][6] = 56;
$keyIndex[2][7] = 62;
$keyIndex[3][0] = 5;
$keyIndex[3][1] = 63;
$keyIndex[3][2] = 41;
$keyIndex[3][3] = 32;
$keyIndex[3][4] = 42;
$keyIndex[3][5] = 51;
$keyIndex[3][6] = 0;
$keyIndex[3][7] = 11;
$keyIndex[4][0] = 9;
$keyIndex[4][1] = 13;
$keyIndex[4][2] = 21;
$keyIndex[4][3] = 33;
$keyIndex[4][4] = 44;
$keyIndex[4][5] = 57;
$keyIndex[4][6] = 61;
$keyIndex[4][7] = 14;
$keyIndex[5][0] = 15;
$keyIndex[5][1] = 59;
$keyIndex[5][2] = 43;
$keyIndex[5][3] = 45;
$keyIndex[5][4] = 52;
$keyIndex[5][5] = 46;
$keyIndex[5][6] = 58;
$keyIndex[5][7] = 16;
$keyIndex[6][0] = 17;
$keyIndex[6][1] = 24;
$keyIndex[6][2] = 27;
$keyIndex[6][3] = 35;
$keyIndex[6][4] = 47;
$keyIndex[6][5] = 37;
$keyIndex[6][6] = 29;
$keyIndex[6][7] = 19;
$keyIndex[7][0] = 20;
$keyIndex[7][1] = 26;
$keyIndex[7][2] = 28;
$keyIndex[7][3] = 36;
$keyIndex[7][4] = 48;
$keyIndex[7][5] = 49;
$keyIndex[7][6] = 39;
$keyIndex[7][7] = 30;
/*
//用户id  课件id  付费老师id  课程老师id  打开时间  token
生成token

$
10

1000


id  int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     2  token   varchar(255)    utf8_general_ci     否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间    全文搜索 全文搜索
     3  datetime    int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     4  userid  int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     5  experimentsid   int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     6  feeuserid   int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     7  counter int(11)         否   无        修改 修改   删除 删除   浏览非重复值 (DISTINCT) 浏览非重复值 (DISTINCT)     主键 主键   唯一 唯一   索引 索引  空间 空间   全文搜索 全文搜索
     8  teacheruserid   int(11)

*/

$type = rand(0, 7);
$SQL = "insert into  userexperimenttoken(id,token,createtime,userid,experimentsid,feeuserid,counter,teacheruserid,coursesid,type)values(0,'$token',now(),'$auth_id' ,'$experimentsid','$feeuserid','0','$teacheruserid','$coursesid',$type);";
$dsql = new DSQL();
$dsql->query($SQL);
$time = time();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>虚拟实验工场-<? print($name) ?> </title>
    <script type='text/javascript' src='js/jquery.min.js'></script>
    <script type="text/javascript">
        <!--
        var unityObjectUrl = "js/UnityObject2.js";
        if (document.location.protocol == 'https:')
            unityObjectUrl = unityObjectUrl.replace("http://", "https://ssl-");
        document.write('<script type="text\/javascript" src="' + unityObjectUrl + '"><\/script>');
        -->

    </script>
    <script>

        //检测当前操作系统
        function  getSystemType() {
            var system = {
                win: false,
                mac: false,
                xll: false,
            };
            var ua = navigator.userAgent;
            // 检测平台
            var p = navigator.platform;
            system.win = p.indexOf('Win') == 0;
            system.mac = p.indexOf('Mac') == 0;
            system.xll = (p.indexOf('Xll') == 0 || p.indexOf('Linux') == 0);
            return system;
        }
        var curSystem=getSystemType();



    </script>
    <script type="text/javascript">
        <!--
        // 获取窗口宽度
        winHeight=1008;
        winWidth=567;

        if (window.innerWidth)
            winWidth = window.innerWidth;
        else if ((document.body) && (document.body.clientWidth))
            winWidth = document.body.clientWidth;
        // 获取窗口高度
        if (window.innerHeight)
            winHeight = window.innerHeight;
        else if ((document.body) && (document.body.clientHeight))
            winHeight = document.body.clientHeight;
        // 通过深入 Document 内部对 body 进行检测，获取窗口大小
        if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth)
        {
            winHeight = document.documentElement.clientHeight;
            winWidth = document.documentElement.clientWidth;
        }

        var config = {
            width: 1008,
            height: 567,
            params: {
				enableDebugging: "0",
				backgroundcolor: "ffffff",
                bordercolor: "000000",
                textcolor: "ffffff",
                logoimage: "img/beijing1.png",
                progressbarimage: "img/progress.png",
                progressframeimage: "img/progressbg.png" 
			}

        };
        function CloseWindow()
        {

                window.opener=null;
                window.open('','_self');
                window.close();

        }
        var u = new UnityObject2(config);

        jQuery(function () {

            var $missingScreen = jQuery("#unityPlayer").find(".missing");
            var $brokenScreen = jQuery("#unityPlayer").find(".broken");
            $missingScreen.hide();
            $brokenScreen.hide();

            u.observeProgress(function (progress) {
                switch (progress.pluginStatus) {
                    case "broken":
                        $brokenScreen.find("a").click(function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            u.installPlugin();
                            return false;
                        });
                        alert("插件缺失，请到下载中心下载安装"); 
						window.navigate("download.php");
                        break;
                    case "missing":
                        $missingScreen.find("a").click(function (e) {
                            e.stopPropagation();
                            e.preventDefault();
                            u.installPlugin();
                            return false;
                        });
                        alert("插件损坏，请到下载中心下载安装"); 
						window.navigate("download.php");
                        break;
                    case "installed":
                        $missingScreen.remove();
                        break;
                    case "first":
                        break;
                }
            });
            u.initPlugin(jQuery("#unityPlayer")[0], "<?print($softfile)?>");
        });
        function SendParas(arg) {
           <? $tokenUrl = $token . "&http://". $_SERVER['HTTP_HOST'] . "/token.php&" .$time ."&". $type; ?>
            var unity = u.getUnity();
			var tokenUrl = "<? print $tokenUrl ?>";
			
            unity.SendMessage("/Message", "parsePramas", tokenUrl);
        }
        -->
    </script>
    <style type="text/css">
        <!--
        body {
            font-family: Helvetica, Verdana, Arial, sans-serif;
            background-color: white;
            color: black;
            text-align: center;
        }

        a:link, a:visited {
            color: #000;
        }

        a:active, a:hover {
            color: #666;
        }

        p.header {
            font-size: small;
        }

        p.header span {
            font-weight: bold;
        }

        p.footer {
            font-size: x-small;
        }

        div.content {
            margin: auto;
            width: 1008px;
        }

        div.broken,
        div.missing {
            margin: auto;
            position: relative;
            top: 50%;
            width: 193px;
        }

        div.broken a,
        div.missing a {
            height: 63px;
            position: relative;
            top: -31px;
        }

        div.broken img,
        div.missing img {
            border-width: 0px;
        }

        div.broken {
            display: none;
        }

        div#unityPlayer {
            cursor: default;
            height: 730px;
            width: 1008px;
        }

        -->
    </style>
</head>
<body>
<div class="content">
    <div id="unityPlayer">
        <div class="missing">
            <a target="_blank" href="download.php">请到下载中心下载</a>
        </div>
        <div class="broken">
            <a target="_blank" href="download.php">请到下载中心下载</a>
        </div>
    </div>
</div>
<p class="footer"><a href="http://www.vrsygc.com/">虚拟实验工场</a><a> 版权所有</a></p>
</body>
</html>
