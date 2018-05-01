<?php// connect to database 	
$connect = mysqli_connect("localhost:3306", "root", "voldemort@5603291") or die("Failed to connect to database");
mysqli_select_db($connect,"prototype") or die( "Unable to select database");
$errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($_POST['email']) or empty($_POST['password'])) {
		$errorMsg = "Please provide both email and password.";		
	}
	else {  
		$email = mysqli_real_escape_string($connect,$_POST['email']);
		$password = mysqli_real_escape_string($connect,$_POST['password']);
		$query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
		$result = mysqli_query($connect,$query);
		if (mysqli_num_rows($result) == 0) {
			// login failed 
			$errorMsg = "Please Sign Up First";			echo $errorMsg;
		}		else {
			// login successful 			
			session_start();
			$_SESSION['email'] = $email;
			// redirect to the profile page 
			header('Location: profile.php');
			exit();
		}
	}
}
?>