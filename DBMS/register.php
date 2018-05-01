<?php
session_start();
$connect = mysqli_connect("localhost:3306", "root", "voldemort@5603291") or die("Failed to connect to database");

mysqli_select_db($connect,"prototype") or die( "Unable to select database");

$errorMsg = array();
$email = "" ;
$firstname = "";
$lastname = "";

if(isset($_POST['submit'])){
	 $firstname = mysqli_real_escape_string($connect, $_POST['firstname']);
	 $lastname = mysqli_real_escape_string($connect, $_POST['lastname']);
	 $password = mysqli_real_escape_string($connect, $_POST['password']);
	 $email = mysqli_real_escape_string($connect, $_POST['email']);
	 $pwd_confirm = mysqli_real_escape_string($connect, $_POST['pwd_confirm']);
	 


if(empty($firstname)){ array_push($errorMsg,"Firstname is required") ;}
if(empty($lastname)){array_push($errorMsg,"Lastname is required");}
if(empty($email)){array_push($errorMsg,"Email is required");}
if(empty($password)){array_push($errorMsg,"Password is required");}
if($password != $pwd_confirm){ array_push($errorMsg,"The Password do not match");}

$user_check_query = "SELECT * FROM user WHERE email='$email' LIMIT 1";

$result = mysqli_query($connect, $user_check_query) ;
$user = mysqli_fetch_assoc($result);

if($user){
	if($user['email'] === $email){
		array_push($errorMsg,"Email already exists");
	}
}

if(count($user) == 0){
	//$pwd = md5($password) ; //encryption
	$query = "INSERT INTO user (Email, Firstname, Lastname, Password)
			  VALUES('$email', '$firstname', '$lastname', '$password')";
			  mysqli_query($connect,$query);
	$_SESSION['email'] = $email;
		header('location: profile.php');
}
}
?>