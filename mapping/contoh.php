<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "alldata";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT ruas FROM 1tablejalan WHERE id = 3";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $jalan = $row["ruas"];
    }
} else {
    echo "0 results";
}

echo "jalan adalah ".$jalan;
mysqli_close($conn);
?>

