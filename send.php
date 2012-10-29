<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    $language=$db->query('select * from `languages` where `language`="'.$_SESSION['lng'].'";')->fetch(PDO::FETCH_ASSOC);
    if($_POST['titleua']!='' && $_POST['titleen']!='') {
        if($_POST['dataua']!='' && $_POST['dataen']!='') {
            $db->exec('insert into `data` (`titleua`,`dataua`,`titleen`,`dataen`,`time`,`username`) values ("'.htmlspecialchars($_POST['titleua']).'","'.htmlspecialchars($_POST['dataua']).'","'.htmlspecialchars($_POST['titleen']).'","'.htmlspecialchars($_POST['dataen']).'","'.date("H:i d.m.Y").'","'.$_SESSION['username'].'");');
        }
        else {
            $_SESSION['errs'].='<br>- '.$language['34'];            
        }
    }
    else {
        $_SESSION['errs'].='<br>- '.$language['35'];
    }
    $_SESSION['errs'].='</h4></font>';
    header('location: index.php');
?>