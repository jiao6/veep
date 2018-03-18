<div id="tags" class="form-group">
  <div class="label">
    <label for="text1">标签列表 </label>
  </div>
    <select style="width:262px" id="selTagList">
      <? $dataTagList = $lessonController->getDataTagList($lessonId); 
      foreach($dataTagList as $tag){ 
if ($tag->getId() >0) {?>
        <OPTION value=""><? echo $tag->getName() ?></OPTION>
      <? }
      } ?>
    </select>
    <input type="button" value="修改标签……"  class="button bg-sub button-small" onclick="prepareToSelect(this)" />
</div>
