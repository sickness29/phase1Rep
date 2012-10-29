<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
		<title>Phase 1</title>
	</head>
	<body>
		<?php
			include_once 'connect.php';
			if(isset($_SESSION['username'])) {
				$db->exec('update `users` set `authdate` = "'.time().'" where `login`="'.$_SESSION['username'].'";');
			}
			if(!isset($_SESSION['lng']) || (isset($_SESSION['lng']) && $_SESSION['lng']=='en')) {
				$_SESSION['lng']='en';
				?>
				<a style="float:right" href="changelng.php?lng=ua"><img src="uploads/ua.jpg"></a><br>
				<?php
			}
			else {
				?>
				<a style="float:right" href="changelng.php?lng=en"><img src="uploads/en.jpg"></a><br>
				<?php	
			}
			$language=$db->query('select * from `languages` where `language`="'.$_SESSION['lng'].'";')->fetch(PDO::FETCH_ASSOC);
			echo @$_SESSION['errs'];
			unset($_SESSION['errs']);
			if(isset($_SESSION['username']) && $_SESSION['usertype']!=3) {
				?>
				<h5 align="right">
					<?php
					echo $language['1'].' '.$_SESSION['username'];
					if ($_SESSION['usertype']==0) {
						?>
						(<?php echo $language['22']; ?>), 
						<a href="logout.php"><?php echo $language['7']; ?></a><br>
						<form action="index.php" method="get">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="<?php echo $language['8']; ?>">
						</form>
						<form action="index.php" method="get">
							<input type="hidden" name="userlist" value="1">
							<input type="submit" value="<?php echo $language['9']; ?>">
						</form>
						<form action="index.php" method="get">
							<input type="hidden" name="editingTrans" value="1">
							<input type="submit" value="<?php echo $language['20']; ?>">
						</form>
						<?php
					}
					if ($_SESSION['usertype']==1) {
						?>
						(<?php echo $language['24']; ?>), 
						<a href="logout.php"><?php echo $language['7']; ?></a><br>
						<form action="index.php" method="get">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="<?php echo $language['8']; ?>">
						</form>
						<?php
					}
					if ($_SESSION['usertype']==2) {
						?>
						(<?php echo $language['23']; ?>), 
						<a href="logout.php"><?php echo $language['7']; ?></a><br>
						<form action="index.php" method="get">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="<?php echo $language['8']; ?>">
						</form>
						<?php
					}
					?>
				<a href="index.php"><?php echo $language['6']; ?></a>
				</h5>
				<?php
			}
			elseif(isset($_SESSION['username']) && $_SESSION['usertype']==3) {
				?>
				<h5 align="right">
					<a href="logout.php"><?php echo $language['7']; ?></a><br>
					<a href="index.php"><?php echo $language['6']; ?></a>
				</h5>
				<?php
			}
			else {
				?>
				<h5 align="right">
					<form action="auth.php" method="post">
						<?php echo $language['2']; ?>:<input type="text" size="10px" name="login" maxlength="30"><br>
						<?php echo $language['3']; ?>:<input type="password" size="10px" name="password" maxlength="30"><br>
						<input type="submit" value="<?php echo $language['4']; ?>">
					</form>
					<form action="index.php" method="get">
						<input type="hidden" name="type" value="register">
						<input type="submit" value="<?php echo $language['5']; ?>">
					</form>
					<a href="index.php"><?php echo $language['6']; ?></a>
				</h5>
				<?php
			}
			if(isset($_GET['type']) && $_GET['type']=="register" && !isset($_SESSION['username'])) {
				//registration
				?>
				<table align="center">
					<form action="registration.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
						<tr><td align="right"><?php echo $language['2']; ?>: </td><td><input type="text" name="login" maxlength="30"></td></tr>
						<tr><td align="right">E-mail: </td><td><input type="text" name="e_mail" maxlength="30"></td></tr>
						<tr><td align="right"><?php echo $language['3']; ?>: </td><td><input type="password" name="pass1" maxlength="30"></td></tr>
						<tr><td align="right"><?php echo $language['3']; ?>: </td><td><input type="password" name="pass2" maxlength="30"></td></tr>
						<tr><td colspan="2" align="center"><input type="submit" value="<?php echo $language['5']; ?>"></td></tr>
					</form>
				</table>
				<?php
			}
			elseif ((isset($_GET['type']) && $_GET['type']=="showProfile") || (isset($_SESSION['type']) && $_SESSION['type']=="showProfile")) {
				//show profile
				unset($_SESSION['type']);
				$q=$db->query('select * from `users` where `login`="'.$_SESSION['username'].'";');
				$q=$q->fetch(PDO::FETCH_ASSOC);
				if ($q['ava']=='') {
					$ava='/uploads/no_ava.png';
				}
				else {
					$ava=$q['ava'];
				}
				?>
				<table align="center" width="400px" border="1">
					<tr>
						<td rowspan="7" align="center" valign="center">
							<img src="<?php echo $ava; ?>">
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo $language['2']; ?>: <?php echo $q['login']; ?> 
						</td>
					</tr>
						<?php
					if($q['name']!='') {
						?>
						<tr>
							<td align="left">
								<?php echo $language['13']; ?>: <?php echo $q['name']; ?> 
							</td>
						</tr>
						<?php
					}	
					if($q['sname']!='') {
						?>
						<tr>
							<td align="left">
								<?php echo $language['14']; ?>: <?php echo $q['sname']; ?> 
							</td>
						</tr>
						<?php
					}
						?>
					<tr>
						<td align="left">
							E-mail: <?php echo $q['e_mail']; ?> 
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo $language['15']; ?>: <?php echo date('H:i d.m.Y',$q['regdate']); ?> 
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo $language['16']; ?>: <?php echo date('H:i d.m.Y',$q['authdate']); ?> 
						</td>
					</tr>
				</table>
				<center>
					<form action="index.php" method="get" >
						<input type="hidden" name="type" value="editProfile">
						<input type="submit" value="<?php echo $language['17']; ?>">
					</form>
					<form action="delprofile.php" method="post" >
						<input type="submit" value="<?php echo $language['18']; ?>">
					</form>
				</center>
				<?php
			}
			elseif ((isset($_GET['type']) && $_GET['type']=="editProfile") || (isset($_SESSION['type']) && $_SESSION['type']=="editProfile")) {
				//edit profile
				unset($_SESSION['type']);
				if($_SESSION['usertype']==0 && isset($_GET['username'])) {
					$usernm=$_GET['username'];
				}
				else {
					$usernm=$_SESSION['username'];
				}
				$q=$db->query('select * from `users` where `login`="'.$usernm.'";');
				$q=$q->fetch(PDO::FETCH_ASSOC);
				if ($q['ava']=='') {
					$ava='/uploads/no_ava.png';
				}
				else {
					$ava=$q['ava'];
				}
				?>
				<form action="update.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="username" value="<?php echo $usernm; ?>">
				<table align="center" width="600px" border="1">
					<tr>
						<td align="center" rowspan="5" align="center" valign="center">
							<img src="<?php echo $ava; ?>">
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo $language['13']; ?>: <input name="name" type="text" maxlength="30" value="<?php echo $q['name']; ?>"> 
						</td>
					</tr>
					<tr>
						<td align="left">
							<?php echo $language['14']; ?>: <input name="sname" type="text" maxlength="30" value="<?php echo $q['sname']; ?>"> 
						</td>
					</tr>
					<tr>
						<td align="left">
							E-mail: <?php echo $q['e_mail']; ?>
						</td>
					</tr>
					<tr>
						<td align="left">
							<input type="file" name="newAva" size="20" accept="image/jpeg,image/png,image/gif">
						</td>
					</tr>
					<?php
					if($usernm!=$_SESSION['username'] && $_SESSION['usertype']==0) {
						?>
						<tr>
							<td align="left" colspan="2">
								<?php echo $language['21']; ?>: 
								<?php
								if($q['type']==0) {
									?>
									<input type="radio" name="type" value="0" checked="true"><?php echo $language['22']; ?>
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="0"><?php echo $language['22']; ?>
									<?php
								} 
								if($q['type']==1) {
									?>
									<input type="radio" name="type" value="1" checked="true"><?php echo $language['24']; ?>
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="1"><?php echo $language['24']; ?>
									<?php
								}
								if($q['type']==2) {
									?>
									<input type="radio" name="type" value="2" checked="true"><?php echo $language['23']; ?>
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="2"><?php echo $language['23']; ?>
									<?php
								}
								if($q['type']==3) {
									?>
									<input type="radio" name="type" value="3" checked="true"><?php echo $language['25']; ?>
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="3"><?php echo $language['25']; ?>
									<?php
								}
								?>
							</td>
					</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" value="<?php echo $language['10']; ?>">
						</td>
					</tr>
				</table>
				</form>
				<?php
			}
			elseif(isset($_GET['editingTrans']) && $_GET['editingTrans']=='1' && $_SESSION['usertype']==0) {
				$lang1=$db->query('select * from `languages` where `language`="en";')->fetch(PDO::FETCH_ASSOC);
				$lang2=$db->query('select * from `languages` where `language`="ua";')->fetch(PDO::FETCH_ASSOC);
				?>
				<form action="editlng.php" method="post">
					<input type="hidden" name="len" value="<?php echo count($lang1); ?>"> 
					<table border="1" align="center" width="800px">
						<tr><td align="center">EN:</td><td align="center">UA:</td></tr>
						<?php
						for($i=0;$i<count($lang1)-1;$i++) {
							?>
							<tr>
								<td align="center">
									<input type="text" name="<?php echo $i.'en'; ?>" value="<?php echo $lang1[$i]; ?>">
								</td>
								<td align="center">
									<input type="text" name="<?php echo $i.'ua'; ?>" value="<?php echo $lang2[$i]; ?>">
								</td>
							</tr>
							<?php
						}
						?>
					</table>
					<center><input type="submit" value="<?php echo $language['19']; ?>"></center>
				</form>
				<?php
			}
			else {
				if(isset($_SESSION['usertype']) && $_SESSION['usertype']==0) {
					//enter as admin
					if(isset($_GET['userlist']) && $_GET['userlist']==1) {
						$q=$db->query('select `id`, `login` from `users` where `login`<>"'.$_SESSION['username'].'";');
						?>
						<table align="center" width="800px" border="1">
						<?php
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							?>
							<tr>
								<td>
									<?php echo $row['login']; ?>
								</td>
								<td width="20%">
									<form action="index.php" method="get">
										<input type="hidden" name="username" value="<?php echo $row['login']; ?>">
										<input type="hidden" name="type" value="editProfile">
										<input type="submit" value="<?php echo $language['11']; ?>">
									</form>
								</td>
								<td width="20%">
									<form action="delprofile.php" method="post">
										<input type="hidden" name="userid" value="<?php echo $row['id']; ?>">
										<input type="submit" value="<?php echo $language['12']; ?>">
									</form>
								</td>
							</tr>
							<?php
						}
						?>
						</table>
						<?php
					}
					elseif(isset($_GET['id'])) {
						if(isset($_GET['editing']) && $_GET['editing']==1) {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							$q=$q->fetch(PDO::FETCH_ASSOC);
							?>
							<table align="center" border="5" width="600px">
							<form action="edit.php" method="post">
							<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
							<tr><td align="center"><input type="text" name="titleua" size="100px" value="<?php echo $q['titleua']; ?>"></td></tr>
							<tr><td align="center">UA: <textarea style="resize: none" rows="10" cols="45" name="dataua"><?php echo $q['dataua']; ?></textarea></td></tr>
							<tr><td align="center"><input type="text" name="titleen" size="100px" value="<?php echo $q['titleen']; ?>"></td></tr>
							<tr><td align="center">EN: <textarea style="resize: none" rows="10" cols="45" name="dataen"><?php echo $q['dataen']; ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="<?php echo $language['19']; ?>"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							if($q->rowCount()==1) {
								$q=$q->fetch(PDO::FETCH_ASSOC);
								?>
								<h2 align="center"><?php echo $q['title'.$_SESSION['lng']]; ?></h2>
								<table border="1" align="center" width="800px">
									<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
									<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data'.$_SESSION['lng']]; ?></td></tr>
									<tr>
										<td align="right">
											<form action="index.php" method="get">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="hidden" name="editing" value="1">
												<input type="submit" value="<?php echo $language['11']; ?>">
											</form>
										</td>
										<td align="left">
											<form action="delete.php" method="post">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="submit" value="<?php echo $language['12']; ?>">
											</form>
										</td>
									</tr>
								</table>
								<?php
							}
							else {
								header('location: index.php');
							}
						}
					}
					else {
						?>
						<h2 align="center"><?php echo $language['0']; ?></h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'.$_SESSION['lng']])>150) {
								$data=substr($row['data'.$_SESSION['lng']],0,150);
								$res=strpos($data,' ');
								if($res===false || $res>150) {}
								else {
									while($data{strlen($data)-1}!=' ') {
										$data=substr($data,0,strlen($data)-1);
									}
								}
								$data.='...';
							}
							else {
								$data=$row['data'.$_SESSION['lng']];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title'.$_SESSION['lng']]; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $language['27']; ?></a></td></tr>
							</table><br>
							<?php
						}
						?>
						<table align="center" border="5" width="600px">
						<form action="send.php" method="post">
						<tr><td align="center"><input type="text" name="titleua" placeholder="Заголовок" size="100px"></td></tr>
						<tr><td align="center">UA: <textarea style="resize: none" rows="10" cols="45" name="dataua"></textarea></td></tr>
						<tr><td align="center"><input type="text" name="titleen" placeholder="Title" size="100px"></td></tr>
						<tr><td align="center">EN: <textarea style="resize: none" rows="10" cols="45" name="dataen"></textarea></td></tr>
						<tr><td align="center"><input type="submit" value="<?php echo $language['10']; ?>"></td></tr>
						</form>
						</table>
						<?php
					}
				}
				elseif(isset($_SESSION['username']) && $_SESSION['usertype']!=3) {
					//enter as user or editor
					if(isset($_GET['id'])) {
						if(@$_GET['editing']==1 && $_SESSION['usertype']==2) {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							$q=$q->fetch(PDO::FETCH_ASSOC);
							?>
							<table align="center" border="5" width="600px">
							<form action="edit.php" method="post">
							<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
							<tr><td align="center"><input type="text" name="titleua" size="100px" value="<?php echo $q['titleua']; ?>"></td></tr>
							<tr><td align="center">UA: <textarea style="resize: none" rows="10" cols="45" name="dataua"><?php echo $q['dataua']; ?></textarea></td></tr>
							<tr><td align="center"><input type="text" name="titleen" size="100px" value="<?php echo $q['titleen']; ?>"></td></tr>
							<tr><td align="center">EN: <textarea style="resize: none" rows="10" cols="45" name="dataen"><?php echo $q['dataen']; ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="<?php echo $language['19']; ?>"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							if($q->rowCount()==1) {
								$q=$q->fetch(PDO::FETCH_ASSOC);
								?>
								<h2 align="center"><?php echo $q['title'.$_SESSION['lng']]; ?></h2>
								<table border="1" align="center" width="800px">
									<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
									<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data'.$_SESSION['lng']]; ?></td></tr>
								<?php
								if ($_SESSION['usertype']==2) {
									?>
									<tr>
										<td align="right">
											<form action="index.php" method="get">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="hidden" name="editing" value="1">
												<input type="submit" value="<?php echo $language['11']; ?>">
											</form>
										</td>
										<td align="left">
											<form action="delete.php" method="post">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="submit" value="<?php echo $language['12']; ?>">
											</form>
										</td>
									</tr>
									<?php
								}
								?>
								</table>
								<?php
							}
							else {
								header('location: index.php');
							}
						}
					}
					else {
						?>
						<h2 align="center"><?php echo $language['0']; ?></h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'.$_SESSION['lng']])>150) {
								$data=substr($row['data'.$_SESSION['lng']],0,150);
								$res=strpos($data,' ');
								if($res===false || $res>150) {}
								else {
									while($data{strlen($data)-1}!=' ') {
										$data=substr($data,0,strlen($data)-1);
									}
								}
								$data.='...';
							}
							else {
								$data=$row['data'.$_SESSION['lng']];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title'.$_SESSION['lng']]; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $language['27']; ?></a></td></tr>
							</table><br>
							<?php
						}
						if ($_SESSION['usertype']==2) {
							?>
							<table align="center" border="5" width="600px">
							<form action="send.php" method="post">
							<tr><td align="center"><input type="text" name="titleua" placeholder="Заголовок" size="100px"></td></tr>
							<tr><td align="center">UA: <textarea style="resize: none" rows="10" cols="45" name="dataua"></textarea></td></tr>
							<tr><td align="center"><input type="text" name="titleen" placeholder="Title" size="100px"></td></tr>
							<tr><td align="center">EN: <textarea style="resize: none" rows="10" cols="45" name="dataen"></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="<?php echo $language['10']; ?>"></td></tr>
							</form>
							</table>
							<?php
						}
					}
				}
				elseif (isset($_SESSION['usertype']) && $_SESSION['usertype']==3) {
					?>
					<h2 align="center"><?php echo $language['26']; ?></h2>
					<?php
				}
				else {
					//not auth
					if(isset($_GET['id'])) {
						$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
						if($q->rowCount()==1) {
							$q=$q->fetch(PDO::FETCH_ASSOC);
							?>
							<h2 align="center"><?php echo $q['title'.$_SESSION['lng']]; ?></h2>
							<table border="1" align="center" width="800px">
								<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
								<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data'.$_SESSION['lng']]; ?></td></tr>
							</table>
							<?php
						}
						else {
							header('location: index.php');
						}
					}
					else {
						?>
						<h2 align="center"><?php echo $language['0']; ?></h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'.$_SESSION['lng']])>150) {
								$data=substr($row['data'.$_SESSION['lng']],0,150);
								$res=strpos($data,' ');
								if($res===false || $res>150) {}
								else {
									while($data{strlen($data)-1}!=' ') {
										$data=substr($data,0,strlen($data)-1);
									}
								}
								$data.='...';
							}
							else {
								$data=$row['data'.$_SESSION['lng']];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title'.$_SESSION['lng']]; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $language['27']; ?></a></td></tr>
							</table><br>
							<?php
						}
					}
				}
			}
		?>
	</body>
</html>