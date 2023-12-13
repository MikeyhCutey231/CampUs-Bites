<?php
require 'config.php';

$database_connection = new Database();
$conn = $database_connection->conn;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM admin_info";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Admin ID</th><th>Username</th><th>Full Name</th><th>Email</th><th>Phone Number</th><th>Profile Picture</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['ADMIN_ID'] . "</td>";
        echo "<td>" . $row['Username'] . "</td>";
        echo "<td>" . $row['Full_Name'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['PhoneNumber'] . "</td>";

        echo "<td><img src='".'upload/' . $row['ProfilePicture'] . "' width='100'></td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No records found.";
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<title>Test Show</title>
<head>

</head>
</html>
