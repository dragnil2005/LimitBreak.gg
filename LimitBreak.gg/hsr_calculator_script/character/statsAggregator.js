const statBonuses = {}; // { hp: { flat: 0, percent: 0 }, atk: { flat: 0, percent: 0 }, ... }
const statSources = {};


const statUpdateListeners = new Set();

export function addStatUpdateListener(listener) {
  statUpdateListeners.add(listener);
}

function notifyStatChange() {
  for (const listener of statUpdateListeners) {
    listener();
  }
}


export function addStatBonusFromSource(stat, value, type = 'flat', source = 'default') {
  if (!statSources[source]) statSources[source] = {};
  if (!statSources[source][stat]) statSources[source][stat] = { flat: 0, percent: 0 };

  statSources[source][stat][type] = value;

  recomputeBonuses();
}

export function removeStatSource(source) {
  delete statSources[source];
  recomputeBonuses();
}

function recomputeBonuses() {
  Object.keys(statBonuses).forEach(stat => {
    statBonuses[stat] = { flat: 0, percent: 0 };
  });

  for (const source of Object.values(statSources)) {
    for (const [stat, bonus] of Object.entries(source)) {
      if (!statBonuses[stat]) statBonuses[stat] = { flat: 0, percent: 0 };
      statBonuses[stat].flat += bonus.flat || 0;
      statBonuses[stat].percent += bonus.percent || 0;
    }
  }

  notifyStatChange();
}

export function getFinalStatBonuses() {
  return statBonuses;
}

const statLabels = {
  hp_percent: 'HP', atk_percent: 'Сила атаки', def_percent: 'Защита', speed: 'Скорость',
  crit_rate: 'Крит. шанс', crit_dmg: 'Крит. урон', effect_hit: 'Эффект пробития',
  heal_boost: 'Бонус исцеления', energy_regen: 'Скорость восстановления энергии',
  effect_hit_rate: 'Шанс попадания эффектов', effect_res: 'Сопротивление эффектам',
  physical_dmg: 'Бонус физ. урона', fire_dmg: 'Бонус огн. урона', ice_dmg: 'Бонус лед. урона',
  lightning_dmg: 'Бонус элек. урона', wind_dmg: 'Бонус ветр. урона',
  quantum_dmg: 'Бонус квант. урона', imaginary_dmg: 'Бонус мним. урона',
  physical_res_ignore: 'Игнор физ. сопр.', fire_res_ignore: 'Игнор огн. сопр.',
  ice_res_ignore: 'Игнор лед. сопр.', lightning_res_ignore: 'Игнор элек. сопр.',
  wind_res_ignore: 'Игнор ветр. сопр.', quantum_res_ignore: 'Игнор квант. сопр.',
  imaginary_res_ignore: 'Игнор мним. сопр.', def_res_ignore: 'Игнор защиты противника'
};

export function renderStatBreakdownList() {
  const container = document.getElementById('stat-breakdown-list');
  container.innerHTML = '';

  const table = document.createElement('table');
  table.className = 'stat-bonus-table';

  const thead = document.createElement('thead');
  thead.innerHTML = `
    <tr>
      <th>Стат</th>
      <th>Процентная прибавка</th>
      <th>Флэтовая прибавка</th>
    </tr>
  `;
  table.appendChild(thead);

  const tbody = document.createElement('tbody');

  Object.entries(statBonuses).forEach(([stat, { flat, percent }]) => {
    if (flat === 0 && percent === 0) return;

    const row = document.createElement('tr');

    const label = statLabels[stat] || stat;
    const percentFormatted = percent ? `${(percent).toFixed(1)}%` : '0%';
    const flatFormatted = flat || 0;

    row.innerHTML = `
      <td>${label}</td>
      <td>${percentFormatted}</td>
      <td>${flatFormatted}</td>
    `;

    tbody.appendChild(row);
  });

  table.appendChild(tbody);
  container.appendChild(table);
}

