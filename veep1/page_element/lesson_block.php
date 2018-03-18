                    <?
                    $NAME_LENGTH = 7;
                    $lessonName = $object->getName();
                    $id = $object->getId();
                    
                    $lessonNameShort = MetaDataGenerator::getShortenString($lessonName, $NAME_LENGTH);
                    $teacher = $object->getTeacher();
                    $universityName = $teacher->getUniversityName();
                    $universityNameShort = MetaDataGenerator::getShortenString($universityName, 6);
                    $studentLimit = $object->getStudentLimit();
                    $studentAmount = $object->getStudentAmount();

                    $introduction = $object->getIntroduction();
                    if (strlen($introduction) > 0) $introduction = "&#10;&#13;". $introduction;
                    $shown = $object->getShown();
                    $href = "href='lesson.php?lessonId=" . $object->getId() . "'";
                    $online = "，可选课：" . ($studentLimit-$studentAmount);
                    if ($shown == MetaDataGenerator::SHOWN_NO) {
                        $href = "";
                        $online = "&nbsp;<font color='red'>(已下线)</font>";
                    }
                    ?>
                    <li onmouseover="showInfoDiv(this, <? print($id) ?>)"><!--  onmouseout="hideInfoDiv(this, <? print($id) ?>)" -->
                        <div class="md_img"><!--  -->
                            <a <? echo $href ?>><img src="<? echo $object->getImg() ?>" width="200" height="150" alt="<? echo $universityName.'-'.$lessonName ?>" title="<? echo $universityName . '-' . $lessonName . $introduction ?>"></a>
                        </div>
                        <div class="midinfo">
                            <p class="infoname">
                                <a href="?universityId=<? echo $teacher->getUniversityId() ?>" title="<? echo $universityName ?>"><? echo $universityNameShort ?></a>
                                <a <? echo $href ?> title="<? echo $lessonName ?>"><? echo $lessonNameShort ?></a>
                            </p>
                            <p class="infoname">选课人数：<? echo $studentAmount . $online ?></p>
                            <p class="infoname">主讲教师：<a href="teacherinfo.php?teacherId=<? echo $teacher->getId() ?>" title="<? echo $teacher->getName() ?>"><? echo MetaDataGenerator::getShortenString($teacher->getName(), 8) ?></a></p>
                        </div>
                    </li>


<div id="InfoDiv<? print($id) ?>" style="display:none; border:2px black solid; background: white; color:black; position:absolute; left:100px; top:<?echo 0+$olddepth*100 ?>px" class="dataDiv">
    <table>
    	<caption style="background:green; color:white"><? echo $universityName. " - " . $lessonName ?></caption>
        <tr>
            <td>课程名称</td>
            <td colspan="3">
                <a href="lessons.php?courseid=<? echo $object->getCourseId() ?>"><? echo $object->getCourseName() ?></a>
            </td>
        </tr>
        <tr>
            <td> 起始时间&nbsp;</td>
            <td><? echo $object->getStartTime() ?>&nbsp;</td>
            <td>&nbsp;终止时间&nbsp;</td>
            <td><? echo $object->getEndTime() ?></td>
        </tr>
        <tr>
            <td>简介</td>
            <td colspan="3"><? echo $object->getIntroduction() ?></td>
        </tr>
    </table>
</div>
