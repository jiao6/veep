<div class="form-group">
	<div class="label">
		<label for="name">排序值</label><span class='hint'>*</span>
	</div>
	<div class="field">
		<input type="text" maxlength="8" name="SORT_ORDER" id="SORT_ORDER" style="width:77px"value="<? if (isset($SORT_ORDER)) {print($SORT_ORDER);} else {print(1000);} ?>" datatype="n"errormsg="排序值，只能填写数字" placeholder="排序值，数字大的排名靠前" nullmsg="排序值，数字大的排名靠前"> 
	</div>
	<font style="position:absolute; left:300px; padding-top:3px" color="red">填写0则不出现在列表中</font>
</div>
