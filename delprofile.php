<?php
	session_start();
	mysql_connect('127.0.0.1','root','rootmysql');
	mysql_select_db('phase1');
	if (isset($_POST['userid'])) {
		//deleted by admin
		$q=mysql_fetch_assoc(mysql_query('select * from `users` where `id`="'.$_POST['userid'].'";'));
		unlink($q['ava']);
		mysql_query('delete from `users` where `id`="'.$_POST['userid'].'";');
	}
	else {
		$q=mysql_fetch_assoc(mysql_query('select * from `users` where `login`="'.$_SESSION['username'].'";'));
		unlink($q['ava']);
		mysql_query('delete from `users` where `login`="'.$_SESSION['username'].'";');
		unset($_SESSION['username']);
		unset($_SESSION['usertype']);
	}
	mysql_close();
	header('Location: index.php');
?>