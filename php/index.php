<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP MySQL User List</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>


<?php
$servername = "mysql"; // service name in docker-compose
$username = "root";
$password = "root";
$dbname = "testdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Connected to MySQL successfully!</h2>";

// Query the users table
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<h3>User List:</h3>";
  echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Name</th><th>Email</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . htmlspecialchars($row["id"]) . "</td><td>" 
              . htmlspecialchars($row["name"]) . "</td><td>" 
              . htmlspecialchars($row["email"]) . "</td></tr>";
  }
  echo "</table>";
} else {
  echo "No users found in the database.";
}

$conn->close();
?>


</body>
</html>
