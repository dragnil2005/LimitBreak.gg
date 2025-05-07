document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".tab-btn");
  const tabs = document.querySelectorAll(".content-tab");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      const target = btn.getAttribute("data-target");
      const side = btn.getAttribute("data-side");

      // определить какие табы скрывать (левые или правые)
      const relevantTabs = Array.from(tabs).filter(tab =>
        side === "left"
          ? ["stats-tab", "result-tab"].includes(tab.id)
          : ["character-tab", "lightcone-tab", "relics-tab", "buffs-tab"].includes(tab.id)
      );

      relevantTabs.forEach(tab => {
        tab.classList.toggle("active", tab.id === target);
      });

      // выделить активную кнопку только в своей группе
      buttons.forEach(b => {
        if (b.getAttribute("data-side") === side) {
          b.classList.toggle("active", b === btn);
        }
      });
    });
  });
  function updateDisplay(id) {
    const input = document.getElementById(id);
    const output = document.getElementById(`${id}-display`);
    if (input && output) {
      output.textContent = input.value;
    }

    if (id === 'level-range') {
      const levels = [1, 20, 30, 40, 50, 60, 70, 80];
      output.textContent = levels[parseInt(input.value)];
    }
  }

  function changeValue(id, delta) {
    const input = document.getElementById(id);
    if (!input) return;
    let newValue = parseInt(input.value) + delta;
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    newValue = Math.max(min, Math.min(max, newValue));
    input.value = newValue;
    updateDisplay(id);
  }

  document.addEventListener("DOMContentLoaded", () => {
    // существующий код переключения вкладок...

    updateDisplay("basic-attack");
    updateDisplay("skill");
    updateDisplay("ult");
    updateDisplay("eidolon");
  });
});
