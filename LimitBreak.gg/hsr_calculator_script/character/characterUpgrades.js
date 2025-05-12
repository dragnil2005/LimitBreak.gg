// characterUpgrades.js

import { addStatBonusFromSource, removeStatSource, renderStatBreakdownList } from './statsAggregator.js';


let activeUpgrades = [];

export function initCharacterUpgrades(characterData, containerId = 'upgrade-container') {
  const container = document.getElementById(containerId);
  if (!container || !characterData?.upgrades?.length) return;

  container.innerHTML = '';

  const list = document.createElement('div');
  list.className = 'upgrade-list';

  activeUpgrades = [...characterData.upgrades];

  const toggleBtn = document.createElement('button');
  toggleBtn.className = 'upgrade-toggle-button';
  updateToggleButtonText(toggleBtn);

  toggleBtn.addEventListener('click', () => {
    const checkboxes = list.querySelectorAll('input[type="checkbox"]');
    const enableAll = activeUpgrades.length !== characterData.upgrades.length;
    activeUpgrades = enableAll ? [...characterData.upgrades] : [];

    checkboxes.forEach(cb => cb.checked = enableAll);
    updateToggleButtonText(toggleBtn);
  });

  container.appendChild(toggleBtn);

  characterData.upgrades.forEach((upgrade, idx) => {
    const entry = document.createElement('div');
    entry.className = 'upgrade-entry';

    const label = document.createElement('span');
    label.textContent = `${upgrade.required_elevation} +${upgrade.value} ${upgrade.stat_type}`;

    const checkbox = document.createElement('input');
    checkbox.type = 'checkbox';
    checkbox.checked = true;
    checkbox.addEventListener('change', () => {
      const included = activeUpgrades.includes(upgrade);
      if (checkbox.checked && !included) {
        activeUpgrades.push(upgrade);
      } else if (!checkbox.checked && included) {
        activeUpgrades = activeUpgrades.filter(u => u !== upgrade);
      }
      updateToggleButtonText(toggleBtn);
    });

    entry.append(label, checkbox);
    list.appendChild(entry);
  });

  container.appendChild(list);
  syncUpgradeBonuses();
}

function updateToggleButtonText(button) {
  button.textContent = activeUpgrades.length === 0 ? 'Вкл. все' : 'Выкл. все';
  syncUpgradeBonuses();
}

export function getActiveUpgrades() {
  return [...activeUpgrades];
}

function syncUpgradeBonuses() {
  // 1. Удаляем ТОЛЬКО старые бонусы от апгрейдов
  removeStatSource('upgrades');

  // 2. Просуммировать активные апгрейды
  const statMap = {};

  for (const upgrade of activeUpgrades) {
    const stat = upgrade.stat_type;
    const value = parseFloat(upgrade.value);

    if (!statMap[stat]) statMap[stat] = 0;
    statMap[stat] += value;
  }

  // 3. Передать все в statsAggregator как процентные прибавки, с источником
  Object.entries(statMap).forEach(([stat, val]) => {
    addStatBonusFromSource(stat, val, 'percent', 'upgrades');
  });

  // 4. Обновить визуальное отображение
  renderStatBreakdownList();
}