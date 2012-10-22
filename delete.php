<?php
    session_start();
    mysql_connect('127.0.0.1','root','rootmysql');
    mysql_selectdb('phase1');
    $_SESSION['errs']='<font color="red"><h4 align="right">';
    if(isset($_POST['id'])) {
        $q=mysql_query('select * from `data` where `id`="'.$_POST[id].'";');
        if(mysql_num_rows($q)==1) {
            $q=mysql_fetch_assoc($q);
            if($_SESSION['username']==$q['username'] || $_SESSION['admin']==1) {
                mysql_query('delete from `data` where `id`="'.$_POST['id'].'";');
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
    mysql_close();
    header('location: index.php');
?>