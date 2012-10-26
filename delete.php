<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if(isset($_POST['id'])) {
        $q=$db->query('select * from `data` where `id`="'.$_POST[id].'";');
        if($q->rowCount()==1) {
            $q=$->fetch(PDO::FETCH_ASSOC);
            if($_SESSION['usertype']==0 || $_SESSION['usertype']==2) {
                $db->exec('delete from `data` where `id`="'.$_POST['id'].'";');
            }
            else {
                $_SESSION['errs'].='<br>- Access denied';
            }
        }
        else {
            $_SESSION['errs'].='<br>- No such data';
        }
    }
    else {
        $_SESSION['errs'].='<br>- Data was not selected';
    }
    $_SESSION['errs'].='</h4></font>';
    header('location: index.php');
?>