<?php
    require "connection.php";

    $sql = "SELECT * FROM user";
    $container = $conn->query($sql) or die("Error running query $sql");

    $userdata = array();
    while ($row = $container->fetch_array()) {
        $userdata[] = array(
            $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
        );
    }

    echo json_encode($userdata);
?>
