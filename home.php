<?php
	header("X-Frame-Options: DENY");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Clubs & Societies</title>
	<link rel="stylesheet" type="text/css" href="home.css">
</head>
        <?php 
            require_once 'connector.php';
        ?>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
            </nav>
        </header>
        
        <section>
            <h1>Welcome to Clubs & Societies!</h1>
            <p>Discover and join various clubs and societies on campus.</p>
        </section>
    </body>
</html>
