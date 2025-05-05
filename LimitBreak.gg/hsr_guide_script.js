
// Получаем id из URL
const params = new URLSearchParams(window.location.search);
const characterId = params.get('id');

// Делаем запрос к PHP-скрипту на сервере OpenServer
fetch(`https://limitbreak.gg/get_HSRcharacters_for_guide.php?id=${characterId}`)
  .then(response => response.json())
  .then(data => {
    console.log(data); // Тут твои данные из базы

    // Выводим данные на страницу
    document.getElementById('character-name').textContent = data.character.name;
    // И так далее для других полей
  })
  .catch(error => {
    console.error('Ошибка загрузки:', error);
  });
