<?php
require "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"])) {
    $userId = $_POST["userId"];

    $sql = "DELETE FROM spot WHERE place_id = $userId";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => "User deleted successfully"]);
    } else {
        echo json_encode(["error" => "Error deleting user: " . $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>
