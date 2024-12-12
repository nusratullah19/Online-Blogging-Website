<?php
$con = mysqli_connect("127.0.0.1","root","","onlinebloggingwebsite") or die("connection was not established");
if (isset($_GET['post_id'])) {
	# code...
	$post_id = $_GET['post_id'];
	$delete_post = "DELETE FROM posts WHERE post_id ='$post_id'";
	$run_delete = mysqli_query($con,$delete_post);
	if ($run_delete) {
		# code...
		echo "<script>alert('A post has been deleted!')</script>";
		echo "<script>window.open('../home.php','_self')</script>";
	}
}