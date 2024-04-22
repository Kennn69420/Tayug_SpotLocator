<?php
require "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"])) {
    $userId = $_POST["userId"];
    $fname = $_POST["fname"];

    $sql = "UPDATE user SET Fname = '$fname' WHERE id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "User updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating user: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
