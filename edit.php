<?php
    session_start();
    include_once 'connect.php';
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if(isset($_POST['id'])) {
        $q=$db->query('select * from `data` where `id`="'.$_POST['id'].'";');
        if($q->rowCount()==1) {
            $q=$q->fetch(PDO::FETCH_ASSOC);
            if($_SESSION['usertype']==0 || $_SESSION['usertype']==2) {
                if($_POST['titleua']!='' && $_POST['titleen']!='') {
                    if($_POST['dataua']!='' && $_POST['dataen']!='') {
                        $db->exec('update `data` set `titleua` = "'.htmlspecialchars($_POST['titleua']).'", `dataua` = "'.htmlspecialchars($_POST['dataua']).'",`titleen` = "'.htmlspecialchars($_POST['titleen']).'", `dataen` = "'.htmlspecialchars($_POST['dataen']).'" where `id`="'.$_POST['id'].'";');
                    }
                    else {
                        $_SESSION['errs'].='<br>- Enter the text';            
                    }
                }
                else {
                    $_SESSION['errs'].='<br>- Enter the title';
                }    
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
        $_SESSION['errs'].='<br>- "id" of the data was not set';
    }
    $_SESSION['errs'].='</h4></font>';
    mysql_close();
    header('location: index.php');
?>