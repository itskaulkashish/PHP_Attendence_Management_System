<?php

//database_connection.php
//datbase connection established database name set to attendance and password is left blank
$connect = new PDO("mysql:host=localhost;dbname=attendance_system","root","");
//after database connection we have set the base url of the system under this variable
$base_url = "http://localhost/online_attendance_system/";
//now go to login.php page to establish database connection
?>