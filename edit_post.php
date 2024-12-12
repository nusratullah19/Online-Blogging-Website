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

								<p><a href='my_messages.php?inbox&u_id = $user_id'>Messages($count_msg)</a></p>
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
								if (isset($_GET['post_id'])) {
									# code...
									$get_id = $_GET['post_id'];
									$get_post ="SELECT * FROM posts WHERE post_id='$get_id'";
									$run_post = mysqli_query($conn,$get_post);
									$row = mysqli_fetch_array($run_post);

									$post_title = $row['post_title'];
									$post_con = $row['post_content'];
								}
					
					
				
							?>
					<form action="" method="post" id="f">
					<h3>Edit Your Post</h3>
					<hr>
					<input type="text" name="title" value="<?php echo $post_title;?>" size="82" required="required"><br>
					<textarea cols="83" rows="4" name="content"><?php echo $post_con;?></textarea><br>
					<select name="topic">
						<option>Select Topic</option>
						<?php
							$get_topics = "SELECT * FROM topics";
							$run_topics = mysqli_query($conn,$get_topics);
							while($row = mysqli_fetch_array($run_topics))
							{
								$topic_id = $row['topic_id'];
								$topic_name = $row['topic_name'];

								echo "<option value='$topic_id'>$topic_name</option>";
							}
					?>
					</select>
					<input type="submit" name="update" value="Update post">

				</form>
				<?php
					if (isset($_POST['update'])) {
						# code...
						$title = $_POST['title'];
						$content = $_POST['content'];
						$topic = $_POST['topic'];

						$update_post ="UPDATE posts SET post_title='$title', post_content='$content', topic_id='$topic' WHERE post_id='$get_id'";
						$run_update = mysqli_query($conn,$update_post);
						if ($run_update) {
							# code...
							echo "<script>alert('post has been updated!')</script>";
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