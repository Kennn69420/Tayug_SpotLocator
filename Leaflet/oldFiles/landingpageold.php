<?php
session_start();
require('connection.php');

if (isset($_POST['btnRegis'])) {
    sleep(2);
    header("Refresh:.5;URL=regisPage.php");
}

$nameError = "";
$passError = "";

if (isset($_POST['btnLogin'])) {
    $Username = $_POST['username'];
    $Password = $_POST['password'];

    $row = null;

    if (empty(trim($Username))) {


        $nameError = "Name is Required";
        echo "<script> window.location = '#login' </script>";
    } else {
        $sql = "SELECT * FROM user WHERE login_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $Username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            $nameError = "Username invalid";
            echo "<script> window.location = '#login' </script>";
        }
    }

    if (empty(trim($Password))) {
        $passError = "Password is Required";
        echo "<script> window.location = '#login' </script>";
    } else {
        // Check if the username is valid before attempting to compare passwords
        if ($row) {
            // Use md5 for comparison
            if (md5($Password) != $row['password']) {
                $passError = 'Password invalid';
                echo "<script> window.location = '#login' </script>";
            } else {
                // Login successful
                sleep(2);
                $_SESSION['username'] = $row['login_user'];
                $_SESSION['password'] = $row['password'];
                header('Location: homePage.php');
                exit();
            }
        }
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $initUsername = $_POST['username'];
    $initPassword = $_POST['password'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        background: #fff;
        min-height: 375vh;
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
        padding: 40px 100px;
        z-index: 100000;
    }

    header.sticky {
        padding: 5px 100px;
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
        justify-content: center;
        align-items: center;
    }

    header ul li {
        position: relative;
        list-style: none;
    }

    header ul li a {
        position: relative;
        margin: 0 15px;
        text-decoration: none;
        color: #fff;
        letter-spacing: 2px;
        font-weight: 700;
        transition: 0.6s;
    }

    ul li a:hover {
        color: green;

    }

    header .logo img:hover {
        border-radius: 30px;
    }

    header.sticky .logo img {
        max-width: 60px;
        width: auto;
        display: block;
        margin: 0 auto;
        border-radius: 30px;
    }

    header.sticky .logo,
    header.sticky ul li a {
        color: wheat;
    }


    header.sticky .logo img:hover {
        border-radius: 5px;
        max-width: 80px;
    }

    header.sticky ul li a:hover {
        color: green;
    }

    .banner {
        background-image: linear-gradient(to top, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.5) 50%), url("images/sflower.jpeg");
        position: relative;
        width: 100%;
        height: 60vh;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        margin: auto;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bp {
        text-align: center;
        color: #fff;
        font-size: 1.5em;
        max-width: 600px;
        line-height: 1.6;
        position: absolute;
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
        transition: background 0.3s ease;
    }

    p:hover {
        background: rgba(0, 133, 64, 1);
        padding: 10px;
        color: #000;
        border-radius: 30px;
    }

    .bh3 {
        text-align: center;
        color: #fff;
        font-size: 2em;
        max-width: 600px;
        line-height: 1.5;
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        transition: background 0.3s ease;
    }

    h3:hover {
        background: rgba(0, 133, 64, 1);
        padding: 5px;
        color: #000;
    }

    .banner2 {
        background: url(images/about.png);
        padding: 25px;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        width: 100%;
        position: relative;
        border-bottom: 2px solid black;
        border-left: none;
        border-top: none;
        border-right: none;
    }

    .center-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        height: 100vh;
        margin-bottom: 100px;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px 5px rgb(20, 15, 18);
        max-width: 350px;
        display: flex;
        flex-direction: column;
        align-items: center;

    }

    .container:active {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px 5px rgb(20, 15, 18);
        max-width: 550px;
        max-height: 500px;
        display: flex;
        flex-direction: column;
        align-items: center;

    }

    .banner2 h2,
    .banner2 h3,
    .banner2 p {
        color: #333;
        margin-bottom: 10px;
        max-width: 600px;
        text-align: center;
    }

    .image-gallery {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .image-gallery img {
        width: 48%;
        max-width: 350px;
        border-radius: 8px;
        margin-bottom: 0;
        box-shadow: 0 0 10px 5px rgb(20, 15, 18);
    }

    .pic1 {
        position: absolute;
        right: 7%;
        bottom: 460px;
        padding: 20px;
        background: #fff;

    }

    .pic2 {
        position: absolute;
        left: 7%;
        bottom: 460px;
        padding: 20px;
        background: #fff;

    }

    .pic1:hover {
        position: absolute;
        right: 7%;
        padding: 23px;
        background: rgba(0, 133, 64, 1);
        border-radius: 40px;
        max-width: 360px;

    }

    .pic2:hover {
        position: absolute;
        left: 7%;
        padding: 23px;
        background: rgba(0, 133, 64, 1);
        border-radius: 40px;
        max-width: 360px;

    }

    .pic1:active {
        max-width: 600px;
        filter: brightness(80%);
    }

    .pic2:active {
        max-width: 600px;
        filter: brightness(80%);
    }

    h4 {
        text-align: center;
        color: #333;
    }

    .banner3 {
        background: url(images/bg.jfif);
        background-size: cover;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        position: relative;
    }

    .container2 {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px 5px rgb(20, 15, 18);
        width: 300px;
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Login Container */
    .login-btn {
        display: block;
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: none;
        border-radius: 4px;
        background: linear-gradient(to right, #007F2A, #4ED97C);
        color: #fff;
        cursor: pointer;
        transition: background 0.3s;
    }

    .login-btn:hover {
        background: linear-gradient(to right, #4ED97C, #007F2A);
    }

    .containerLogin {
        display: flex;
        max-width: 900px;
        background-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 0 10px 5px rgb(20, 15, 18);
        padding: 60px;
        gap: 100px;
        position: relative;
        border-radius: 40px;
    }

    .containerLogin::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 1px;
        height: 100%;
        background-color: #ddd;
        transform: translateX(-50%);
    }

    .left-side {
        flex: 1;
        overflow: hidden;
        margin-right: 20px;
    }

    .left-side img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .right-side {
        flex: 1;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    #formValidation {
        max-width: 500px;
        width: 100%;
    }

    h6 {
        text-align: center;
        font-size: 24px;
        color: #000;
    }

    .form-label {
        display: block;
        margin-bottom: 10px;
        color: #fff;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        box-sizing: border-box;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .error-container {
        margin-bottom: 15px;
        text-align: center;
        color: red;
    }

    .btn-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .btn {
        width: 100%;
        padding: 15px;
        margin: 10px 0;
        background: linear-gradient(to right, #007F2A, #4ED97C);
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
        border-radius: 4px;
    }

    .btn:hover {
        background: linear-gradient(to right, #4ED97C, #007F2A);
    }

    /* error message */
    .redirectInput {
        background-color: transparent;
        border: none;
        color: #3498db;
        text-decoration: underline;
        cursor: pointer;
        margin-top: 15px;
    }

    span {
        font-style: italic;
        font-weight: 600;
        color: red;
    }

    /* footer css */
    .dont {
        text-decoration: none;
    }

    .btn-container a {
        text-decoration: none;
        color: #3498db;
    }

    .carousel-container {
        max-width: 00px;
        /* Adjust the maximum width of the carousel */
        margin: auto;
        overflow: hidden;
        position: relative;
        margin-top: 1000px;
        /* Adjust the margin-top value for spacing */
    }

    .carousel-slide {
        display: none;
        text-align: center;
    }

    .carousel-slide img {
        max-width: 100%;
        /* Adjust image size */
        height: auto;
        border-radius: 8px;
    }

    .prev-btn,
    .next-btn {
        position: absolute;
        top: 50%;
        font-size: 20px;
        background: none;
        border: none;
        cursor: pointer;
        color: #fff;
        transition: color 0.3s ease;
    }

    .prev-btn:hover,
    .next-btn:hover {
        color: green;
    }

    .prev-btn {
        left: 10px;
    }

    .next-btn {
        right: 10px;
    }

    footer {
        background-color: #232D3F;
        color: #fff;
        padding: 30px;
        text-align: center;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        /* Wrap the content to the next line if needed */
        align-items: center;
        justify-content: center;
        position: fixed;
        bottom: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    footer h6 {
        margin: 0;
        /* Remove default margin */
        font-size: 24px;
        color: #fff;
    }

    footer p {
        margin: 0;
        /* Remove default margin */
        margin-top: 5px;
        color: #ddd;
        /* Lighter color for additional information */
    }

    footer img {
        max-width: 100px;
        margin-right: 20px;
        border-radius: 5px;
    }

    footer .footer-section {
        margin: 20px;
        /* Adjust the spacing between sections */
    }

    footer ul {
        list-style: none;
        /* Remove the list-style (dots) */
        padding: 0;
        /* Remove default padding */
    }

    footer ul li {
        position: relative;
        list-style: none;
        /* Remove the list-style (dots) */
    }

    footer ul li a {
        position: relative;
        text-decoration: none;
        color: #fff;
        transition: 0.6s;
    }

    footer ul li a:hover {
        color: green;
    }

    footer div {
        max-width: 250px;
        /* Set a maximum width for each section */
    }

    footer p {
        margin: 1px 0;
        /* Add some margin to the bottom of the paragraphs */
    }

    .footer-section {
        margin: 20px;
        /* Adjust the spacing between sections */
    }

    footer img:hover {
        max-width: 150px;
        border-radius: 5px;
    }

    .banner4 {
        font-family: 'Arial', sans-serif;
        margin-top: 50px;
        padding: 0;
        background-color: #f4f4f4;
        color: #333;
        display: flex;
        flex-direction: row;
        /* Stack containers vertically */
        align-items: center;
        /* Center items horizontally */
        justify-content: center;
        /* Center items vertically */
        min-height: 100vh;
    }

    .banner4 h1 {
        position: absolute;
        text-align: center;
        top: 20px;
    }

    .banner4 h1,
    h2,
    h3 {
        color: #2e9179;
    }

    .banner4 .container {
        background-color: #fff;
        border: 2px solid #000;
        margin: 10px 10px 0 10px;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        height: 400px;
        width: 15%;
        max-width: 600px;
        text-align: left;
    }

    #map {
        width: 500px;
        height: 500px;
    }
</style>

<body>
    <header>
        <a href="#" class="logo"> <img src="images/Spot Locator 2.png" alt="Logo"></a>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#login">Locate Now</a></li>
            <li><a href="#services">Service</a></li>
            <li><a href="adminLogin.php">Admin</a></li>
        </ul>
    </header>
    <script type="text/javascript">
        window.addEventListener("scroll", function() {
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0)
        })
    </script>

    <section class="banner" id="home">
        <h3 class="bh3">WELCOME TO SPOT LOCATOR</h3>
        <p class="bp">Embark on a journey of discovery and exploration as we unveil the hidden treasures nestled within
            the heart of Tayug.</p>
    </section>
    <script type="text/javascript">
        window.addEventListener("scroll", function() {
            var header = document.querySelector("header");
            header.classList.toggle("sticky", window.scrollY > 0)
        })
    </script>
    <section class="banner2" id="about">
        <div class="center-container">
            <div class="container">
                <h2>About Us</h2>
                <p>Welcome to Spot Locator, your gateway to discovering the hidden gems of Tayug, Pangasinan. We are
                    passionate about showcasing the charm and beauty of our town. Our platform is designed to help you
                    explore and experience the best that Tayug has to offer. Join us on this journey of exploration and
                    make lasting memories in our beloved town.</p>

                <p>Tayug is a picturesque town in Pangasinan, known for its rich history, vibrant culture, and stunning
                    landscapes. Nestled amidst lush greenery, Tayug offers a perfect blend of tradition and modernity.</p>

                <div class="image-gallery">
                    <img class="pic1" src="images/plaza.jpg" alt="Tayug Image 1">
                    <img class="pic2" src="images/plaza2.jpg" alt="Tayug Image 2">
                </div>
            </div>
            <!--put your carousel here-->
    </section>

    <section class="banner3" id="login">
        <div class="containerLogin">
            <div class="left-side">
                <script src="adminAPI.js"></script>
                <div id="map"></div>
                <script>
                    const initial_position = {
                        lat: 16.0279,
                        lng: 120.7442,
                    };

                    async function initMap() {
                        await google.maps.importLibrary("maps");

                        map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 17,
                            center: initial_position,
                            mapTypeID: "satellite",
                        });
                    }
                    initMap();
                </script>
            </div>
            <div class="right-side">
                <form id="formValidation" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h6>User Login</h6>
                    <label class="form-label">Username</label>
                    <input class="form-control" type="text" name="username" id="username" placeholder="Enter your Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <span>
                        <?php echo $nameError ?>
                    </span>
                    <br> <br>
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" id="password" placeholder="Enter your Password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
                    <span>
                        <?php echo $passError ?>
                    </span>
                    <br>
                    <br>
                    <div class="btn-container">
                        <input class="btn" type="submit" value="Login" name="btnLogin">
                        <p>You don't have an account? <a href="regisPage.php">Click here</a></p>
                    </div>

                </form>
            </div>
        </div>
        <script>
            document.querySelector('.redirectInput').addEventListener('click', function() {
                window.location.href = 'regisPage.php';
            });
        </script>
    </section>
    <section class="banner4" id="services">
        <br>
        <br>
        <br>
        <br>
        <h1>

            Our Features & Services.

        </h1>

        <div class="container" id="communications-container">
            <h3>Real-Time Location Tracking</h3>
            <p>Tayug Spot Locator offers real-time tracking capabilities,
                it's easy to see where you can eat, hang out and much more.
            </p>
        </div>

        <div class="container" id="Inspired-container">
            <h3>Weather Monitoring and Alerts</h3>
            <p>Tayug Spot Locator includes weather monitoring capabilities,
                users can receive real-time weather updates for their location.
                This feature can be crucial for outdoor enthusiasts,
                allowing them to make informed decisions based on current and upcoming weather conditions.</p>
        </div>

        <div class="container" id="Happy-container">
            <h3>Happy Users</h3>
            <p>easy to use even the elderly will not have difficulty when using it.
                Those who use it will be happy because it will make it easier for them to find where they are going,
                especially for foreigners.</p>
        </div>
    </section>
    <footer id="footer">
        <img src="images/Spot Locator 2.png" alt="Spot Locator Logo">
        <p>&copy; 2023 Spot Locator. Unlocking the Charm and Beauty of Tayug, Pangasinan.</p>
        <div class="footer-section">

            <!-- Add your member information here -->
        </div>
        <div class="footer-section">
            <h6>Front End</h6>
            <ul>
                <li><a href="https://www.facebook.com/hidingfakebush13/">Kyle Divinus Casing</a></li>
                <li><a href="https://www.facebook.com/FlipFlop.Siads">Justine Siador</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h6>Back End</h6>
            <ul>
                <li><a href="https://www.facebook.com/0936.Kennnnnnnnnnnnnnnnnnn">Ken Laurence Martinez</a></li>
                <li><a href="https://www.facebook.com/teroyzkie123">Terylle Ramos</a></li>
            </ul>
        </div>
        <div class="footer-section" style="margin-left: auto; text-align: right;">
            <h6>Contact Us</h6>
            <p>Email: info@spotlocator.com</p>
            <p>Phone: +123 456 7890</p>
        </div>
    </footer>

    <script>
        window.addEventListener("scroll", function() {
            var footer = document.getElementById("footer");
            var banner = document.querySelector(".banner");
            var banner2 = document.querySelector(".banner2");
            var banner3 = document.querySelector(".banner3");
            var banner4 = document.querySelector(".banner4");

            if (banner4.getBoundingClientRect().top <= window.innerHeight && footer.getBoundingClientRect().bottom >= 0) {
                footer.style.opacity = 1; // Show the footer
            } else {
                footer.style.opacity = 0; // Hide the footer
            }
        });
    </script>
    <script>
        function redirectTo(type) {
            if (type === 'user') {
                window.location.href = 'loginPage.php';
            } else if (type === 'admin') {
                window.location.href = 'adminLogin.php';
            }
        }
    </script>


</body>

</html>