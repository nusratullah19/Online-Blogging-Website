<!DOCTYPE html>
<html>
<head>
	<title>Social Network</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>
	<div class="container">
		<div class="header_wrapper">
			<div class="header">
				<img src="images/logo.png">
				<form method="post" action="login.php" id="form1">
					<strong>Email</strong>
					<input type="email" name="email" placeholder="Email...." required="">
					<strong>Password</strong>
					<input type="password" name="pass" placeholder="Password...." required="">
					<input type="submit" value="Login" name="login" style="background-color: orange; height: 30px;">
				</form>
			</div>
		</div>
		<div class="clr"></div>
		
		<div class="content">
			<div class="banner">
			<h2>Join the largest education network</h2>
				<img src="images/bannerbg.jpg">
			</div>
			<div class="form2">
				<div class="form-section">
				<h2><center>Signup Today!</center></h2>
				<form action="user_insert.php" method="post">
					<label>Name:</label>
					<input type="text" name="u_name" placeholder="Enter Username...." required="required" style="margin-left: 49px;"><br>
					<label>Password:</label>
					<input type="password" name="u_password" placeholder="Enter password...." required="required" style="margin-left: 17px;"><br>
					<label>Email:</label>
					<input type="text" name="u_email" placeholder="Enter email...." required="required" style="margin-left: 50px;"><br>
					
					<label>Country:</label>
					<select name="u_country" style="margin-left: 30px;">
							<option>Select country</option>
							<option>Pakistan</option>
							<option>India</option>
							<option>Afghanistan</option>
							<option>China</option>
							<option>Iraq</option>
							<option>Iran</option>
							<option>Turkey</option>
							<option>USA</option>

					</select><br>
					<label>Gender:</label>
					<select name="u_gender" style="margin-left: 35px;">
						<option>Select gender</option>
						<option>Male</option>
						<option>Female</option>
						

					</select><br>
					<label>Birthday:</label>
					<input type="date" name="u_birthday" placeholder="Enter birthday...." required="required" style="margin-left: 26px;"><br>

					<input type="submit" value="Submit" name="submit" style="margin-left: 100px; width: 185px; background-color: orange;">

				</form>
				<?php include("user_insert.php"); ?>
				</div>
				
			</div>
		</div>
		<div class="clr"></div>

		<div class="footer">
			<h2>&copy; Copy All Rigth Reserved 2024 Design by NusratUllah</h2>
		</div>
	</div>
</body>
</html>