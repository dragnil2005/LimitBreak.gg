<?php
// Параметры подключения
$host = "127.127.126.5";
$user = "root";
$password = "";
$dbname = "weapons_bd";

// Устанавливаем соединение
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// 1) Читаем все оружия
$sql = "SELECT id, name, rarity, path, image FROM weapons";
$result = $conn->query($sql);

$weapons = [];
$ids = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $ids[] = $id;
        $weapons[$id] = [
            'id'     => $id,
            'name'   => $row['name'],
            'rarity' => $row['rarity'],
            'path'   => $row['path'],
            'image'  => $row['image'],
            'stats'  => [],
            'skills' => []
        ];
    }
}

// Если нет оружий — отдаём пустой массив
if (empty($ids)) {
    echo json_encode([], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// Превращаем массив id в строку вида "1,2,3,…"
$id_list = implode(',', $ids);

// 2) Читаем все характеристики (stats) для этих оружий
$sql_stats = "
    SELECT weapon_id, level, hp, atk, def
    FROM weapon_stats
    WHERE weapon_id IN ($id_list)
    ORDER BY weapon_id, CAST(level AS UNSIGNED)
";
$res_stats = $conn->query($sql_stats);
if ($res_stats) {
    while ($row = $res_stats->fetch_assoc()) {
        $wid = (int)$row['weapon_id'];
        $weapons[$wid]['stats'][] = [
            'level' => $row['level'],
            'hp'    => (float)$row['hp'],
            'atk'   => (float)$row['atk'],
            'def'   => (float)$row['def']
        ];
    }
}

// 3) Читаем все скилы (skills) для этих оружий
$sql_skills = "
    SELECT id, weapon_id, awaken_level, description, target, activation, buff_value, buff_stat, stacks
    FROM weapon_skills
    WHERE weapon_id IN ($id_list)
    ORDER BY weapon_id, CAST(awaken_level AS UNSIGNED)
";
$res_skills = $conn->query($sql_skills);
if ($res_skills) {
    while ($row = $res_skills->fetch_assoc()) {
        $wid = (int)$row['weapon_id'];
        $weapons[$wid]['skills'][] = [
            'id'           => (int)$row['id'],
            'awaken_level' => $row['awaken_level'],
            'description'  => $row['description'],
            'target'       => $row['target'],
            'activation'   => $row['activation'],
            'buff_value'   => (float)$row['buff_value'],
            'buff_stat'    => $row['buff_stat'],
            'stacks'       => (int)$row['stacks']
        ];
    }
}

// Закрываем соединение
$conn->close();

// Выводим JSON (с русскими буквами и «красивым» отступом)
header('Content-Type: application/json; charset=utf-8');
echo json_encode(
    array_values($weapons),
    JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
);
