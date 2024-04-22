<?php
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'spot_locator';

    $conn = new mysqli($host, $username, $password, $db);

    if($conn -> connect_error){
        die.("Failed").$conn->connect_error;
    }
?>