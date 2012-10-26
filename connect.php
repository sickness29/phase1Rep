<?php
	$db_user = 'root';
	$db_pass = 'rootmysql';
	try {
    	$db = new PDO('mysql:dbname=phase1;host=127.0.0.1', $db_user, $db_pass);
    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e) {
    	die("I cannot connect to db:" . $e->getMessage());
	}
?>