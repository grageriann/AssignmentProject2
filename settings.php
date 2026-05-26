<?php
$host = "localhost";
$user = "root";          
$pwd  = "";              
$sql_db = "db";          

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("<p class='error'>Database connection failure.</p>");
}
?>