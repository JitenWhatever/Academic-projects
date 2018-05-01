<?php
// connect to database 	
$connect = mysqli_connect("127.0.0.1:3306", "root", "voldemort@5603291");
if (!$connect) {		die("Failed to connect to database");
}
mysqli_select_db($connect,"prototype") or die( "Unable to select database");

session_start();
if (!isset($_SESSION['email'])) {
	header('Location: login.html');
	exit();
}
$query = "SELECT firstname, lastname, sex, Dob, currentcity, hometown " .
		 "FROM user " .
		 "INNER JOIN regularuser ON user.email = regularuser.email " .
		 "WHERE user.email = '{$_SESSION['email']}'";
$result = mysqli_query($connect,$query);
if (!$result) {
	print "<p class='error'>Error: " . mysqli_error($connect) . "</p>";
	exit();
}
$row = mysqli_fetch_array($result);
if (!$row) {
	print "<p>Error: No data returned from database.  Administrator login NOT supported.</p>";
	print "<a href='logout.php'>Logout</a>";
	exit();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Profile</title>
	</head>
	<body>
		<div id="main_container">
			<div id="header">
				<div class="logo"><img src="login.jpg" border="0" alt="" title="" /></div>       
			</div>
			<div class="menu">
				<ul>                                                                         
					<li class="selected"><a href="profile.php">view profile</a></li>
					<li><a href="edit_profile.php">edit profile</a></li>
					<li><a href="view_friends.php">view friends</a></li>
					<li><a href="friend_search.php">search for friends</a></li>
					<li><a href="requests.php">requests</a></li>
					<li><a href="logout.php">log out</a></li>              
				</ul>
			</div>
			<div class="center_content">
			
			<div class="center_left">
			<div class="title_name"><?php print $row['firstname'] . ' ' . $row['lastname']; ?></div>          
					
			<div class="features">   
			<div class="profile_section">			<table width="80%">
								<tr>
									<td class="item_label">Sex</td>
									<td><?php if ($row['sex'] == 'M') { print 'Male';} else {print 'Female';} ?></td>
								</tr>
								<tr>
									<td class="item_label">Birthdate</td>
									<td><?php print $row['birthdate'];?></td>
								</tr>
								<tr>
									<td class="item_label">Current City</td>
									<td><?php print $row['currentcity'];?></td>
								</tr>
																	<tr>									<td class="item_label">HomeTown</td>									<td><?php print $row['hometown'];?></td>								</tr>
								<tr>
									<td class="item_label">Interests</td>
<td><ul>
<?php
											$query = "SELECT interest FROM userinterests WHERE email='{$_SESSION['email']}'";
		$result = mysql_query($query);
		 while ($row = mysql_fetch_array($result)){
				print "<li>{$row['interest']}</li>";
		}		?></ul>
		</td>
		</tr>
			</table>						
			</div>			
					 </div> 
				</div> 
				<div class="clear"></div> 
			</div>    
			<div id="footer">                                             
				<div class="right_footer"><a href='#'  target="_blank">add own css file</a></div>       
			</div>
		</div>
	</body>
</html>