<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    $language=$db->query('select * from `languages` where `language`="'.$_SESSION['lng'].'";')->fetch(PDO::FETCH_ASSOC);
    if(isset($_POST['id'])) {
        $q=$db->query('select * from `data` where `id`="'.$_POST[id].'";');
        if($q->rowCount()==1) {
            if($_SESSION['usertype']==0 || $_SESSION['usertype']==2) {
                $db->exec('delete from `data` where `id`="'.$_POST['id'].'";');
            }
            else {
                $_SESSION['errs'].='<br>- '.$language['31'];
            }
        }
        else {
            $_SESSION['errs'].='<br>- '.$language['32'];
        }
    }
    else {
        $_SESSION['errs'].='<br>- '.$language['33'];
    }
    $_SESSION['errs'].='</h4></font>';
    header('location: index.php');
?>