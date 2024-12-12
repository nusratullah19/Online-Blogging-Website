<table width="700">
								<tr>
									<th>Receiver :</th>
									<th>Subject :</th>
									<th>Date :</th>
									<th>Reply :</th>
								</tr>

								<?php
									$sel_msg ="SELECT * FROM messages WHERE sender='$user_id' ORDER BY 1 DESC";
									$run_msg = mysqli_query($conn,$sel_msg);
									$count_msg = mysqli_num_rows($run_msg);


									while ($row_msg = mysqli_fetch_array($run_msg)) {
										# code..
										$msg_id = $row_msg['msg_id'];
										$msg_receiver = $row_msg['receiver'];
										$msg_sender = $row_msg['sender'];
										$msg_sub = $row_msg['msg_sub'];
										$msg_topic = $row_msg['msg_topic'];
										$msg_date = $row_msg['msg_date'];

										$get_receiver ="SELECT * FROM users WHERE user_id='$msg_id'";
										$run_receiver = mysqli_query($conn,$get_receiver);
										$row = mysqli_fetch_array($run_receiver);
										$receiver_name = $row['user_name'];
									
								?>

								<tr align="center">
									<td><a href="user_profile.php?u_id=<?php echo $msg_receiver; ?>" target="blank"><?php echo $receiver_name; ?></a></td>
									<td><a href="my_messages.php?sent&msg_id=<?php echo $msg_id; ?>"><?php echo $msg_sub; ?></a></td>
									<td><?php echo $msg_date ?></td>
									<td><a href="my_messages.php?sent&msg_id=<?php echo $msg_id; ?>">Reply</a></td>

								</tr>
								<?php } ?>
							</table>



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
									<p><b> My Message :</b> $msg_topic</p>
									<p><b> Their Reply :</b> $reply_content</p>
								</center>";


							}
						