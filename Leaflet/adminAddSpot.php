<?php
include_once("connection.php");
if (isset($_POST['Addbtn'])) {
  $placeNAME = $_POST['place_name'];
  $placeDESC = $_POST['place_desc'];
  $placeLAT = $_POST['place_lat'];
  $placeLNG = $_POST['place_lng'];

  $targetDirectory = "uploads/";
  $targetFile = $targetDirectory . basename($_FILES["image"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

  $check = getimagesize($_FILES["image"]["tmp_name"]);
  if ($check === false) {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  if ($_FILES["image"]["size"] > 10000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "jfif") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  } else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
      $conn->query("INSERT INTO spots VALUES ('', '$placeNAME', '$placeDESC','$placeLAT', '$placeLNG', '$targetFile')");
      echo "<script>alert('Spot is Added');</script>";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
} else {
  if (isset($_POST['btnReturn'])) {
    header("location: adminMain.php");
  }
}

$stmt = $conn->query("SELECT * FROM spots");
$user = [];

while ($row = $stmt->fetch_assoc()) {
  array_push($user, $row);
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" type="image/x-icon" href="icon/tayug_icon-removebg-preview.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
  <title>Admin Add Spot</title>
  <style>
    #get_current {
      display: none;
    }

    body {
      background-color: white;
      display: flex;
      justify-content: space-between;
    }

    #left-content {
      max-width: 400px;
    }

    form {
      display: flex;
      flex-direction: column;
      margin-bottom: 50px;
    }

    label {
      flex: 1;
      padding: 5px;
      width: 150px;
    }

    input {
      flex: 1;
      padding: 5px;
      margin-bottom: 10px;
      width: 100%;
    }

    #map {
      margin-top: 20px;
      width: 60%;
      height: 750px;
    }
  </style>
</head>

<body>
  <div id="left-content">
    <h1>Add Spot</h1>
    <form action="" method="post" enctype="multipart/form-data">
      <div>
        <label for="place_name">Place Name</label>
        <input type="text" name="place_name" id="place_name" />
      </div>
      <div>
        <label for="place_desc">Place Description</label> <br>
        <textarea name="place_desc" id="place_desc"></textarea>
      </div>
      <div>
        <label for="place_lat">Latitude</label>
        <input type="text" name="place_lat" id="lat" />
      </div>

      <div>
        <label for="place_lng">Longitude</label>
        <input type="text" name="place_lng" id="lng" />
      </div>
      <div>
        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" accept="image/*" />
      </div>
      <div>
        <label for="register">Register</label>
        <input type="submit" id="register" name="Addbtn">
      </div>
      <br>
      <div>
        <input type="submit" name="btnReturn" class="redirectInput" value="Return">
      </div>
    </form>

  </div>

  <script>
    $(document).ready(function() {
      loaddata();

      function loaddata() {
        $.ajax({
          url: "adminSpotData.php",
          dataType: 'json',
          success: function(result) {
            var result = eval(result);
            $("#myTableinSpot").DataTable({
              processing: true,
              serverside: true,
              destroy: true,
              data: result,
              columns: [{
                  data: 0
                },
                {
                  data: 1
                },
                {
                  data: 2
                }

              ]
            });
          }
        });

      }
    });
  </script>
  <div id="map"></div>

  <button id="get_current"></button>
  <?php
  echo "
        <table id=\"myTableinSpot\" id=\"display\">
          <thead>
            <tr>
              <th>Place Id</th>
              <th>Place Name</th>
              <th>Place Description </th>
              <td></td>
              <td></td>
            </tr>
          </thead>
        <tbody>
        </tbody>
    </table>
    ";
  ?>
  <script src="adminAPI.js"></script>
  <script>
    let map;
    let marker;
    let user = <?php echo json_encode($user); ?>;
    let inputLat = document.getElementById("lat");
    let inputLng = document.getElementById("lng");
    let btnCurrent = document.getElementById("get_current");
    const initial_position = {
      lat: 16.0279,
      lng: 120.7442,
    };

    async function initMap() {
      await google.maps.importLibrary("maps");

      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 17,
        center: initial_position,
        mapTypeID: "hybrid",
      });
      user.forEach(user => {
        let infowindow = new google.maps.InfoWindow({
          content: `<h4>${user['place_name']} </h4>`,
        });
        let marker = new google.maps.Marker({
          position: {
            lat: parseFloat(user['place_lat']),
            lng: parseFloat(user['place_lng']),
          },
          map
        });
        marker.addListener('click', () => {
          infowindow.open({
            anchor: marker,
            map,
          });
        });
      });
      const infowindow = new google.maps.InfoWindow({
        content: "<h1> Initial Position </h1>",
        ariaLabel: "Uluru",
        maxWidth: 50,
      });
      marker = new google.maps.Marker({
        position: initial_position,
        map,
        title: "Hello World!",
      });

      marker.addListener("click", () => {
        infowindow.open({
          anchoer: marker,
          map,
        });
      });
      map.addListener("click", (result) => {
        marker.setMap(null);
        const temp_post = {
          lat: result.latLng.lat(),
          lng: result.latLng.lng(),
        };
        marker = new google.maps.Marker({
          position: temp_post,
          map,
          title: "Hello World!",
        });

        inputLat.value = temp_post.lat;
        inputLng.value = temp_post.lng;
      });
    }

    btnCurrent.addEventListener('click', () => {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
          marker.setMap(null);
          const temp_post = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
          marker = new google.maps.Marker({
            position: temp_post,
            map,
            title: "Hello World!",
          });
          map.setCenter(temp_post);
          map.setZoom(20)

        });
      } else {
        alert("not supported");
      }
    })


    initMap();
  </script>
</body>

</html>