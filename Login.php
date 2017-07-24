<?php
    // Start the session
    session_start();
?>

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
    if (isset($_POST["name"]) && isset($_POST["password"]) && isset($_POST["email"]) && $_POST["password"] == $_POST["passwordconfirm"]) {
		$typed_password = $_POST["password"];
		$typed_email = $_POST["email"];
		$typed_name = $_POST["name"];
    
    // Encrypting password and email for database.
		$typed_password = password_hash($typed_password, PASSWORD_DEFAULT, ['cost' => 12]);
		$typed_email = $typed_email; // update: emails no longer encrypted.
		$servername = "127.0.0.1";
		// Examples of data for MYSQL database connection.
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
		    password text NOT NULL,
		    name text NOT NULL,
		    id MEDIUMINT NOT NULL AUTO_INCREMENT,
		    primary key (id)
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
    		$sql = "INSERT INTO usertable (email, password, name)
    		VALUES ('$typed_email', '$typed_password', '$typed_name');";
    		$send_query($conn, $sql);
    		$user_id = mysqli_query($conn, "SELECT id FROM usertable WHERE name='$typed_name'");
    		$_SESSION['user'] = $user_id;
    		mysqli_close($conn);
            }
    }
    if (isset($_SESSION['user'])) {
        echo "<a href='signout.php'>Logout</a>";
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>
<body>
    <style>
        *{
            text-align: center;
        }
    </style>
    <h1>Sign Up:</h1>
    <br>
    <form action="Login.php" method="POST">
    Email: <input type="text" name="email"><br>
    Name: <input type="text" name="name"><br>
    Password: <input type="text" name="password"><br>
    Confirm Password: <input type="text" name="passwordconfirm"><br>
    <input type="submit">
    </form>
</body>

</html>
