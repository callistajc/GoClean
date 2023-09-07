<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "root";
$dbname = "GoClean";

// Create connection
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>