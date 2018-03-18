<?
session_start();

require_once("config/config.php");
require_once("config/dsql.php");
require_once("header.php");

$dsql = new DSQL();
?>
<div class="contain2 clss">
	<div class="testtop">
		<div class="testline"></div>
				<div class="testtxt"  >实验列表</div>
		</div>
		<ul class="testbtm" style="position: relative;margin-top: 45px;">
			<?
			$whereinfo  ="";
			if ($search) {
				$whereinfo .= " and (    (binary   name  like '%$search%')or(binary content like '%$search%')   )  ";
			}

			$SQL = "SELECT count(*) as allcount  FROM    experiments  a  where (status=0)  $whereinfo   ";
			$dsql->query($SQL);


			$dsql->next_record();
			$numrows = $dsql->f("allcount");

			if (!isset ($pagesize))
				$pagesize = 10;
			if ((!isset ($page)) or $page < 1)
				$page = 1;
			$pages = intval($numrows / $pagesize);
			if ($numrows % $pagesize)
				$pages++;
			if ($page > $pages)
				$page = $pages;
			$offset = ($page - 1) * $pagesize;


			$first = 1;
			$prev = $page - 1;
			$next = $page + 1;
			$last = $pages;
			if($offset<0)$offset=0;

			$SQL = "SELECT  *  from experiments where (status=0)  $whereinfo  order by sort asc limit $offset,$pagesize";
			//echo $SQL;
			$dsql->query($SQL);
			$i = 0;
			while ($dsql->next_record()) {
				$i++;
				$id = $dsql->f('id');
				$name = $dsql->f('name');
				$content = $dsql->f('content');
				$softfile = $dsql->f('softfile');
				$reportfile = $dsql->f('reportfile');
				$userid = $dsql->f('userid');
				$status = $dsql->f('status');
				$groupid = $dsql->f('groupid');
				$img = $dsql->f('img');
				$type = $dsql->f('type');
				$difficulty = $dsql->f('difficulty');
				echo "<li>
				<div class=\"clsimg\"></div>
				<img src=\"$img\" height=\"180px\">
				<div class=\"clsrt\">
					<p class=\"clsname\">
					 $name</p>
					<p class=\"clsinfo\"><a name=\"$id\"></a>
						$content<br/>
						实验难度系数：$difficulty<br/>
						实验类型：$type<br/>
					</p>
				</div>
			</li>";
			}
			if($i==0){
				echo "<li>
				<div class=\"clsimg\"></div>
				<img src=\"img/btm1.jpg\" height=\"180px\">
				<div class=\"clsrt\">
					<p class=\"clsname\">
					 </p>
					<p class=\"clsinfo\">
						 无相关实验
					</p>
				</div>
			</li>";

			}
			?>

		</ul>
	<?
	echo "<br><div id=text14>共 $numrows 条 <a href='?id=$id&search=$search&page=$first'>首页</a><a href='?id=$id&search=$search&page=$prev'>上一页</a><a href='?id=$id&search=$search&&page=$next'>下一页</a><a href='?id=$id&search=$search&page=$last'>尾页</a><span>[$page/$pages]</span></div>";
	?>
	</div>
	<script type="text/javascript">
		$('.tm-ul li').removeClass('zhuye').eq(2).addClass('zhuye');
	</script>
	<?
include("footer.php");
?>