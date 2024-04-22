<?php
require "connection.php";

$validFname = "";
$validMname = "";
$validLname = "";
$validAge = "";
$validNum = "";
$validEmail = "";
$validLoginUser = "";
$validPass = "";
$validConPass = "";
$duplicateEmail = "";

if (isset($_POST['register'])) {
    $Fname = ucfirst($_POST['Fname']);
    $Mname =  ucfirst($_POST['Mname']);
    $Lname =  ucfirst($_POST['Lname']);
    $Age = $_POST['Age'];
    $Num = $_POST['Num'];
    $Email = $_POST['Email'];
    $loginUser = $_POST['loginUser'];
    $Pass = $_POST['Pass'];
    $conPass = $_POST['conPass'];


    if (empty($Email)) {
        $validEmail = "Email is required";
    } elseif (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $Email)) {
        $validEmail = "Please enter a valid email";
    } else {
        $sql = ("SELECT * FROM user WHERE  email_add = '$Email'");
        $result  = $conn->query($sql);
        $rowCount = mysqli_num_rows($result);
        if ($rowCount > 0) {
            $validEmail = "Email already exists!";
        }
    }
    if (empty($Fname)) {
        $validFname = "First Name is required";
    } else {
        $Fname = htmlspecialchars($Fname);
        if (!preg_match("/^[a-zA-Z ]+$/", $Fname)) {
            $validFname = "First Name should only contain valid letters";
        }
    }
    if (empty(trim($Mname))) {
        $validMname = "Middle Initial is required";
    } else {
        $Mname = htmlspecialchars($Mname);
        if (!preg_match("/^[a-zA-Z]$/", $Mname)) {
            $validMname = "Middle Initial should only contain one letter";
        }
    }
    if (empty(trim($Lname))) {
        $validLname = "Last Name is required";
    } else {
        $Lname = htmlspecialchars($Lname);
        if (!preg_match("/^[a-zA-Z]+$/", $Lname)) {
            $validLname = "First Name should only contain valid letters";
        }
    }
    if (empty($Age)) {
        $validAge = "Age is required";
    } else {
        if (!preg_match("/^\d+$/", $Age)) {
            $validAge = "Age must only contains Number";
        } else {
            if ($Age <= 17) {
                $validAge = "You must be at least 18 to use this Website!";
            }
        }
    }
    if (empty($Num)) {
        $validNum = "Number cannot be empty!";
    } else {

        if (!preg_match("/^[09]\d{10}$/", $Num)) {
            $validNum = "Phone number is invalid";
        }
    }
    if (empty($loginUser)) {
        $validLoginUser = "LoginUser is Required";
    } else {
        if (!preg_match("/^[a-zA-Z0-9_]+$/", $loginUser)) {
            $validLoginUser = "Invalid LoginUser";
        } else {
            $sql = ("SELECT * FROM user WHERE login_user = '$loginUser'");
            $result  = $conn->query($sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount > 0) {
                $validLoginUser = "LoginUser already taken!";
            }
        }
    }
    if (empty($Pass)) {
        $validPass = "Password is required";
        // } elseif (!preg_match("/^[a-zA-Z0-9]{10}$/", $Pass)) {
    } elseif (!preg_match("/^[a-zA-Z0-9]{10,}$/", $Pass)) {
        $validPass = "Password must be 10 characters ";
    }

    if (empty($conPass)) {
        $validConPass = "Confirm Password is required";
    } elseif ($conPass != $Pass) {
        $validConPass = "Passwords do not match";
    }
    if (empty($validEmail) && empty($validFname) && empty($validMname) && empty($validLname) && empty($validAge) && empty($validNum) && empty($validLoginUser) && empty($validPass) && empty($validConPass)) {
        $hashedPassword = md5($Pass);
        $sql = ("INSERT INTO user VALUES ('', '$Fname', '$Mname', '$Lname', '$Age', '$Num', '$Email', '$loginUser', '$hashedPassword')");
        if ($conn->query($sql) === TRUE) {
            sleep(2);
            echo "
            <script>
                alert('Successully Registered');
                window.location = 'adminMAin.php'
            </script>
        ";
        }
    }
}
if (isset($_POST['return'])) {
    header("Refresh:0;URL=adminMain.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intFanem = $_POST['Fname'];
    $intMname = $_POST['Mname'];
    $intLname = $_POST['Lname'];
    $intAge = $_POST['Age'];
    $intNum = $_POST['Num'];
    $intUser = $_POST['loginUser'];
    $intPass = $_POST['Pass'];
    $intconPass = $_POST['Pass'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>Registration Page</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        position: relative;
        z-index: 2;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        border-radius: 5px;
        width: 400px;
        text-align: center;
    }

    .form-box {
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .error-container {
        margin-bottom: 10px;
    }

    input[type="submit"] {
        background-color: #4caf50;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>

<body>
    <div class="container">

        <div class="form-container">
            <div class="form-box">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" autocomplete="off">
                    <h1>Registration Form</h1>

                    <label for="Email">Email Address</label>
                    <input type="text" id="Email" name="Email" placeholder="Email Address" value="<?php echo isset($_POST['Email']) ? htmlspecialchars($_POST['Email']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validEmail ?>
                        </span>
                    </div>
                    <br>
                    <label for="Fname">First Name</label>
                    <input type="text" id="Fname" name="Fname" placeholder="First Name" value="<?php echo isset($_POST['Fname']) ? htmlspecialchars($_POST['Fname']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validFname ?>
                        </span>
                    </div>
                    <br>
                    <label for="Mname">Middle Initial</label>
                    <input type="text" id="Mname" name="Mname" placeholder="Middle Initial" value="<?php echo isset($_POST['Mname']) ? htmlspecialchars($_POST['Mname']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validMname ?>
                        </span>
                    </div>
                    <br>
                    <label for="Lname">Last Name</label>
                    <input type="text" id="Lname" name="Lname" placeholder="Last Name" value="<?php echo isset($_POST['Lname']) ? htmlspecialchars($_POST['Lname']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validLname ?>
                        </span>
                    </div>
                    <br>
                    <label for="Age">Age</label>
                    <input type="text" id="Age" name="Age" placeholder="Age" value="<?php echo isset($_POST['Age']) ? htmlspecialchars($_POST['Age']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validAge ?>
                        </span>
                    </div>
                    <br>
                    <label for="Num">Phone Number</label>
                    <input type="text" id="Num" name="Num" placeholder="Phone Number" value="<?php echo isset($_POST['Num']) ? htmlspecialchars($_POST['Num']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validNum ?>
                        </span>
                    </div>
                    <br>
                    <label for="loginUser">Username</label>
                    <input type="text" id="loginUser" name="loginUser" placeholder="Login Username" value="<?php echo isset($_POST['loginUser']) ? htmlspecialchars($_POST['loginUser']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validLoginUser ?>
                        </span>
                    </div>
                    <br>
                    <label for="Pass">Password</label>
                    <input type="password" id="Pass" name="Pass" placeholder="Password" value="<?php echo isset($_POST['Pass']) ? htmlspecialchars($_POST['Pass']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validPass ?>
                        </span>
                    </div>
                    <br>
                    <label for="conPass">Confirm Password</label>
                    <input type="password" id="conPass" name="conPass" placeholder="Confirm Password" value="<?php echo isset($_POST['conPass']) ? htmlspecialchars($_POST['conPass']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validConPass ?>
                        </span>
                    </div>
                    <br>
                    <br>
                    <div class="form-wrapper">
                        <input type="submit" name="register" value="Register">
                        <input type="submit" name="return" value="Return">

                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>