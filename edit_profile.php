<!DOCTYPE html>
<?php
session_start();
include("includes/connection.php");
include("functions/functions.php");
if (!isset($_SESSION['user_email'])) {
	# code...
	header("location: index.php");
}

?>
<html>
<head>
	<title>social network</title>
	<link rel="stylesheet" type="text/css" href="styles/home_sty.css">
			<link rel="stylesheet" type="text/css" href="styles/home.css">

</head>
<body>
	<div class="container">
		<div id="head_wrap">
			<div id="header">
				<ul id="menu">
					<li><a href="home.php">Home</a></li>
					<li><a href="members.php">Members</a></li>
					<strong>Topics:</strong>
					<?php
					$get_topics = "SELECT * FROM topics";
					$run_topics = mysqli_query($conn,$get_topics);
					while($row = mysqli_fetch_array($run_topics))
					{
						$topic_id = $row['topic_id'];
						$topic_name = $row['topic_name'];

						echo "<li><a href='topic.php?topic=$topic_id'>$topic_name</a></li>";
					}
					?>
				</ul>
				<form method="get" action="result.php" id="form1">
					<input type="text" name="user_query" placeholder="Search a topic">
					<input type="submit" name="search" value="search">
				</form>
			</div>
		</div>

		<div class="content">
			<div id="user_timeline">
				<div id="user_detail">
					<?php
						$user = $_SESSION['user_email'];
						$get_user = "SELECT * FROM users WHERE user_email ='$user'";
						$run_user = mysqli_query($conn,$get_user);
						$row = mysqli_fetch_array($run_user);

						$user_id = $row['user_id'];
						$user_name = $row['user_name'];
						$user_pass = $row['user_password'];
						$user_email = $row['user_email'];
						$user_country = $row['user_country'];
						$user_gender = $row['user_gender'];
						$user_image = $row['user_image'];
						$register_date = $row['user_reg_date'];
						$last_login = $row['user_last_login'];

						$user_posts = "SELECT * FROM posts where user_id ='$user_id'";
						$run_posts = mysqli_query($conn,$user_posts);
						$posts = mysqli_num_rows($run_posts);

						$sel_msg = "SELECT * FROM messages where receiver = '$user_id' AND status = 'unread' ORDER BY 1 DESC";
						$run_msg = mysqli_query($conn,$sel_msg);
						$count_msg = mysqli_num_rows($run_msg);

						echo "
							<center>
							<img src ='users/$user_image' width='200' height='200'>
							</center>			
							<div id='user_mention'>
								<p><strong>Name:</strong>$user_name</p>
								<p><strong>Country:</strong>$user_country</p>
								<p><strong>Last Login:</strong>$last_login</p>
								<p><strong>Member since:</strong>$register_date</p>

								<p><a href='my_messages.php?inbox&u_id = $user_id'>Messages($count_msg)</a></p>
								<p><a href='my_posts.php?u_id=$user_id'>My Posts($posts)</a></p>
								<p><a href='edit_profile.php?u_id=$user_id'>Edit My Account</a></p>
								<p><a href='logout.php'>Logout</a></p>


							</div>			
							";
					?>
				</div>
			</div>
			<div>
				<form action="" method="post" id="f" class="ff" enctype="multipart/form-data">
					<table>
						<tr align="center">
							<td colspan="6"><h2>Edit your profile:</h2></td>
						</tr>
						<tr>
							<td align="right"><h2>Name:</h2></td>
							<td><input type="text" name="u_name" required="required" value="<?php echo $user_name; ?>"></td>
						</tr>
						<tr>
							<td align="right"><h2>Password:</h2></td>
							<td><input type="text" name="u_pass" required="required" value="<?php echo $user_pass; ?>"></td>
						</tr>
						<tr>
							<td align="right"><h2>Email:</h2></td>
							<td><input type="text" name="u_email" required="required" value="<?php echo $user_email; ?>"></td>
						</tr>
						<tr>
							<td align="right"><h2>Country:</h2></td>
							<td>
								<select name="u_country" disabled="disabled">
									<option><?php echo $user_country; ?></option>
									<option>Afghanistan</option>
									<option>Pakistan</option>
									<option>India</option>
									<option>Iran</option>
									<option>Iraq</option>
									<option>USA</option>
									<option>Africa</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"><h2>Gender:</h2></td>
							<td>
								<select name="u_gender" disabled="disabled">
									<option><?php echo $user_gender; ?></option>
									<option>male</option>
									<option>Female</option>
									
								</select>
							</td>
						</tr>
						<tr>
							<td align="right"><h2>Photo:</h2></td>
							<td><input type="file" name="uploadfile" required="required"></td>
						</tr>
						<tr align="center">
							
							<td colspan="6"><input type="submit" name="update" value="update"></td>
						</tr>
					</table>
				</form>
				<?php
					

					if(isset($_POST['update']))
					{
					    $u_name =$_POST['u_name'];
					    $u_pass = $_POST['u_pass'];
						$u_email = $_POST['u_email'];
						//$u_image = $_FILES['u_image']['name'];
						//$image_tmp = $_FILES['u_image']['tmp_name'];

						//move_uploaded_file($image_tmp,"social_network/users/$u_image");
						$filename = $_FILES["uploadfile"]["name"];
						$tempname = $_FILES["uploadfile"]["tmp_name"];
						$folder = "users/".$filename;
						move_uploaded_file($tempname, $folder);
				
						
						$update = "UPDATE users SET user_name='$u_name',user_password='$u_pass',user_email='$u_email',user_image='$filename' WHERE user_id='$user_id'";
						$run = mysqli_query($conn,$update);
						
						if ($run) {
							# code...
							echo "<script>alert('Your Profile Updated')</script>";
							echo "<script>window.open('home.php','_self')</script>";
						}

						
					}

?>
			</div>
			</div>
		</div>
	</div>
</body>
</html>
