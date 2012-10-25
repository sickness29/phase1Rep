<?php
    session_start();
    function generateSalt() {
		$salt = '';
		$length = rand(5,10);
		for($i=0; $i<$length; $i++) {
			$salt .= chr(rand(33,126));
		}
		return $salt;
	}
    mysql_connect('127.0.0.1','root','rootmysql');
    mysql_selectdb('phase1');
    $login=trim($_POST['login']);
    $e_mail=filter_var($_POST['e_mail'],FILTER_VALIDATE_EMAIL);
    $q=mysql_query('select * from `users` where `login`="'.$login.'";');
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($login!='' && preg_match("/^[a-z0-9_-]{3,20}$/",$login)) {
        if(mysql_num_rows($q)==0) {
            if($e_mail!=FALSE) {
                if($_POST['pass1']==$_POST['pass2']) {
                    $salt=generateSalt();
                    $password=md5($_POST['pass1'].md5($salt));
                    mysql_query('insert into `users` (`login`,`e_mail`,`password`,`salt`,`type`,`regdate`) values ("'.$login.'","'.$e_mail.'","'.$password.'","'.$salt.'",1,"'.time().'");');
                    $_SESSION['username']=$login;
                    $_SESSION['usertype']=1;
                }
                else {
                    $_SESSION['errs'].='<br>- Passwords are not the same';
                }
            }
            else {
                $_SESSION['errs'].='<br>- Incorrect e-mail';
            }
        }
        else {
            $_SESSION['errs'].='<br>- This login is busy';
        }
    }
    else {
        $_SESSION['errs'].='<br>- Please enter valid login';
    }
    $_SESSION['errs'].='</h4></font>';
    mysql_close();
    header('location: index.php');
?>