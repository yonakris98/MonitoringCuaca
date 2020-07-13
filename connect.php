<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dbcuaca";

    // Create connection
    $con = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
?>