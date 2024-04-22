<?php
session_start();
include "connection.php";

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

function getLastCommentId($conn)
{
    $result = $conn->query("SELECT MAX(comment_id) as max_id FROM comments");
    $row = $result->fetch_assoc();
    return $row['max_id'];
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
        $comment_id = getLastCommentId($conn);

        $insert_rating_stmt = $conn->prepare("INSERT INTO ratings (place_id, id, comment_id, rating_value) VALUES (?, ?, ?, ?)");
        $insert_rating_stmt->bind_param("ssss", $place_id, $user_id, $comment_id, $rating_value);

        if ($insert_rating_stmt->execute()) {
            echo "Rating added successfully!";
        } else {
            echo "Error adding rating: " . $insert_rating_stmt->error;
        }

        $insert_rating_stmt->close();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Spot List View</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            padding: 30px;
            margin: 0;
            padding-top: 80px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.6s;
            padding: 20px 100px;
            z-index: 100000;
            background-color: #232D3F;
        }

        header.sticky {
            padding: 10px 100px;
            background: #232D3F;
        }

        header .logo img {
            max-width: 60px;
            width: auto;
            display: block;
            margin: 0 auto;
            border-radius: 5px;
        }

        header ul {
            position: relative;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        header ul li {
            margin: 0 15px;
        }

        header ul li a {
            text-decoration: none;
            color: #fff;
            letter-spacing: 2px;
            font-weight: 700;
            transition: 0.6s;
        }

        header ul li a:hover {
            color: green;
        }

        h1 {
            text-align: center;
            margin-top: 60px;
        }

        .heading-container {
            text-align: center;
            margin: 20px auto;
        }

        .heading-container h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .heading-container img {
            width: 600px;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 5px;
        }

        .rating-container {
            text-align: center;
            margin: 20px auto;
        }

        .rating-container h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .rating-container p {
            font-size: 18px;
            color: #666;
        }

        .comment-container {
            margin: 20px auto;
        }

        .comment-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .comment-box p {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
        }

        .comment-box img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 5px;
        }

        .comment-box p strong {
            font-weight: bold;
        }

        .comment-box p span {
            display: block;
            margin-top: 10px;
            font-size: 16px;
            color: #555;
        }

        h1,
        h2,
        h3,
        p {
            color: #333;
        }

        .imggg {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 5px;
            background-color: #fff;
            margin: 20px auto;
            display: block;
            max-width: 50%;
            height: auto;
            border-radius: 5px;
        }

        .comment-form-container {
            text-align: center;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f5f5f5;
            width: 60%;
        }

        .comment-form label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        .comment-form textarea,
        .comment-form input[type='file'],
        .comment-form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        .comment-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .comment-form button:hover {
            background-color: #45a049;
        }

        .file-input-container {
            margin-top: 10px;
            position: relative;
        }

        .file-input-label {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
        }

        .file-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <a href="#" class="logo"> <img src="icon/tayug_icon-removebg-preview.png" alt="Logo"></a>
        <ul>
            <li><a href="homePage.php">Home</a></li>
            <li><a href="spotList.php">Return</a></li>
        </ul>
    </header>
    <?php
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    if (!empty($username)) {
        echo "<h1>Welcome, $username!</h1>";
    }
    ?>
    <?php
    if (isset($_GET['id'])) {
        $place_id = $_GET['id'];

        $stmt = $conn->prepare("
        SELECT
            spots.place_name,
            spots.place_image,
            AVG(ratings.rating_value) AS avg_rating
        FROM spots
        LEFT JOIN comments ON spots.place_id = comments.place_id
        LEFT JOIN ratings ON comments.comment_id = ratings.comment_id
        WHERE spots.place_id = ?
    ");
        $stmt->bind_param("s", $place_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $row = $result->fetch_assoc();

            if ($row) {
                $place_name = $row['place_name'];
                $image_place = $row['place_image'];
                $avg_rating = $row['avg_rating'];

                echo "<div class='heading-container'>";
                echo "<h1>$place_name</h1>";

                if (!empty($image_place)) {
                    echo "<img src='$image_place' alt='$place_name' />";
                } else {
                    echo "No image available";
                }

                echo "</div>";

                echo "<div class='rating-container'>";
                echo "<h2>Overall Rating:</h2>";

                if (!empty($avg_rating)) {
                    $overall_rating_percentage = round($avg_rating * 20);
                    echo "<p>$overall_rating_percentage%</p>";
                } else {
                    echo "<p>No ratings available</p>";
                }

                echo "</div>";

                echo "<div class='comment-container'>";
                echo "<h2>Comments:</h2>";
                $stmt = $conn->prepare("
                SELECT
                    user.login_user,
                    comments.comment_text,
                    comments.comment_image,
                    ratings.rating_value
                FROM comments
                LEFT JOIN user ON comments.id = user.id
                LEFT JOIN ratings ON comments.comment_id = ratings.comment_id
                WHERE comments.place_id = ?
            ");
                $stmt->bind_param("s", $place_id);
                $stmt->execute();
                $result_comments = $stmt->get_result();

                if ($result_comments) {
                    while ($row_comment = $result_comments->fetch_assoc()) {
                        $login = $row_comment['login_user'];
                        $comment_text = $row_comment['comment_text'];
                        $comment_image = $row_comment['comment_image'];
                        $rating_value = $row_comment['rating_value'];
                        echo "<div class='comment-box'>";
                        echo "<p><strong>$login:</strong> $comment_text";

                        if (!empty($comment_image)) {
                            echo "<br><img src='$comment_image' alt='Comment Image' style='width: 300px; height: 300px;' />";
                        }
                        if (!empty($rating_value)) {
                            echo "<span> <h4> Rating: $rating_value Star </h4> </span>";
                        }

                        echo "</p>";
                        echo "</div>";
                    }
                }
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
    ?>


    <?php
    if (isset($_SESSION['username'])) {
        $userId = getUserIdByUsername($conn, $_SESSION['username']);

        if ($userId !== false) {
            echo "<div class='comment-form-container'>";
            echo "<h2>Add Comment:</h2>";
            echo "<form class='comment-form' method='post' enctype='multipart/form-data'>";
            echo "<label for='comment_text'>Your Comment:</label>";
            echo "<textarea name='comment_text' placeholder='Enter your comment...' required></textarea>";
            echo "<br>";
            echo "<label for='rating'>Select Rating:</label>";
            echo "<input type='radio' name='rating' value='1'> 1";
            echo "<input type='radio' name='rating' value='2'> 2";
            echo "<input type='radio' name='rating' value='3'> 3";
            echo "<input type='radio' name='rating' value='4'> 4";
            echo "<input type='radio' name='rating' value='5'> 5";
            echo ' <div class="file-input-container">';
            echo '<label for="comment_image" class="file-input-label">Choose Image</label>';
            echo '<input type="file" name="comment_image" id="comment_image" class="file-input">
        </div>';
            echo "<br>";
            echo "<button type='submit' name='submit_comment'>Submit Comment</button>";
            echo "</form>";
            echo "</div>";
        } else {
            echo "Error: Unable to fetch user information.";
        }
    } else {
        echo "<h3> Please log in to add comments.";
    }
    ?>
</body>

</html>