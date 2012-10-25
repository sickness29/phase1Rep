<?php
    session_start();
    mysql_connect('127.0.0.1','root','rootmysql');
    mysql_selectdb('phase1');
    $login=$_POST['login'];
    $password=$_POST['password'];
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($login!='' && $password!='') {
        $q=mysql_query('select * from `users` where `login`="'.$login.'";');
        if(mysql_num_rows($q)==1) {
            $q=mysql_fetch_assoc($q);
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
    mysql_close();
    header("location: index.php");
?>