export function initTabsManager() {
  initSideTabs('.left-tabs', '.left-container .tab-container');
  initSideTabs('.right-tabs', '.right-container .tab-container');
}

function initSideTabs(buttonsSelector, containersSelector) {
  const buttons = document.querySelectorAll(`${buttonsSelector} .tab-btn`);
  const tabs = document.querySelectorAll(containersSelector);

  buttons.forEach(button => {
    button.addEventListener('click', () => {
      const targetId = button.dataset.target;

      // 1. Скрываем все вкладки этой группы
      tabs.forEach(tab => {
        tab.classList.remove('active');
        tab.style.display = 'none';
      });

      // 2. Показываем нужную вкладку
      const targetTab = document.getElementById(targetId);
      if (targetTab) {
        targetTab.classList.add('active');
        targetTab.style.display = 'block';
      }

      // 3. Обновляем активную кнопку
      buttons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
    });
  });
}
