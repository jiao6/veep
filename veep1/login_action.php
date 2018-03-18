<?
require_once("config/config.php");
require_once("config/dsql.php");
header("Content-type: text/html; charset=utf-8");
$dsql = new DSQL();
//echo $passwd;
session_start();
if(strtolower( $_SESSION["authnum_session"])!=strtolower($verifycode)){

    echo "<script>alert(' 请输入正确的验证码');window.location='login.php'</script>\n";
   exit;
}
$bpasswd = bpasswd($passwd);

$SQL = "select * from users where email='$email' and password='$bpasswd' and status!=1   ";
//echo $SQL;

$dsql->query($SQL);

if($dsql->next_record()) {
    /*
   $pwd =  $dsql->f('pwd');
    if($pwd!="$passwd"){
        echo "<br><script>alert('密码 错误');history.go(-1)</script>\n";
        exit;
    }
*/
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

    if (!session_is_registered('auth_import_email')) {
        session_register('auth_import_email');
    }

    $auth = 'login';
    $auth_username = $dsql->f('truename');
    $auth_id = $dsql->f('id');
    $auth_pid = $dsql->f('usertype');
    $auth_email = $email;

    $auth_feeuserid = $dsql->f('feeuserid');
    $auth_nickname = $dsql->f('nickname');

    $studentno = $dsql->f('studentno');
    $university_id = $dsql->f('university_id');

    $ischeckemail = $dsql->f('ischeckemail');
    $creator_id = $dsql->f('creator_id');


    $_SESSION['auth_import_email'] = $university_id.$studentno;


    $_SESSION['auth_username'] = $auth_username;
    $_SESSION['auth_nickname'] = $auth_nickname;
    $_SESSION['auth'] = $auth;
    $_SESSION['auth_id'] =   $auth_id;
    $_SESSION['auth_pid'] =   $auth_pid;
    if ($username == "admin") {
        $auth = "admin";
    }


    $_SESSION['auth'] = $auth;
    $_SESSION['auth_email'] = $email;
    echo $auth_pid;
    //echo "}}$ischeckemail!=1&&$creator_id>0&&$auth_pid==1";
    if($ischeckemail!=1&&$creator_id>0&&$auth_pid==1){
        Header('Location:studentuseredit.php');
        exit;
    }else if($ischeckemail!=1&&$auth_pid==1){

        //Header('Location:useractiveemail.php');
        Header('Location:index.php');
        exit;
    }else{
        Header('Location:index.php');
        exit;
    }





} else {
    $auth = false;
    echo "<br><script>alert('用户名或者密码错误');history.go(-1)</script>\n";
}

function go()
{
    Header('Location:index.php');
}

function fallLogin()
{
    die('对不起,您的输入有误!!!');
}

exit;
?>
