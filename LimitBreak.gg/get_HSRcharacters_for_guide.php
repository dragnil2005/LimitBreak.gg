<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_clean();

// подключение к БД
$mysqli = new mysqli("127.127.126.5", "root", "", "hsr_characters_bd");

// проверка подключения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// получаем id из GET
$characterId = intval($_GET['id']);

// получаем данные персонажа
$characterResult = $mysqli->query("SELECT * FROM characters WHERE id = $characterId");
if ($characterResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Персонаж не найден']);
    exit;
}
$character = $characterResult->fetch_assoc();

// получаем статистику персонажа для всех уровней
$statsResult = $mysqli->query("SELECT * FROM character_stats WHERE character_id = $characterId");
$stats = $statsResult->fetch_all(MYSQLI_ASSOC);

// получаем скиллы персонажа
$skillsResult = $mysqli->query("SELECT * FROM character_skills WHERE character_id = $characterId");
$skills = [];

while ($skill = $skillsResult->fetch_assoc()) {
    // проверяем флаги скиллов
    $skillId = $skill['id'];

    $skill['is_dmg'] = (bool)$skill['is_dmg'];

        // если это боевой скилл
    if ($skill['is_dmg']) {
        $dmgLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_dmg_level WHERE skill_id = ?");
        $dmgLevelsQuery->bind_param("i", $skill['id']);
        $dmgLevelsQuery->execute();
        $dmgLevelsResult = $dmgLevelsQuery->get_result();

        $dmgLevels = [];

        while ($level = $dmgLevelsResult->fetch_assoc()) {
            // Подставляем значения в описание
            $level['description'] = str_replace(
                ["X_DMG", "Y_DMG"],
                [$level['dmg_percent_main'], $level['dmg_percent_secondary']],
                $skill['description']
            );

            // Собираем нужные параметры в массив
            $dmgLevels[] = [
                'level'                      => $level['level'],
                'description'                => $level['description'],
                'target_type'                => $level['target_type'],
                'weakness_break_value_main'  => $level['weakness_break_value_main'],
                'weakness_break_value_secondary' => $level['weakness_break_value_secondary'],
                'dmg_percent_main'           => $level['dmg_percent_main'],
                'dmg_percent_secondary'      => $level['dmg_percent_secondary'],
                'dmg_info'                   => $level['dmg_info']
            ];
        }

        // Добавляем dmg_levels в скилл
        $skill['dmg_levels'] = $dmgLevels;

            // Добавляем скилл в массив персонажа
        $character['character_skills'][] = [
            'id'              => $skill['id'],
            'character_id'    => $skill['character_id'],
            'name'            => $skill['name'],
            'type'            => $skill['type'],
            'energy_restore'  => $skill['energy_restore'],
            'dmg_levels'      => $skill['is_dmg'] ? $skill['dmg_levels'] : null
        ];
    }

    if ($skill['is_repeat_dmg'] === 'true') {
        $repeatDmgResult = $mysqli->query("SELECT * FROM character_skills_repeat_dmg_level WHERE skill_id = $skillId");
        $repeatDmgLevels = $repeatDmgResult->fetch_all(MYSQLI_ASSOC);
        
        // Создаем копию оригинального описания для замены
        $modifiedDescription = $skill['description'];
        
        foreach ($repeatDmgLevels as $level) {
            // Определяем значение для замены
            $replaceValue = str_contains($level['hit_order'], 'main') 
                ? $level['dmg_percent_main'] 
                : $level['dmg_percent_secondary'];
            
            // Заменяем плейсхолдер в описании
            $modifiedDescription = str_replace(
                $level['hit_order'],
                $replaceValue,
                $modifiedDescription
            );
        }
        
        // Сохраняем модифицированное описание
        $skill['description'] = $modifiedDescription;
        $skill['repeat_dmg_levels'] = $repeatDmgLevels;
    }

    if ($skill['is_rebound_dmg'] == 'true') {
        $dmgResult = $mysqli->query("SELECT * FROM character_skills_rebound_dmg_level WHERE skill_id = " . $skill['id']);
        $skill['rebound_dmg_levels'] = $dmgResult->fetch_all(MYSQLI_ASSOC);
    }

    if ($skill['is_buff'] == 'true') {
        // Логика для получения баффов (пример, можно добавить запросы в соответствующие таблицы)
        $buffResult = $mysqli->query("SELECT * FROM character_skills_buff_level WHERE skill_id = " . $skill['id']);
        $skill['buff_levels'] = $buffResult->fetch_all(MYSQLI_ASSOC);
    }

    if ($skill['is_debuff'] == 'true') {
        $debuffResult = $mysqli->query("SELECT * FROM character_skills_debuff_level WHERE skill_id = " . $skill['id']);
        $skill['debuff_levels'] = $debuffResult->fetch_all(MYSQLI_ASSOC);
    }

    if ($skill['is_heal'] == 'true') {
        $healResult = $mysqli->query("SELECT * FROM character_skills_heal_level WHERE skill_id = " . $skill['id']);
        $skill['heal_levels'] = $healResult->fetch_all(MYSQLI_ASSOC);
    }

    if ($skill['is_shield'] == 'true') {
        $shieldResult = $mysqli->query("SELECT * FROM character_skills_shield_level WHERE skill_id = " . $skill['id']);
        $skill['shield_levels'] = $shieldResult->fetch_all(MYSQLI_ASSOC);
    }
    $skills[] = $skill;
}

//получаем следы 
$tracesResult = $mysqli->query("SELECT * FROM character_traces WHERE character_id = $characterId");
$traces = $tracesResult->fetch_all(MYSQLI_ASSOC);

//получаем апгрэйды
$upgradesResult = $mysqli->query("SELECT * FROM character_upgrades WHERE character_id = $characterId");
$upgrades = $upgradesResult->fetch_all(MYSQLI_ASSOC);

if ($character['path'] === 'Память') {

    //получаем мема
    $memoResult = $mysqli->query("SELECT * FROM memosprite_stats WHERE character_id = $characterId");
    $memoStats = $memoResult->fetch_all(MYSQLI_ASSOC);

    $memospriteFinalStats = [];

    foreach ($stats as $statRow) {
        foreach ($memoStats as $memoRow) {
            $memospriteFinalStats[] = [
                'level' => $statRow['level'],
                'hp' => ($statRow['hp'] * ($memoRow['hp_percent'] / 100)) + $memoRow['hp_flat'],
                'atk' => ($statRow['atk'] * ($memoRow['atk_percent'] / 100)) + $memoRow['atk_flat'],
                'def' => ($statRow['def'] * ($memoRow['def_percent'] / 100)) + $memoRow['def_flat'],
                'speed' => ($statRow['speed'] * ($memoRow['speed_percent'] / 100)) + $memoRow['speed_flat'],
                'crit_rate' => ($statRow['crit_rate'] * ($memoRow['crit_rate'] / 100)),
                'crit_dmg' => ($statRow['crit_dmg'] * ($memoRow['crit_dmg'] / 100))
            ];
        }
    }

    // Забираем мемоспрайт скиллы
    $memospriteSkillsResult = $mysqli->query("SELECT * FROM memosprite_skills WHERE character_id = $characterId");
    $memospriteSkills = [];

    while ($skill = $memospriteSkillsResult->fetch_assoc()) {
        $skillId = $skill['id'];

        if ($skill['is_dmg'] === 'true') {
            $dmgResult = $mysqli->query("SELECT * FROM memosprite_skills_dmg_level WHERE skill_id = $skillId");
            $skill['dmg_levels'] = $dmgResult->fetch_all(MYSQLI_ASSOC);
            foreach ($dmgLevels as &$level) {
                $level['description'] = str_replace(
                    ["X_DMG", "Y_DMG"],
                    [$level['dmg_percent_main'], $level['dmg_percent_secondary']],
                    $skill['description']
                );
            }
            $skill['dmg_levels'] = $dmgLevels;
        }

        if ($skill['is_repeat_dmg'] === 'true') {
            $repeatDmgResult = $mysqli->query("SELECT * FROM memosprite_skills_repeat_dmg_level WHERE skill_id = $skillId");
            $repeatDmgLevels = $repeatDmgResult->fetch_all(MYSQLI_ASSOC);
            
            // Создаем копию оригинального описания для замены
            $modifiedDescription = $skill['description'];
            
            foreach ($repeatDmgLevels as $level) {
                // Определяем значение для замены
                $replaceValue = str_contains($level['hit_order'], 'main') 
                    ? $level['dmg_percent_main'] 
                    : $level['dmg_percent_secondary'];
                
                // Заменяем плейсхолдер в описании
                $modifiedDescription = str_replace(
                    $level['hit_order'],
                    $replaceValue,
                    $modifiedDescription
                );
            }
            
            // Сохраняем модифицированное описание
            $skill['description'] = $modifiedDescription;
            $skill['repeat_dmg_levels'] = $repeatDmgLevels;
        }

        if ($skill['is_buff'] === 'true') {
            $buffResult = $mysqli->query("SELECT * FROM memosprite_skills_buff_level WHERE skill_id = $skillId");
            $skill['buff_levels'] = $buffResult->fetch_all(MYSQLI_ASSOC);
        }

        if ($skill['is_debuff'] === 'true') {
            $debuffResult = $mysqli->query("SELECT * FROM memosprite_skills_debuff_level WHERE skill_id = $skillId");
            $skill['debuff_levels'] = $debuffResult->fetch_all(MYSQLI_ASSOC);
        }

        if ($skill['is_heal'] === 'true') {
            $healResult = $mysqli->query("SELECT * FROM memosprite_skills_heal_level WHERE skill_id = $skillId");
            $skill['heal_levels'] = $healResult->fetch_all(MYSQLI_ASSOC);
        }

        if ($skill['is_shield'] === 'true') {
            $shieldResult = $mysqli->query("SELECT * FROM memosprite_skills_shield_level WHERE skill_id = $skillId");
            $skill['shield_levels'] = $shieldResult->fetch_all(MYSQLI_ASSOC);
        }

        if ($skill['is_rebound_dmg'] === 'true') {
            $reboundResult = $mysqli->query("SELECT * FROM memosprite_skills_rebound_dmg_level WHERE skill_id = $skillId");
            $skill['rebound_dmg_levels'] = $reboundResult->fetch_all(MYSQLI_ASSOC);
        }

        $memospriteSkills[] = $skill;
    }
}

// Формируем результат
$response = [
    'character' => $character,
    'stats' => $stats,
    'traces' => $traces,
    'upgrades' => $upgrades
];

// Если path = 'Память' — добавляем memosprite_stats
if ($character['path'] === 'Память') {
    $response['memosprite_stats'] = $memospriteFinalStats;
    $response['memosprite_skills'] = $memospriteSkills;
}

echo json_encode($response);
$mysqli->close();
?>
