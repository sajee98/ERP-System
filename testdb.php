<?php
// include your db connection file
include './config/function.php';

// check if connection is established
if ($mysqli->connect_errno) {
    echo "❌ Database connection failed: " . $mysqli->connect_error;
} else {
    echo "✅ Database connected successfully!<br>";
    echo "Host info: " . $mysqli->host_info . "<br>";
    echo "Server info: " . $mysqli->server_info . "<br>";
}

// optional: test a query
$result = $mysqli->query("SHOW DATABASES");
if ($result) {
    echo "<h4>Available Databases:</h4>";
    while ($row = $result->fetch_assoc()) {
        echo $row['Database'] . "<br>";
    }
    $result->free();
}

$mysqli->close();
?>
