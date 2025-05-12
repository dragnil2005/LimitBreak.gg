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
    const stats = window.currentCharacter?.stats || [];
    initCharacterStats(stats, levelData.level);
  });

  const charImage = document.getElementById('selected-character-image');
  if (charImage) {
    charImage.addEventListener('click', () => {
      openModal(); // теперь будет найдено
    });
  }
});
/**
 * Загружает персонажа по ID и передаёт данные в блок уровней
 * @param {number|string} id
 */
export function loadCharacterById(id) {
  fetch(`get_HSRcharacters_for_guide.php?id=${id}`)
    .then(res => res.json())
    .then(character => {
      // character содержит: character (основные данные), skills, memosprite_skills
      const fullCharacterData = {
        ...character.character,
        skills: character.skills || [],
        memosprite_skills: character.memosprite_skills || [],
        upgrades: character.upgrades || [],
        traces: character.traces || [],
        stats: character.stats || []
      };

      window.currentCharacter = fullCharacterData;

      initCharacterLevel(fullCharacterData);
      initCharacterUpgrades(fullCharacterData, 'upgrade-container');
      initCharacterTraces(fullCharacterData.traces);
      console.log('[Traces] Loaded:', fullCharacterData.traces);
      const levelData = getCharacterLevelData();
      initCharacterStats(fullCharacterData.stats || [], levelData.level);
      console.log('[initCharacterStats] Входные данные:', fullCharacterData.stats);
      console.log('[initCharacterUpgrades] Входные данные:', fullCharacterData.upgrades);
    })
    .catch(err => console.error('Ошибка загрузки персонажа:', err));
}