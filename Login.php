<?php
    // Force HTTPS for security
    if($_SERVER["HTTPS"] != "on") {
        $pageURL = "Location: https://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        header($pageURL);
    }
    
    // Checks if password or email is empty.
    if (isset($_POST["password"]) && isset($_POST["email"])) {
		$typed_password = $_POST["password"];
		$typed_email = $_POST["email"];
    
    // Hashing (encrypting) password and email for database.
		$typed_password = password_hash($typed_password, PASSWORD_DEFAULT, ['cost' => 12]);
		$typed_email = $typed_email; // update: emails no longer encrypted.
		$servername = "127.0.0.1";
		$username = "seth";
		$password = "sethconnell";
		$db_name = "DataTest";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $db_name);
		
		$send_query = function($link, $sql) {
			if (mysqli_query($link, $sql)){
			} else{
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
		};

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		
		// Create table.
		$sql = "CREATE TABLE IF NOT EXISTS usertable(
		    email text NOT NULL,
		    password text NOT NULL
		)";
		$send_query($conn, $sql);
		
		// Check to see if email already exists.
		
		$testsql = mysqli_query($conn, "SELECT * FROM usertable WHERE email = '$typed_email'");
         if(mysqli_num_rows($testsql)>=1)
           {
            echo "Error: Account already exists.";
           }
         else
            {
    		$sql = "INSERT INTO usertable (email, password)
    		VALUES ('$typed_email', '$typed_password');";
    		$send_query($conn, $sql);
    		mysqli_close($conn);
            }
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>
<body>
    <h1>Sign Up:</h1>
    <br>
    <form action="Login.php" method="POST">
    Email: <input type="text" name="email"><br>
    Password: <input type="text" name="password"><br>
    <input type="submit">
    </form>
</body>

</html>
