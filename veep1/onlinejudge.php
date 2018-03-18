<?
include_once("header.php");
include_once("config/config.php");
?>
<?php
session_start();
echo "<meta charset=\"UTF-8\"> ";

$xmlfilename=$_GET['filename'];
//$prefix=substr($xmlfilename,0,-4);
//$htmlfilename=$prefix.".html";

/*if(!file_exists($htmlfilename)){
	$idtr=anysxml("test2.xml",1);
	include "$htmlfilename";
}
else include "$htmlfilename";*/
$xmlfilepath="../xml/".$xmlfilename;
$idtr=anysxml("$xmlfilepath","text.php",1);//不生成html
echo $idtr[0];
include "$idtr[0]";
/*if($_SESSION['xmlfilename'] == ""){
	$_SESSION["$xmlfilename"] = $nidtr;
	$_SESSION['xmlfilename'] = $xmlfilename;
}*/
$_SESSION['xmlfilename'] = $xmlfilename;
$_SESSION["$xmlfilename"] = $idtr;
?>
<?
include("footer.php");
?>;
