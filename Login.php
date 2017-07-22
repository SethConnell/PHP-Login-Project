<?php
    if (isset($_POST["name"])) {
		$name = $_POST["name"];
        echo "<h1>Screw you " . $name . "</h1>"; 
		$servername = "127.0.0.1";
		$username = "seth";
		$password = "sethconnell";
		$db_name = "DataTest";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $db_name);
		
		$send_query = function($link, $sql) {
			if (mysqli_query($link, $sql)){
			    echo "Query sent successfully.";
			} else{
			    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
			}
		};

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "CREATE TABLE IF NOT EXISTS persons(
		    name text NOT NULL
		)";
		$send_query($conn, $sql);
		$sql = "INSERT INTO persons (name)
		VALUES ('$name');";
		$send_query($conn, $sql);
		mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html>
<head>
<title>Title of the document</title>
</head>
<body>
    <form action="Login.php" method="POST">
    Name: <input type="text" name="name"><br>
    <input type="submit">
    </form>
</body>

</html>
