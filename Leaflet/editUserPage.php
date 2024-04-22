<?php
session_start();
require "connection.php";

if (!isset($_SESSION['adminUser'])) {
    header("Refresh:0;URL=adminLogin.php");
}

if (isset($_POST['returnBtn'])) {
    header("Refresh:0;URL=adminMain.php");
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["userId"])) {
    $userId = $_GET["userId"];

    $sql = "SELECT * FROM user WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 0;
                }

                h1 {
                    color: #333;
                }

                form {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                label {
                    display: block;
                    margin-bottom: 8px;
                }

                input {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    box-sizing: border-box;
                }

                button {
                    background-color: #4caf50;
                    color: #fff;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    margin-right: 10px;
                    /* Added margin-right for spacing */
                }

                #returnBtn {
                    background-color: #ff5722;
                    /* Changed color for return button */
                }

                button:hover {
                    background-color: #45a049;
                }
            </style>
        </head>

        <body>
            <header>
                <button type="submit" id="returnBtn" name="returnBtn">Return</button>
            </header>

            <form id="editForm">
                <input type="hidden" name="userId" value="<?php echo $userId; ?>">

                <label for="fname">First Name:</label>
                <input id="fname" type="text" name="Fname" value="<?php echo $userData['first_name']; ?>"><br>

                <label for="mname">Middle Initial:</label>
                <input id="mname" type="text" name="Mname" value="<?php echo $userData['middle_initial']; ?>"><br>

                <label for="lname">Last Name:</label>
                <input id="lname" type="text" name="Lname" value="<?php echo $userData['last_name']; ?>"><br>
                <label for="age">Sex:</label>
                <input id="age" type="text" name="Age" value="<?php echo $userData['sex']; ?>"><br>
                <label for="age">Address:</label>
                <input id="age" type="text" name="Age" value="<?php echo $userData['address']; ?>"><br>

                <label for="age">BirthDate:</label>
                <input id="age" type="text" name="Age" value="<?php echo $userData['birthdate']; ?>"><br>


                <label for="num">Phone Number:</label>
                <input id="num" type="text" name="Num" value="<?php echo $userData['phone_num']; ?>"><br>

                <label for="email">Email Address:</label>
                <input id="email" type="email" name="Email" value="<?php echo $userData['email_add']; ?>"><br>

                <label for="username">Login User:</label>
                <input id="username" type="text" name="loginUser" value="<?php echo $userData['login_user']; ?>"><br>

                <button type="submit" id="saveBtn">Save Changes</button>
            </form>

            <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function() {
                    $("#editForm").submit(function(e) {
                        e.preventDefault();
                        var userId = $("input[name='userId']").val();
                        var fname = $("#fname").val();
                        var mname = $("#mname").val();
                        var lname = $("#lname").val();
                        var age = $("#age").val();
                        var num = $("#num").val();
                        var email = $("#email").val();
                        var username = $("#username").val();

                        $.ajax({
                            url: 'updateUser.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                userId: userId,
                                fname: fname,
                                mname: mname,
                                lname: lname,
                                age: age,
                                num: num,
                                email: email,
                                username: username
                            },
                            success: function(response) {
                                console.log(response);

                                if (response.success) {
                                    alert("User Updated Successfully");
                                    window.location.href = 'adminmain.php';
                                } else {
                                    alert("Error: " + response.error);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr);
                                alert("An error occurred while processing the request.");
                            }
                        });
                    });

                    // Adding click event for the return button
                    $("#returnBtn").click(function() {
                        window.location.href = 'adminMain.php';
                    });
                });
            </script>
        </body>

        </html>
<?php
    } else {
        echo "User not found";
    }
} else {
    echo "Invalid request";
}
?>