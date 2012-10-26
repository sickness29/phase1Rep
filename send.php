<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if($_POST['titleua']!='' && $_POST['titleen']!='') {
        if($_POST['dataua']!='' && $_POST['dataen']!='') {
            $db->exec('insert into `data` (`titleua`,`dataua`,`titleen`,`dataen`,`time`,`username`) values ("'.htmlspecialchars($_POST['titleua']).'","'.htmlspecialchars($_POST['dataua']).'","'.htmlspecialchars($_POST['titleen']).'","'.htmlspecialchars($_POST['dataen']).'","'.date("H:i d.m.Y").'","'.$_SESSION['username'].'");');
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