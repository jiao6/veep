<?php
session_start();
require_once("config/config.php");
require_once("config/dsql.php");

if(!$auth) loginFalse();

function loginFalse() {
	Header("Location:login.php");
}
if($auth_pid!=3){

	Header("Location:login.php");
}
    require_once("header.php");

$dsql = new DSQL();

if($ac=="add"){
    error_reporting(9);
	mkdir("data");
    mkdir("data/experimentfile");
    mkdir("data/experimentimg");
	$experimentfile = "data/experimentfile/". $_FILES["experimentfile"]["name"];
    $experimentimg = "data/experimentimg/". $_FILES["experimentimg"]["name"];
    copy($_FILES["experimentfile"]["tmp_name"],$experimentfile);
    copy($_FILES["experimentimg"]["tmp_name"],$experimentimg);


		$SQL = "insert into experiments
 (id,name,content,reportfile,type,difficulty,userid,softfile,img,groupid)values(0,'$name','$content','$reportfile','$type','$difficulty','$userid','$experimentfile','$experimentimg','$groupid')";
		if(!$dsql->query($SQL)){
			 //echo "un  success:$SQL ";
			 echo "<br>未成功\n";
			echo "<script >alert('未成功');window.location='experimentslist.php';</script>\n";
			 exit;
		}else{
			  echo "<script >alert('保存成功');window.location='experimentslist.php';</script>\n";
			  //echo "success:$SQL ";
		}
	exit;
}

?>
<div id="admin-nav">
	<div>
    	<ul class="nav admin-nav" style="height: 0;">
        	<li class="active">
           		<ul class="nav nav-inline admin-nav">
                    <?
                   	include("menu.php");
                   	?>
               	</ul>
            </li>
       	</ul>
    </div>
   	<div class="admin">
		<ul class="bread">
			<li><a href="experimentslist.php">实验管理</a></li>
			<li>新增实验</li>
		</ul>
		<!--div class="rhead">
			<div class="rhead1">新增实验</div>
			<div class="rhead2"><a href="experimentslist.php">实验管理</a></div>
		</div-->
		<div class="rfg"></div>
		<div class="rgwin">
			<form action="experiment_add.php" class="rgform form-x"   method="post"  enctype="multipart/form-data">
				<div class="form-group">
                	<div class="label">
						<label for="name">请选择类型</label>
                    </div>
                    <div class="field">
					<select name="groupid" size=1 class="input">
						<option value="1"> - 无 - </option>
						<?
						$SQL = "SELECT    id ,	name 	  	,uid ,	pid 	,path 	,status     FROM experimentsgroup  order by path     ";
						//echo $SQL . "\n";

						$dsql->query($SQL);
						$nbsp = $pernbsp = "|---------";
						$olddepth =1;
						while($dsql->next_record()){
							$id=$dsql->f('id');
							$group_name=$dsql->f('name');
							$insertdate=$dsql->f('insertdate');
							$userid=$dsql->f('userid');
							$group_pid=$dsql->f('pid');
							$path =$dsql->f('path');
							$newdepth =  substr_count($path,',');
							$select = "";
							if($id==$pid){
								$select = "selected='selected' ";
							}
							if($newdepth==$olddepth){
								//$nbsp = "|--";
								echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";

							}else if($newdepth>$olddepth){
								$nbsp = $nbsp.$pernbsp;
								echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";
							}else{
								$depthno = $olddepth - $newdepth;
								$nbsp = substr($nbsp,0,strlen($nbsp)-strlen($pernbsp)*$depthno);
								echo "<option value=\"$id\" $select>$nbsp|$group_name</option>";
							}
							$olddepth =  $newdepth;
						}
						?>
					</select>
                    </div>
				</div>
				<div class="form-group">
                	<div class="label">
						<label for="name">名称</label>
                    </div>
                    <div class="field">    
                        <input type="text" maxlength="100" id="name" name="name" class="input"/>
					</div>
                </div>                   
				<div class="form-group">
                	<div class="label">
						<label for="name">简介</label>
                    </div>
                    <div class="field">    
                    	<input type="text" maxlength="100" id="content" name="content" class="input"/>                      
					</div>
                </div>
				<div class="form-group">
                	<div class="label">
						<label for="name">实验图片</label>
                    </div>
                    <div class="field">    
                   		<input type="file" id="experimentimg" name="experimentimg" class="input"/>                       
					</div>
				<div class="form-group">
                	<div class="label">
						<label for="name">实验文件</label>
                    </div>
                     <div class="field">   
                        <input type="file" id="experimentfile" class="input" name="experimentfile" />
                     </div>
				</div>
				<div class="form-group">
                	<div class="label">
						<label for="name">实验报告地址</label>
                    </div>
                     <div class="field">    
                        <input type="text" maxlength="100" id="reportfile" name="reportfile" class="input" />
                     </div>
				</div>
				<!--div>
					<label for="name">难度系数</label>
					<select name="difficulty" placeholder="">
						<option value="简单">简单</option>
						<option value="一般">一般</option>
						<option value="难">难</option>
					</select>
				</div>
				<div>
					<label for="name">类型</label>
					<select name="type" placeholder="">
						<option value="演示型">演示型</option>
						<option value="验证型">验证型</option>
						<option value="设计型">设计型</option>
					</select>
				</div-->
                            <? include "page_element/difficulty.php" ?><!-- 实验难度 -->
                            <? include "page_element/experiment_type.php" ?><!-- 实验类型 -->
				<div class="form-group">
                	<div class="label">
						<label for="name">排序值</label>
                    </div>
                    <div class="field">    
                    	<input type="text" maxlength="6" id="sort" name="sort" class="input"/>
                    </div>
				</div>

				<input type="hidden" name="ac" value="add" >
				<div class="form-group">
                	<div class="label">
                    <label for="name"></label>
                    </div>
                    <div class="field">
					<input type="submit" id="submit" name="submit" class="btn2 button bg-main" value="保存">
                    </div>
				</div>
			</form>
		</div>
<?
 
include ("footer.php");
?>