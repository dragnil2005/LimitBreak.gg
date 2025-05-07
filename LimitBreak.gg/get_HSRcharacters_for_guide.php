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

$characterId = intval($_GET['id']);
$characterResult = $mysqli->query("SELECT * FROM characters WHERE id = $characterId");

if ($characterResult->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Персонаж не найден']);
    exit;
}

$character = $characterResult->fetch_assoc();

// Получаем статистику
$statsResult = $mysqli->query("SELECT * FROM character_stats WHERE character_id = $characterId");
$stats = $statsResult->fetch_all(MYSQLI_ASSOC);

// Получаем скиллы
$characterSkillsResult = $mysqli->query("SELECT * FROM character_skills WHERE character_id = $characterId");
$characterSkills = [];

while ($skill = $characterSkillsResult->fetch_assoc()) {
    $skillId = $skill['id'];

    // DMG
    $dmgLevels = [];
    if ($skill['is_dmg']) {
        $dmgLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_dmg_level WHERE skill_id = ?");
        $dmgLevelsQuery->bind_param("i", $skillId);
        $dmgLevelsQuery->execute();
        $dmgLevelsResult = $dmgLevelsQuery->get_result();

        while ($level = $dmgLevelsResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_DMG", "Y_DMG"],
                [ (string)$level['dmg_percent_main'], (string)$level['dmg_percent_secondary'] ],
                $skill['description']
            );
            $dmgLevels[] = $level;
        }
        $skill['dmg_levels'] = $dmgLevels;
    }

    // Repeat DMG
    $repeatDmgLevels = [];
    $descriptionTemplate = $skill['description'];
    if ($skill['is_repeat_dmg']) {
        $repeatDmgQuery = $mysqli->prepare("SELECT * FROM character_skills_repeat_dmg_level WHERE skill_id = ?");
        $repeatDmgQuery->bind_param("i", $skillId);
        $repeatDmgQuery->execute();
        $repeatDmgResult = $repeatDmgQuery->get_result();

        while ($row = $repeatDmgResult->fetch_assoc()) {
            $repeatDmgLevels[] = $row;
        }
    }

    // Rebound DMG
    $reboundDmgLevels = [];
    if ($skill['is_rebound_dmg']) {
        $reboundDmgQuery = $mysqli->prepare("SELECT * FROM character_skills_rebound_dmg_level WHERE skill_id = ?");
        $reboundDmgQuery->bind_param("i", $skillId);
        $reboundDmgQuery->execute();
        $reboundDmgResult = $reboundDmgQuery->get_result();

        while ($level = $reboundDmgResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_REBOUND_DMG_MAIN", "X_REBOUND_DMG_SECONDARY"],
                [(string)$level['dmg_percent_main'], (string)$level['dmg_percent_secondary']],
                $skill['description']
            );
            $reboundDmgLevels[] = $level;
        }
        $skill['rebound_dmg_levels'] = $reboundDmgLevels;
    }

    // Buff
    $buffLevels = [];
    if ($skill['is_buff']) {
        $buffLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_buff_level WHERE skill_id = ?");
        $buffLevelsQuery->bind_param("i", $skillId);
        $buffLevelsQuery->execute();
        $buffLevelsResult = $buffLevelsQuery->get_result();

        while ($level = $buffLevelsResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_BUFF", "X_BUFF_PER_STACK"],
                [$level['buff_value'], $level['buff_increase_per_stack']],
                $skill['description']
            );
            $buffLevels[] = $level;
        }
        $skill['buff_levels'] = $buffLevels;
    }

    // Debuff
    $debuffLevels = [];
    if ($skill['is_debuff']) {
        $debuffLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_debuff_level WHERE skill_id = ?");
        $debuffLevelsQuery->bind_param("i", $skillId);
        $debuffLevelsQuery->execute();
        $debuffLevelsResult = $debuffLevelsQuery->get_result();

        while ($level = $debuffLevelsResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_DEBUFF", "X_DEBUFF_PER_STACK"],
                [$level['debuff_value'], $level['debuff_increase_per_stack']],
                $skill['description']
            );
            $debuffLevels[] = $level;
        }
        $skill['debuff_levels'] = $debuffLevels;
    }

    // Heal
    $healLevels = [];
    if ($skill['is_heal']) {
        $healLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_heal_level WHERE skill_id = ?");
        $healLevelsQuery->bind_param("i", $skillId);
        $healLevelsQuery->execute();
        $healLevelsResult = $healLevelsQuery->get_result();

        while ($level = $healLevelsResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_HEAL_PERSENT_MAIN", "X_HEAL_FLAT_MAIN", "X_HEAL_PERSENT_SECONDARY", "X_HEAL_FLAT_SECONDARY"],
                [$level['heal_percent_main'], $level['heal_flat_main'], $level['heal_percent_secondary'], $level['heal_flat_secondary']],
                $skill['description']
            );
            $healLevels[] = $level;
        }
        $skill['heal_levels'] = $healLevels;
    }

    // Shield
    $shieldLevels = [];
    if ($skill['is_shield']) {
        $shieldLevelsQuery = $mysqli->prepare("SELECT * FROM character_skills_shield_level WHERE skill_id = ?");
        $shieldLevelsQuery->bind_param("i", $skillId);
        $shieldLevelsQuery->execute();
        $shieldLevelsResult = $shieldLevelsQuery->get_result();

        while ($level = $shieldLevelsResult->fetch_assoc()) {
            $level['description'] = str_replace(
                ["X_SHIELD_PERCENT", "X_SHIELD_INCREASE_PER_STACK"],
                [(string)$level['shield_percent'], (string)$level['shield_increase_per_stack']],
                $skill['description']
            );
            $shieldLevels[] = $level;
        }
        $skill['shield_levels'] = $shieldLevels;
    }

    // Общая обработка description_level
    $combinedLevels = array_merge($dmgLevels, $repeatDmgLevels, $buffLevels, $debuffLevels, $healLevels, $reboundDmgLevels, $shieldLevels);
    $maxLevel = 0;
    foreach ($combinedLevels as $row) {
        if ($row['level'] > $maxLevel) {
            $maxLevel = $row['level'];
        }
    }

    $allDescriptions = [];
    for ($i = 1; $i <= $maxLevel; $i++) {
        $replaceMap = [];

        foreach ($dmgLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_DMG"] = (string)$row['dmg_percent_main'];
                $replaceMap["Y_DMG"] = (string)$row['dmg_percent_secondary'];
            }
        }

        foreach ($repeatDmgLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["{$row['hit_order']}_main"] = $row['dmg_percent_main'];
                $replaceMap["{$row['hit_order']}_secondary"] = $row['dmg_percent_secondary'];
            }
        }

        foreach ($reboundDmgLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_REBOUND_DMG_MAIN"] = (string)$row['dmg_percent_main'];
                $replaceMap["X_REBOUND_DMG_SECONDARY"] = (string)$row['dmg_percent_secondary'];
            }
        }

        foreach ($shieldLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_SHIELD_PERCENT"] = (string)$row['shield_percent'];
                $replaceMap["X_SHIELD_INCREASE_PER_STACK"] = (string)$row['shield_increase_per_stack'];
            }
        }

        foreach ($buffLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_BUFF"] = $row['buff_value'];
                $replaceMap["X_BUFF_PER_STACK"] = $row['buff_increase_per_stack'];
            }
        }

        foreach ($debuffLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_DEBUFF"] = $row['debuff_value'];
                $replaceMap["X_DEBUFF_PER_STACK"] = $row['debuff_increase_per_stack'];
            }
        }

        foreach ($healLevels as $row) {
            if ($row['level'] == $i) {
                $replaceMap["X_HEAL_PERSENT_MAIN"] = $row['heal_percent_main'];
                $replaceMap["X_HEAL_FLAT_MAIN"] = $row['heal_flat_main'];
                $replaceMap["X_HEAL_PERSENT_SECONDARY"] = $row['heal_percent_secondary'];
                $replaceMap["X_HEAL_FLAT_SECONDARY"] = $row['heal_flat_secondary'];
            }
        }

        $levelDescription = $descriptionTemplate;

        foreach ($replaceMap as $key => &$value) {
            if ($value === null) {
                $value = 0;
            }
        }
        unset($value);

        foreach ($replaceMap as $key => $value) {
            $levelDescription = str_replace($key, $value, $levelDescription);
        }

        $allDescriptions[] = $levelDescription;

    }

    $skill['description_level'] = $allDescriptions;
    $characterSkills[] = $skill;
}

// Получаем traces
$tracesResult = $mysqli->query("SELECT * FROM character_traces WHERE character_id = $characterId");
$traces = $tracesResult->fetch_all(MYSQLI_ASSOC);

// Получаем upgrades
$upgradesResult = $mysqli->query("SELECT * FROM character_upgrades WHERE character_id = $characterId");
$upgrades = $upgradesResult->fetch_all(MYSQLI_ASSOC);

// Финальный вывод
$response = [
    'character' => $character,
    'stats' => $stats,
    'skills' => $characterSkills,
    'traces' => $traces,
    'upgrades' => $upgrades
];


// Обработка мемоспрайта, если путь — Память
if ($character['path'] === 'Память') {
    // Получаем статистику
    $memospriteStatsResult = $mysqli->query("SELECT * FROM memosprite_stats WHERE character_id = $characterId");
    $memospriteStats = $memospriteStatsResult->fetch_assoc();

    $characterStatsResult = $mysqli->query("SELECT * FROM character_stats WHERE character_id = $characterId");
    $characterStatsList = [];
    while ($row = $characterStatsResult->fetch_assoc()) {
        $characterStatsList[$row['level']] = $row;
    }

    $memospriteFinalStats = [];

    foreach ($characterStatsList as $level => $characterStats) {
        $memospriteFinalStats[$level] = [
            'hp' => (float)$characterStats['hp'] * ((float)$memospriteStats['hp_percent'] / 100) + (float)$memospriteStats['hp_flat'],
            'atk' => (float)$characterStats['atk'] * ((float)$memospriteStats['atk_percent'] / 100) + (float)$memospriteStats['atk_flat'],
            'def' => (float)$characterStats['def'] * ((float)$memospriteStats['def_percent'] / 100) + (float)$memospriteStats['def_flat'],
            'speed' => (float)$characterStats['speed'] * ((float)$memospriteStats['speed_percent'] / 100) + (float)$memospriteStats['speed_flat'],
            'crit_rate' => (float)$characterStats['crit_rate'],
            'crit_dmg' => (float)$characterStats['crit_dmg']
        ];

    }
    $response['memosprite_stats'] = $memospriteFinalStats;

    // Получаем скиллы
    $memospriteSkillsResult = $mysqli->query("SELECT * FROM memosprite_skills WHERE character_id = $characterId");
    $memospriteSkills = [];

    while ($skill = $memospriteSkillsResult->fetch_assoc()) {
        $skillId = $skill['id'];

        $dmgLevels = [];
        if ($skill['is_dmg']) {
            $query = $mysqli->prepare("SELECT * FROM memosprite_skills_dmg_level WHERE skill_id = ?");
            $query->bind_param("i", $skillId);
            $query->execute();
            $result = $query->get_result();
            while ($level = $result->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_DMG", "Y_DMG"],
                    [(string)$level['dmg_percent_main'], (string)$level['dmg_percent_secondary']],
                    $skill['description']
                );
                $dmgLevels[] = $level;
            }
            $skill['dmg_levels'] = $dmgLevels;
        }

        // Repeat DMG
        $repeatDmgLevels = [];
        $descriptionTemplate = $skill['description'];
        if ($skill['is_repeat_dmg']) {
            $repeatDmgQuery = $mysqli->prepare("SELECT * FROM memosprite_skills_repeat_dmg_level WHERE skill_id = ?");
            $repeatDmgQuery->bind_param("i", $skillId);
            $repeatDmgQuery->execute();
            $repeatDmgResult = $repeatDmgQuery->get_result();

            while ($row = $repeatDmgResult->fetch_assoc()) {
                $repeatDmgLevels[] = $row;
            }
        }

        $reboundDmgLevels = [];
        if ($skill['is_rebound_dmg']) {
            $query = $mysqli->prepare("SELECT * FROM memosprite_skills_rebound_dmg_level WHERE skill_id = ?");
            $query->bind_param("i", $skillId);
            $query->execute();
            $result = $query->get_result();
            while ($level = $result->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_REBOUND_DMG_MAIN", "X_REBOUND_DMG_SECONDARY"],
                    [(string)$level['dmg_percent_main'], (string)$level['dmg_percent_secondary']],
                    $skill['description']
                );
                $reboundDmgLevels[] = $level;
            }
            $skill['rebound_dmg_levels'] = $reboundDmgLevels;
        }

        $shieldLevels = [];
        if ($skill['is_shield']) {
            $query = $mysqli->prepare("SELECT * FROM memosprite_skills_shield_level WHERE skill_id = ?");
            $query->bind_param("i", $skillId);
            $query->execute();
            $result = $query->get_result();
            while ($level = $result->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_SHIELD_PERCENT", "X_SHIELD_INCREASE_PER_STACK"],
                    [(string)$level['shield_percent'], (string)$level['shield_increase_per_stack']],
                    $skill['description']
                );
                $shieldLevels[] = $level;
            }
            $skill['shield_levels'] = $shieldLevels;
        }

        // Buff
        $buffLevels = [];
        if ($skill['is_buff']) {
            $buffLevelsQuery = $mysqli->prepare("SELECT * FROM memosprite_skills_buff_level WHERE skill_id = ?");
            $buffLevelsQuery->bind_param("i", $skillId);
            $buffLevelsQuery->execute();
            $buffLevelsResult = $buffLevelsQuery->get_result();

            while ($level = $buffLevelsResult->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_BUFF", "X_BUFF_PER_STACK"],
                    [$level['buff_value'], $level['buff_increase_per_stack']],
                    $skill['description']
                );
                $buffLevels[] = $level;
            }
            $skill['buff_levels'] = $buffLevels;
        }

        // Debuff
        $debuffLevels = [];
        if ($skill['is_debuff']) {
            $debuffLevelsQuery = $mysqli->prepare("SELECT * FROM memosprite_skills_debuff_level WHERE skill_id = ?");
            $debuffLevelsQuery->bind_param("i", $skillId);
            $debuffLevelsQuery->execute();
            $debuffLevelsResult = $debuffLevelsQuery->get_result();

            while ($level = $debuffLevelsResult->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_DEBUFF", "X_DEBUFF_PER_STACK"],
                    [$level['debuff_value'], $level['debuff_increase_per_stack']],
                    $skill['description']
                );
                $debuffLevels[] = $level;
            }
            $skill['debuff_levels'] = $debuffLevels;
        }

        // Heal
        $healLevels = [];
        if ($skill['is_heal']) {
            $healLevelsQuery = $mysqli->prepare("SELECT * FROM memosprite_skills_heal_level WHERE skill_id = ?");
            $healLevelsQuery->bind_param("i", $skillId);
            $healLevelsQuery->execute();
            $healLevelsResult = $healLevelsQuery->get_result();

            while ($level = $healLevelsResult->fetch_assoc()) {
                $level['description'] = str_replace(
                    ["X_HEAL_PERSENT_MAIN", "X_HEAL_FLAT_MAIN", "X_HEAL_PERSENT_SECONDARY", "X_HEAL_FLAT_SECONDARY"],
                    [$level['heal_percent_main'], $level['heal_flat_main'], $level['heal_percent_secondary'], $level['heal_flat_secondary']],
                    $skill['description']
                );
                $healLevels[] = $level;
            }
            $skill['heal_levels'] = $healLevels;
        }

        $combinedLevels = array_merge($dmgLevels, $repeatDmgLevels, $buffLevels, $debuffLevels, $healLevels, $reboundDmgLevels, $shieldLevels);
        $maxLevel = 0;
        foreach ($combinedLevels as $row) {
            if ($row['level'] > $maxLevel) {
                $maxLevel = $row['level'];
            }
        }

        $allDescriptions = [];
        for ($i = 1; $i <= $maxLevel; $i++) {
            $replaceMap = [];

            foreach ($dmgLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_DMG"] = (string)$row['dmg_percent_main'];
                    $replaceMap["Y_DMG"] = (string)$row['dmg_percent_secondary'];
                }
            }

            foreach ($repeatDmgLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["{$row['hit_order']}_main"] = $row['dmg_percent_main'];
                    $replaceMap["{$row['hit_order']}_secondary"] = $row['dmg_percent_secondary'];
                }
            }

            foreach ($reboundDmgLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_REBOUND_DMG_MAIN"] = (string)$row['dmg_percent_main'];
                    $replaceMap["X_REBOUND_DMG_SECONDARY"] = (string)$row['dmg_percent_secondary'];
                }
            }

            foreach ($shieldLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_SHIELD_PERCENT"] = (string)$row['shield_percent'];
                    $replaceMap["X_SHIELD_INCREASE_PER_STACK"] = (string)$row['shield_increase_per_stack'];
                }
            }

            foreach ($buffLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_BUFF"] = $row['buff_value'];
                    $replaceMap["X_BUFF_PER_STACK"] = $row['buff_increase_per_stack'];
                }
            }

            foreach ($debuffLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_DEBUFF"] = $row['debuff_value'];
                    $replaceMap["X_DEBUFF_PER_STACK"] = $row['debuff_increase_per_stack'];
                }
            }

            foreach ($healLevels as $row) {
                if ($row['level'] == $i) {
                    $replaceMap["X_HEAL_PERSENT_MAIN"] = $row['heal_percent_main'];
                    $replaceMap["X_HEAL_FLAT_MAIN"] = $row['heal_flat_main'];
                    $replaceMap["X_HEAL_PERSENT_SECONDARY"] = $row['heal_percent_secondary'];
                    $replaceMap["X_HEAL_FLAT_SECONDARY"] = $row['heal_flat_secondary'];
                }
            }

            $levelDescription = $skill['description'];

            foreach ($replaceMap as $key => &$value) {
                if ($value === null) {
                    $value = 0;
                }
            }
            unset($value);

            foreach ($replaceMap as $key => $value) {
                $levelDescription = str_replace($key, $value, $levelDescription);
            }

            $allDescriptions[] = $levelDescription;
        }

        $skill['description_level'] = $allDescriptions;
        $memospriteSkills[] = $skill;
    }

    $response['memosprite_stats'] = $memospriteFinalStats;
    $response['memosprite_skills'] = $memospriteSkills;
}

// Получаем eidolons
$eidolonsResult = $mysqli->query("SELECT * FROM character_eidolons WHERE character_id = $characterId");
$eidolons = $eidolonsResult->fetch_all(MYSQLI_ASSOC);
$response['eidolons'] = $eidolons;

echo json_encode($response, JSON_UNESCAPED_UNICODE);

?>
