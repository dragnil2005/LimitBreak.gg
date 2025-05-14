import { finalCalculatedStats } from './character/stats_tab.js';
import { getFinalStatBonuses } from './character/statsAggregator.js';
import { getCharacterLevelData } from './character/characterLevel.js';

let skills = [];
let selectedSkills = [];

export function initDamageTab(character) {
  skills = character.skills || [];
  selectedSkills = [];

  const dropdown = document.getElementById('skill-dropdown');
  const container = document.getElementById('damage-calculations-container');
  dropdown.innerHTML = '';
  container.innerHTML = '';

  const btn = document.getElementById('open-skill-list-btn');
  btn.onclick = () => {
    dropdown.classList.toggle('hidden');
    if (dropdown.innerHTML === '') {
      skills.forEach(skill => {
        const b = document.createElement('button');
        b.textContent = skill.name;
        b.addEventListener('click', () => {
          dropdown.classList.add('hidden');
          selectedSkills.push(skill);
          renderDamageBlock(skill, container);
        });
        dropdown.appendChild(b);
      });
    }
  };
}

function parsePercent(value) {
  const num = parseFloat(String(value).replace('%', '').replace(',', '.'));
  return isNaN(num) ? 0 : num;
}

function parseTrueDmg(value) {
  const raw = parsePercent(value);
  return 1 + raw / 100;
}

function calculateDefMultiplier(levelAttacker, levelEnemy = 95, defReduction = 0, defIgnore = 0) {
  const attacker = levelAttacker + 20;
  const denominator = (levelEnemy + 20) * (1 - defReduction - defIgnore) + attacker;
  return attacker / denominator;
}

function calculateResMultiplier(baseRes = 0.2, resIgnore = 0) {
  if (resIgnore <= baseRes) {
    return 1 - (baseRes - resIgnore);
  } else {
    return 1 - ((resIgnore - baseRes) / 2);
  }
}

function renderDamageBlock(skill, container) {
  const box = document.createElement('div');
  box.className = 'damage-skill-box';

  const levelData = getCharacterLevelData();
  const level = getSkillLevelFromData(skill.type, levelData);
  const { multiplier, scaleStat, secondaryMultiplier, targetType } = getSkillMultiplierAndStatType(skill, level);
  const baseStat = parseFloat(finalCalculatedStats[scaleStat] || 0);

  const crit_dmg = parsePercent(finalCalculatedStats.crit_dmg || '0%');
  const element_dmg = parsePercent(finalCalculatedStats[`${scaleStat}_dmg`] || '0%');
  const all_dmg = parsePercent(finalCalculatedStats.all_dmg || '0%');
  const defReduction = parsePercent(finalCalculatedStats.def_res || '0%') / 100;
  const defIgnore = parsePercent(finalCalculatedStats.def_res_ignore || '0%') / 100;
  const resIgnore = parsePercent(finalCalculatedStats.all_res_ignore || '0%') / 100;
  const trueDmg = parseTrueDmg(finalCalculatedStats.true_dmg || '0%');
  const vulnerability = typeof vulnerability_Multiplier_dmg !== 'undefined' ? vulnerability_Multiplier_dmg : 1;
  const weaken = 0;
  const mitigation = 0;
  const attackerLevel = levelData.level || 80;

  const baseDMG = baseStat * multiplier;
  const critMult = 1 + crit_dmg / 100;
  const dmgBoost = 1 + (element_dmg + all_dmg) / 100;
  const weakenMult = 1 - weaken;
  const defMult = calculateDefMultiplier(attackerLevel, 95, defReduction, defIgnore);
  const resMult = calculateResMultiplier(0.2, resIgnore);
  const broken = 0.9;

  const finalDamage = baseDMG * critMult * dmgBoost * weakenMult * defMult * resMult * vulnerability * (1 - mitigation) * broken * trueDmg;

  console.log('[Damage Calculation]', {
    baseDMG,
    critMult,
    dmgBoost,
    weakenMult,
    defMult,
    resMult,
    vulnerability,
    mitigationMultiplier: 1 - mitigation,
    broken,
    trueDmg,
    finalDamage
  });

  let content = `
    <h3>${skill.name} <span>(Ур. ${level}, ${scaleStat.toUpperCase()})</span></h3>
    <p>Урон по центральной цели: <strong>${Math.round(finalDamage)}</strong></p>
  `;

  if (targetType === '3') {
    const secondaryDMG = baseStat * secondaryMultiplier * critMult * dmgBoost * weakenMult * defMult * resMult * vulnerability * (1 - mitigation) * broken * trueDmg;
    content += `<p>Урон по соседним целям: <strong>${Math.round(secondaryDMG)}</strong></p>`;
  }

  box.innerHTML = content;
  container.appendChild(box);
}

function getSkillLevelFromData(typeLabel, characterLevelData) {
  if (!characterLevelData || !characterLevelData.skillLevels) return 1;
  return characterLevelData.skillLevels[typeLabel] || 1;
}

function getSkillMultiplierAndStatType(skill, level) {
  if (!Array.isArray(skill.dmg_levels)) {
    return { multiplier: 1.0, scaleStat: 'atk', secondaryMultiplier: 1.0, targetType: '1' };
  }

  const found = skill.dmg_levels.find(lvl => Number(lvl.level) === Number(level));
  if (!found) return { multiplier: 1.0, scaleStat: 'atk', secondaryMultiplier: 1.0, targetType: '1' };

  const raw = String(found.dmg_percent_main || '100%').replace('%', '').replace(',', '.');
  const multiplier = parseFloat(raw) / 100 || 1.0;

  const rawSecondary = String(found.dmg_percent_secondary || '0%').replace('%', '').replace(',', '.');
  const secondaryMultiplier = parseFloat(rawSecondary) / 100 || 0;

  const scaleStat = (found.dmg_info || 'atk').toLowerCase();
  const targetType = found.target_type || '1';

  return { multiplier, scaleStat, secondaryMultiplier, targetType };
}

document.addEventListener('DOMContentLoaded', () => {
  // initDamageTab вызывается из main.js
});
