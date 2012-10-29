<?php
    session_start();
    include_once 'connect.php';
    $login=$_POST['login'];
    $password=$_POST['password'];
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    $language=$db->query('select * from `languages` where `language`="'.$_SESSION['lng'].'";')->fetch(PDO::FETCH_ASSOC);
    if($login!='' && $password!='') {
        $q=$db->query('select * from `users` where `login`="'.$login.'";');
        if($q->rowCount()==1) {
            $q=$q->fetch(PDO::FETCH_ASSOC);
            if(md5($password.md5($q['salt']))==$q['password']) {
                $_SESSION['username']=$login;
                $_SESSION['usertype']=$q['type'];
            }
            else {
                $_SESSION['errs'].='<br>- '.$language['28'];
            }
        }
        else {
            $_SESSION['errs'].='<br>- '.$language['29'];
        }
    }
    else {
        $_SESSION['errs'].='<br>- '.$language['30'];
    }
    $_SESSION['errs'].='</h4></font>';
    header("location: index.php");
?>