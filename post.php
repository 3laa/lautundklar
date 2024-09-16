<?php
$servername = "localhost:3306";
$username = "enfold_lautundklar";
$password = "1^9S2ogn6";
$dbname = "enfold_lautundklar";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM luk_wp_posts WHERE post_type LIKE 'post' AND ID != 15996 ORDER BY ID DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $counter = 0;
    while ($row = $result->fetch_array()) {
        $id = $row["ID"];
        $sql = "INSERT INTO luk_wp_postmeta (post_id, meta_key, meta_value) VALUES ('$id', '_aviaLayoutBuilder_active', 'active'),  ('$id', 'header_transparency', 'header_transparent');";

        echo "<h1>NUM: $counter | ID: $id</h1>";

        if ($conn->query($sql) === TRUE) {
            echo "New records created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $counter++;
    }
}

$conn->close();