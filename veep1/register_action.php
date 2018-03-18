<?

require_once("header.php");
require_once("config/dsql.php");

$dsql = new DSQL();
//echo $passwd;
 error_reporting(ALL);
if($action=='add'){
	$SQL = "select * from users where email='$email'";
	//echo $SQL;
	$dsql->query($SQL);
	if($dsql->next_record()){
		echo "<br><script>alert('用户 $email 已经存在,请重新填写');window.location='register.php'</script>\n";
	}else{
		$bpasswd = bpasswd($password);
		$userimg="img/teacher.png";
		if($usertype==1){
			 
		}else if($usertype==2){
			$userimg="img/teacher.png";
		}else{
			echo "<br><script>alert('请选择老师或者学生身份');window.location='register.php'</script>\n";
			exit;
		}
		$SQL = "insert into users (id,nickname,email,password,university,college,phonenumber,truename,university_Id,created_at,updated_at,pwd,usertype,feeuserid,userimg)values(0,'$nickname','$email','$bpasswd','$university','$college','$phonenumber','$truename','$universityId',now(),now(),'$password','$usertype','$feeuserid','$userimg')";
		if(!$dsql->query($SQL)){
			 //echo "un  success:$SQL ";
			 echo "<br>注册用户未成功\n";
			 exit;
		}else {
			echo "<br><script>alert('注册用户成功');window.location='index.php'</script>\n";
			//echo "success:$SQL ";

			$SQL = "select * from users where email='$email' and  pwd= '$password' and status!=1 ";
			//echo $SQL;
			$dsql->query($SQL);

			if ($dsql->next_record()) {

				if (!session_is_registered('auth')) {
					session_register('auth');
				}
				if (!session_is_registered('auth_nickname')) {
					session_register('auth_nickname');
				}
				if (!session_is_registered('auth_username')) {
					session_register('auth_username');
				}
				if (!session_is_registered('auth_id')) {
					session_register('auth_id');
				}
				if (!session_is_registered('auth_pid')) {
					session_register('auth_pid');
				}
				if (!session_is_registered('auth_email')) {
					session_register('auth_email');
				}
				if (!session_is_registered('auth_feeuserid')) {
					session_register('auth_feeuserid');
				}
				$auth = 'login';
				$auth_username = $dsql->f('truename');
				$auth_id = $dsql->f('id');
				$auth_pid = $dsql->f('usertype');
				$auth_email = $email;

				$auth_feeuserid = $dsql->f('feeuserid');
				$auth_nickname = $dsql->f('nickname');

				$_SESSION['auth_username'] = $auth_username;
				$_SESSION['auth_nickname'] = $auth_nickname;
				$_SESSION['auth'] = $auth;
				$_SESSION['auth_id'] = $auth_id;
				$_SESSION['auth_pid'] = $auth_pid;
				if ($username == "admin") {
					$auth = "admin";
				}


				$_SESSION['auth'] = $auth;
				$_SESSION['auth_email'] = $email;
				echo $auth_pid;


				Header('Location:index.php');


			}
		}
	}
}
include("footer.php");
?>