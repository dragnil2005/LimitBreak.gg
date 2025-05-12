import { loadCharacterById } from '../main.js';

const modalFilters = {
  rarity: 'all',
  dmg: 'all',
  path: 'all',
};

let allCharacters = [];

export function initModalCharacterSelector() {
  const modal = document.getElementById('character-modal');
  const rarityButtons = document.querySelectorAll('#rarity-filter button');

  // === Обработка кликов по фильтрам ===
  rarityButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      rarityButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      modalFilters.rarity = btn.dataset.value;
      renderCharacterList();
    });
  });

  // Закрытие модалки по клику на фон
  modal.addEventListener('click', e => {
    if (e.target === modal) closeModal();
  });

  loadCharacters();
}

function loadCharacters() {
  fetch('get_HSRcharacters_for_main.php')
    .then(res => res.json())
    .then(data => {
      allCharacters = data;
      renderDynamicFilters(data);
      renderCharacterList();
    })
    .catch(err => console.error('Ошибка загрузки персонажей:', err));
}

function renderDynamicFilters(characters) {
  const dmgFilter = document.getElementById('dmg-type-filter');
  const pathFilter = document.getElementById('path-filter');

  // Очистка старых кнопок
  dmgFilter.querySelectorAll('button:not([data-value="all"])').forEach(e => e.remove());
  pathFilter.querySelectorAll('button:not([data-value="all"])').forEach(e => e.remove());

  const dmgSet = new Map();
  const pathSet = new Map();

  characters.forEach(ch => {
    if (!dmgSet.has(ch.dmg_type)) {
      dmgSet.set(ch.dmg_type, ch.dmg_type_image);
    }
    if (!pathSet.has(ch.path)) {
      pathSet.set(ch.path, ch.path_image);
    }
  });

  dmgSet.forEach((img, name) => {
    const btn = document.createElement('button');
    btn.dataset.value = img.toLowerCase();
    btn.innerHTML = `<img src="damageType_image/${img}" alt="${name}" title="${name}">`;
    btn.addEventListener('click', () => {
      dmgFilter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      modalFilters.dmg = btn.dataset.value;
      renderCharacterList();
    });
    dmgFilter.appendChild(btn);
  });

  pathSet.forEach((img, name) => {
    const btn = document.createElement('button');
    btn.dataset.value = name;
    btn.innerHTML = `<img src="path_image/${img}" alt="${name}" title="${name}">`;
    btn.addEventListener('click', () => {
      pathFilter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      modalFilters.path = btn.dataset.value;
      renderCharacterList();
    });
    pathFilter.appendChild(btn);
  });
}

function renderCharacterList() {
  const grid = document.getElementById('character-list');
  const count = document.getElementById('char-count');
  grid.innerHTML = '';

  const filtered = allCharacters.filter(ch => {
    if (modalFilters.rarity !== 'all' && ch.rarity !== modalFilters.rarity) return false;
    if (modalFilters.dmg !== 'all' && !ch.dmg_type_image.toLowerCase().includes(modalFilters.dmg)) return false;
    if (modalFilters.path !== 'all' && ch.path !== modalFilters.path) return false;
    return true;
  });

  count.textContent = `(${filtered.length})`;

  filtered.forEach(ch => {
    const card = document.createElement('div');
    card.className = `character-card rarity-${ch.rarity}`;
    card.innerHTML = `
      <img src="character_image/${ch.image}" alt="${ch.name}">
      <div class="char-label">${ch.name}</div>
      <div class="overlay-icons">
        <img src="path_image/${ch.path_image}" alt="${ch.path}">
        <img src="damageType_image/${ch.dmg_type_image}" alt="${ch.dmg_type}">
      </div>
    `;

    card.addEventListener('click', () => {
      selectCharacter(ch);             // обновляем UI
      loadCharacterById(ch.id);        // загружаем JSON с уровнями и скилами
      closeModal();                    // закрываем модалку
    });

    grid.appendChild(card);
  });
}

function selectCharacter(ch) {
  const img = document.getElementById('selected-character-image');
  img.src = `character_image/${ch.image}`;
  img.dataset.id = ch.id;
  img.dataset.path = ch.path;

  document.getElementById('char-name').textContent = ch.name;
  document.getElementById('char-stars').innerHTML =
    '★'.repeat(+ch.rarity).split('').map(star =>
      `<span class="star" style="color:${+ch.rarity === 5 ? '#FFD700' : '#A050F0'}">${star}</span>`
    ).join('');
  document.getElementById('char-path-icon').src = `path_image/${ch.path_image}`;
  document.getElementById('char-path-label').textContent = ch.path;
  document.getElementById('char-dmg-icon').src = `damageType_image/${ch.dmg_type_image}`;
  document.getElementById('char-dmg-label').textContent = ch.dmg_type;
}

export function openModal() {
  document.getElementById('character-modal')?.classList.add('active');
}

export function closeModal() {
  document.getElementById('character-modal')?.classList.remove('active');
}
