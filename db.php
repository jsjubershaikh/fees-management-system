<?php
$servername = "localhost"; 
$username = "root";        
$password = "Root@123";    
$dbname = "fees_record";   


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
