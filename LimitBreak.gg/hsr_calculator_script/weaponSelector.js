// weaponSelector.js
import { initWeaponUI } from './lightcone.js';

const modalFilters = { rarity: 'all', path: 'all' };
let allWeapons = [];

function initModalWeaponSelector() {
  const modal = document.getElementById('weapon-modal');
  const rarityBtns = modal.querySelectorAll('#weapon-rarity-filter button');
  const pathGroup  = modal.querySelector('#weapon-path-filter');

  // фильтр редкости
  rarityBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      rarityBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      modalFilters.rarity = btn.dataset.value;
      renderWeaponList();
    });
  });

  // закрытие кликом на затемнённый фон
  modal.addEventListener('click', e => {
    if (e.target === modal) closeWeaponModal();
  });

  loadWeapons();
}

function loadWeapons() {
  fetch('/hsr_calculator_script/weapons_reader.php')
    .then(r => r.json())
    .then(data => {
      allWeapons = data;
      renderDynamicFilters(data);
      renderWeaponList();
    })
    .catch(err => console.error('Ошибка загрузки оружия:', err));
}

function renderDynamicFilters(weapons) {
  const pathFilter = document.getElementById('weapon-path-filter');
  pathFilter.querySelectorAll('button:not([data-value="all"])').forEach(el => el.remove());

  const paths = [...new Set(weapons.map(w => w.path))];
  paths.forEach(path => {
    const btn = document.createElement('button');
    btn.dataset.value = path;
    btn.textContent   = path;
    btn.addEventListener('click', () => {
      pathFilter.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      modalFilters.path = btn.dataset.value;
      renderWeaponList();
    });
    pathFilter.appendChild(btn);
  });
}

function renderWeaponList() {
  const grid  = document.getElementById('weapon-list');
  const count = document.getElementById('weapon-count');
  grid.innerHTML = '';

  const filtered = allWeapons.filter(w => {
    if (modalFilters.rarity !== 'all' && String(w.rarity) !== modalFilters.rarity) return false;
    if (modalFilters.path   !== 'all' && w.path !== modalFilters.path) return false;
    return true;
  });

  count.textContent = filtered.length;

  filtered.forEach(w => {
    const card = document.createElement('div');
    card.className = 'weapon-card';
    card.innerHTML = `
      <img src="weapons_image/${w.image}" alt="${w.name}">
      <div class="weapon-label">${w.name}</div>
      <div class="overlay-icons">
        <span class="rarity">${'★'.repeat(w.rarity)}</span>
        <span class="path">${w.path}</span>
      </div>
    `;
    card.addEventListener('click', () => {
      // обновляем выбранное оружие и перерисовываем слайдеры
      const tab = document.getElementById('lightcone-tab');
      tab.dataset.weaponId = w.id;
      initWeaponUI(tab, w);
      closeWeaponModal();
    });
    grid.appendChild(card);
  });
}

function openWeaponModal()  { document.getElementById('weapon-modal').classList.add('active'); }
function closeWeaponModal() { document.getElementById('weapon-modal').classList.remove('active'); }

document.addEventListener('DOMContentLoaded', () => {
  initModalWeaponSelector();
  document
    .getElementById('selected-weapon-image')
    .addEventListener('click', openWeaponModal);
});
