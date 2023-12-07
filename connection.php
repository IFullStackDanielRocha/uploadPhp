<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "files_upload";


$mysqli = new mysqli($host, $user, $pass, $db);

if($mysqli->connect_errno){
    echo "Connect failed: " . $mysqli->connect_errno;
    exit();
}