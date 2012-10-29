<?php
	session_start();
	include_once 'connect.php';
	if(@$_SESSION['usertype']==0 && isset($_POST['len'])) {
		$n=$_POST['len'];
		$q1='update `languages` set `0`="'.htmlspecialchars($_POST['0ua']).'"';
		$q2='update `languages` set `0`="'.htmlspecialchars($_POST['0en']).'"';
		for($i=1;$i<$n-1;$i++) {
			$q1.=', `'.$i.'`="'.htmlspecialchars($_POST[$i.'ua']).'"';
			$q2.=', `'.$i.'`="'.htmlspecialchars($_POST[$i.'en']).'"';
		}
		$q1.=' where `language`="ua";';
		$q2.=' where `language`="en";';
		$db->exec($q1);
		$db->exec($q2);
	}
	header('Location: index.php');
?>