<?php
session_start();
require "connection.php";

if (!isset($_SESSION['adminUser'])) {
    header("Refresh:0;URL=adminLogin.php");
}
if (isset($_POST['logout'])) {
    if (session_destroy()) {
        header("Refresh:0;URL=adminLogin.php");
    }
}
if (isset($_POST['addSpot'])) {
    header("Refresh:0;URL=adminAddSpot.php");
}
if (isset($_POST['addUser'])) {
    header("Refresh:0;URL=adminAdd.php");
}
if (isset($_POST['viewUser'])) {
    header("Refresh:0;URL=adminViewUser.php");
}
$sql = "SELECT * FROM user";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <title>Main Admin Page</title>
</head>
<style>
    body {
        background-color: white;
    }

    .flex {
        display: flex;
    }

    .dashboard-box {
        flex: 1;
        display: block;
        border: 1px solid #ccc;
        padding: 10px;
        margin: 10px;
        text-align: center;
    }

    h2 {
        margin-bottom: 10px;
    }

    .minimalist-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        border: none;
        background-color: #D9EAD3;
        color: #333;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s, transform 0.3s;
    }

    .minimalist-button:hover {
        background-color: #C3D2BD;
        transform: translateY(-2px);
    }

    .styled-input-button {
        margin-right: 20px;
        margin-bottom: 10px;
        padding: 10px 20px;
        font-size: 16px;
        text-align: center;
        text-decoration: none;
        border: none;
        background-color: #D9EAD3;
        color: black;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
    }

    .styled-input-button:hover {
        background-color: #C3D2BD;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    }

    .dtb {
        height: 400px;
    }

    #myTable {
        background-color: white;
    }

    .iconms {
        height: 200px;
        width: 200px;
        order: 1;
    }

    .image-mss {
        width: 100px;
        display: block;
        padding: 10px;
        margin: 10px;
        text-align: center;
    }
</style>

<body>
    <h1>Admin</h1>
    <div class="flex">
        <div class="dashboard-box">
            <h2>
                Users
                <?php
                $sql = "SELECT COUNT(*) AS total_users FROM user";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $totalUsers = $row['total_users'];
                } else {
                    $totalUsers = 0;
                }
                ?>
                <p>Total Users: <?php echo $totalUsers; ?></p>
            </h2>
        </div>

        <div class="dashboard-box">
            <h2>
                Spots
                <?php
                $sql = "SELECT COUNT(*) AS total_spots FROM spots";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $totalSpots = $row['total_spots'];
                } else {
                    $totalSpots = 0;
                }
                ?>
                <p>Total Spots: <?php echo $totalSpots; ?></p>
            </h2>
        </div>
        <div class="img-mss">
            <img src="icon/tayug_icon-removebg-preview.png" alt="" class="iconms">
        </div>
    </div>
    <hr>
    <form method="post"> <br>
        <input type="submit" name="addSpot" class="styled-input-button" value="Add Spot">
        <button id="toggleButton" name="addUser" class="styled-input-button">Add User</button>
        <input type="submit" name="viewUser" class="styled-input-button" value="View User">
        <br>
        <br>
        <hr>
        <h2>Admin Viewing Spots </h2> <br>
        <?php
        include "adminViewSpots.php";
        ?>
        <br>
        <input type="submit" name="logout" class="styled-input-button" value="Logout">
    </form>
    <br>
    <br>
</body>

</html>