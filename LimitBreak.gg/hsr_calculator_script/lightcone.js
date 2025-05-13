// lightcone.js
document.addEventListener('DOMContentLoaded', () => {
  const tab = document.getElementById('lightcone-tab');
  const weaponId = tab.dataset.weaponId;
  const url = `/hsr_calculator_script/weapons_reader.php?id=${weaponId}`;

  fetch(url)
    .then(res => {
      if (!res.ok) throw new Error(res.statusText);
      return res.json();
    })
    .then(data => {
      const weapon = data[0];
      initWeaponUI(tab, weapon);
    })
    .catch(err => {
      console.error('Не удалось загрузить оружие:', err);
      tab.innerHTML += '<p style="color:red;">Ошибка загрузки данных.</p>';
    });
});

function initWeaponUI(container, weapon) {
  // 1) Картинка
  const img = container.querySelector('#selected-weapon-image');
  img.src = `weapons_image/${weapon.image}`;
  img.alt = weapon.name;

  // 2) Слайдер stats
  const statsSlider = container.querySelector('#stats-slider');
  const statsValue  = container.querySelector('#stats-slider-value');
  const statsInfo   = container.querySelector('#stats-info');

  statsSlider.min   = 0;
  statsSlider.max   = weapon.stats.length - 1;
  statsSlider.value = 0;
  statsValue.textContent = weapon.stats[0].level;

  statsSlider.oninput = () => {
    const idx = +statsSlider.value;
    const stat = weapon.stats[idx];
    statsValue.textContent = stat.level;
    renderStatInfo(stat, statsInfo);
  };
  // Первый раз отрисуем сразу
  renderStatInfo(weapon.stats[0], statsInfo);

  // 3) Слайдер skills
  const skillsSlider = container.querySelector('#skills-slider');
  const skillsValue  = container.querySelector('#skills-slider-value');
  const skillsInfo   = container.querySelector('#skills-info');

  skillsSlider.min   = 1;
  skillsSlider.max   = 5;
  skillsSlider.value = 1;
  skillsValue.textContent = weapon.skills[0].awaken_level;

  skillsSlider.addEventListener('input', () => {
    const lvl = skillsSlider.value;
    skillsValue.textContent = lvl;
    // передаём уровень + весь массив weapon.skills
    renderSkillInfo(lvl, weapon.skills, skillsInfo);
  });

  // И сразу отрисовать первый уровень
  renderSkillInfo(skillsSlider.value, weapon.skills, skillsInfo);
}

function renderStatInfo(stat, container) {
  container.innerHTML = `
    <h4>Stats на уровне ${stat.level}</h4>
    <ul>
      <li>HP: ${stat.hp}</li>
      <li>ATK: ${stat.atk}</li>
      <li>DEF: ${stat.def}</li>
    </ul>
  `;
  window.dispatchEvent(new CustomEvent('weaponStatsChanged', {
    detail: { hp: stat.hp, atk: stat.atk, def: stat.def }
  }));
}

function renderSkillInfo(level, skills, container) {
  // Фильтруем нужные умения
  const matched = skills.filter(s => String(s.awaken_level) === String(level));

  // Если нет ни одного — показываем заглушку
  if (matched.length === 0) {
    container.innerHTML = `<p>Умений для пробуждения ${level} нет.</p>`;
    return;
  }

  // Собираем все <p> с описаниями
  const descriptionsHTML = matched
    .map(s => `<p>${s.description}</p>`)
    .join('\n');

  // Рендерим
  container.innerHTML = `
    <h4>Пробуждение ${level}</h4>
    ${descriptionsHTML}
  `;
}



export { initWeaponUI };
