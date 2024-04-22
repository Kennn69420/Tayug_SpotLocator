<?php
session_start();
include "connection.php";

// Function to fetch user ID based on username
function getUserIdByUsername($conn, $username)
{
    $stmt = $conn->prepare("SELECT id FROM user WHERE login_user = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    } else {
        return false;
    }
}

if (isset($_POST['return'])) {
    header("Refresh:.5;URL=spotList.php");
}

if (isset($_POST['submit_comment'])) {
    $comment_text = $_POST['comment_text'];
    $place_id = $_GET['id'];
    $username = $_SESSION['username'];

    $user_id = getUserIdByUsername($conn, $username);

    if ($user_id !== false) {
        $comment_image = null;
        if ($_FILES['comment_image']['error'] == 0) {
            $upload_dir = 'comment_images/';
            $uploaded_file = $upload_dir . basename($_FILES['comment_image']['name']);
            move_uploaded_file($_FILES['comment_image']['tmp_name'], $uploaded_file);
            $comment_image = $uploaded_file;
        }

        $insert_comment_stmt = $conn->prepare("INSERT INTO comments (place_id, id, comment_text, comment_image) VALUES (?, ?, ?, ?)");
        $insert_comment_stmt->bind_param("ssss", $place_id, $user_id, $comment_text, $comment_image);

        if ($insert_comment_stmt->execute()) {
            echo "Comment added successfully!";
        } else {
            echo "Error adding comment: " . $insert_comment_stmt->error;
        }

        $insert_comment_stmt->close();
    } else {
        echo "Error: Unable to fetch user information.";
    }
    if (isset($_POST['rating'])) {
        $rating_value = $_POST['rating'];
        $user_id = getUserIdByUsername($conn, $_SESSION['username']);

        if ($user_id !== false) {
            $insert_rating_stmt = $conn->prepare("INSERT INTO ratings (place_id, id, rating_value) VALUES (?, ?, ?)");
            $insert_rating_stmt->bind_param("sss", $place_id, $user_id, $rating_value);

            if ($insert_rating_stmt->execute()) {
                echo "Rating added successfully!";
            } else {
                echo "Error adding rating: " . $insert_rating_stmt->error;
            }

            $insert_rating_stmt->close();
        } else {
            echo "Error: Unable to fetch user information.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spot List View</title>
</head>
<style>
    img {
        width: 400px;
        height: 400px;
    }
</style>

<body>
    <form method="post">
        <button name="return"> Navigate to Other Spots</button>
    </form>
    <?php
    echo $_SESSION['username'];
    ?>

    <?php
    if (isset($_GET['id'])) {
        $place_id = $_GET['id'];

        $stmt = $conn->prepare("SELECT spots.place_name, spots.place_image, comments.comment_id, user.login_user, comments.comment_text, comments.comment_image
            FROM spots
            LEFT JOIN comments ON spots.place_id = comments.place_id
            LEFT JOIN user ON comments.id = user.id
            WHERE spots.place_id = ?");
        $stmt->bind_param("s", $place_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();

            if ($row) {
                $place_name = $row['place_name'];
                $image_place = $row['place_image'];

                echo "<p>$place_name</p>";

                if (!empty($image_place)) {
                    echo "<img src='$image_place' alt='$place_name' />";
                } else {
                    echo "No image available";
                }

                echo "<h2>Comments:</h2>";

                do {
                    $login = $row['login_user'];
                    $comment_text = $row['comment_text'];
                    $comment_image = $row['comment_image'];

                    echo "<p><strong>$login:</strong> $comment_text</p>";

                    if (!empty($comment_image)) {
                        echo "<img src='$comment_image' alt='Comment Image' />";
                    }
                } while ($row = $result->fetch_assoc());
            } else {
                echo "Spot not found";
            }
        } else {
            echo "Error in database query";
        }

        $stmt->close();
    } else {
        echo "No spot ID provided";
    }

    if (isset($_SESSION['username'])) {
        $userId = getUserIdByUsername($conn, $_SESSION['username']);

        if ($userId !== false) {
            echo "
            <h2>Add Comment:</h2>
            <form method='post' enctype='multipart/form-data'>
                <label for='comment_text'>Your Comment:</label>
                <textarea name='comment_text' placeholder='Enter your comment...' required></textarea>
                <br>
                <label for='rating'>Select Rating:</label>
                <input type='radio' name='rating' value='1'> 1
                <input type='radio' name='rating' value='2'> 2
                <input type='radio' name='rating' value='3'> 3
                <input type='radio' name='rating' value='4'> 4
                <input type='radio' name='rating' value='5'> 5
                <label for='comment_image'>Upload Image:</label>
                <input type='file' name='comment_image'>
                <br>
                <button type='submit' name='submit_comment'>Submit Comment</button>
            </form>
            ";
        } else {
            echo "Error: Unable to fetch user information.";
        }
    } else {
        echo "Please log in to add comments.";
    }
    ?>

    <h1>ETO COMMENT ./. </h1>
</body>

</html>