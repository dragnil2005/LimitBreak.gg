// main.js
import { initTabsManager } from './tabsManager.js';
import { initCharacterSelector } from './character/characterSelector.js';
import { openModal, initModalCharacterSelector } from './character/modalSelector.js';
import {
  initCharacterLevel,
  onLevelChange,
  getCharacterLevelData
} from './character/characterLevel.js';
import { initCharacterUpgrades } from './character/characterUpgrades.js';
import { initCharacterTraces } from './character/characterTraces.js';
import { initCharacterStats } from './character/stats_tab.js';

document.addEventListener('DOMContentLoaded', () => {
  initTabsManager();
  initCharacterSelector();
  initModalCharacterSelector();
  loadCharacterById(1);

  onLevelChange(() => {
    const levelData = getCharacterLevelData();
    const stats     = window.currentCharacter?.stats || [];
    // Сбрасываем оружейный бонус при смене уровня
    initCharacterStats(stats, levelData.level, { hp: 0, atk: 0, def: 0 });
    window.currentCharLevel = levelData.level;
  });

  // Слушаем событие из lightcone.js
  window.addEventListener('weaponStatsChanged', e => {
    const bonus = e.detail; // { hp, atk, def }
    const stats = window.currentCharacter?.stats || [];
    const level = window.currentCharLevel || getCharacterLevelData().level;
    initCharacterStats(stats, level, bonus);
  });

  // Открытие модалки персонажа
  const charImage = document.getElementById('selected-character-image');
  if (charImage) {
    charImage.addEventListener('click', openModal);
  }
});

/**
 * Загружает персонажа по ID и инициализирует блоки
 * @param {number|string} id
 */
export function loadCharacterById(id) {
  fetch(`get_HSRcharacters_for_guide.php?id=${id}`)
    .then(res => res.json())
    .then(character => {
      const fullCharacterData = {
        ...character.character,
        skills: character.skills || [],
        memosprite_skills: character.memosprite_skills || [],
        upgrades: character.upgrades || [],
        traces: character.traces || [],
        stats: character.stats || []
      };

      window.currentCharacter  = fullCharacterData;
      window.currentCharStats  = fullCharacterData.stats;
      // initCharacterLevel внутри себя не меняет levelData сразу, поэтому получаем уровень после инициализации
      initCharacterLevel(fullCharacterData);
      window.currentCharLevel = getCharacterLevelData().level;

      initCharacterUpgrades(fullCharacterData, 'upgrade-container');
      initCharacterTraces(fullCharacterData.traces);

      // Первый вызов stats без оружейного бонуса
      initCharacterStats(
        fullCharacterData.stats,
        window.currentCharLevel,
        { hp: 0, atk: 0, def: 0 }
      );
    })
    .catch(err => console.error('Ошибка загрузки персонажа:', err));
}
