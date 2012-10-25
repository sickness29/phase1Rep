<?php
	session_start();
	$_SESSION['lng']=$_GET['lng'];
	header('Location: index.php');
?>