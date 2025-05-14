// buffs.js
import {
  addStatBonusFromSource,
  removeStatSource,
  renderStatBreakdownList
} from './character/statsAggregator.js';

const CUSTOM_SOURCE = 'userBuffs';

// Теперь у каждого поля явно указан stat (без суффикса), тип бонуса и метка
const BUFF_FIELDS = [
  { stat: 'hp',                type: 'flat',    label: 'HP (flat)' },
  { stat: 'hp',                type: 'percent', label: 'HP (%)' },
  { stat: 'atk',               type: 'flat',    label: 'Сила атаки (flat)' },
  { stat: 'atk',               type: 'percent', label: 'Сила атаки (%)' },
  { stat: 'def',               type: 'flat',    label: 'Защита (flat)' },
  { stat: 'def',               type: 'percent', label: 'Защита (%)' },
  { stat: 'speed',             type: 'flat',    label: 'Скорость (flat)' },
  { stat: 'speed',             type: 'percent', label: 'Скорость (%)' },
  { stat: 'crit_rate',         type: 'percent', label: 'Крит. шанс (%)' },
  { stat: 'crit_dmg',          type: 'percent', label: 'Крит. урон (%)' },
  { stat: 'effect_hit',        type: 'percent', label: 'Эффект пробития (%)' },
  { stat: 'heal_bonus',        type: 'percent', label: 'Бонус исцеления (%)' },
  { stat: 'energy_regen',      type: 'flat',    label: 'Скорость восстановления энергии (flat)' },
  { stat: 'effect_hit_rate',   type: 'percent', label: 'Шанс попадания эффектов (%)' },
  { stat: 'effect_res',        type: 'percent', label: 'Сопротивление эффектам (%)' },
  { stat: 'physical_dmg',      type: 'percent', label: 'Бонус физ. урона (%)' },
  { stat: 'fire_dmg',          type: 'percent', label: 'Бонус огн. урона (%)' },
  { stat: 'ice_dmg',           type: 'percent', label: 'Бонус лед. урона (%)' },
  { stat: 'lightning_dmg',     type: 'percent', label: 'Бонус элек. урона (%)' },
  { stat: 'wind_dmg',          type: 'percent', label: 'Бонус ветр. урона (%)' },
  { stat: 'quantum_dmg',       type: 'percent', label: 'Бонус квант. урона (%)' },
  { stat: 'imaginary_dmg',     type: 'percent', label: 'Бонус мним. урона (%)' },
  { stat: 'physical_res_ignore',    type: 'percent', label: 'Игнор физ. сопротивления (%)' },
  { stat: 'fire_res_ignore',        type: 'percent', label: 'Игнор огн. сопротивления (%)' },
  { stat: 'ice_res_ignore',         type: 'percent', label: 'Игнор лед. сопротивления (%)' },
  { stat: 'lightning_res_ignore',   type: 'percent', label: 'Игнор элек. сопротивления (%)' },
  { stat: 'wind_res_ignore',        type: 'percent', label: 'Игнор ветрового сопротивления (%)' },
  { stat: 'quantum_res_ignore',     type: 'percent', label: 'Игнор квантового сопротивления (%)' },
  { stat: 'imaginary_res_ignore',   type: 'percent', label: 'Игнор мнимого сопротивления (%)' },
  { stat: 'def_res_ignore',          type: 'percent', label: 'Игнор защиты противника (%)' },
];

export function initBuffsTab() {
  const container = document.getElementById('buffs-form-container');

  // 1) Строим таблицу
  const table = document.createElement('table');
  table.innerHTML = `
    <thead>
      <tr><th>Стат</th><th>Значение</th></tr>
    </thead>
    <tbody>
      ${BUFF_FIELDS.map(f => `
        <tr>
          <td>${f.label}</td>
          <td>
            <input
              type="number"
              step="any"
              id="buff-${f.stat}-${f.type}"
              placeholder="0"
            />
          </td>
        </tr>
      `).join('')}
    </tbody>
  `;
  container.appendChild(table);

  // 2) При любом вводе пересчитываем пользовательские бонусы
  container.addEventListener('input', () => {
    // Сброс старых
    removeStatSource(CUSTOM_SOURCE);

    // Добавляем новые
    BUFF_FIELDS.forEach(f => {
      const inp = document.getElementById(`buff-${f.stat}-${f.type}`);
      const val = parseFloat(inp.value);
      if (!isNaN(val) && val !== 0) {
        addStatBonusFromSource(f.stat, val, f.type, CUSTOM_SOURCE);
      }
    });

    // Перерисовываем разбивку
    renderStatBreakdownList();
  });

  // 3) Один раз отрисовать пустую
  renderStatBreakdownList();
}

// Инициализируем при загрузке
document.addEventListener('DOMContentLoaded', initBuffsTab);
