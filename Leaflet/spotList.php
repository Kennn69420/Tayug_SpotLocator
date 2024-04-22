<?php
session_start();
include "connection.php";
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
    <title>Spot List</title>
    <style>
        * {
            padding: 5px;
        }

        h1 {
            font-style: italic;
            font-family: Arial, Helvetica, sans-serif;

        }

        body {
            padding-top: 70px;
            background-color: #FBF9F1;

        }

        .image-container {
            background-color: white;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .image-box {
            width: 600px;
            margin-bottom: 20px;
            border: 1px solid #0F1035;
            box-sizing: border-box;
            transition: box-shadow 0.3s;
        }

        img {
            width: 400px;
            height: 350px;
            object-fit: cover;
            transition: width 0.3s;
        }

        img:hover {
            width: 500px;
        }

        .image-box:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 1, 0.7);
        }

        p {
            font-style: italic;
            font-size: 18px;
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
            padding-top: 10px;
            padding: 10px 100px;
            background: #232D3F;
        }

        header .logo img {
            max-width: 70px;
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

        .imggg {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
            margin: 20px auto;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            text-align: center;
            margin-bottom: 40px;
        }

        form {
            max-width: 600px;
            margin: auto;
            text-align: center;
            margin-bottom: 40px;
        }

        label,
        textarea,
        input {
            display: block;
            margin-bottom: 16px;
            width: 100%;
        }

        textarea {
            height: 100px;
        }

        input[type="radio"] {
            margin-right: 8px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        header {
            background-color: #232D3F;
            padding: 20px 0;
            text-align: center;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        li {
            margin: 0 15px;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            transition: color 0.6s;
        }

        a:hover {
            color: green;
        }

        .logo {
            width: 100px;
        }
    </style>
</head>

<body>
    <?php
    $isLoggedIn = isset($_SESSION['username']);

    echo '<header>
            <a class="logo"> <img src="icon/tayug_icon-removebg-preview.png" class="imggg" alt="Logo"></a>
            <ul>
                <li><a href="homePage.php">Home</a></li>';

    if (!$isLoggedIn) {
        echo '<li><a href="landingpage.php#login">Login</a></li>';
    }

    echo '<li><a href="homePage.php">Return</a></li>
          </ul>
          </header>';
    ?>
    <center>
        <h1> Lists of Spots </h1>
    </center>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search...">
        <script>
            $(document).ready(function() {
                $("#searchInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $(".image-box").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
            });
        </script>
    </div>
    <div class="image-container">
        <?php
        $sql = ("SELECT * FROM spots ORDER BY place_name ASC");
        $result = $conn->query($sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $placeID = $row['place_id'];
            $placeName = $row["place_name"];
            $place_image = $row['place_image'];

            echo "
                <div class='image-box'>
                    <p>$placeName</p>
                    <a href='spotListView.php?id=" . $placeID . "'><img src='$place_image' alt='$placeName'/></a>
                </div>
                ";
        }
        ?>
    </div>
</body>

</html>