<?php
    session_start();
    if(isset($_SESSION['username'])){
        header("location: homePage.php");
    }
    else{
        header("location: landingpage.php");
    }
?>