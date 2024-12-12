<?php
	
	$query ="SELECT * FROM posts";
	$result = mysqli_query($con,$query);
	//count the total record
	$total_posts = mysqli_num_rows($result);
	//using ceil funcation to divide the total record per page 
	$total_pages = ceil($total_posts / $per_page);
	//going to first page
	echo 
		"<center>
		<div id='pagination'>
			<a href='home.php?page=1'>First Page </a>
		";
		for ($i=1; $i < $total_pages ; $i++) { 
			# code...
			echo "<a href='home.php?page=$i'> $i </a>";
		}
	//going to last page
	echo"<a href='home.php?page=$total_pages'> last page</a></center></div>";
?>