<?php
include("includes/connection.php");

if(isset($_POST['submit']))
{
    $name =mysqli_real_escape_string($conn,$_POST['u_name']);
    $pass = mysqli_real_escape_string($conn,$_POST['u_password']);
	$email = mysqli_real_escape_string($conn,$_POST['u_email']);
	$country = mysqli_real_escape_string($conn,$_POST['u_country']);
	$gender = mysqli_real_escape_string($conn,$_POST['u_gender']);
	$birthday = mysqli_real_escape_string($conn,$_POST['u_birthday']);
	$status = "unverified";
	$posts = "no";
	$ver_code = mt_rand();

	if (strlen($pass) < 8)
	{
		echo "<script>alert('Password should be minimum 8 character')</script>";
		exit();
	}
	$check_email = "SELECT * FROM users where user_email = '$email'";
	$run_email = mysqli_query($conn,$check_email);
	$check = mysqli_num_rows($run_email);
	if($check == 1)
	{
		echo "<script>alert('Email already exist,try another!')</script>";
		exit();
	}
	$insert = "insert into users (user_name,user_password,user_email,user_country,user_gender,user_birthday,user_image,user_reg_date,status,ver_code,posts) values ('$name','$pass','$email','$country','$gender','$birthday','default.jpg',NOW(),'$status','$ver_code','$posts')";
	$query = mysqli_query($conn,$insert);

	if($query)
	{
		echo "<h3 style='width:400px; text-align:justif; color:green;'>Hi,$name congratulation, registration is almost complete,please check your email for final verification.</h3>";
	}	
	else
	{
		echo "Registration failed, try again";
	}
}

?>