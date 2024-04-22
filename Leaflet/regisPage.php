<?php
require "connection.php";

$validFname = "";
$validMname = "";
$validLname = "";
$validBdate = "";
$validSex = "";
$validAddress = "";
$validEmail = "";
$validLoginUser = "";
$validNum = "";
$validPass = "";
$validConPass = "";
$duplicateEmail = "";

$Sex = isset($_POST['sex']) ? $_POST['sex'] : '';
$Bdate = isset($_POST['Bdate']) ? $_POST['Bdate'] : '';
if (isset($_POST['register'])) {
    $Fname = ucfirst($_POST['Fname']);
    $Mname =  ucfirst($_POST['Mname']);
    $Lname =  ucfirst($_POST['Lname']);
    $Address = $_POST['Address'];
    $Email = $_POST['Email'];
    $Num = $_POST['Num'];
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
    if (empty($Bdate)) {
        $validBdate = "Birthdate is required";
    }

    if (empty($Sex)) {
        $validSex = "Sex cannot be optional!";
    } elseif ($Sex != 'male' && $Sex != 'female') {
        $validSex = "Invalid Sex";
    }

    if (empty($Address)) {
        $validAddress = "Address is a must!";
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
    if (empty($validEmail) && empty($validFname) && empty($validMname) && empty($validLname)  && empty($validSex) && empty($validAddress) && empty($validBdate) && empty($validNum) && empty($validLoginUser) && empty($validPass) && empty($validConPass)) {
        $hashedPassword = md5($Pass);
        $sql = "INSERT INTO user (first_name, middle_initial, last_name, sex, address, birthdate, phone_num, email_add, login_user, password) VALUES ('$Fname', '$Mname', '$Lname', '$Sex', '$Address', '$Bdate', '$Num', '$Email', '$loginUser', '$hashedPassword')";
        if ($conn->query($sql) === TRUE) {
            sleep(2);
            echo "
                    <script>
                        alert('Successully Registered');
                        window.location = 'landingpage.php #login'
                    </script>
                ";
        }
    }
}

if (isset($_POST['login'])) {
    header("Refresh:0;URL=loginPage.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $intFanem = $_POST['Fname'];
    $intMname = $_POST['Mname'];
    $intLname = $_POST['Lname'];
    $intSex = $_POST['sex'];
    $intAddress = $_POST['Address'];
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>Registration Page</title>
</head>
<style>
    *{
        font-family: 'Poppins', sans-serif;
    }

    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f4f4f4;
    }

    .container {
        display: flex;
        max-width: 1200px;
        background-color: #f4f4f4;
        margin: 0 auto;
    }

    .image-container img {
        border-radius: 20px;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .form-container {
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        flex: 1;
        background-color: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .form-wrapper {
        display: flex;
        justify-content: space-between;
        padding: 5px;
        background-color: #fff;
        position: sticky;
        bottom: 0;
        width: 100%;
        box-sizing: border-box;
        margin-top: 10%;
    }

    .image-container {
        flex: 1;
        overflow: hidden;
    }


    .form-box {
        flex: 1;
        padding: 40px;
        box-sizing: border-box;
        max-height: 70vh;
        overflow-y: auto;
    }

    .form-box h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    label {
        display: inline-block;
        margin-bottom: 10px;
        color: #333;
    }

    input {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        box-sizing: border-box;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .error-container {
        display: block;
        text-align: left;
        margin-bottom: 15px;
        color: red;
    }


    input[type="submit"] {
        width: 48%;
        padding: 15px;
        background-color: #3498db;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-radius: 4px;
    }

    input[type="submit"]:hover {
        background-color: #267bbf;
    }

    .logBTN {
        width: 48%;
        background-color: transparent;
        border: none;
        color: #3498db;
        text-decoration: underline;
        cursor: pointer;
    }

    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #3498db;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-track {
        background-color: #f4f4f4;
    }

    * {
        scrollbar-color: #3498db #f4f4f4;
        scrollbar-width: thick;
    }

    *::-ms-scrollbar {
        width: 20px;
    }

    *::-ms-scrollbar-thumb {
        background-color: #3498db;
        border-radius: 10px;
    }

    *::-ms-scrollbar-track {
        background-color: #f4f4f4;
    }

    header {
        background-color: #232D3F;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.6s;
        padding: 15px 50px;
        z-index: 100000;
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
        justify-content: center;
        align-items: center;
    }

    header ul li {
        position: relative;
        list-style: none;
    }

    header ul li a {
        position: relative;
        margin: 5px 15px;
        text-decoration: none;
        color: #fff;
        letter-spacing: 2px;
        font-weight: 700;
        transition: 0.6s;
        right: 90px;
    }

    ul li a:hover {
        color: green;
    }

    header .logo img:hover {
        border-radius: 30px;
    }

    @media only screen and (max-width: 768px) {
        .container {
            flex-direction: column;
        }

        .form-box,
        .image-container {
            width: 100%;
        }

        input[type="submit"],
        .logBTN {
            width: 100%;
        }

        .image-container {
            display: none;
        }
    }
</style>
</head>

<body>
    <header>
        <a href="#" class="logo"> <img src="icon/tayug_icon-removebg-preview.png" alt="Logo"></a>
        <ul>
            <li><a href="landingpage.php">Home</a></li>
        </ul>
    </header>
    <div class="container">
        <div class="image-container">
            <img src="images/tayug.jfif" alt="Image">
        </div>
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
                    <label for="sex">Sex</label>
                    &nbsp; &nbsp; &nbsp; &nbsp;
                    <select id="sex" name="sex">
                        <option value=" " <?php echo ($Sex === ' ') ? 'selected' : ''; ?>> </option>
                        <option value="male" <?php echo ($Sex === 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($Sex === 'female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validSex ?>
                        </span>
                    </div>
                    <br>
                    <label for="Address">Address</label>
                    <input type="text" id="Address" name="Address" placeholder="Address" value="<?php echo isset($_POST['Address']) ? htmlspecialchars($_POST['Address']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validAddress ?>
                        </span>
                    </div>
                    <br>
                    <label for="Bdate">Birthdate</label>
                    <input type="date" id="Bdate" name="Bdate" value="<?php echo isset($_POST['Bdate']) ? htmlspecialchars($_POST['Bdate']) : ''; ?>">
                    <div class="error-container">
                        <span style="color: red">
                            <?php echo $validBdate ?>
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
                    <center>
                        <input type="submit" name="register" value="Register"> <br>
                        <a href="landingpage.php #login"> Already have an Account? </a>
                    </center>
                </form>
            </div>
        </div>
    </div>
</body>

</html>