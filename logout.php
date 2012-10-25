<?php
    session_start();
	unset($_SESSION['usertype']);
    unset($_SESSION['username']);
    header('location: index.php');
?>