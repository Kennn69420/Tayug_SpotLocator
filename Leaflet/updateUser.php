<?php
require "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"])) {
    $userId = $_POST["userId"];
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $age = $_POST["age"];
    $num = $_POST["num"];
    $email = $_POST["email"];
    $username = $_POST["username"];

    // Validate and sanitize input (add more validation as needed)
    $userId = filter_var($userId, FILTER_VALIDATE_INT);
    $fname = filter_var($fname, FILTER_SANITIZE_STRING);
    $mname = filter_var($mname, FILTER_SANITIZE_STRING);
    $lname = filter_var($lname, FILTER_SANITIZE_STRING);
    $age = filter_var($age, FILTER_VALIDATE_INT);
    $num = filter_var($num, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    if ($userId === false || $fname === false || $mname === false || $lname === false || $age === false || $num === false || $email === false || $username === false) {
        echo json_encode(["error" => "Invalid input"]);
        exit();
    }

    // Update user information using prepared statement
    $sql = "UPDATE user SET first_name = ?, middle_initial = ?, last_name = ?, age = ?, phone_num = ?, email_add = ?, login_user = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiisss", $fname, $mname, $lname, $age, $num, $email, $username, $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => "User updated successfully"]);
    } else {
        echo json_encode(["error" => "Error updating user: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid request"]);
}
