<?php
// connect to database 	
$connect = mysqli_connect("localhost:3306", "root", "voldemort@5603291");
if (!$connect) {
	die("Failed to connect to database");
}
mysqli_select_db($connect,"prototype") or die( "Unable to select database");
session_start();
if (!isset($_SESSION['email'])) {
	header('Location: login.html');
	exit();
}
$query = "SELECT firstname, lastname " .
		 "FROM user " .
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
$user_name = $row['firstname'] . " " . $row['lastname'];
unset($result);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$name = mysqli_real_escape_string($connect,$_POST['name']);
	$email = mysqli_real_escape_string($connect,$_POST['email']);
	$hometown = mysqli_real_escape_string($connect,$_POST['hometown']);
		$query = "SELECT regularuser.email, firstname, lastname, hometown " .
			 "FROM user " .
			 "INNER JOIN regularuser ON regularuser.email = user.email " .
			 "WHERE regularuser.email NOT IN " .
			 "	(SELECT friendemail FROM friendship WHERE email = '{$_SESSION['email']}') " . 
			 "AND regularuser.email <> '{$_SESSION['email']}'";
			 
	if (!empty($name) or !empty($email) or !empty($hometown)) {
		$query = $query . " AND (1=0 ";		
		if (!empty($name)) {
			$query = $query . " OR firstname LIKE '%$name%' OR lastname LIKE '%$name%' ";
		}
		if (!empty($email)) {
			$query = $query . " OR regularuser.email LIKE '%$email%' ";
		}		if (!empty($hometown)) {
			$query = $query . " OR hometown LIKE '%$hometown%' ";
		}
		$query = $query . ") ";
	}
	$query = $query . " ORDER BY lastname, firstname";
	$result = mysqli_query($connect,$query);
	if (!$result) {
		print '<p class="error">Error: ' . mysqli_error($connect) . '</p>';
		exit();
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Friend Search</title>		
	</head>
	<body>
		<div id="main_container">
			<div id="header">
				<div class="logo"><img src="login.jpg" border="0" alt="" title="" /></div>       
			</div>
			<div class="menu">
				<ul>                                                                         
					<li><a href="profile.php">view profile</a></li>
					<li><a href="edit_profile.php">edit profile</a></li>
					<li><a href="view_friends.php">view friends</a></li>
					<li class="selected"><a href="friend_search.php">search for friends</a></li>
					<li><a href="requests.php">requests</a></li>
					<li><a href="logout.php">log out</a></li>              
				</ul>
			</div>
			<div class="center_content">
				<div class="center_left">
			<div class="title_name">				<?php print $user_name; ?></div>          
					<div class="features">   
						<div class="profile_section">
							<div class="subtitle">Search for Friends</div>    
						<form name="searchform" action="friend_search.php" method="post">
							<table width="80%">								
								<tr>
									<td class="item_label">Name</td>
									<td><input type="text" name="name" /></td>
								</tr>
								<tr>
									<td class="item_label">Email</td>
									<td><input type="text" name="email" /></td>
								</tr>
								<tr>
									<td class="item_label">Hometown</td>
									<td><input type="text" name="hometown" /></td>
								</tr>
							</table>
							<a href="javascript:searchform.submit();" class="fancy_button">search</a> 
							</form>
						</div>
<?php
						if (isset($result)) {
													
							print "<div class='profile_section'>";
							print "<div class='subtitle'>Search Results</div>";							
							print "<table width='80%'>";
							print "<tr><td class='heading'>Name</td><td class='heading'>Hometown</td></tr>";
							while ($row = mysqli_fetch_array($result)){
								$friendemail = urlencode($row['email']);
								print "<tr>";
								print "<td><a href='friend_request.php?friendemail=$friendemail'>{$row['firstname']} {$row['lastname']}</a></td>";
								print "<td>{$row['hometown']}</td>";									
								print "</tr>";
							}
							print "</table>";
							print "</div>";
						}
	?>
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