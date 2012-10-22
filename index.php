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
			echo @$_SESSION['errs'];
			unset($_SESSION['errs']);
			if(isset($_POST['type'])) {
				//registration
				?>
				<table align="center">
					<form action="registration.php" method="post">
						<input type="hidden" name="type" value="<?php echo $_POST['type']; ?>">
						<tr><td align="right">Login: </td><td><input type="text" name="login"></td></tr>
						<tr><td align="right">E-mail: </td><td><input type="text" name="e_mail"></td></tr>
						<tr><td align="right">Password: </td><td><input type="password" name="pass1"></td></tr>
						<tr><td align="right">Password: </td><td><input type="password" name="pass2"></td></tr>
						<tr><td colspan="2" align="center"><input type="submit" value="Register"></td></tr>
					</form>
				</table>
				<?php
			}
			else {
				mysql_connect('127.0.0.1','root','rootmysql');
				mysql_selectdb('phase1');
				if(@$_SESSION['admin']==1) {
					//enter as admin
					?>
					<h5 align="right">
						You are logged as <?php echo $_SESSION['username']; ?> (admin), 
						<a href="logout.php">Log out</a><br>
						<form action="index.php" method="post">
							<input type="hidden" name="type" value="admin">
							<input type="submit" value="Register new admin">
						</form>
					</h5>
					<?php
					if(isset($_GET['id'])) {
						if(@$_GET['editing']==1) {
							$q=mysql_query('select * from `data` where `id`="'.$_GET['id'].'";');
							$q=mysql_fetch_assoc($q);
							?>
							<table align="center" border="5" width="600px">
							<form action="edit.php" method="post">
							<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
							<tr><td align="center"><input type="text" name="title" size="100px" value="<?php echo htmlspecialchars_decode($q['title']); ?>"></td></tr>
							<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"><?php echo htmlspecialchars_decode($q['data']); ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="Save"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=mysql_query('select * from `data` where `id`="'.$_GET['id'].'";');
							if(mysql_num_rows($q)==1) {
								$q=mysql_fetch_assoc($q);
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
						$q=mysql_query('select * from `data` order by `id` desc;');
						for($i=0;$i<mysql_num_rows($q);$i++) {
							if(strlen(mysql_result($q,$i,'data'))>150) {
								$data=substr(mysql_result($q,$i,'data'),0,150);
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
								$data=mysql_result($q,$i,'data');
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo mysql_result($q,$i,'username'); ?></td><td align="right"><?php echo mysql_result($q,$i,'time'); ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>"><?php echo mysql_result($q,$i,'title'); ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>">Read more...</a></td></tr>
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
				elseif(isset($_SESSION['username'])) {
					//enter as user
					?>
					<h5 align="right">
						You are logged as <?php echo $_SESSION['username']; ?>, 
						<a href="logout.php">Log out</a>
					</h5>
					<?php
					if(isset($_GET['id'])) {
						if(@$_GET['editing']==1) {
							$q=mysql_query('select * from `data` where `id`="'.$_GET['id'].'";');
							$q=mysql_fetch_assoc($q);
							?>
							<table align="center" border="5" width="600px">
							<form action="edit.php" method="post">
							<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
							<tr><td align="center"><input type="text" name="title" size="100px" value="<?php echo htmlspecialchars_decode($q['title']); ?>"></td></tr>
							<tr><td align="center"><textarea style="resize: none" rows="10" cols="45" name="data"><?php echo htmlspecialchars_decode($q['data']); ?></textarea></td></tr>
							<tr><td align="center"><input type="submit" value="Save"></td></tr>
							</form>
							</table>
							<?php
						}
						else {
							$q=mysql_query('select * from `data` where `id`="'.$_GET['id'].'";');
							if(mysql_num_rows($q)==1) {
								$q=mysql_fetch_assoc($q);
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
						$q=mysql_query('select * from `data` order by `id` desc;');
						for($i=0;$i<mysql_num_rows($q);$i++) {
							if(strlen(mysql_result($q,$i,'data'))>150) {
								$data=substr(mysql_result($q,$i,'data'),0,150);
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
								$data=mysql_result($q,$i,'data');
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo mysql_result($q,$i,'username'); ?></td><td align="right"><?php echo mysql_result($q,$i,'time'); ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>"><?php echo mysql_result($q,$i,'title'); ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>">Read more...</a></td></tr>
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
				else {
					//not auth
					?>
					<h5 align="right">
						<form action="auth.php" method="post">
							Login:<input type="text" size="10px" name="login"><br>
							Password:<input type="password" size="10px" name="password"><br>
							<input type="submit" value="Log in">
						</form>
						<form action="index.php" method="post">
							<input type="hidden" name="type" value="user">
							<input type="submit" value="Register new user">
						</form>
					</h5>
					<?php
					if(isset($_GET['id'])) {
						$q=mysql_query('select * from `data` where `id`="'.$_GET['id'].'";');
						if(mysql_num_rows($q)==1) {
							$q=mysql_fetch_assoc($q);
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
						$q=mysql_query('select * from `data` order by `id` desc;');
						for($i=0;$i<mysql_num_rows($q);$i++) {
							if(strlen(mysql_result($q,$i,'data'))>150) {
								$data=substr(mysql_result($q,$i,'data'),0,150);
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
								$data=mysql_result($q,$i,'data');
							}
							?>
							<table border="2" align="center" width="800px">
								<tr><td align="left" width="50%"><?php echo mysql_result($q,$i,'username'); ?></td><td align="right"><?php echo mysql_result($q,$i,'time'); ?></td></tr>
								<tr><td colspan="2" align="center" ><font size="5"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>"><?php echo mysql_result($q,$i,'title'); ?></a></font></td></tr>
								<tr><td colspan="2" align="justify" height="100px" colspec="95%"><?php echo $data; ?></td></tr>
								<tr><td colspan="2" align="right"><a href="index.php?id=<?php echo mysql_result($q,$i,'id'); ?>">Read more...</a></td></tr>
							</table><br>
							<?php
						}
					}
				}
				mysql_close();
			}
		?>
	</body>
</html>