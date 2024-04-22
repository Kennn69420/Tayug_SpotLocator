<?php
    require "connection.php";

    $sql = "SELECT * FROM spots";
    $container = $conn->query($sql) or die("Error running query $sql");

    $userdata = array();
    while ($row = $container->fetch_array()) {
        $userdata[] = array(
            $row[0], $row[1], $row[2]
        );
    }
    echo json_encode($userdata);
?>
