<?php
    session_start();
    include_once 'connect.php';
    $login=$_POST['login'];
    $password=$_POST['password'];
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($login!='' && $password!='') {
        $q=$db->query('select * from `users` where `login`="'.$login.'";');
        if($q->rowCount()==1) {
            $q=$q->fetch(PDO::FETCH_ASSOC);
            if(md5($password.md5($q['salt']))==$q['password']) {
                $_SESSION['username']=$login;
                $_SESSION['usertype']=$q['type'];
            }
            else {
                $_SESSION['errs'].='<br>- Incorrect password';
            }
        }
        else {
            $_SESSION['errs'].='<br>- No such user';
        }
    }
    else {
        $_SESSION['errs'].='<br>- Enter login and password';
    }
    $_SESSION['errs'].='</h4></font>';
    header("location: index.php");
?>