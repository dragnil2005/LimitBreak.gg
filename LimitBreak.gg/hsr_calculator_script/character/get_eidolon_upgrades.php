<?php

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_clean();

$mysqli = new mysqli("127.127.126.5", "root", "", "hsr_characters_bd");

if ($mysqli->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => "Ошибка подключения: " . $mysqli->connect_error]));
}

$character_id = isset($_GET['character_id']) ? intval($_GET['character_id']) : 0;

if ($character_id === 0) {
    http_response_code(400);
    die(json_encode(['error' => 'Некорректный character_id']));
}

$query = $mysqli->prepare("
    SELECT eidolon_number, skill_type, level_increase, max_level_override
    FROM hsr_eidolon_skill_upgrades
    WHERE character_id = ?
");

if (!$query) {
    http_response_code(500);
    die(json_encode(['error' => 'Ошибка подготовки запроса']));
}

$query->bind_param("i", $character_id);
$query->execute();

$result = $query->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
