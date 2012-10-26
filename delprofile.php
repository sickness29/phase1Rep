<?php
	session_start();
	include_once 'connect.php';
	if (isset($_POST['userid'])) {
		//deleted by admin
		$q=$db->query('select * from `users` where `id`="'.$_POST['userid'].'";');
		$q=$q->fetch(PDO::FETCH_ASSOC);
		unlink($q['ava']);
		$db->exec('delete from `users` where `id`="'.$_POST['userid'].'";');
	}
	else {
		$q=$db->query('select * from `users` where `login`="'.$_SESSION['username'].'";');
		$q=$q->fetch(PDO::FETCH_ASSOC);
		unlink($q['ava']);
		$db->exec('delete from `users` where `login`="'.$_SESSION['username'].'";');
		unset($_SESSION['username']);
		unset($_SESSION['usertype']);
	}
	header('Location: index.php');
?>