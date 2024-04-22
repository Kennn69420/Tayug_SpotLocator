<?php
session_start();
require "connection.php";
if (isset($_POST['logBTN'])) {
    header("location: loginPage.php");
}

$nameErr = "";
$passErr = "";
$sql = ("SELECT * FROM admin");
$result  = $conn->query($sql);
$row = mysqli_fetch_assoc($result);
if (isset($_POST['adminBTN'])) {
    $adminUser = $_POST['adminUser'];
    $adminPass = $_POST['adminPass'];


    if (empty($adminUser)) {
        $nameErr = "Admin Username is missing";
    } else {
        if ($adminUser != $row['adminUser']) {
            $nameErr = 'Invalid credentials';
        } else {
            if ($adminUser == $row['adminUser']) {
                $nameErr = "";
            }
        }
    }
    if (empty($adminPass)) {
        $passErr = "Admin Password is missing";
    } else {
        if ($adminPass != $row['adminPass']) {
            $passErr = 'Invalid credentials';
        } else {
            if ($adminPass == $row['adminPass']) {
                $passErr = "";
            }
        }
    }

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['adminUser'] = $row['adminUser'];
        $_SESSION['adminPass'] = $row['adminPass'];
        if ($adminUser == $row['adminUser']  && $adminPass == $row['adminPass']) {
            if (isset($_SESSION['adminUser'])) {
                header('Refresh:0;URL=adminMain.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/adminlogStyle.css" type="text/css">
    <title>Document</title>
</head>
<style>
    body,
    html {
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #D6D6D4;
    }

    .container {
        max-width: 400px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 8px;
        color: #333;
    }

    input {
        padding: 10px;
        margin-bottom: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .login-btn {
        background-color: #4caf50;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .login-btn:hover {
        background-color: #45a049;
    }

    .error-message {
        color: #ff0000;
        margin-top: 10px;
    }

    .footer {
        margin-top: 20px;
        text-align: center;
        color: #777;
    }
</style>

<body>
    <div class="main">
        <div class="container p-5 my-5 bg-dark text-white w-50">
            <form method="post" action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h2>Admin Login</h2>
                <label class="form-label">Admin Username</label>
                <input class="form-control" type="text" name="adminUser" placeholder="Username" value="<?php echo isset($_POST['adminUser']) ? htmlspecialchars($_POST['adminUser']) : ''; ?>"> <br>
                <span style="color: red">
                    <?php echo $nameErr ?>
                </span> <br>
                <label class="form-label">Admin Password</label>
                <input class="form-control" type="password" name="adminPass" placeholder="Password" value="<?php echo isset($_POST['adminPass']) ? htmlspecialchars($_POST['adminPass']) : ''; ?>"> <br>
                <span style="color: red">
                    <?php echo $passErr ?>
                </span> <br>
                <input class="btn btn-light" type="submit" value="Login" name="adminBTN"> <br>
            </form>
            <br>
        </div>
    </div>
    <script>
        document.querySelector('.redirectInput').addEventListener('click', function() {
            window.location.href = 'landingpage.php';
        });
    </script>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $adUser = $_POST['adminUser'];
        $adPass = $_POST['adminPass'];
    }
    ?>
</body>
</body>

</html>