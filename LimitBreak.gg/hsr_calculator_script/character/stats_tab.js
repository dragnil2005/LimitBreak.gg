import { addStatUpdateListener, getFinalStatBonuses } from './statsAggregator.js';

let lastStats = null;
let lastLevel = null;

export function initCharacterStats(stats, level) {
  lastStats = stats;
  lastLevel = level;

  const external = getFinalStatBonuses();
  window.calculatedBonuses = {};

  for (const [stat, value] of Object.entries(external)) {
    if (typeof value === 'object' && value !== null && 'flat' in value && 'percent' in value && !stat.endsWith('_flat') && !stat.endsWith('_percent')) {
      console.log('значение value:', stat);
      window.calculatedBonuses[`${stat}_flat`] = value.flat;
      window.calculatedBonuses[`${stat}_percent`] = value.percent;
    } 
    else {
      // если имя заканчивается на _flat или _percent — вставляем как есть
      if (stat.endsWith('_flat')) {
        window.calculatedBonuses[stat] = value.flat;
      } else if (stat.endsWith('_percent')) {
        window.calculatedBonuses[stat] = value.percent;
      } else {
        // иначе вставляем как универсальный бонус
        window.calculatedBonuses[stat] = value;
      }
    }
  }


  console.log('[initCharacterStats] Итоговые бонусы:', window.calculatedBonuses);

  const baseStats = ['hp', 'atk', 'def', 'speed'];
  const sectionTitles = {
    base: 'Базовые характеристики',
    advanced: 'Продвинутые характеристики',
    damage: 'Тип урона',
    ignore: 'Игнорирование сопротивлений'
  };

  const statLabels = {
    hp: 'HP', atk: 'Сила атаки', def: 'Защита', speed: 'Скорость', 
    crit_rate: 'Крит. шанс', crit_dmg: 'Крит. урон', effect_hit: 'Эффект пробития',
    heal_boost: 'Бонус исцеления', energy_regen: 'Скорость восстановления энергии',
    effect_hit_rate: 'Шанс попадания эффектов', effect_res: 'Сопротивление эффектам',
    physical_dmg: 'Бонус физ. урона', fire_dmg: 'Бонус огн. урона', ice_dmg: 'Бонус лед. урона',
    lightning_dmg: 'Бонус элек. урона', wind_dmg: 'Бонус ветр. урона',
    quantum_dmg: 'Бонус квант. урона', imaginary_dmg: 'Бонус мним. урона', all_dmg: 'Бонус всего урона',
    physical_res_ignore: 'Игнор физ. сопр.', fire_res_ignore: 'Игнор огн. сопр.',
    ice_res_ignore: 'Игнор лед. сопр.', lightning_res_ignore: 'Игнор элек. сопр.',
    wind_res_ignore: 'Игнор ветр. сопр.', quantum_res_ignore: 'Игнор квант. сопр.',
    imaginary_res_ignore: 'Игнор мним. сопр.'
  };

  const statsContainer = document.getElementById('stats-tab');
  statsContainer.innerHTML = ''; // Очистка перед заполнением
  const levelStats = stats.find(stat => stat.level === level.toString()) || {};

  function createSection(title) {
    const section = document.createElement('div');
    section.className = 'stats-section';

    const header = document.createElement('h3');
    header.textContent = title;
    section.appendChild(header);

    const table = document.createElement('table');
    table.className = 'stats-table';
    section.appendChild(table);

    statsContainer.appendChild(section);
    return table;
  }

  function addRow(table, label, base, percent, flat = 0, isPercent = false) {
    const row = document.createElement('tr');

    const labelCell = document.createElement('td');
    labelCell.textContent = label;
    row.appendChild(labelCell);

    const baseCell = document.createElement('td');
    baseCell.textContent = isPercent ? `${base.toFixed(1)}%` : base;
    row.appendChild(baseCell);

    const bonusCell = document.createElement('td');
    bonusCell.textContent = percent > 0 ? `+${percent.toFixed(1)}%` : '0%';
    row.appendChild(bonusCell);

    const flatCell = document.createElement('td');
    flatCell.textContent = isPercent ? '—' : `+${flat}`;
    row.appendChild(flatCell);

    const totalCell = document.createElement('td');
    const total = isPercent ? `${(base + percent).toFixed(1)}%` : Math.floor(base * (1 + percent / 100) + flat);
    totalCell.textContent = total;
    row.appendChild(totalCell);

    table.appendChild(row);
  }

  // Секции
  const baseTable = createSection(sectionTitles.base);
  baseStats.forEach(stat => {
    const label = statLabels[stat];
    const base = parseFloat(levelStats[stat] || 0);
    const percent = parseFloat(window.calculatedBonuses?.[`${stat}_percent`] || 0);
    const flat = parseFloat(window.calculatedBonuses?.[`${stat}_flat`] || 0);
    addRow(baseTable, label, base, percent, flat);
  });

  const advancedTable = createSection(sectionTitles.advanced);
  ['crit_rate', 'crit_dmg', 'effect_hit', 'heal_boost', 'energy_regen', 'effect_hit_rate', 'effect_res'].forEach(stat => {
    const label = statLabels[stat];
    const base = parseFloat(levelStats[stat] || 0);
    const percent = parseFloat(window.calculatedBonuses?.[`${stat}_percent`] || 0);
    addRow(advancedTable, label, base, percent, 0, true);
  });

  const damageTable = createSection(sectionTitles.damage);
  ['physical_dmg', 'fire_dmg', 'ice_dmg', 'lightning_dmg', 'wind_dmg', 'quantum_dmg', 'imaginary_dmg', 'all_dmg'].forEach(stat => {
    const label = statLabels[stat];
    const base = parseFloat(levelStats[stat] || 0);
    const percent = parseFloat(window.calculatedBonuses?.[`${stat}_percent`] || 0);
    addRow(damageTable, label, base, percent, 0, true);
  });

  const ignoreTable = createSection(sectionTitles.ignore);
  ['physical_res_ignore', 'fire_res_ignore', 'ice_res_ignore', 'lightning_res_ignore', 'wind_res_ignore', 'quantum_res_ignore', 'imaginary_res_ignore'].forEach(stat => {
    const label = statLabels[stat];
    const base = parseFloat(levelStats[stat] || 0);
    const percent = parseFloat(window.calculatedBonuses?.[`${stat}_percent`] || 0);
    addRow(ignoreTable, label, base, percent, 0, true);
  });
}

// Обновление при изменении статов
addStatUpdateListener(() => {
  if (lastStats && lastLevel) {
    initCharacterStats(lastStats, lastLevel);
  }
});
