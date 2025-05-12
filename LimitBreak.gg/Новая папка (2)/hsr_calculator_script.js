// ==== КОНСТАНТЫ ====
const levelSteps = [1, 20, 30, 40, 50, 60, 70, 80];

// ==== КЭШИ И СОСТОЯНИЯ ====
let statsCache = {};
let upgradesCache = {};
let modalCharacters = [];
const modalFilters = { rarity: 'all', damageType: 'all', path: 'all' };
const activeUpgrades = new Set();

// ==== МАППИНГ ТИПОВ СТАТОВ ====
const statLabels = {
  hp_percent: 'HP %', atk_percent: 'АТК %', def_percent: 'ЗАЩИТА %',
  speed_percent: 'Скорость %', speed: 'Скорость',
  crit_rate: 'Крит. шанс', crit_dmg: 'Крит. урон',
  bonus_phys_dmg: 'Бонус физ. урона', bonus_fire_dmg: 'Бонус огн. урона',
  bonus_ice_dmg: 'Бонус лед. урона', bonus_electro_dmg: 'Бонус электр. урона',
  bonus_wind_dmg: 'Бонус ветр. урона', bonus_quantum_dmg: 'Бонус квант. урона',
  bonus_imaginary_dmg: 'Бонус мним. урона', chance_of_effects_to_hit: 'Шанс эффектов',
  resistance_to_effects: 'Резист эффектов'
};
// сопоставление stat_type из БД с ключами baseStats/bonusStats
const keyMap = {
  hp_percent: 'hp',
  atk_percent: 'atk',
  def_percent: 'def',
  speed_percent: 'speed',
  speed: 'speed',
  crit_rate: 'critRate',
  crit_dmg: 'critDmg'
};

// ==== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ====
async function fetchJSON(url) {
  const response = await fetch(url);
  if (!response.ok) throw new Error('Network error');
  return response.json();
}

async function loadStatsForCharacter(charId) {
  if (!statsCache[charId]) {
    const data = await fetchJSON(`get_HSRcharacters_for_guide.php?id=${charId}`);
    statsCache[charId] = data.stats;
  }
  return statsCache[charId];
}

async function loadUpgradesForCharacter(charId) {
  if (!upgradesCache[charId]) {
    const data = await fetchJSON(`get_HSRcharacters_for_guide.php?id=${charId}`);
    upgradesCache[charId] = data.upgrades || [];
  }
  return upgradesCache[charId];
}

// ==== ОБНОВЛЕНИЕ СТАТИСТИК ====
async function updateStats() {
  const tbody = document.querySelector('#stats-table tbody');
  const loading = document.getElementById('stats-loading');
  const errorEl = document.getElementById('stats-error');
  tbody.innerHTML = '';
  errorEl.style.display = 'none';
  loading.style.display = 'block';

  try {
    const characterImg = document.getElementById('selected-character-image');
    const characterId = characterImg.dataset.id;
    const levelIndex = +document.getElementById('level-range').value;
    const actualLevel = levelSteps[levelIndex];

    const stats = await loadStatsForCharacter(characterId);
    const entry = stats.find(row => +row.level === actualLevel);
    if (!entry) throw new Error(`No stats for level ${actualLevel}`);

    // Базовые показатели
    const baseStats = {
      atk: +entry.atk || 0,
      def: +entry.def || 0,
      hp: +entry.hp || 0,
      speed: +entry.speed || 0,
      critRate: +entry.crit_rate || 0,
      critDmg: +entry.crit_dmg || 0,
      energy: +entry.energy || 0
    };

    // Бонусы из апгрейдов
    const bonusStats = { atk:0, def:0, hp:0, speed:0, critRate:0, critDmg:0, energy:0 };
    const extraBonuses = {};
    const upgrades = await loadUpgradesForCharacter(characterId);
    activeUpgrades.forEach(idx => {
      const up = upgrades[idx];
      if (!up) return;
      const key = keyMap[up.stat_type];
      if (key) bonusStats[key] += +up.value;
      else extraBonuses[up.stat_type] = (extraBonuses[up.stat_type] || 0) + +up.value;
    });

    // Доп. поля + бонусы из БД и апгрейдов
    const healingBonus = entry.healingBonus != null ? +entry.healingBonus : 0;
    const shieldStrength = entry.shieldStrength != null ? +entry.shieldStrength : 0;
    const bonusPhysical = (entry.bonus_phys_dmg || 0) + (extraBonuses.bonus_phys_dmg || 0);
    const bonusFire = (entry.bonus_fire_dmg || 0) + (extraBonuses.bonus_fire_dmg || 0);
    const bonusIce = (entry.bonus_ice_dmg || 0) + (extraBonuses.bonus_ice_dmg || 0);
    const bonusElectro = (entry.bonus_electro_dmg || 0) + (extraBonuses.bonus_electro_dmg || 0);
    const bonusWind = (entry.bonus_wind_dmg || 0) + (extraBonuses.bonus_wind_dmg || 0);
    const bonusQuantum = (entry.bonus_quantum_dmg || 0) + (extraBonuses.bonus_quantum_dmg || 0);
    const bonusImaginary = (entry.bonus_imaginary_dmg || 0) + (extraBonuses.bonus_imaginary_dmg || 0);
    const bonusAll = (entry.bonus_all_dmg || 0) + (extraBonuses.bonus_all_dmg || 0);

    // Составляем таблицу
    const rows = [
      [ 'АТК',        (baseStats.atk + bonusStats.atk).toFixed(2) ],
      [ 'ЗАЩИТА',     (baseStats.def + bonusStats.def).toFixed(2) ],
      [ 'HP',         (baseStats.hp + bonusStats.hp).toFixed(2) ],
      [ 'Скорость',   (baseStats.speed + bonusStats.speed).toFixed(2) ],
      [ 'Крит. шанс', (baseStats.critRate + bonusStats.critRate).toFixed(2) + '%' ],
      [ 'Крит. урон', (baseStats.critDmg + bonusStats.critDmg).toFixed(2) + '%' ],
      [ 'Восст. энергии', (baseStats.energy + bonusStats.energy).toFixed(2) ],
      [ 'Бонус лечения',     healingBonus + '%' ],
      [ 'Прочность щита',   shieldStrength ],
      [ 'Бонус физ. урона',  bonusPhysical + '%' ],
      [ 'Бонус огн. урона',  bonusFire + '%' ],
      [ 'Бонус лед. урона',  bonusIce + '%' ],
      [ 'Бонус эл. урона',   bonusElectro + '%' ],
      [ 'Бонус ветр. урона', bonusWind + '%' ],
      [ 'Бонус квант. урона', bonusQuantum + '%' ],
      [ 'Бонус мним. урона',  bonusImaginary + '%' ],
      [ 'Бонус всего урона', bonusAll + '%' ]
    ];

    // Путь Память
    if (characterImg.dataset.path === 'Память') {
      rows.push([ 'Урон навыка духа памяти',  (entry.dmgSpiritSkill  || 0) + '%' ]);
      rows.push([ 'Урон таланта духа памяти', (entry.dmgSpiritTalent || 0) + '%' ]);
    }

    // Рендер
    rows.forEach(([label, val]) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${label}</td><td>${val}</td>`;
      tbody.appendChild(tr);
    });

  } catch (error) {
    console.error('updateStats:', error);
    document.getElementById('stats-error').style.display = 'block';
  } finally {
    loading.style.display = 'none';
  }
}

// ==== ПОЛЗУНКИ ====
function updateDisplay(id) {
  const input = document.getElementById(id);
  const display = document.getElementById(`${id}-display`);
  if (input && display) display.textContent = input.value;
}

// изменение числового input (+/-)
function changeValue(id, delta) {
  const input = document.getElementById(id);
  if (!input) return;
  let val = +input.value + delta;
  const min = +input.min || 0;
  const max = +input.max || Infinity;
  input.value = Math.min(max, Math.max(min, val));
  updateDisplay(id);
  if (id === 'level-range') {
    updateLevelDisplay(); updateStats(); updateSpiritRows();
  } else {
    updateStats();
  }
}

function updateLevelDisplay() {
  const input = document.getElementById('level-range');
  const display = document.getElementById('level-display');
  if (input && display) display.textContent = levelSteps[input.value];
}

// кнопки на странице должны вызывать changeValue('basic-attack', 1) или -1 и т.д.

// ==== УЛУЧШЕНИЯ ====
async function renderUpgrades() {
  const characterId = document.getElementById('selected-character-image').dataset.id;
  const container = document.getElementById('upgrades-list');
  if (!container) return;
  container.innerHTML = '';
  const ups = await loadUpgradesForCharacter(characterId);

  ups.forEach((u, idx) => {
    const row = document.createElement('div'); row.className = 'upgrade-row';
    const elev = document.createElement('div'); elev.className = 'upgrade-elev'; elev.textContent = u.required_elevation;
    const label = document.createElement('label');
    const checkbox = document.createElement('input'); checkbox.type = 'checkbox'; checkbox.dataset.idx = idx;
    checkbox.checked = activeUpgrades.has(idx);
    checkbox.addEventListener('change', () => {
      if (checkbox.checked) activeUpgrades.add(idx);
      else activeUpgrades.delete(idx);
      updateStats(); updateToggleAllBtn();
    });
    label.append(checkbox, ' ' + (statLabels[u.stat_type] || u.stat_type));
    const val = document.createElement('div'); val.className = 'upgrade-value';
    val.textContent = '+' + u.value + (u.isPercent ? '%' : '');
    row.append(elev, label, val);
    container.appendChild(row);
  });

  updateToggleAllBtn();
}

function toggleAllUpgrades() {
  const characterId = document.getElementById('selected-character-image').dataset.id;
  loadUpgradesForCharacter(characterId).then(ups => {
    if (activeUpgrades.size < ups.length) {
      ups.forEach((_, i) => activeUpgrades.add(i));
    } else {
      activeUpgrades.clear();
    }
    renderUpgrades(); updateStats();
  });
}

function updateToggleAllBtn() {
  const btn = document.getElementById('toggle-all-upgrades');
  if (!btn) return;
  const characterId = document.getElementById('selected-character-image').dataset.id;
  const total = (upgradesCache[characterId] || []).length;
  btn.textContent = activeUpgrades.size < total ? 'Вкл все' : 'Выкл все';
}

// ==== ТАБЫ И ОКНО ВЫБОРА ПЕРСОНАЖА ====
function initTabs() {
  const leftBtns = document.querySelectorAll('.tab-btn[data-side="left"]');
  const rightBtns = document.querySelectorAll('.tab-btn[data-side="right"]');
  const leftIds = ['stats-tab', 'result-tab'];
  const rightIds = ['character-tab', 'lightcone-tab', 'relics-tab', 'buffs-tab'];
  leftBtns.forEach(btn => btn.addEventListener('click', () => {
    const tgt = btn.dataset.target;
    leftIds.forEach(id => document.getElementById(id).classList.toggle('active', id === tgt));
    leftBtns.forEach(b => b.classList.toggle('active', b === btn));
  }));
  rightBtns.forEach(btn => btn.addEventListener('click', () => {
    const tgt = btn.dataset.target;
    rightIds.forEach(id => document.getElementById(id).classList.toggle('active', id === tgt));
    rightBtns.forEach(b => b.classList.toggle('active', b === btn));
  }));
}

function resetModalUI() {
  document.querySelectorAll('.sort-category-modal button, .sort-category-modal img').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.sort-category-modal').forEach(cat => {
    const allBtn = cat.querySelector('button'); if (allBtn) allBtn.classList.add('active');
  });
}

function renderModalCharacters(list) {
  const cont = document.getElementById('character-list-modal'); if (!cont) return;
  cont.innerHTML = '';
  list.forEach(ch => {
    const div = document.createElement('div'); div.className = 'character-icon-option';
    const src = ch.id === '1' ? 'Castoria.png' : ch.image;
    div.innerHTML = `<img src="character_image/${src}" alt="${ch.name}" title="${ch.name}">`;
    div.addEventListener('click', () => {
      const img = document.getElementById('selected-character-image');
      img.src = `character_image/${ch.image}`;
      img.dataset.id = ch.id; img.dataset.path = ch.path;
      document.getElementById('character-name').textContent = ch.name;
      document.getElementById('character-stars').textContent = '★'.repeat(+ch.rarity);
      closeModal(); resetModalUI(); renderUpgrades(); updateStats(); updateSpiritRows(); renderTraces();
    });
    cont.appendChild(div);
  });
}

function loadModalChars() {
  fetchJSON('get_HSRcharacters_for_main.php').then(data => { modalCharacters = data; renderModalCharacters(data); });
}

function openModal() { const m = document.getElementById('character-modal'); if (m) { m.style.display = 'flex'; resetModalUI(); loadModalChars(); }}
function closeModal() { const m = document.getElementById('character-modal'); if (m) m.style.display = 'none'; }

function updateSpiritRows() {
  const p = document.getElementById('selected-character-image').dataset.path;
  document.querySelectorAll('.spirit-row').forEach(r => r.style.display = p === 'Память' ? 'flex' : 'none');
}

// ==== СЛЕДЫ ====
async function loadTracesForCharacter(id) {
  const data = await fetchJSON(`get_HSRcharacters_for_guide.php?id=${id}`);
  return data.traces || [];
}

// Хранит, какие следы активированы
const activeTraces = new Set();

function createTraceRow(t, idx) {
  const row = document.createElement('div');
  row.className = 'trace-row';
  row.style.position = 'relative';

  // required elevation
  const elev = document.createElement('div');
  elev.className = 'trace-elev';
  elev.textContent = t.required_elevation;
  row.append(elev);

  // name + description
  const info = document.createElement('div');
  info.className = 'trace-info';
  const title = document.createElement('div');
  title.className = 'trace-name';
  title.textContent = t.name;
  const desc = document.createElement('div');
  desc.className = 'trace-desc';
  desc.textContent = t.description;
  info.append(title, desc);
  row.append(info);

  // toggle
  const toggle = document.createElement('div');
  toggle.className = 'upgrade-toggle trace-toggle';
  const setOn = on => {
    toggle.classList.toggle('active', on);
    toggle.textContent = on ? '✓' : '';
  };
  setOn(activeTraces.has(idx));
  toggle.addEventListener('click', () => {
    if (activeTraces.has(idx)) activeTraces.delete(idx);
    else activeTraces.add(idx);
    setOn(activeTraces.has(idx));
    updateStats();
  });
  row.append(toggle);

  // buff value
  if (t.stat_type) {
    const buff = document.createElement('div');
    buff.className = 'trace-buff';
    const label = statLabels[t.stat_type] || t.stat_type;
    const baseValue = +t.buf_value || 0;
    buff.innerHTML = `${label}: <span class="buff-value">${baseValue}${t.stat_type.endsWith('_percent') ? '%' : ''}</span>`;
    row.append(buff);

    // slider stacks
    if (t.max_stacks > 1) {
      const sliderCont = document.createElement('div');
      sliderCont.className = 'trace-slider';
      const slider = document.createElement('input');
      slider.type = 'range';
      slider.min = 1;
      slider.max = t.max_stacks;
      slider.value = 1;
      const sliderVal = document.createElement('span');
      sliderVal.className = 'slider-value';
      sliderVal.textContent = '1';
      slider.addEventListener('input', () => {
        const v = +slider.value;
        sliderVal.textContent = v;
        const total = baseValue * v;
        buff.querySelector('.buff-value').textContent = total + (t.stat_type.endsWith('_percent') ? '%' : '');
      });
      sliderCont.append(slider, sliderVal);
      row.append(sliderCont);
    }
  }

  // target type
  const tgt = document.createElement('div');
  tgt.className = 'trace-target';
  const map = {
    self: 'На себя',
    memosprite: 'На мемоспрайта',
    'memosprite+single': 'На союзника + мемоспрайт',
    single: 'На союзника',
    all: 'На всех'
  };
  tgt.textContent = map[t.target_type] || t.target_type;
  row.append(tgt);

  return row;
}

async function renderTraces() {
  const container = document.getElementById('traces-list');
  if (!container) return;
  container.innerHTML = '';

  const charId = document.getElementById('selected-character-image').dataset.id;
  const traces = await loadTracesForCharacter(charId);

  traces.forEach((t, i) => {
    container.appendChild(createTraceRow(t, i));
  });
}


// ==== ИНИЦИАЛИЗАЦИЯ ====
document.addEventListener('DOMContentLoaded', () => {
  initTabs();
  document.getElementById('toggle-all-upgrades')?.addEventListener('click', toggleAllUpgrades);
  ['basic-attack','skill','ult','talent','spirit-skill','spirit-talent','eidolon'].forEach(id => {
    updateDisplay(id); const e = document.getElementById(id); if(e) e.addEventListener('input', () => updateDisplay(id));
  });
  const lvl = document.getElementById('level-range'); if(lvl) lvl.addEventListener('input', () => { updateLevelDisplay(); updateStats(); updateSpiritRows(); });
  document.getElementById('selected-character-image').addEventListener('click', openModal);
  document.getElementById('character-modal').addEventListener('click', e => { if (e.target.id === 'character-modal') closeModal(); });
  renderUpgrades(); updateStats(); renderTraces();
});
