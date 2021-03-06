<?php
	session_start();
	$firstname = "";
	$lastname = "";
	$email = "";
	$errors = array();
	
	$db = mysqli_connect('localhost', 'root', '', 'profilesystem') or die("Cannot connect to DB");
	
	if (isset($_POST['register'])){
		$firstname = mysqli_real_escape_string($db,$_POST['firstname']);
		$lastname = mysqli_real_escape_string($db,$_POST['lastname']);
		$email = mysqli_real_escape_string($db,$_POST['email']);
		$password_1 = mysqli_real_escape_string($db,$_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db,$_POST['password_2']);
		
		if(empty($firstname)){
			array_push($errors, "firstname is required");
		}
		if(empty($lastname)){
			array_push($errors, "lastname is required");
		}
		if(empty($email)){
			array_push($errors, "email is required");
		}
		if(empty($password_1)){
			array_push($errors, "password is required");
		}
		if($password_1 != $password_2){
			array_push($errors, "two password do not match");
		}
		
		$user_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
  		$result = mysqli_query($db, $user_check_query);
  		$user = mysqli_fetch_assoc($result);
  
  		if ($user) { // if user exists
   			 if ($user['email'] === $email) {
      			array_push($errors, "email already exists");
    		}
  		}

 		// Finally, register user if there are no errors in the form
 		if (count($errors) == 0) {
  			$password = md5($password_1);//encrypt the password before saving in the database
  			$query = "INSERT INTO users (firstname, lastname, email, password) Values ('$firstname', '$lastname', '$email', '$password')";
  			mysqli_query($db, $query);
  			$_SESSION['email'] = $email;
  			$_SESSION['success'] = "You are now logged in";
  			header('location: index.php');
  }
}

?>