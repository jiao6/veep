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
			echo"��Ҫ�µ��ļ�������";
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
���ļ�".$filename."����Ϊ<form action=\"\" method=\"put\">
<input type=\"hidden\" name=\"dir\" value=\"$dir\">
<input type=\"hidden\" name=\"filename\" value=\"$filename\">
<input type=\"text\" name=\"ren_name\">
<input type=\"hidden\" name=\"action\" value=\"toren\">
<input type=\"submit\"  value=\"ȷ��\"></form>
<a href=\"?dir=$dir\">--����</a>
</body></html>";
exit;
                   }
elseif($action1=="ren"){
	echo"
		��Ŀ¼".$filename."����Ϊ<form action=\"\" method=\"put\">
		<input type=\"hidden\" name=\"dir\" value=\"$dir\">
		<input type=\"hidden\" name=\"filename\" value=\"$filename\">
		<input type=\"text\" name=\"ren_name\">
		<input type=\"hidden\" name=\"action1\" value=\"toren\">
		<input type=\"submit\"  value=\"ȷ��\"></form>
		<a href=\"?dir=$dir\">--����</a>
		</body></html>";
	exit;
}

if ($editfile!=""&&$action!='copy'&&$n=='1'||$newfile!="")//�༭�ļ�
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
   ��ǰ�ļ�:
    <input type=\"text\" name=\"editfilename\" value=$editfile> �����޸��ļ��� <a href=\"?dir=$dir\">--����</a>
    <br>����:<input type=\"checkbox\" name=\"copy\" value=\"1\"> ע��:ѡ�и��ƺ�,������ļ�����,ԭ�ļ����ݲ����!<br>
    <textarea name=\"editfiletxt\" cols=\"110\" rows=\"26\" style=\"background:#ffffff;border: 1px solid #500000;color:#500000\">$contents</textarea>
    <br>
    <input type=\"hidden\" name=\"action\" value=\"copy\">
    <input type=\"submit\" value=\"�ύ\">
    <input type=\"reset\" value=\"����\"></td></tr>
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
					echo $source_name."�Ѹ����ϴ�<br>";
				}
				else{
					echo $source_name."�Ѿ����� �������ϴ�!<br>";
				}
			}
			else
			{
				@copy($source,"$BASEDIR$dir/$source_name"); 
				echo $source_name."���ϴ�<br>"; 
			}
		} 
	}//end for
}
////////////////
//�༭�ļ�����
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
			echo"д��ɹ�";
		}
		elseif(!file_exists($filename))
		{
			echo"ʧ��";
		}
	}
	if($copy=="1"&&$editfile==$editfilename)
	{
		echo"��Ҫ���ƣ���������ļ��ĸ����֣����������ļ�����ͬ����<a 	href='javascript:history.back(1)'>����</a>";
	}
	elseif($copy=="1"&&$editfile!=$editfilename)
	{
		echo"��ʼ����...<br>";
		$lastfilename="$BASEDIR$dir/$editfilename";
		$editfiletxt=stripslashes("$editfiletxt");
		$editfiletxt= str_replace("<////textarea>","<///textarea>",$editfiletxt);
		writetofile("$lastfilename","$editfiletxt");
		if(file_exists($lastfilename)){
			echo"д��ɹ�";
		}elseif(!file_exists($lastfilename)){
			echo"ʧ��";
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
echo "�ļ��Ѳ�����<br>";
}
if(!file_exists($BASEDIR.$delfile)&&$xx!="1")
echo"ɾ���ɹ�";
else
echo"ɾ��ʧ��";
}
?>
<?php
if($deldir!="")
{
	$deldirs="$BASEDIR$dir/$deldir";
	if(file_exists($deldirs)) //�Ƿ�Ϊ��Ŀ¼
	{
		$mydir=@dir($deldirs);
		while(@$files=$mydir->read())
		{
		$i=$i+1;
		if($i>2)break;
		}
		$mydir->close(); //���ر�,����Ͳ���ɾ��
	}
	if(file_exists("$deldirs")&&$i==2)
	{
		@chmod("$deldirs",0777);
		@rmdir("$deldirs");
		$xy="1";
	}
  if($i>2)
  echo"��Ŀ¼�����ļ�,Ҫ������Ŀ¼ȫ��ɾ��,���--<a href='?dir=$dir&deldir=$deldir&deltree=$deldir'>ɾ������Ŀ¼</a>";
  if(!file_exists("$deldirs"))echo"Ŀ¼������!";
  if($xy=="1")echo"ɾ�����!";
}


if($deltree!=""&&$deldir!="")
{
	if($deltree==$deldir)
	{
		$deldirs="$BASEDIR$dir/$deltree";
		if(file_exists("$deldirs"))
		{
			deltree($deldirs);
			echo"ɾ������Ŀ¼���!<br>";
		}else{
			echo"�ļ��Ѳ�����!<br>";
		}
	}
	else{
		echo"�������!<a href='javascript:history.back(1)'>����</a>";
	}
}
?>
<?
if($action=="toren"){
$oldname=$BASEDIR.$dir."/".$filename;
$newname=$BASEDIR.$dir."/".$ren_name;
 if(file_exists($newname)){
  echo "<script>alert(\"���ļ�(".$newname.")�Ѿ����ڣ��뷵������һ��\");
	window.history.back(-1)</script>";exit;}
 if(@rename($oldname,$newname))
  echo $filename."��Ϊ".$ren_name."�ɹ�";
                   }
?>
<?
if($action1=="toren"){
$oldname=$BASEDIR.$dir."/".$filename;
$newname=$BASEDIR.$dir."/".$ren_name;
 if(is_dir($newname)){
 echo "<script>alert(\"���ļ�(".$newname.")�Ѿ����ڣ��뷵������һ��\");
	window.history.back(-1)</script>";exit;}
 if(@rename($oldname,$newname))
  echo $filename."��Ϊ".$ren_name."�ɹ�";
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
			alert("�Ѿ�ѡ�����ļ�����ֱ���ύ��");
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
		alert("�Ѿ�û�п�ɾ����ѡ���!");
	}
}
function checknew()
{
	var reg=document.forms.news.create.value;
	if(reg=="")
	{
		alert("�������ļ������֣�");
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
		alert("����ѡ���ļ���");
		return false;
	}
}
</script><center>
<div align="center">
  <h3>ƽ̨���� �ظ�����
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
      <font size="2">�½��ļ���:</font> 
      <INPUT TYPE="text" NAME="create" size="15" style="background-color: #FFFFFF; border-bottom:black 1px solid; border-left:black 1px solid; border-right: black 1px solid; border-top:black 1px solid; font-size: 9pt; height: 18px">
              <input type="image" border="0" name="imageField3" src="images/newbutton.gif" width="60" height="20">
       </FORM>
</td>

<td>&nbsp;&nbsp;</td><td>	   <form method="post" action="?action=newsfile&dir=<?echo"$dir";?>"> �ļ�: <input name="newfile" type="text" style="background-color: #FFFFFF; border-bottom:black 1px solid; border-left:black 1px solid; border-right: black 1px solid; border-top:black 1px solid; font-size: 9pt; height: 18px" size="15">
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
			echo"��Ŀ¼����";
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
				echo"Ŀ¼�����ɹ�";
			}else{
				echo"Ŀ¼����ʧ��";
			}
			
		}
	}
	
		 ?>    
<TABLE WIDTH="100%"  BORDER="0" CELLSPACING="0" CELLPADDING="0"  BGCOLOR="666666" TEXT="#3300FF">
  
  <tr> 
            
    <td width="40%" class=TableHead2><font color="#FFFFFF" size="2"><strong>Ŀ¼����</strong></font></td>
    <td width="60%" class=TableHead2><strong><font color="#FFFFFF" size="2">����</font></strong></td>
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
				<td width='30%' class='TableHead2'><a href='?dir=$dir&deldir=$file'>ɾ��</a></td>
				 <td width='30%' class='TableHead2'><a href='?dir=$dir&action1=ren&filename=$file'>����</a></td>
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
					echo "<table width='100%' cellspacing='0' cellpadding='0' onMouseOut=\"this.bgColor='#D0E6CE';\" onMouseOver=\"this.bgColor='#ffffff';\"> <tr><td width='100%' class='TableHead2'>��<a href='?dir=$ds'> �ϼ�Ŀ¼</a></TD></TR><TR><TD colSpan=99 height=1><IMG border=0 height=1          src='images/cccccc.gif'  width='100%'></TD></TR></table>";
				}
			}
		}
	}

	?>
	      
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr bgcolor="#666666"> 
    <td width="42%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>�ļ���</strong></font></td>
    <td width="20%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>����</strong></font></td>
    <td width="15%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>��С</strong></font></td>
    <td width="24%" class='TableHead2'><font color="#FFFFFF" size="2"><strong>����</strong></font></td>
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
				 <td width='6%'  ><a href='?downfile=$dir/$file' target='_blank'>����</a> </td>
				<td width='6%'  ><a href='?dir=$dir&editfile=$file&n=1'>�༭</a> </td>
			 <td width='6%'  ><a href='?dir=$dir&delfile=$dir/$file'>ɾ��</a></td>
			<td width='6%'  ><a href='?dir=$dir&action=ren&filename=$file'>����</a></td>
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
			�����ļ�:&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:addfile()"><img src="images/add.gif" border="0"></a><br>
			�����ϴ�:<input type="radio" name="up_flag" value="y">��<input type="radio" name="up_flag" value="n" checked>��<br>
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


function deltree($TagDir) //��Ŀ¼���ļ�ɾ�� 
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
