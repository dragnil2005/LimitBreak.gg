document.addEventListener('DOMContentLoaded', function () {
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

  const params = new URLSearchParams(window.location.search);
  const characterId = params.get('id');

  fetch(`get_HSRcharacters_for_guide.php?id=${characterId}`)
    .then(response => response.json())
    .then(data => {
      const char = data.character;

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

      function createSkillItem(skill) {
        const li = document.createElement('li');
        li.className = 'skill-item';

        const title = document.createElement('div');
        title.className = 'skill-title';

        const typeLabel = document.createElement('div');
        typeLabel.className = 'skill-type';
        typeLabel.innerText = skill.type || '';

        const nameLine = document.createElement('div');
        nameLine.className = 'skill-name-line';

        const levelLabel = document.createElement('span');
        levelLabel.className = 'skill-lv';
        levelLabel.innerText = 'Lv. 1';

        const nameText = document.createElement('span');
        nameText.className = 'skill-name';
        nameText.innerText = skill.name || 'Без названия';

        nameLine.appendChild(levelLabel);
        nameLine.appendChild(nameText);
        title.appendChild(nameLine);
        title.appendChild(typeLabel);

        const description = document.createElement('div');
        description.className = 'skill-description';

        const slider = document.createElement('input');
        slider.type = 'range';
        slider.min = 1;
        let maxLevel = 1;

        if (Array.isArray(skill.description_level) && skill.description_level.length > 1) {
          maxLevel = skill.description_level.length;
          slider.max = maxLevel;
          slider.value = 1;
          description.innerText = skill.description_level[0];
          slider.addEventListener('input', function () {
            levelLabel.innerText = 'Lv. ' + slider.value;
            description.innerText = skill.description_level[slider.value - 1] || 'Описание недоступно';
          });
        } else {
          slider.style.display = 'none';
          description.innerText = skill.description || 'Описание недоступно';
        }

        li.appendChild(title);
        li.appendChild(description);
        li.appendChild(slider);

        return li;
      }

      const skillsList = document.querySelector('.skills-list');
      skillsList.innerHTML = '';

      if (data.skills && Array.isArray(data.skills)) {
        data.skills.forEach(skill => {
          const skillItem = createSkillItem(skill);
          skillsList.appendChild(skillItem);
        });
      }

      if (char.path === 'Память' && data.memosprite_skills && Array.isArray(data.memosprite_skills)) {
        const header = document.createElement('h2');
        header.innerText = 'Скиллы Мемоспрайта';
        skillsList.appendChild(header);

        data.memosprite_skills.forEach(skill => {
          const skillItem = createSkillItem(skill);
          skillsList.appendChild(skillItem);
        });
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