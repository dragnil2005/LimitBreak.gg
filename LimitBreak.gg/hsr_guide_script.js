const pathIcons = {
  'Память': 'path_image/remembrance.png',
  'Охотник': 'path_image/hunt.png',
  'Разрушитель': 'path_image/destruction.png',
  'Небытие': 'path_image/nihility.png',
  'Гармония': 'path_image/harmony.png',
  'Сохранение': 'path_image/preservation.png',
  'Изобилие': 'path_image/abundance.png',
  'Эрудиция': 'path_image/erudition.png'
};

const elementIcons = {
  'Огонь': 'damageType_image/fire.png',
  'Лёд': 'damageType_image/ice.png',
  'Физический': 'damageType_image/physical.png',
  'Квантовый': 'damageType_image/quantum.png',
  'Воображение': 'damageType_image/imaginary.png',
  'Электричество': 'damageType_image/lightning.png',
  'Ветер': 'damageType_image/wind.png'
};

document.addEventListener('DOMContentLoaded', function () {
  const params = new URLSearchParams(window.location.search);
  const characterId = params.get('id');

  fetch(`get_HSRcharacters_for_guide.php?id=${characterId}`)
    .then(response => response.json())
    .then(data => {
      const char = data.character;
      console.log('ВСЯ СТРУКТУРА char:', char);
      console.log('char.path:', `"${char.path}"`);
      console.log('data.memorysprite_skills:', data.memorysprite_skills);


      document.getElementById('character-name').innerText = char.name || '???';
      document.getElementById('rarity').innerText = char.rarity || '-';
      document.getElementById('path-icon').src = pathIcons[char.path] || '';
      document.getElementById('path-name').innerText = char.path || '';
      document.getElementById('element-icon').src = elementIcons[char.dmg_type] || '';
      document.getElementById('element-name').innerText = char.dmg_type || '';
      document.getElementById('character-image').src = `character_image/${char.image}` || '';

      const tbody = document.querySelector('.stats-table tbody');
      tbody.innerHTML = '';
      data.stats.forEach(stat => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${stat.level}</td>
          <td>${stat.atk}</td>
          <td>${stat.def}</td>
          <td>${stat.hp}</td>
          <td>${stat.speed}</td>
          <td>${stat.crit_rate}</td>
          <td>${stat.crit_dmg}</td>
          <td>${stat.max_energy || '-'}</td>
        `;
        tbody.appendChild(row);
      });

      const skillsList = document.querySelector('.skills-list');
      skillsList.innerHTML = '';

      data.skills.forEach(skill => {
        const li = document.createElement('li');
        li.className = 'skill-item';

        const title = document.createElement('div');
        title.className = 'skill-title';

        // Левая часть: Lv. + Название
        const nameLine = document.createElement('div');
        nameLine.className = 'skill-name-line';

        const levelLabel = document.createElement('span');
        levelLabel.className = 'skill-lv';
        levelLabel.innerText = 'Lv. 1';

        const skillName = document.createElement('span');
        skillName.className = 'skill-name';
        skillName.innerText = skill.name;

        nameLine.appendChild(levelLabel);
        nameLine.appendChild(skillName);

        // Правая часть: Тип навыка
        const skillType = document.createElement('span');
        skillType.className = 'skill-type';
        skillType.innerText = `${skill.type}`;

        title.appendChild(nameLine);
        title.appendChild(skillType);

        // Описание
        const descBox = document.createElement('div');
        descBox.className = 'skill-description';
        if (skill.description_level && skill.description_level.length > 0) {
          descBox.innerText = skill.description_level[0];
        } else if (skill.description) {
          descBox.innerText = skill.description;
        } else {
          descBox.innerText = 'Описание отсутствует';
        }

        li.appendChild(title);
        li.appendChild(descBox);

        // Слайдер уровней
        if (skill.description_level && skill.description_level.length > 1 && skill.type !== 'Талант' && skill.type !== 'Техника') {
          const slider = document.createElement('input');
          slider.type = 'range';
          slider.min = 1;
          slider.max = skill.description_level.length;
          slider.value = 1;
          slider.addEventListener('input', () => {
            descBox.innerText = skill.description_level[slider.value - 1];
            levelLabel.innerText = `Lv. ${slider.value}`;
          });
          li.appendChild(slider);
        }

        skillsList.appendChild(li);
      });

      // Вставь до: if (char.path.trim() === 'Память' && Array.isArray(data.memorysprite_skills))
      console.log('ПРОВЕРКА: путь =', `"${char.path}"`, 'тип:', typeof data.memorysprite_skills, 'isArray:', Array.isArray(data.memorysprite_skills));

      // === НАВЫКИ МЕМОСПРАЙТА (если есть) ===
      if (char.path.trim() === 'Память' && Array.isArray(data.memorysprite_skills)) {
        console.log('Навыки мемоспрайта загружены:', data.memorysprite_skills);

        const memoskillsHeader = document.createElement('h2');
        memoskillsHeader.innerText = 'НАВЫКИ МЕМОСПРАЙТА';
        skillsList.parentNode.insertBefore(memoskillsHeader, skillsList.nextSibling);

        const memorySkillsList = document.createElement('ul');
        memorySkillsList.className = 'skills-list';
        skillsList.parentNode.insertBefore(memorySkillsList, memoskillsHeader.nextSibling);

        data.memorysprite_skills.forEach(skill => {
          console.log('Обрабатываю мемо-скилл:', skill.name);

          const li = document.createElement('li');
          li.className = 'skill-item';

          const title = document.createElement('div');
          title.className = 'skill-title';

          const nameLine = document.createElement('div');
          nameLine.className = 'skill-name-line';

          const levelLabel = document.createElement('span');
          levelLabel.className = 'skill-lv';
          levelLabel.innerText = 'Lv. 1';

          const skillName = document.createElement('span');
          skillName.className = 'skill-name';
          skillName.innerText = skill.name;

          nameLine.appendChild(levelLabel);
          nameLine.appendChild(skillName);

          const skillType = document.createElement('span');
          skillType.className = 'skill-type';
          skillType.innerText = `(${skill.type})`;

          title.appendChild(nameLine);
          title.appendChild(skillType);

          const descBox = document.createElement('div');
          descBox.className = 'skill-description';
          if (skill.description_level && skill.description_level.length > 0) {
            descBox.innerText = skill.description_level[0];
          } else if (skill.description) {
            descBox.innerText = skill.description;
          } else {
            descBox.innerText = 'Описание отсутствует';
          }

          li.appendChild(title);
          li.appendChild(descBox);

          if (skill.description_level && skill.description_level.length > 1 && skill.type !== 'Талант' && skill.type !== 'Техника') {
            const slider = document.createElement('input');
            slider.type = 'range';
            slider.min = 1;
            slider.max = skill.description_level.length;
            slider.value = 1;
            slider.addEventListener('input', () => {
              descBox.innerText = skill.description_level[slider.value - 1];
              levelLabel.innerText = `Lv. ${slider.value}`;
            });
            li.appendChild(slider);
          }

          memorySkillsList.appendChild(li);
        });
      } else {
        console.log('Навыки мемоспрайта отсутствуют или путь не "Память".');
      }    

      const tracesList = document.querySelector('.traces-list');
      tracesList.innerHTML = '';
      data.traces.forEach(trace => {
        const li = document.createElement('li');
        li.className = 'trace-item';
        li.innerHTML = `
          <span class="trace-lvl">${trace.required_elevation || "N/A"}:</span>
          <span class="trace-name">${trace.name}</span><br>
          <span class="trace-description">${trace.description}</span>
        `;
        tracesList.appendChild(li);
      });

      const upgradesList = document.querySelector('.upgrades-list');
      upgradesList.innerHTML = '';
      data.upgrades.forEach(upg => {
        const li = document.createElement('li');
        li.className = 'upgrade-item';
        li.innerHTML = `
          <span class="upgrade-lvl">${upg.required_elevation || ""}:</span>
          <span class="upgrade-description">+${upg.value} к ${upg.stat_type}</span>
        `;
        upgradesList.appendChild(li);
      });

      const eidolonsList = document.querySelector('.eidolons-list');
      eidolonsList.innerHTML = '';
      data.eidolons.forEach(eid => {
        const li = document.createElement('li');
        li.className = 'eidolon-item';
        li.innerHTML = `
          <span class="eidolon-lvl">${eid.number_eidolon}.</span>
          <span class="eidolon-name">${eid.name}</span><br>
          <span class="eidolon-description">${eid.description}</span>
        `;
        eidolonsList.appendChild(li);
      });
    })
    .catch(err => {
      console.error('Ошибка загрузки данных персонажа:', err);
    });
});
