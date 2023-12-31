<?php

//connect
$conn = mysqli_connect('localhost', 'root', '', 
'project3_book') or die('connection failed');

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>