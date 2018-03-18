<?
$universityId = '<div class="form-group">' .
    '<div class="label"><label for="name">学校</label></div>'.
    '<div class="field"><input class="input" type="text" maxlength="30" id="university" name="university" autocomplete="off" datatype="*" errormsg="选择学校" placeholder="学校" value="' . $university .'" readOnly style="" onclick="showProvince(this, 100, '. 0 .', \''. $university .'\')" ></div>'.
    '<input type="hidden" id="universityId" name="universityId" value="'.$universityId.'" />'
    . '</div>';
echo $universityId;

?>