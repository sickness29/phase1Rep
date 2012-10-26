<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($_POST['title']!='') {
        if($_POST['data']!='') {
            $db->exec('insert into `data` (`title`,`data`,`time`,`username`) values ("'.htmlspecialchars($_POST['title']).'","'.htmlspecialchars($_POST['data']).'","'.date("H:i d.m.Y").'","'.$_SESSION['username'].'");');
        }
        else {
            $_SESSION['errs'].='<br>- Enter the text';            
        }
    }
    else {
        $_SESSION['errs'].='<br>- Enter the title';
    }
    $_SESSION['errs'].='</h4></font>';
    header('location: index.php');
?>