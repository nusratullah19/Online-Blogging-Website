<!DOCTYPE html>
<?php
session_start();
include("includes/connection.php");
include("functions/functions.php");

?>
<html>
<head>
	<title>social network</title>
			<link rel="stylesheet" type="text/css" href="styles/home.css">

	<link rel="stylesheet" type="text/css" href="styles/home_sty.css">
</head>
<body>
	<div class="container">
		<div id="head_wrap">
			<div id="header">
				<ul id="menu">
					<li><a href="home.php">Home</a></li>
					<li><a href="members.php">Members</a></li>
					<strong class="heading">Topics:</strong>
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
						$user_country = $row['user_country'];
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

								<p><a href='my_messages.php?inbox&u_id=$user_id'>Messages($count_msg)</a></p>
								<p><a href='my_posts.php?u_id=$user_id'>My Posts($posts)</a></p>
								<p><a href='edit_profile.php?u_id=$user_id'>Edit My Account</a></p>
								<p><a href='logout.php'>Logout</a></p>


							</div>			
							";
					?>
				</div>
			</div>
			<div id="content_timeline">
			<?php
				if (isset($_GET['u_id'])) {
					# code...
					$u_id = $_GET['u_id'];
					$sel ="SELECT * FROM users WHERE user_id='$u_id'";
					$run = mysqli_query($conn,$sel);
					$row = mysqli_fetch_array($run);

					$user_name = $row['user_name'];
					$user_image = $row['user_image'];
					$reg_date = $row['user_reg_date'];

				}
			?>
			<h2>Send message to <span style='color: red;'><?php echo $user_name; ?></span></h2>
				<form action="message.php?u_id=<?php echo $u_id; ?>" method="post" id="f">
					<hr>
					<input type="text" name="msg_title" placeholder="Message title" size="82" required="required"><br>
					<textarea cols="83" rows="4" name="msg" placeholder="write your message description......."></textarea><br>
					
					<input type="submit" name="message" value="Send Message">

				</form>
				<img src="users/<?php echo $user_image; ?>" style="border:2px solid black;border-radius: 5px" width="100" height="100">
				<p><strong><?php echo $user_name; ?> </strong>is member of this site since: <?php echo $reg_date; ?></p>

				<?php
					if (isset($_POST['message'])) {
						# code...
						$msg_title = $_POST['msg_title'];
						$msg = $_POST['msg'];

						$insert ="INSERT INTO messages(sender,receiver,msg_sub,msg_topic,reply,status,msg_date) VALUES ('$user_id','$u_id','$msg_title','$msg','no_reply','unread',NOW())";

						$run_insert = mysqli_query($con,$insert);
						if ($run_insert) {
							# code...
							echo "<center><h2>Message was sent to ". $user_name ."</h2></center>";
						}
						else{
							echo "<center><h2>Message was sent not sent</h2></center>";

						}
					}
				?>
			</div>
			</div>
		</div>
	</div>
</body>
</html>