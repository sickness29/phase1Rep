<?php
	session_start();
	$_SESSION['lng']=$_GET['lng'];
	header('Location: '.$_SERVER['HTTP_REFERER']);
?>