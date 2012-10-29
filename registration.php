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
    include_once 'connect.php';
    $login=trim($_POST['login']);
    $e_mail=filter_var($_POST['e_mail'],FILTER_VALIDATE_EMAIL);
    $q=$db->query('select * from `users` where `login`="'.$login.'";');
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    $language=$db->query('select * from `languages` where `language`="'.$_SESSION['lng'].'";')->fetch(PDO::FETCH_ASSOC);
    if($login!='' && preg_match("/^[a-z0-9_-]{3,20}$/",$login)) {
        if($q->rowCount()==0) {
            if($e_mail!=FALSE) {
                $q=$db->query('select * from `users` where `e_mail`="'.$e_mail.'";');
                if($q->rowCount()==0) {
                    if($_POST['pass1']==$_POST['pass2']) {
                        $salt=generateSalt();
                        $password=md5($_POST['pass1'].md5($salt));
                        $db->exec('insert into `users` (`login`,`e_mail`,`password`,`salt`,`type`,`regdate`) values ("'.$login.'","'.$e_mail.'","'.$password.'","'.$salt.'",1,"'.time().'");');
                        $_SESSION['username']=$login;
                        $_SESSION['usertype']=1;
                    }
                    else {
                        $_SESSION['errs'].='<br>- '.$language['37'];
                    }
                }
                else {
                    $_SESSION['errs'].='<br>- '.$language['38'];
                }
            }
            else {
                $_SESSION['errs'].='<br>- '.$language['39'];
            }
        }
        else {
            $_SESSION['errs'].='<br>- '.$language['40'];
        }
    }
    else {
        $_SESSION['errs'].='<br>- '.$language['41'];
    }
    $_SESSION['errs'].='</h4></font>';
    if(isset($_SESSION['username'])) {
        header('location: index.php');
    }
    else {
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
?>