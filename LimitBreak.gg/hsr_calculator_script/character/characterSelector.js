// characterSelector.js
let allCharacters = [];

export async function initCharacterSelector() {
  const data = await fetch('get_HSRcharacters_for_main.php').then(r => r.json());
  allCharacters = data;

  const defaultChar = allCharacters.find(ch => ch.id === '1') || allCharacters[0];
  renderSelectedCharacter(defaultChar);
}

function renderSelectedCharacter(character) {
  document.getElementById('selected-character-image').src = `character_image/${character.image}`;
  document.getElementById('selected-character-image').dataset.id = character.id;
  document.getElementById('char-name').textContent = character.name;

  const stars = +character.rarity;
  const starHTML = Array.from({length: stars}, () => {
    return `<span class="star" style="color: ${stars === 5 ? '#FFD700' : '#A347FF'}">★</span>`;
  }).join('');
  document.getElementById('char-stars').innerHTML = starHTML;

  document.getElementById('char-path-icon').src = `path_image/${character.path_image}`;
  document.getElementById('char-path-label').textContent = character.path;

  document.getElementById('char-dmg-icon').src = `damageType_image/${character.dmg_type_image}`;
  document.getElementById('char-dmg-label').textContent = character.dmg_type;
}

document.getElementById('selected-character-image').addEventListener('click', () => {
  console.log("Открыта модалка");
});
export async function loadCharacterById(id) {
  const res = await fetch(`get_HSRcharacters_for_guide.php?id=${id}`);
  const data = await res.json();
  const character = data.character;
  // тут можно дальше использовать character, если нужно рендерить
  return character;
}

