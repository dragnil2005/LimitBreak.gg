import { addStatBonusFromSource, removeStatSource, renderStatBreakdownList } from './statsAggregator.js';


const activeTraces = [];

export function initCharacterTraces(traces) {
  const container = document.getElementById('trace-container');
  container.innerHTML = ''; // очистка

  traces.forEach((trace, index) => {
    const {
      name,
      description,
      required_elevation,
      stat_type,
      buf_value,
      max_stacks
    } = trace;

    const block = document.createElement('div');
    block.className = 'trace-block';

    // Заголовок
    const header = document.createElement('div');
    header.className = 'trace-header';

    const req = document.createElement('span');
    req.className = 'trace-req';
    req.textContent = required_elevation;

    const title = document.createElement('div');
    title.className = 'trace-name';
    title.textContent = name;

    header.append(req, title);

    // Описание
    const desc = document.createElement('div');
    desc.className = 'trace-desc';
    desc.textContent = description;

    // Блок управления (галочка или слайдер)
    const control = document.createElement('div');
    control.className = 'trace-control';

    if (max_stacks > 1) {
      // Слайдер
      const slider = document.createElement('input');
      slider.type = 'range';
      slider.min = 0;
      slider.max = max_stacks;
      slider.value = 0;
      slider.className = 'trace-slider';

      const valueDisplay = document.createElement('span');
      valueDisplay.className = 'trace-buff-value';

      function updateBuffValue() {
        const value = +slider.value;

        if (stat_type && buf_value) {
          valueDisplay.textContent = `${(buf_value * value).toFixed(2)} ${stat_type}`;
          valueDisplay.style.color = value > 0 ? '#79d4f5' : '#ffffff';
        } else {
          valueDisplay.textContent = '';
          valueDisplay.style.color = '#ffffff';
        }

        activeTraces[index] = value;
        updateTraceStatBonuses(traces);
      }


      slider.addEventListener('input', updateBuffValue);
      updateBuffValue();

      control.append(slider, valueDisplay);
    } else if (stat_type !== null) {
        // Галочка
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'trace-checkbox';

        const buffText = document.createElement('span');
        buffText.className = 'trace-buff-value';
        buffText.textContent = `${buf_value} ${stat_type}`;
        buffText.style.color = '#ffffff'; // начальный цвет

        checkbox.addEventListener('change', () => {
          const isActive = checkbox.checked;
          activeTraces[index] = isActive ? 1 : 0;
          buffText.style.color = isActive ? '#79d4f5' : '#ffffff';
          updateTraceStatBonuses(traces);
        });

        control.append(buffText, checkbox);
      }

    block.append(header, desc, control);
    container.appendChild(block);
  });
}



export function getActiveTraces() {
  return [...activeTraces];
}

function updateTraceStatBonuses(traces) {
  // Удаляем ВСЕ старые трейсы одним вызовом
  removeStatSource('traces');

  // Собираем бонусы от всех активных трейсов
  const traceBonuses = {};

  traces.forEach((trace, index) => {
    const value = activeTraces[index];
    if (!value || value === 0) return;

    const stat = trace.stat_type;
    const bufValue = parseFloat(trace.buf_value);
    const totalValue = bufValue * value;

    // Накапливаем бонус по стату
    if (!traceBonuses[stat]) traceBonuses[stat] = 0;
    traceBonuses[stat] += totalValue;
  });

  // Теперь добавляем все как один источник 'traces'
  for (const [stat, value] of Object.entries(traceBonuses)) {
    addStatBonusFromSource(stat, value, 'percent', 'traces');
  }

  renderStatBreakdownList();
}

