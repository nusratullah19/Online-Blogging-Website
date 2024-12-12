<?php
$con = mysqli_connect("127.0.0.1","root","","onlinebloggingwebsite") or die("connection was not established");


function insertPost(){
	if(isset($_POST['sub'])){
		global $con;
		global $user_id;
		$title = addslashes($_POST['title']);
		$content = addslashes($_POST['content']);
		$topic = $_POST['topic'];
		if($content =="" OR $title ==""){
			echo "<h2>Please enter topic and its description</h2>";
			exit();
		}
		else{
			$insert ="INSERT into posts
			(user_id,topic_id,post_title,post_content,post_date) values ('$user_id','$topic','$title','$content',NOW())";
			$run = mysqli_query($con,$insert);
			if($run){
				echo "<h2>Posted to Timeline</h2>";
				$update = "UPDATE users set posts ='yes' where user_id='$user_id'";
				$run_update = mysqli_query($con,$update);
			}
		}

	}
}





function get_posts(){
	global $con;
	$per_page =5;
	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}
	else{
		$page =1;

	}
	$start_form = ($page-1) * $per_page;
	$get_posts = "SELECT * from posts ORDER by 1 DESC LIMIT $start_form, $per_page";
	$run_posts = mysqli_query($con,$get_posts);

	while ($row_posts=mysqli_fetch_array($run_posts))
	{
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$post_title = $row_posts['post_title'];
		$content = substr($row_posts['post_content'],0,150);
		$post_date = $row_posts['post_date'];

		//getting the user who has posted the thread
		$user="SELECT * FROM users where user_id = '$user_id' AND posts ='yes'";
		$run_user =mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		echo "
							<div id='posts' style='border:1px solid black;background-color:orange; padding:8px;'>
							<p><img src ='users/$user_image' width='50' height='50' style='float:left; padding-right:10px;'><p>
							<p><a href='user_profile.php?u_id=$user_id'>$user_name</a></p>			
							 <h4>$post_title</h4>
							 <h2><i>$post_date</i></h2>
							 <h3>$content</h3>
							<a href='single.php?post_id=$post_id' style='float:right;'><button>See Replies or Reply To This</button></a>


							</div>	<br>		
							";
	}
	include("pagination.php");
	
}

	function single_post(){
		if(isset($_GET['post_id'])){

		global $con;
		$get_id = $_GET['post_id'];
		$get_post ="SELECT * FROM posts WHERE post_id='$get_id'";
		$run_posts = mysqli_query($con,$get_post);

	while ($row_posts=mysqli_fetch_array($run_posts))
	{
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_content'];
		$post_date = $row_posts['post_date'];

		//getting the user who has posted the thread
		$user="SELECT * FROM users where user_id = '$user_id' AND posts ='yes'";
		$run_user =mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		//Getting user session
		$user_com = $_SESSION['user_email'];
		$get_com = "SELECT * FROM users where user_email='$user_com'";
		$run_com = mysqli_query($con,$get_com);
		$row_com = mysqli_fetch_array($run_com);
		$user_com_id = $row_com['user_id'];
		$user_com_name = $row_com['user_name'];

		echo "
							<div id='posts' style='border:1px solid black;background-color:orange; padding:8px;'>
							<p><img src ='users/$user_image' width='50' height='50' style='float:left;'><p>
							<p><a href='edit_profile.php?u_id = $user_id'>$user_name</a></p>			
							 <h3>$post_title</h3>
							 <h3>$post_date</h3>
							 <h3>$content</h3>
							<a href='single.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>See Replies or Reply To This</button></a>


							</div>	<br>		
							";
		include("comments.php");
		echo "
			<form action='' method='post' id='reply'>
				<textarea cols='50' rows='5' name='comments' placeholder='write your reply'></textarea><br>
				<input type='submit' name='reply' value='Reply To This' style='background-color: orange; border:none; padding:10px;'>
			</form>
		";

		if (isset($_POST['reply'])) {
			# code...
			$comment = $_POST['comment'];
			$insert = "INSERT INTO comments
			(post_id,user_id,comment,comment_author,date) VALUES ('$post_id','$user_id','$comment','$user_com_name',NOW()); 
			";
			$run = mysqli_query($con,$insert);
			echo "Your reply was added";
		}
	}
		
	}
}

function members(){
	global $con;
	$user ="SELECT * FROM users";
	$run_user = mysqli_query($con,$user);
	echo "<br><h2>New Members on this site:</h2><hr><br>";

	while ($row_user = mysqli_fetch_array($run_user)) {
		# code...
		$user_id = $row_user['user_id'];
		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];
		echo "
			<span>
			<a href='user_profile.php?u_id=$user_id'>
			<img src='users/$user_image' width='50' height='50' title='$user_name' style='float:left;margin:1px'>
			</a>
			</span>
		";
	}
}


function user_posts(){
	global $con;
	if (isset($_GET['u_id'])) {
		# code...
		$u_id = $_GET['u_id'];
	}
	$get_post ="SELECT * FROM posts WHERE user_id='$u_id' ORDER BY 1 DESC LIMIT 5";
	$run_posts = mysqli_query($con,$get_post);

	while ($row_posts=mysqli_fetch_array($run_posts))
	{
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_content'];
		$post_date = $row_posts['post_date'];

		//getting the user who has posted the thread
		$user="SELECT * FROM users where user_id = '$user_id' AND posts ='yes'";
		$run_user =mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		echo "
							<div id='posts' style='border:1px solid black;background-color:orange; padding:8px;'>
							<p><img src ='users/$user_image' width='50' height='50' style='float:left;'><p>
							<p><a href='edit_profile.php?u_id = $user_id'>$user_name</a></p>			
							 <h3>$post_title</h3>
							 <h3>$post_date</h3>
							 <h3>$content</h3>
							<a href='single.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>View</button></a>
							<a href='edit_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Edit</button></a>
							<a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Delete</button></a>
							</div>	<br>		
							";
							include("delete_post.php");

}
}

	function user_profile(){
		if (isset($_GET['u_id'])) {
			# code...
			global $con;
			$user_id = $_GET['u_id'];
			$select = "SELECT * FROM users WHERE user_id ='$user_id'";
			$run = mysqli_query($con,$select);
			$row = mysqli_fetch_array($run);

			$user_id = $row['user_id'];
			$user_name = $row['user_name'];
			$user_country = $row['user_country'];
			$user_gender = $row['user_gender'];
			$user_image = $row['user_image'];
			$register_date = $row['user_reg_date'];
			$last_login = $row['user_last_login'];
			if ($user_gender=='male') {
				# code...
				$msg = "Send him a message";
			}
			else{
				$msg ="Send her a message";
			}

		}
						echo "
							<div id='user_profile' style='font-family:sans-serif'>
							<img src ='users/$user_image' width='100' height='100' style='float:right;'>
							<br>	
							
							<p><strong>Name:</strong> $user_name</p>
							<p><strong>Gender:</strong> $user_gender</p>
							<p><strong>Country:</strong> $user_country</p>
							<p><strong>Last Login:</strong> $last_login</p>
							<p><strong>Member since:</strong> $register_date</p>
							<a href='message.php?u_id=$user_id' ><button style='background-color: orange; padding:7px; border:none; margin:5px'>$msg</button></a>


							</div>			
							";

	}


	function show_topics(){
	global $con;
	if (isset($_GET['topic'])) {
		# code...
		$id = $_GET['topic'];
	}
	$get_post ="SELECT * FROM posts WHERE topic_id='$id'";
	$run_posts = mysqli_query($con,$get_post);

	while ($row_posts=mysqli_fetch_array($run_posts))
	{
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_content'];
		$post_date = $row_posts['post_date'];

		//getting the user who has posted the thread
		$user="SELECT * FROM users where user_id = '$user_id' AND posts ='yes'";
		$run_user =mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		echo "
							<div id='posts' style='border:1px solid black;background-color:orange; padding:8px;'>
							<p><img src ='users/$user_image' width='50' height='50' style='float:left;'><p>
							<p><a href='edit_profile.php?u_id = $user_id'>$user_name</a></p>			
							 <h3>$post_title</h3>
							 <h3>$post_date</h3>
							 <h3>$content</h3>
							<a href='single.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>View</button></a>
							<a href='edit_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Edit</button></a>
							<a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Delete</button></a>
							</div>	<br>		
							";
							include("delete_post.php");

}
}



function results(){
	global $con;
	if (isset($_GET['search'])) {
		# code...
		$search_query = $_GET['user_query'];
	}
	$get_post ="SELECT * FROM posts WHERE post_title LIKE '%$search_query%' OR post_content LIKE '%$search_query%'";
	$run_posts = mysqli_query($con,$get_post);

	while ($row_posts=mysqli_fetch_array($run_posts))
	{
		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['user_id'];
		$post_title = $row_posts['post_title'];
		$content = $row_posts['post_content'];
		$post_date = $row_posts['post_date'];

		//getting the user who has posted the thread
		$user="SELECT * FROM users where user_id = '$user_id' AND posts ='yes'";
		$run_user =mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		echo "
							<div id='posts' style='border:1px solid black;background-color:orange; padding:8px;'>
							<p><img src ='users/$user_image' width='50' height='50' style='float:left;'><p>
							<p><a href='edit_profile.php?u_id = $user_id'>$user_name</a></p>			
							 <h3>$post_title</h3>
							 <h3>$post_date</h3>
							 <h3>$content</h3>
							<a href='single.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>View</button></a>
							<a href='edit_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Edit</button></a>
							<a href='functions/delete_post.php?post_id=$post_id' style='float:right;'><button style='padding:10px;'>Delete</button></a>
							</div>	<br>		
							";
							include("delete_post.php");

}
}
?>
