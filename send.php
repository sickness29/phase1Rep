<?php
    session_start();
    mysql_connect('127.0.0.1','root','rootmysql');
    mysql_selectdb('phase1');
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($_POST['title']!='') {
        if($_POST['data']!='') {
            mysql_query('insert into `data` (`title`,`data`,`time`,`username`) values ("'.htmlspecialchars($_POST['title']).'","'.htmlspecialchars($_POST['data']).'","'.date("H:i d.m.Y").'","'.$_SESSION['username'].'");');
        }
        else {
            $_SESSION['errs'].='<br>- Enter the text';            
        }
    }
    else {
        $_SESSION['errs'].='<br>- Enter the title';
    }
    $_SESSION['errs'].='</h4></font>';
    mysql_close();
    header('location: index.php');
?>