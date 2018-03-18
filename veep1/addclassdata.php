<? 
error_reporting(0);
session_start();
require_once("config/config.php");;
require_once("config/dsql.php");
if(!isset($dsql)){
	$dsql = new DSQL();
}
//查询该课程或课堂的实验
$SQL = "SELECT ce . * , e.name as  ename 
	FROM coursesexperiment ce, experiments e 
	WHERE ce.coursesid =$theId and ce.COURSE_TYPE=$COURSE_TYPE AND e.id = ce.experimentsid ORDER BY ce.sort ASC";

                        //echo $SQL . "\n";

                        $dsql->query($SQL);

                      
                        while($dsql->next_record()){
                           $id = $dsql->f('id');
						   $name = $dsql->f('name');
						   $ename = $dsql->f('ename');

						   if(strlen($name)<2){
								$name = $ename;
						   }	
							$starttime = $dsql->f('starttime');
							$endtime = $dsql->f('endtime');
							 
							$score = $dsql->f('score');
							$count = $dsql->f('count');
							$scoringmode = trim($dsql->f('scoringmode'));
							$timemode = $dsql->f('timemode');
							 
							$isshowscore = trim($dsql->f('isshowscore'));
							$userid = $dsql->f('userid');
							$coursesid = $dsql->f('coursesid');
							$experimentsid = $dsql->f('experimentsid');
							$sort = $dsql->f('sort');
							$info[] = "{\"id\":\"$id\",\"name\":\"$name\",\"starttime\":\"$starttime\",\"endtime\":\"$endtime\",\"score\":\"$score\",\"count\":\"$count\",\"scoringmode\":\"$scoringmode\",\"timemode\":\"$timemode\" ,\"isshowscore\":\"$isshowscore\" ,\"sort\":\"$sort\" }";
						
						}
						 echo ('{"data":['.implode(",\r\n",$info).']}');

 
 
?>