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
			<div id="msg">
				<p align="center">
					<a href="my_messages.php?inbox">My Inbox</a> ||
					<a href="my_messages.php?sent">Sent Items</a>
					</p>
					<?php
					if (isset($_GET['sent'])) {
						# code...
						include("sent.php");
					}
					?>


					<?php
						if (isset($_GET['inbox'])) {?>
						
							<table width="700">
								<tr>
									<th>Sender :</th>
									<th>Subject :</th>
									<th>Date :</th>
									<th>Reply :</th>
								</tr>

								<?php
									$sel_msg ="SELECT * FROM messages WHERE receiver='$user_id' ORDER BY 1 DESC";
									$run_msg = mysqli_query($conn,$sel_msg);
									$count_msg = mysqli_num_rows($run_msg);


									while ($row_msg = mysqli_fetch_array($run_msg)) {
										# code...
										$msg_id = $row_msg['msg_id'];
										$msg_receiver = $row_msg['receiver'];
										$msg_sender = $row_msg['sender'];
										$msg_sub = $row_msg['msg_sub'];
										$msg_topic = $row_msg['msg_topic'];
										$msg_date = $row_msg['msg_date'];

										$get_sender ="SELECT * FROM users WHERE user_id='$msg_sender'";
										$run_sender = mysqli_query($conn,$get_sender);
										$row = mysqli_fetch_array($run_sender);
										$sender_name = $row['user_name'];
									
								?>

								<tr align="center">
									<td><a href="user_profile.php?u_id=<?php echo $msg_sender; ?>" target="blank"><?php echo $sender_name; ?></a></td>
									<td><a href="my_messages.php?inbox&msg_id=<?php echo $msg_id; ?>"><?php echo $msg_sub; ?></a></td>
									<td><?php $msg_date ?></td>
									<td><a href="my_messages.php?inbox&msg_id=<?php echo $msg_id; ?>">Reply</a></td>

								</tr>
								
							</table>
						
						 <?php } ?>
						<?php
							if (isset($_GET['msg_id'])) {
								# code...
								$get_id = $_GET['msg_id'];

								$sel_message="SELECT * FROM messages WHERE msg_id='$get_id'";
								$run_message = mysqli_query($conn,$sel_message);
								$row_message = mysqli_fetch_array($run_message);

								$msg_subject = $row_message['msg_sub'];
								$msg_topic = $row_message['msg_topic'];
								$reply_content = $row_message['reply'];


								$update_unread="UPDATE messages SET status='read' WHERE msg_id='$get_id'";
								$run_unread = mysqli_query($conn,$update_unread);

								echo "<center>
									<br>
									<hr>
									<h2>$msg_subject</h2>
									<p><b> Message :</b> $msg_topic</p>
									<p><b> Reply :</b> $reply_content</p>

									<form action='' method='post'>
									<textarea cols='30' rows='5' name='reply'></textarea><br>
									<input type='submit' style='background-color: orange; border:none; padding:10px;' name='msg_reply' value='Reply To This'>
									</form>
								</center>";

								if (isset($_POST['msg_reply'])) {
									# code...
									$user_reply=$_POST['reply'];

									if ($reply_content!='no_reply') {
										# code...
										echo "<h2 align=center>The message was already replaid</h2>";
										exit();
									}
									else{
										$update_msg="UPDATE messages SET reply='$user_reply' WHERE msg_id='$get_id' AND reply='no_reply'";
										$run_update = mysqli_query($conn,$update_msg);
										echo "Message was replied";
									}
								}
							}
						}
					?>
					
			
			</div>
			</div>
		</div>

</body>
</html>