<?php

require_once('connection.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $markers = [];

    $stmt = $conn->prepare('SELECT spots.*, AVG(ratings.rating_value) AS avg_rating
                            FROM spots
                            LEFT JOIN comments ON spots.place_id = comments.place_id
                            LEFT JOIN ratings ON comments.comment_id = ratings.comment_id
                            GROUP BY spots.place_id');
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $marker = [
            'id' => $row['place_id'],
            'name' => $row['place_name'],
            'lat' => $row['place_lat'],
            'lng' => $row['place_lng'],
            'image' => $row['place_image'],
            'desc' => $row['place_desc'],
            'avg_rating' => $row['avg_rating'],
        ];

        $markers[] = $marker;
    }

    echo json_encode($markers);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

$conn->close();
?>
