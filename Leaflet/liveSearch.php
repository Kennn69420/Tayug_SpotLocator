<?php
    include ("connection.php");

    if (isset($_POST['input'])) {
        $input = $_POST['input'];
        $query = "SELECT * FROM spots WHERE place_name LIKE '%$input%'";
        $result = $conn->query($query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $placeName = $row['place_name'];
                $latitude = $row['place_lat'];
                $longitude = $row['place_lng']; 

                echo "<div class='search-result' data-latitude='$latitude' data-longitude='$longitude'> <hr>  &#x2022;$placeName</div>";
            }
        } else {
            echo "No result";
        }
    }
?>