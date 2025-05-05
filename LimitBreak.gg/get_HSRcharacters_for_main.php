<?php
$host = "127.127.126.5";
$user = "root";
$password = "";
$dbname = "hsr_characters_bd";

$conn = new mysqli($host, $user, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$sql = "SELECT id, name, rarity, path, path_image, dmg_type, dmg_type_image, image FROM characters";
$result = $conn->query($sql);

$characters = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $characters[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($characters);
?>
