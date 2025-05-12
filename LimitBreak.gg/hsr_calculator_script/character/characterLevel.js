// characterLevel.js

// ======= Настройки =======
const allowedLevels = [1,20,30,40,50,60,70,80];
const EXCLUDED_SKILL_TYPES = ['Техника'];
const MAX_EIDOLONS = 6;


const levelChangeCallbacks = [];

// ======= Базовые максимальные уровни навыков (без эйдолонов) =======
const baseSkillMaxLevels = {
  'Базовая атака': 6,
  'Навык': 10,
  'Сверхспособность': 10,
  'Талант': 10,
  'Навык духа памяти': 6,
  'Талант духа памяти': 6
};

// ======= Состояние =======
let currentCharacter     = null;
let currentCharacterEidolonUpgrades = [];
let selectedLevel        = allowedLevels[0];
let selectedEidolons      = 0;
const skillLevels        = {}; // { type: текущий уровень }

// ======= Инициализация =======
export async function initCharacterLevel(characterData, skillsContainerId = 'character-level-container') {
  if (!characterData) return;
  currentCharacter = characterData;

  // 1) Сброс состояния
  selectedLevel   = allowedLevels[0];
  selectedEidolons = 0;
  Object.keys(skillLevels).forEach(k => delete skillLevels[k]);

  // 2) Подгружаем апгрейды эйдолонов
  currentCharacterEidolonUpgrades = await fetchEidolonSkillUpgrades(characterData.id);

  // 3) Рисуем блоки
  _renderLevelBlock();
  _renderEidolonBlock();
  _renderSkills(skillsContainerId);
}

// ======= Блок «Уровень персонажа» =======
function _renderLevelBlock() {
  const wrap = document.querySelector('#character-tab .character-selector');
  wrap.querySelectorAll('.level-block').forEach(el => el.remove());

  const block = document.createElement('div');
  block.className = 'skill-block level-block';

  const lbl = document.createElement('label');
  lbl.className = 'level-label';
  lbl.textContent = 'Уровень персонажа';

  const slider = document.createElement('input');
  slider.type = 'range';
  slider.className = 'skill-slider';
  slider.min  = 0;
  slider.max  = allowedLevels.length - 1;
  slider.step = 1;
  slider.value= 0;

  const maxLvl = allowedLevels[allowedLevels.length - 1];
  const disp   = document.createElement('span');
  disp.className = 'level-value';
  disp.textContent = `${selectedLevel}/${maxLvl}`;

  slider.addEventListener('input', e => {
    selectedLevel = allowedLevels[+e.target.value];
    disp.textContent = `${selectedLevel}/${maxLvl}`;
    console.log('Character level ←', selectedLevel);

    levelChangeCallbacks.forEach(cb => cb());
  });

  block.append(lbl, slider, disp);
  wrap.appendChild(block);
}

// ======= Блок «Эйдолоны» =======
function _renderEidolonBlock() {
  const wrap = document.querySelector('#character-tab .character-selector');
  wrap.querySelectorAll('.eidolon-block').forEach(el => el.remove());

  const block = document.createElement('div');
  block.className = 'skill-block eidolon-block';

  const lbl = document.createElement('label');
  lbl.className = 'level-label';
  lbl.textContent = 'Эйдолоны';

  const slider = document.createElement('input');
  slider.type = 'range';
  slider.className = 'skill-slider';
  slider.min  = 0;
  slider.max  = MAX_EIDOLONS;
  slider.step = 1;
  slider.value= 0;

  const disp   = document.createElement('span');
  disp.className = 'level-value';
  disp.textContent = `${selectedEidolons}/${MAX_EIDOLONS}`;

  slider.addEventListener('input', e => {
    selectedEidolons = +e.target.value;
    disp.textContent    = `${selectedEidolons}/${MAX_EIDOLONS}`;
    console.log('Eidolons ←', selectedEidolons);
    _updateSkillDisplays();
  });

  block.append(lbl, slider, disp);
  wrap.appendChild(block);
}

// ======= Рендер всех навыков =======
function _renderSkills(skillsContainerId) {
  const container = document.getElementById(skillsContainerId);
  if (!container) return;
  container.innerHTML = '';

  // 1) Сливаем базы и память
  const baseSkills = (currentCharacter.skills||[])
    .filter(s => !EXCLUDED_SKILL_TYPES.includes(s.type));
  const memSkills  = (currentCharacter.path==='Память' && Array.isArray(currentCharacter.memosprite_skills))
    ? currentCharacter.memosprite_skills.filter(s=>!EXCLUDED_SKILL_TYPES.includes(s.type))
    : [];

  // 2) Группируем по type
  const group = arr => arr.reduce((m,s)=>{
    (m[s.type]=m[s.type]||[]).push(s);
    return m;
  },{});
  const basicMap  = group(baseSkills);
  const memoryMap = group(memSkills);

  // 3) Рисуем
  const wrapper = document.createElement('div');
  wrapper.className = 'skills-list';
  _renderSkillGroup(wrapper, basicMap);
  if (Object.keys(memoryMap).length) {
    const div = document.createElement('div');
    div.className = 'skill-divider';
    wrapper.appendChild(div);
    _renderSkillGroup(wrapper, memoryMap);
  }
  container.appendChild(wrapper);

  console.log('After init:', getCharacterLevelData());
}

// ======= Рендер группы навыков =======
function _renderSkillGroup(wrapper, skillMap) {
  Object.entries(skillMap).forEach(([type, skills]) => {
    const block = document.createElement('div');
    block.className = 'skill-block';

    // Метка
    const lbl = document.createElement('label');
    lbl.className = 'level-label';
    lbl.textContent = type;

    // Кнопки минус/плюс
    const minusBtn = document.createElement('button');
    minusBtn.className = 'minus-btn';
    minusBtn.textContent = '−';
    const plusBtn = document.createElement('button');
    plusBtn.className = 'plus-btn';
    plusBtn.textContent = '+';

    // Параметры диапазона
    const baseMax    = baseSkillMaxLevels[type] || 1;
    const minLvl     = _getMinLevel(type);
    const overrideMax= _getMaxOverride(type) || baseMax;

    // Слайдер (диапазон всегда 1…baseMax)
    const slider = document.createElement('input');
    slider.type      = 'range';
    slider.className = 'skill-slider';
    slider.min       = 1;
    slider.max       = baseMax;
    slider.step      = 1;
    slider.value     = minLvl;

    // Отображение текущего/максимального уровня
    const disp = document.createElement('span');
    disp.className = 'skill-value';
    disp.textContent = `${minLvl + _getBonus(type)}/${overrideMax}`;

    // Инициализируем состояние
    skillLevels[type] = minLvl + _getBonus(type);

    // Функция обновления
    function update(rawValue) {
      // 1) Clamp по базовому диапазону
      const v = Math.max(1, Math.min(baseMax, rawValue));
      slider.value = v;

      // 2) Считаем бонус и итоговый уровень
      const bonus     = _getBonus(type);
      const effective = v + bonus;

      // 3) Сохраняем и отображаем
      skillLevels[type] = effective;
      disp.textContent  = `${effective}/${overrideMax}`;

      console.log(`Skill ${type} ←`, effective);
    }

    // Навешиваем слушатели
    minusBtn.addEventListener('click', () => update(+slider.value - 1));
    plusBtn .addEventListener('click', () => update(+slider.value + 1));
    slider  .addEventListener('input', e => update(+e.target.value));

    // Собираем блок
    block.append(lbl, minusBtn, slider, plusBtn, disp);
    wrapper.appendChild(block);
  });
}

// ======= Обновление подписей навыков при смене эйдолонов =======
function _updateSkillDisplays() {
  document
    .querySelectorAll('.skills-list .skill-block')
    .forEach(block => {
      const type   = block.querySelector('label').textContent;
      const slider = block.querySelector('.skill-slider');
      const disp   = block.querySelector('.skill-value');

      const baseMax    = baseSkillMaxLevels[type] || 1;
      const overrideMax= _getMaxOverride(type) || baseMax;
      const bonus      = _getBonus(type);
      const rawValue   = +slider.value;
      const effective  = rawValue + bonus;

      // Обновляем в памяти и на экране
      skillLevels[type] = effective;
      disp.textContent  = `${effective}/${overrideMax}`;

      console.log(`Skill ${type} updated ←`, effective);
    });
}

// ======= Вспомогательная функция для расчёта суммарного бонуса =======
function _getBonus(type) {
  return (currentCharacterEidolonUpgrades || [])
    .filter(u => u.skill_type === type && u.eidolon_number <= selectedEidolons)
    .reduce((sum, u) => sum + (u.level_increase || 0), 0);
}

// ======= Суммируем level_increase от выбранных эйдолонов =======
function _getMinLevel(type) {
  return 1 + (currentCharacterEidolonUpgrades||[])
    .filter(u=>u.skill_type===type && u.eidolon_number<=selectedEidolons)
    .reduce((s,u)=>s+(u.level_increase||0),0);
}

// ======= Ищем наибольший max_level_override от выбранных эйдолонов =======
function _getMaxOverride(type) {
  return (currentCharacterEidolonUpgrades||[])
    .filter(u=>u.skill_type===type && u.eidolon_number<=selectedEidolons)
    .reduce((m,u)=>u.max_level_override?Math.max(m,u.max_level_override):m,0);
}

// ======= Форматируем текст: "current+bonus / overrideMax" =======
function _formatSkillText(type, cur, baseMax, overrideMax) {
  const bonus = (currentCharacterEidolonUpgrades||[])
    .filter(u=>u.skill_type===type && u.eidolon_number<=selectedEidolons)
    .reduce((s,u)=>s+(u.level_increase||0),0);
  const maxVal = overrideMax || baseMax;
  return `${cur+bonus}/${maxVal}`;
}

// ======= Устойчивый fetch апгрейдов эйдолонов =======
async function fetchEidolonSkillUpgrades(characterId) {
  try {
    const resp = await fetch(`hsr_calculator_script/character/get_eidolon_upgrades.php?character_id=${characterId}`);
    if (!resp.ok) {
      console.error('Fetch eidolon upgrades failed:', resp.status);
      return [];
    }
    return await resp.json();
  } catch (err) {
    console.error('Ошибка загрузки eidolon_skill_upgrades:', err);
    return [];
  }
}
export function onLevelChange(callback) {
  levelChangeCallbacks.push(callback);
}

// ======= API: текущее состояние =======
export function getCharacterLevelData() {
  const out = {
    level: selectedLevel,
    eidolons: selectedEidolons,
    skillLevels: { ...skillLevels }
  };
  console.log('getCharacterLevelData →', out);
  return out;
}
