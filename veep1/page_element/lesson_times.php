<div class="form-group">
	<div class="label">
		<label for="text3">开始时间：</label>
	</div>
	<div class="field">
    <input type="text" name="START_TIME" value="<? if (isset($START_TIME)){print($START_TIME);} else {print("2016-10-13 11:11:11");}?>" class="input" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
	</div>
</div>
<div class="form-group">
	<div class="label">
		<label for="text3">结束时间：</label>
	</div>
	<div class="field">
    <input type="text" name="END_TIME" class="input"  value="<? if (isset($END_TIME)){print($END_TIME);} else {print("2099-12-31 23:59:59");} ?>" onClick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"> 
  </div>
</div>
