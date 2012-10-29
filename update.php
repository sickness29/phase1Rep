<?php
	session_start();
	include_once 'connect.php';
	if (isset($_POST['type'])) {
		$usernm=$_POST['username'];
		$tp=$_POST['type'];
	}
	else {
		$usernm=$_SESSION['username'];
	}
	$q=$db->query('select * from `users` where `login`="'.$usernm.'";');
	$q=$q->fetch(PDO::FETCH_ASSOC);
	if(isset($_POST['type'])==FALSE) {
		$tp=$q['type'];
	}
	if (empty($_FILES['newAva']['name'])==FALSE) {
		$filename=$_FILES['newAva']['name'];
		$path='uploads/'.$filename;
		$source=$_FILES['newAva']['tmp_name'];
		copy($source, $path);
		if(preg_match('/[.](GIF)|(gif)$/', $filename)) $image = imagecreatefromgif($path);
    	if(preg_match('/[.](PNG)|(png)$/', $filename)) $image = imagecreatefrompng($path);
        if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) $image = imagecreatefromjpeg($path);
        $w = 150;
        $src_width = imagesx($image);
        $src_height = imagesy($image);
        $dest = imagecreatetruecolor($w, $w);
        imagecopyresampled($dest, $image, 0, 0, 0, 0, $w, $w, min($src_width,$src_height), min($src_width,$src_height));
        unlink($path);
        $path = 'uploads/'.date('dmYHis').$q['id'].'.jpg';
        imagejpeg($dest, $path);
        imagedestroy($dest);
        $avatar = $path;
        if($q['ava']!='') {
        	unlink($q['ava']);
        }
	}
	else {
		$avatar=$q['ava'];
	}
	$name=trim($_POST['name']);
	$sname=trim($_POST['sname']);
	$db->exec('update `users` set `name`="'.htmlspecialchars($name).'", `sname`="'.htmlspecialchars($sname).'", `ava`="'.$avatar.'", `type`="'.$tp.'" where `login`="'.$usernm.'";');
	$_SESSION['type']="showProfile";
	header("Location: index.php");
?>