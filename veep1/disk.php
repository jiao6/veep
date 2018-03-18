<?php

session_start();

  require_once("config/config.php");
if(!$auth) loginFalse();
//ob_start();
function loginFalse() {
	Header("Location:login.php");
}
        $dir= str_replace("\\\\","\\",$dir); 
 ob_start();   
$DEBUG = 1;
if($DEBUG) $timebegin = gettimeofday(); 
 
$BASEDIR = "/var/www/html/veep/u3d/";

if ($downfile) {
		if (!@is_file($BASEDIR.$downfile))
			echo"你要下的文件不存在";
        $filename = basename($BASEDIR.$downfile);
		$filename_info = explode('.', $filename);
		$fileext = $filename_info[count($filename_info)-1];
		header('Content-type: application/x-'.$fileext);
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Description: PHP3 Generated Data');
		readfile($BASEDIR.$downfile);
		exit;
}
  

if($action=="ren"){
echo"
把文件".$filename."改名为<form action=\"\" method=\"put\">
<input type=\"hidden\" name=\"dir\" value=\"$dir\">
<input type=\"hidden\" name=\"filename\" value=\"$filename\">
<input type=\"text\" name=\"ren_name\">
<input type=\"hidden\" name=\"action\" value=\"toren\">
<input type=\"submit\"  value=\"确定\"></form>
<a href=\"?dir=$dir\">--返回</a>
</body></html>";
exit;
                   }
elseif($action1=="ren"){
	echo"
		把目录".$filename."改名为<form action=\"\" method=\"put\">
		<input type=\"hidden\" name=\"dir\" value=\"$dir\">
		<input type=\"hidden\" name=\"filename\" value=\"$filename\">
		<input type=\"text\" name=\"ren_name\">
		<input type=\"hidden\" name=\"action1\" value=\"toren\">
		<input type=\"submit\"  value=\"确定\"></form>
		<a href=\"?dir=$dir\">--返回</a>
		</body></html>";
	exit;
}

if ($editfile!=""&&$action!='copy'&&$n=='1'||$newfile!="")//编辑文件
{
 if($newfile==""){
  $filename = "$BASEDIR$dir/$editfile";
  //echo $filename;
  $fd = @fopen( $filename, "r" );
  $contents = @fread($fd, filesize($filename));
  @fclose( $fd );
  $contents= str_replace("///textarea>","////textarea>",$contents); 
  }else{
  $editfile=$newfile;
  $filename = "$BASEDIR$dir/$editfile";
  }
  echo"
  <form name=\"edit\" method=\"post\" action=\"\">
  <tr> 
    <td class=\"t11\">
   当前文件:
    <input type=\"text\" name=\"editfilename\" value=$editfile> 可以修改文件名 <a href=\"?dir=$dir\">--返回</a>
    <br>复制:<input type=\"checkbox\" name=\"copy\" value=\"1\"> 注意:选中复制后,需给新文件改名,原文件内容不会变!<br>
    <textarea name=\"editfiletxt\" cols=\"110\" rows=\"26\" style=\"background:#ffffff;border: 1px solid #500000;color:#500000\">$contents</textarea>
    <br>
    <input type=\"hidden\" name=\"action\" value=\"copy\">
    <input type=\"submit\" value=\"提交\">
    <input type=\"reset\" value=\"重置\"></td></tr>
  </form></body></html>";
exit;
}
/////////////////
if($job=="upload")
{
 	for($i=0;$i<=$addcounts;$i++)
	{

	 


 
		$source=$_FILES["myfile$i"]['tmp_name'];
 
		$source_name=$_FILES["myfile$i"]['name'];
		 
		if(@$source!="") 
		{ 
			@$v=file_exists($BASEDIR.$dir); 
			if(!$v) 
			{ 
				mkdir(@$BASEDIR.$dir,0777); 
			} 
			@chmod($BASEDIR.$dir,0777);
			if(file_exists("$BASEDIR$dir/$source_name")=="1")
			{
				if($up_flag=="y")
				{
					@unlink($BASEDIR.$dir/$source_name);
					@copy($source,"$BASEDIR$dir/$source_name");
					echo $source_name."已覆盖上传<br>";
				}
				else{
					echo $source_name."已经存在 请重新上传!<br>";
				}
			}
			else
			{
				@copy($source,"$BASEDIR$dir/$source_name"); 
				echo $source_name."已上传<br>"; 
			}
		} 
	}//end for
}
////////////////
//编辑文件部分
if ($editfile!=""||$editfilename!="")
{
	if($editfile!=""){
		$filename1="$BASEDIR$dir/$editfile";
	}else{ 
		$editfile=$editfilename;
	}
	$filename="$BASEDIR$dir/$editfilename";
	if($copy!="1")
	{
		$editfiletxt=stripslashes("$editfiletxt");
		$editfiletxt= str_replace("////textarea>","///textarea>",$editfiletxt);
		writetofile("$filename","$editfiletxt");
		if($editfile!=$editfilename)
		{
			unlink($filename1);
		}
		if(file_exists($filename))
		{
			echo"写入成功";
		}
		elseif(!file_exists($filename))
		{
			echo"失败";
		}
	}
	if($copy=="1"&&$editfile==$editfilename)
	{
		echo"您要复制？必须给新文件改个名字，现在两个文件名相同，请<a 	href='javascript:history.back(1)'>返回</a>";
	}
	elseif($copy=="1"&&$editfile!=$editfilename)
	{
		echo"开始复制...<br>";
		$lastfilename="$BASEDIR$dir/$editfilename";
		$editfiletxt=stripslashes("$editfiletxt");
		$editfiletxt= str_replace("<////textarea>","<///textarea>",$editfiletxt);
		writetofile("$lastfilename","$editfiletxt");
		if(file_exists($lastfilename)){
			echo"写入成功";
		}elseif(!file_exists($lastfilename)){
			echo"失败";
		}
	}
}
?>


<?php
if(@$delfile!="")
{
if(file_exists($BASEDIR.$delfile))
{
@unlink($BASEDIR.$delfile);
}
else
{
$xx="1";
echo "文件已不存在<br>";
}
if(!file_exists($BASEDIR.$delfile)&&$xx!="1")
echo"删除成功";
else
echo"删除失败";
}
?>
<?php
if($deldir!="")
{
	$deldirs="$BASEDIR$dir/$deldir";
	if(file_exists($deldirs)) //是否为空目录
	{
		$mydir=@dir($deldirs);
		while(@$files=$mydir->read())
		{
		$i=$i+1;
		if($i>2)break;
		}
		$mydir->close(); //不关闭,后面就不能删除
	}
	if(file_exists("$deldirs")&&$i==2)
	{
		@chmod("$deldirs",0777);
		@rmdir("$deldirs");
		$xy="1";
	}
  if($i>2)
  echo"此目录里有文件,要将整个目录全部删除,请点--<a href='?dir=$dir&deldir=$deldir&deltree=$deldir'>删除整个目录</a>";
  if(!file_exists("$deldirs"))echo"目录不存在!";
  if($xy=="1")echo"删除完必!";
}


if($deltree!=""&&$deldir!="")
{
	if($deltree==$deldir)
	{
		$deldirs="$BASEDIR$dir/$deltree";
		if(file_exists("$deldirs"))
		{
			deltree($deldirs);
			echo"删除整个目录完必!<br>";
		}else{
			echo"文件已不存在!<br>";
		}
	}
	else{
		echo"错误操作!<a href='javascript:history.back(1)'>返回</a>";
	}
}
?>
<?
if($action=="toren"){
$oldname=$BASEDIR.$dir."/".$filename;
$newname=$BASEDIR.$dir."/".$ren_name;
 if(file_exists($newname)){
  echo "<script>alert(\"该文件(".$newname.")已经存在，请返回重输一个\");
	window.history.back(-1)</script>";exit;}
 if(@rename($oldname,$newname))
  echo $filename."改为".$ren_name."成功";
                   }
?>
<?
if($action1=="toren"){
$oldname=$BASEDIR.$dir."/".$filename;
$newname=$BASEDIR.$dir."/".$ren_name;
 if(is_dir($newname)){
 echo "<script>alert(\"该文件(".$newname.")已经存在，请返回重输一个\");
	window.history.back(-1)</script>";exit;}
 if(@rename($oldname,$newname))
  echo $filename."改为".$ren_name."成功";
                     }

?>
<script language="JavaScript" src="../function/function.js"></script>
<SCRIPT LANGUAGE="JavaScript">
var addStr1 = "<INPUT TYPE='file' NAME='myfile";
var addStr2 = "' size='14'><a href='javascript:cutfiles()'><img src='images/cut.gif' border='0'></a><br>";
var  addcount=0;
function addfile()
{
	addcount +=1;
	addfiles.innerHTML +="<INPUT TYPE='file' NAME='myfile"+addcount+"' size='14'><a href='javascript:cutfiles()'><img src='images/cut.gif' border='0'></a><br>";
	document.forms.up.addcounts.value=addcount;
}
function cutfiles()
{
	with(document.forms.up)
	{
		count=0;
		for(i=0;i<elements.length;i++)
		{
			if(elements[i].type=="file" && elements[i].value != "")
				count ++;
		}
		if(count >1)
		{
			alert("已经选择了文件，请直接提交！");
			return;
		}
	}
	try
	{
		index  = addfiles.innerHTML.lastIndexOf("<INPUT");
		addfiles.innerHTML = addfiles.innerHTML.substring(0,index);
		addcount -=1;
		document.forms.up.addcounts.value=addcount;
	}
	catch(e)
	{
		alert("已经没有可删除的选择框!");
	}
}
function checknew()
{
	var reg=document.forms.news.create.value;
	if(reg=="")
	{
		alert("请输入文件夹名字！");
		return false;
	}
	if(reg.indexOf("\ ") > -1)
		reg = reg.substring(0,reg.indexOf("\ "));
	reg = reg.replace("\\","/");
        if(reg.indexOf('/') > -1)
                reg = reg.substring(0,reg.indexOf('/'));
	document.forms.news.create.value =  reg;
}
function checkup()
{
	if(document.forms.up.myfile0.value=="")
	{
		alert("请先选择文件！");
		return false;
	}
}
</script><center>
<div align="center">
  <h3>平台管理 回复管理
  </h3>
</div>
<table><tr><td>
 <script language="JavaScript">
                <!--
                  //WriteTop2();
                    //-->
               </script>

<table>
<tr><td>
<FORM METHOD=POST ACTION="disk.php?job=create" onsubmit="return(checknew())" name="news">
			<INPUT TYPE="hidden" name="dir" value="<?print($dir)?>">
      <font size="2">新建文件夹:</font> 
      <INPUT TYPE="text" NAME="create" size="15" style="background-color: #FFFFFF; border-bottom:black 1px solid; border-left:black 1px solid; border-right: black 1px solid; border-top:black 1px solid; font-size: 9pt; height: 18px">
              <input type="image" border="0" name="imageField3" src="images/newbutton.gif" width="60" height="20">
       </FORM>
</td>

<td>&nbsp;&nbsp;</td><td>	   <form method="post" action="?action=newsfile&dir=<?echo"$dir";?>"> 文件: <input name="newfile" type="text" style="background-color: #FFFFFF; border-bottom:black 1px solid; border-left:black 1px solid; border-right: black 1px solid; border-top:black 1px solid; font-size: 9pt; height: 18px" size="15">
          <input type="image" border="0" name="imageField3" src="images/newbutton.gif" width="60" height="20">
      </form>
</td></tr>
</table>
<?
//echo $job;
//echo $BASEDIR.$path."".$create;
	if($job=="create"){
//echo $BASEDIR.$path."".$create;
		if(file_exists($BASEDIR.$dir."/".$create)){
			echo"该目录存在";
		}else{
			if(!is_dir($BASEDIR.$dir)){
				@mkdir($BASEDIR,0700);
				@mkdir($BASEDIR.$dir,0700);
			}
			$mkdirs =$BASEDIR.$dir."/".$create;
			//echo "<br>";
			//echo $BASEDIR.$path."".$create;
			@mkdir($mkdirs,0700);
			@chmod($mkdirs,0700);
			//echo $mkdirs;
			if(file_exists($mkdirs)){
				echo"目录创建成功";
			}else{
				echo"目录创建失败";
			}
			
		}
	}
	
		 ?>    
<TABLE WIDTH="100%"  BORDER="0" CELLSPACING="0" CELLPADDING="0"  BGCOLOR="666666" TEXT="#3300FF">
  
  <tr> 
            
    <td width="40%" class=TableHead2><font color="#FFFFFF" size="2"><strong>目录名称</strong></font></td>
    <td width="60%" class=TableHead2><strong><font color="#FFFFFF" size="2">操作</font></strong></td>
        <TR>
                      <TD colSpan=99 height=1><IMG border=0 height=1 
                        src="images/cccccc.gif" 
                        width="100%"></TD></TR>
      </table>
		<?
	@$dirs=opendir($BASEDIR.$dir);
	while (@$file=readdir($dirs)) {
		 @$b="$BASEDIR$dir/$file";
		 @$a=is_dir($b);
		 if($a=="1"){
			if($file!=".."&&$file!=".")
			{
				//$fileperm=substr(base_convert(fileperms("$BASEDIR$dir/$file"),10,8),-4);
				echo "<table width='100%' cellspacing='0' cellpadding='0' onMouseOut=\"this.bgColor='#D0E6CE';\" onMouseOver=\"this.bgColor='#ffffff';\">
				 <tr> 
					 <td width='40%' class='TableHead2'><img src=images/folder.png><a href='?dir=$dir/$file'>$file</a></td>
				<td width='30%' class='TableHead2'><a href='?dir=$dir&deldir=$file'>删除</a></td>
				 <td width='30%' class='TableHead2'><a href='?dir=$dir&action1=ren&filename=$file'>改名</a></td>
				</tr>
				  <TR>
                      <TD colSpan=99 height=1><IMG border=0 height=1 
                        src='images/cccccc.gif' 
                        width='100%'></TD></TR></table>";
			}
			else
			{
				if($file==".."){
					$ds = dirname($dir);
					if($ds=="/")$ds="";
					if($ds=="\\")$ds="";
					echo "<table width='100%' cellspacing='0' cellpadding='0' onMouseOut=\"this.bgColor='#D0E6CE';\" onMouseOver=\"this.bgColor='#ffffff';\"> <tr><td width='100%' class='TableHead2'>↑<a href='?dir=$ds'> 上级目录</a></TD></TR><TR><TD colSpan=99 height=1><IMG border=0 height=1          src='images/cccccc.gif'  width='100%'></TD></TR></table>";
				}
			}
		}
	}

	?>
	      
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#666666"> 
    <td width="42%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>文件名</strong></font></td>
    <td width="20%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>日期</strong></font></td>
    <td width="15%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>大小</strong></font></td>
    <td width="24%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>操作</strong></font></td>
  </tr>
</table>
	<?
	@closedir($dirs); 


	@$dirs=opendir($BASEDIR.$dir);
     while (@$file=readdir($dirs)) {
		@$b="$BASEDIR$dir/$file";
		@$a=is_dir($b);
		if($a=="0"){
			@$size=filesize("$BASEDIR$dir/$file");
			@$size=$size/1024 ;
			@$size= number_format($size, 3);    
			@$lastsave=date("Y-n-d H:i",filectime("$BASEDIR$dir/$file"));
			//@$fileperm=substr(base_convert(fileperms("$BASEDIR$dir/$file"),10,8),-4);
			echo "
			<table width='100%' cellspacing='0' cellpadding='0' onMouseOut=\"this.bgColor='#D0E6CE';\" onMouseOver=\"this.bgColor='#ffffff';\">
				 <tr class=Table1>
				  <td width='32%'  >$file  $isread </td>
			 <td width='20%'  >$lastsave</td>
			   <td width='15%'  >$size KB</td>
				 <td width='6%'  ><a href='?downfile=$dir/$file' target='_blank'>下载</a> </td>
				<td width='6%'  ><a href='?dir=$dir&editfile=$file&n=1'>编辑</a> </td>
			 <td width='6%'  ><a href='?dir=$dir&delfile=$dir/$file'>删除</a></td>
			<td width='6%'  ><a href='?dir=$dir&action=ren&filename=$file'>改名</a></td>
			</tr>
			  <TR>
                      <TD colSpan=99 height=1><IMG border=0 height=1 
                        src='images/cccccc.gif' 
                        width='100%'></TD></TR>
			</table>";
			//@closedir($dirs); 
		}
	 }

@closedir($dirs);



?>



    <FORM METHOD=POST ACTION="disk.php?dir=<?print($dir)?>&job=upload" enctype="multipart/form-data" name="up">	  
			上载文件:&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfile()"><img src="images/add.gif" border="0"></a><br>
			覆盖上传:<input type="radio" name="up_flag" value="y">是<input type="radio" name="up_flag" value="n" checked>否<br>
			<INPUT TYPE="file" NAME="myfile0" size="14" style="background-color: #FFFFFF; border-bottom:black 1px solid; border-left:black 1px solid; border-right: black 1px solid; border-top:black 1px solid; font-size: 9pt; height: 18px"><br>
			<span id="addfiles"></span>
			<input type="hidden" name="addcounts" >
            <input type="image" border="0" name="imageField" src="images/updata.gif" onclick="return (checkup())">
	  </FORM> 

<?php
if ($DEBUG){
	$timeend = gettimeofday();
	$time = $timeend['sec'] - $timebegin['sec'];
	$time3 = $time + ($timeend['usec']-$timebegin['usec'])/1000000;
	echo "<CENTER><FONT color=#000000>"."Refresh Time:$time3"."</FONT></CENTER>";
}
?><hr></center></tr></td></table>

<script language="JavaScript">
                <!--
                  //WriteCr3();
                    //-->
               </script>
</BODY>
</HTML>
<?
function writetofile($filename,$book)
{
@$fd=fopen("$filename","w");
@fwrite($fd,$book);
@fclose($fd);
}	


function deltree($TagDir) //整目录及文件删除 
{ 
	$mydir=dir($TagDir); 
	while($file=$mydir->read()){ 
		if(@(is_dir("$TagDir/$file")) AND ($file!=".") AND ($file!="..")) 
		{ 
		deltree("$TagDir/$file"); 
		} 
		else
		{
			@chmod("$TagDir/$file",0777);
			@unlink("$TagDir/$file"); 
		}
	} 
	$mydir->close(); 
	@chmod("$TagDir",0777);
	@rmdir($TagDir); 
} 


	function getMyPath($mainpath, $relativepath)
	{
		global $dir;

		$mainpath_info           = explode('/', $mainpath);
		$relativepath_info       = explode('/', $relativepath);
		$relativepath_info_count = count($relativepath_info);
		for ($i=0; $i<$relativepath_info_count; $i++) {
			if ($relativepath_info[$i] == '.' || $relativepath_info[$i] == '') continue;
			if ($relativepath_info[$i] == '..') {
				$mainpath_info_count = count($mainpath_info);
				unset($mainpath_info[$mainpath_info_count-1]);
				continue;
			}
			$mainpath_info[count($mainpath_info)] = $relativepath_info[$i];
		} //end for

		return implode('/', $mainpath_info);
	}
 


 include("config/footer.php");
?>
