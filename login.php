<!-- Student Name :       David Williams -->
<!-- Student Id Number :  C00263768 -->
<!-- Date :               07/03/2023 -->
<!-- Purpose :  Login for the website-->
<?php
	header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="login.css">
</head>
	<body>
	<<?php
	require_once 'connector.php';

	// Initialize $con variable
	$con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); 

	if (!$con) {
		// handle connection error
	}


	// Create login_attempts table if it doesn't exist
	$createTable = "CREATE TABLE IF NOT EXISTS login_attempts (
					id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					username VARCHAR(50) NOT NULL,
					attempts INT(11) NOT NULL DEFAULT '0',
					attempts_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";
	$con->query($createTable);

	if (isset($_POST['login'])) {
		// Sanitize username and password for security
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// Hash password
		$hashedPassword = hash('sha3-256', $password, true);
		$hashedPassword_hex = bin2hex($hashedPassword);

		// Check if user exists
		$sql = "SELECT * FROM students WHERE username = '$username' AND password = '$hashedPassword_hex'";
		$result = $con->query($sql);

		if ($result->num_rows == 1) {
			// User exists, login successful
			$location = "userPage.php?username=".$username;
			echo "<script type='text/javascript'>alert('Login successful');window.location='$location'</script>";
		} else {
			// User does not exist or login failed
			// Increment login attempts for the username
			$sql = "INSERT INTO login_attempts (username, attempts) VALUES ('$username', 1) ON DUPLICATE KEY UPDATE attempts=attempts+1";
			$con->query($sql);

			// Check login attempts for the username
			$sql = "SELECT attempts FROM login_attempts WHERE username = '$username'";
			$result = $con->query($sql);
			$row = $result->fetch_assoc();
			$attempts = $row['attempts'];

			if ($attempts >= 5) {
				// Too many failed attempts, lock user out
				echo "<script type='text/javascript'>alert('Too many failed attempts. Please try again later.');</script>";
			} else {
				// Failed login, show error message
				echo "<script type='text/javascript'>alert('Username and password do not match');</script>";
			}
		}
	}
?>

		<div class="content">
			<form id="loginForm" method="post" action="">
				<div class="bigTextDiv">
					<h1 class="loginText">SETU Clubs & Societies | Login</h1>
				</div>
				<input type="text" class="usernameBox" id="username" name="username" placeholder="Username" autofocus="autofocus" required="required"/>
				<br><br>
				<input type="password" class="passwordBox" id="password" name="password" placeholder="Password" required="required"/>
				<br><br>
				<input type="submit" class="button1" id="login" value="Login" name="login" />
				<br><br>
				<a href="home.php" class="homeButton">Home</a>
				<br><br>
				<a href="register.php" class="registerButton">Register</a>
			</form>
		</div>
	</body>
</html>
