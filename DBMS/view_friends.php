<?php

// connect to database 	
$connect = mysqli_connect("127.0.0.1:3306", "root", "voldemort@5603291");
if (!$connect) {
	die("Failed to connect to database");
}
mysqli_select_db($connect,"prototype") or die( "Unable to select database");
session_start();
if (!isset($_SESSION['email'])) {
	header('Location: login.html');
	exit();
}
$query = "SELECT firstname, lastname, sex, birthdate, currentcity, hometown " .
		 "FROM user " .
		 "INNER JOIN regularuser ON user.email = regularuser.email " .
		 "WHERE user.email = '{$_SESSION['email']}'";
$result = mysqli_query($connect,$query);
if (!$result) {
	print "<p>Error: " . mysqli_error($connect) . "</p>";
	exit();
}
$row = mysqli_fetch_array($result);
if (!$row) {
	print "<p>Error: No data returned from database.</p>";
	exit();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>View Friends</title>
	</head>
	<body>
		<div id="main_container">	<div id="header">
				<div class="logo"><img src="login.jpg" border="0" alt="" title="" /></div>       
			</div>
			<div class="menu">
				<ul>                                                                         
					<li><a href="profile.php">view profile</a></li>
					<li><a href="edit_profile.php">edit profile</a></li>
					<li class="selected"><a href="view_friends.php">view friends</a></li>
					<li><a href="friend_search.php">search for friends</a></li>
					<li><a href="requests.php">requests</a></li>
					<li><a href="logout.php">log out</a></li>              
				</ul>
			</div>
			<div class="center_content">
			
				<div class="center_left">
					<div class="title_name"><?php print $row['firstname'] . ' ' . $row['lastname']; ?></div>          
					
					<div class="features">   
						
						<div class="profile_section">
							
							<table width="80%">
								<tr>
									<td class="heading">Name</td>
									<td class="heading">Relationship</td>
									<td class="heading">Connected Since</td>
								</tr>
<?php								
								
								$query = "SELECT firstname, lastname, relationship, dateconnected " .
										 "FROM friendship " .
										 "INNER JOIN regularuser ON regularuser.email = friendship.friendemail " .
										 "INNER JOIN user ON user.email = regularuser.email " .
										 "WHERE friendship.email='{$_SESSION['email']}'" .
										 "AND dateconnected IS NOT NULL " .
										 "ORDER BY dateconnected DESC";
										 
								$result = mysqli_query($connect,$query);
								if (!$result) {
									print "<p class='error'>Error: " . mysqli_error($connect) . "</p>";
									exit();
								}
								
								while ($row = mysqli_fetch_array($result)){
									print "<tr>";
									print "<td>{$row['firstname']} {$row['lastname']}</td>";
									print "<td>{$row['relationship']}</td>";
									print "<td>{$row['dateconnected']}</td>";
									print "</tr>";							
								}
								?>
							</table>
						</div>
					 </div> 
				</div> 
				<div class="clear"></div> 
			</div>    
			<div id="footer">                                              
				<div class="right_footer"><a href="#"  target="_blank">add own css file</a></div>       
			</div>
		</div>
	</body>
</html>