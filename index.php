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
				?>
				<a style="float:right" href="changelng.php?lng=ua"><img src="uploads/ua.jpg"></a><br>
				<?php
			}
			else {
				?>
				<a style="float:right" href="changelng.php?lng=en"><img src="uploads/en.jpg"></a><br>
				<?php	
			}
			echo @$_SESSION['errs'];
			unset($_SESSION['errs']);
			if(isset($_SESSION['username']) && $_SESSION['usertype']!=3) {
				?>
				<h5 align="right">
					You are logged as <?php echo $_SESSION['username']; ?> 
					<?php
					if ($_SESSION['usertype']==0) {
						?>
						(admin), 
						<a href="logout.php">Log out</a><br>
						<form action="index.php" method="post">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="Show profile">
						</form>
						<form action="index.php" method="post">
							<input type="hidden" name="userlist" value="1">
							<input type="submit" value="Show user profiles">
						</form>
						<?php
					}
					if ($_SESSION['usertype']==1) {
						?>
						, 
						<a href="logout.php">Log out</a><br>
						<form action="index.php" method="post">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="Show profile">
						</form>
						<?php
					}
					if ($_SESSION['usertype']==2) {
						?>
						(editor), 
						<a href="logout.php">Log out</a><br>
						<form action="index.php" method="post">
							<input type="hidden" name="type" value="showProfile">
							<input type="submit" value="Show profile">
						</form>
						<?php
					}
					?>
				<a href="index.php">Go to main page</a>
				</h5>
				<?php
			}
			elseif(isset($_SESSION['username']) && $_SESSION['usertype']==3) {
				?>
				<h5 align="right">
					<a href="logout.php">Log out</a><br>
					<a href="index.php">Go to main page</a>
				</h5>
				<?php
			}
			else {
				?>
				<h5 align="right">
					<form action="auth.php" method="post">
						Login:<input type="text" size="10px" name="login" maxlength="30"><br>
						Password:<input type="password" size="10px" name="password" maxlength="30"><br>
						<input type="submit" value="Log in">
					</form>
					<form action="index.php" method="post">
						<input type="hidden" name="type" value="register">
						<input type="submit" value="Register new user">
					</form>
					<a href="index.php">Go to main page</a>
				</h5>
				<?php
			}
			if(isset($_POST['type']) && $_POST['type']=="register") {
				//registration
				?>
				<table align="center">
					<form action="registration.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
						<tr><td align="right">Login: </td><td><input type="text" name="login" maxlength="30"></td></tr>
						<tr><td align="right">E-mail: </td><td><input type="text" name="e_mail" maxlength="30"></td></tr>
						<tr><td align="right">Password: </td><td><input type="password" name="pass1" maxlength="30"></td></tr>
						<tr><td align="right">Password: </td><td><input type="password" name="pass2" maxlength="30"></td></tr>
						<tr><td colspan="2" align="center"><input type="submit" value="Register"></td></tr>
					</form>
				</table>
				<?php
			}
			elseif ((isset($_POST['type']) && $_POST['type']=="showProfile") || (isset($_SESSION['type']) && $_SESSION['type']=="showProfile")) {
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
							Login: <?php echo $q['login']; ?> 
						</td>
					</tr>
						<?php
					if($q['name']!='') {
						?>
						<tr>
							<td align="left">
								Name: <?php echo $q['name']; ?> 
							</td>
						</tr>
						<?php
					}	
					if($q['sname']!='') {
						?>
						<tr>
							<td align="left">
								Second Name: <?php echo $q['sname']; ?> 
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
							Registered: <?php echo date('H:i d.m.Y',$q['regdate']); ?> 
						</td>
					</tr>
					<tr>
						<td align="left">
							Last log in: <?php echo date('H:i d.m.Y',$q['authdate']); ?> 
						</td>
					</tr>
				</table>
				<center>
					<form action="index.php" method="post" >
						<input type="hidden" name="type" value="editProfile">
						<input type="submit" value="Edit Profile">
					</form>
					<form action="delprofile.php" method="post" >
						<input type="submit" value="Delete Profile">
					</form>
				</center>
				<?php
			}
			elseif ((isset($_POST['type']) && $_POST['type']=="editProfile") || (isset($_SESSION['type']) && $_SESSION['type']=="editProfile")) {
				//edit profile
				unset($_SESSION['type']);
				if($_SESSION['usertype']==0 && isset($_POST['username'])) {
					$usernm=$_POST['username'];
					unset($_POST['username']);
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
							Name: <input name="name" type="text" maxlength="30" value="<?php echo $q['name']; ?>"> 
						</td>
					</tr>
					<tr>
						<td align="left">
							Second Name: <input name="sname" type="text" maxlength="30" value="<?php echo $q['sname']; ?>"> 
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
					if($usernm!=$_SESSION['username']) {
						?>
						<tr>
							<td align="left">
								Type: 
								<?php
								if($q['type']==0) {
									?>
									<input type="radio" name="type" value="0" checked="true">Admin
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="0">Admin
									<?php
								} 
								if($q['type']==1) {
									?>
									<input type="radio" name="type" value="1" checked="true">User
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="1">User
									<?php
								}
								if($q['type']==2) {
									?>
									<input type="radio" name="type" value="2" checked="true">Editor
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="2">Editor
									<?php
								}
								if($q['type']==3) {
									?>
									<input type="radio" name="type" value="3" checked="true">Banned
									<?php
								}
								else {
									?>
									<input type="radio" name="type" value="3">Banned
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
							<input type="submit" value="Send">
						</td>
					</tr>
				</table>
				</form>
				<?php
			}
			else {
				if(isset($_SESSION['usertype']) && $_SESSION['usertype']==0) {
					//enter as admin
					if(isset($_POST['userlist']) && $_POST['userlist']==1) {
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
									<form action="index.php" method="post">
										<input type="hidden" name="username" value="<?php echo $row['login']; ?>">
										<input type="hidden" name="type" value="editProfile">
										<input type="submit" value="Edit">
									</form>
								</td>
								<td width="20%">
									<form action="delprofile.php" method="post">
										<input type="hidden" name="userid" value="<?php echo $row['id']; ?>">
										<input type="submit" value="Delete">
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
							<tr><td align="center"><input type="text" name="title" size="100px" value="<?php echo $q['title']; ?>"></td></tr>
							<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"><?php echo $q['data']; ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="Save"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							if($q->rowCount()==1) {
								$q=$q->fetch(PDO::FETCH_ASSOC);
								?>
								<h2 align="center"><?php echo $q['title']; ?></h2>
								<table border="1" align="center" width="800px">
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
									<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
									<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data']; ?></td></tr>
									<tr>
										<td align="right">
											<form action="index.php" method="get">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="hidden" name="editing" value="1">
												<input type="submit" value="Edit">
											</form>
										</td>
										<td align="left">
											<form action="delete.php" method="post">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="submit" value="Delete">
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
						<h2 align="center">Welcome to main page</h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'])>150) {
								$data=substr($row['data'],0,150);
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
								$data=$row['data'];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>">Read more...</a></td></tr>
							</table><br>
							<?php
						}
						?>
						<table align="center" border="5" width="600px">
						<form action="send.php" method="post">
						<tr><td align="center"><input type="text" name="title" placeholder="Title" size="100px"></td></tr>
						<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"></textarea></td></tr>
						<tr><td align="center"><input type="submit" value="Send"></td></tr>
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
							<tr><td align="center"><input type="text" name="title" size="100px" value="<?php echo $q['title']; ?>"></td></tr>
							<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"><?php echo $q['data']; ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="Save"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
							if($q->rowCount()==1) {
								$q=$q->fetch(PDO::FETCH_ASSOC);
								?>
								<h2 align="center"><?php echo $q['title']; ?></h2>
								<table border="1" align="center" width="800px">
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
									<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
									<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data']; ?></td></tr>
								<?php
								if ($_SESSION['usertype']==2) {
									?>
									<tr>
										<td align="right">
											<form action="index.php" method="get">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="hidden" name="editing" value="1">
												<input type="submit" value="Edit">
											</form>
										</td>
										<td align="left">
											<form action="delete.php" method="post">
												<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
												<input type="submit" value="Delete">
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
						<h2 align="center">Welcome to main page</h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'])>150) {
								$data=substr($row['data'],0,150);
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
								$data=$row['data'];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>">Read more...</a></td></tr>
							</table><br>
							<?php
						}
						if ($_SESSION['usertype']==2) {
							?>
							<table align="center" border="5" width="600px">
							<form action="send.php" method="post">
							<tr><td align="center"><input type="text" name="title" placeholder="Title" size="100px"></td></tr>
							<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="Send"></td></tr>
							</form>
							</table>
							<?php
						}
					}
				}
				elseif (isset($_SESSION['usertype']) && $_SESSION['usertype']==3) {
					?>
					<h2 align="center">You was banned</h2>
					<?php
				}
				else {
					//not auth
					if(isset($_GET['id'])) {
						$q=$db->query('select * from `data` where `id`="'.$_GET['id'].'";');
						if($q->rowCount()==1) {
							$q=$q->fetch(PDO::FETCH_ASSOC);
							?>
							<h2 align="center"><?php echo $q['title']; ?></h2>
							<table border="1" align="center" width="800px">
								<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
								<tr><td align="left"  width="50%"><?php echo $q['username']; ?></td><td align="right"><?php echo $q['time']; ?></td></tr>
								<tr><td colspan="2" align="justify" height="100px"><?php echo $q['data']; ?></td></tr>
							</table>
							<?php
						}
						else {
							header('location: index.php');
						}
					}
					else {
						?>
						<h2 align="center">Welcome to main page</h2>
						<?php
						$q=$db->query('select * from `data` order by `id` desc;');
						$nrow=$q->rowCount();
						for($i=0;$i<$nrow;$i++) {
							$row=$q->fetch(PDO::FETCH_ASSOC);
							if(strlen($row['data'])>150) {
								$data=substr($row['data'],0,150);
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
								$data=$row['data'];
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo $row['username']; ?></td><td align="right"><?php echo $row['time']; ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo $row['id']; ?>">Read more...</a></td></tr>
							</table><br>
							<?php
						}
					}
				}
			}
		?>
	</body>
</html>