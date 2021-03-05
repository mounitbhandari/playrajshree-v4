<?php
$servername = "playrajshreegoa.com";
$username = "srg2642_root";
$password = "v%w8z8Gs#}+4";
$dbname = "srg2642_playrajshreegoa_db";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$sql = "select set_default_payout();";
if (mysqli_query($conn, $sql)) {
    echo "Payout set";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?> 


