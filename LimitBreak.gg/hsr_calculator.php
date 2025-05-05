<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Подсчет урона персонажа</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<!-- Left-tabs: кнопки переключения -->
	<div class="left-tabs">
		<button class="tab-btn" onclick="showTab('left-container', 'stats')">
			<img src="tab_stats_light.png" alt="Характеристики">
		</button>
		<button class="tab-btn" onclick="showTab('left-container', 'character_list')">
			<img src="tab_share_light.png" alt="Мои персонажи">
		</button>
	</div>

	<!-- Left-container: отображение содержимого в зависимости от выбранной вкладки -->
	<div id="left-container" class="left-container">
		<!-- Таб для характеристик -->
		<div id="stats-tab" class="content-tab">
			<h2>Основные характеристики</h2>
			<div class="stat-row">
				<div class="stat-name">HP</div>
				<div class="stat-base" id="hp-base"></div>
				<div class="stat-bonus-percent" id="hp-bonus-percent"></div>
				<div class="stat-bonus-flat" id="hp-bonus-flat"></div>
				<div class="stat-total" id="hp-total"></div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Сила атаки</div>
				<div class="stat-base" id="atk-base"></div>
				<div class="stat-bonus-percent" id="atk-bonus-percent"></div>
				<div class="stat-bonus-flat" id="atk-bonus-flat"></div>
				<div class="stat-total" id="atk-total"></div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Защита</div>
				<div class="stat-base" id="def-base"></div>
				<div class="stat-bonus-percent" id="def-bonus-percent"></div>
				<div class="stat-bonus-flat" id="def-bonus-flat"></div>
				<div class="stat-total" id="def-total"></div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Скорость</div>
				<div class="stat-base" id="speed-base"></div>
				<div class="stat-bonus-percent" id="speed-bonus-percent"></div>
				<div class="stat-bonus-flat" id="speed-bonus-flat"></div>
				<div class="stat-total" id="speed-total"></div>
			</div>

			<h2>Вторичные характеристики</h2>
			<div class="stat-row">
				<div class="stat-name">Восстановление энергии</div>
				<div class="stat-base" id="energy-recharge-base">%</div>
				<div class="stat-bonus" id="energy-recharge-bonus">%</div>
				<div class="stat-total" id="energy-recharge-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Шанс крит. попадания</div>
				<div class="stat-base" id="crit-rate">%</div>
				<div class="stat-bonus" id="crit-rate-bonus">%</div>
				<div class="stat-total" id="crit-rate-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Крит. урон</div>
				<div class="stat-base" id="crit-dmg">%</div>
				<div class="stat-bonus" id="crit-dmg-bonus">%</div>
				<div class="stat-total" id="crit-dmg-total">%</div>
			</div>
            <div class="stat-row">
                <div class="stat-name">Эффект пробития</div>
                <div class="stat-base">0</div>
                <div class="stat-bonus" id="break-effect-bonus">%</div>
                <div class="stat-total" id="break-effect-total">%</div>
            </div>
            <div class="stat-row">
                <div class="stat-name">Шанс попадания эффектов</div>
                <div class="stat-base">0</div>
                <div class="stat-bonus" id="effect-hit-chance-bonus">%</div>
                <div class="stat-total" id="effect-hit-chance-total">%</div>
            </div>
            <div class="stat-row">
                <div class="stat-name">Сопротивление эффектам</div>
                <div class="stat-base">0</div>
                <div class="stat-bonus" id="effect-resistance-bonus">%</div>
                <div class="stat-total" id="effect-resistance-total">%</div>
            </div>

			<h2>Элементы</h2>
			<div class="stat-row">
				<div class="stat-name">Бонус физ. урона</div>
				<div class="stat-base" id="physical-dmg">%</div>
				<div class="stat-bonus" id="physical-dmg-bonus">%</div>
				<div class="stat-total" id="physical-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус огненово урона</div>
				<div class="stat-base" id="pyro-dmg">%</div>
				<div class="stat-bonus" id="pyro-dmg-bonus">%</div>
				<div class="stat-total" id="pyro-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус крио урона</div>
				<div class="stat-base" id="cryo-dmg">%</div>
				<div class="stat-bonus" id="cryo-dmg-bonus">%</div>
				<div class="stat-total" id="cryo-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус электро урона</div>
				<div class="stat-base" id="electro-dmg">%</div>
				<div class="stat-bonus" id="electro-dmg-bonus">%</div>
				<div class="stat-total" id="electro-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус ветряного урона</div>
				<div class="stat-base" id="anemo-dmg">%</div>
				<div class="stat-bonus" id="anemo-dmg-bonus">%</div>
				<div class="stat-total" id="anemo-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус квантового урона</div>
				<div class="stat-base" id="quantum-dmg">%</div>
				<div class="stat-bonus" id="quantum-dmg-bonus">%</div>
				<div class="stat-total" id="quantum-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Бонус мнимого урона</div>
				<div class="stat-base" id="imaginary-dmg">%</div>
				<div class="stat-bonus" id="imaginary-dmg-bonus">%</div>
				<div class="stat-total" id="imaginary-dmg-total">%</div>
			</div>

			<h2>Модификаторы урона</h2>
			<div class="stat-row">
				<div class="stat-name">Весь урон</div>
				<div class="stat-base" id="all-dmg">%</div>
				<div class="stat-bonus" id="all-dmg-bonus">%</div>
				<div class="stat-total" id="all-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Урон обычной атаки</div>
				<div class="stat-base" id="normal-attack-dmg">%</div>
				<div class="stat-bonus" id="normal-attack-dmg-bonus">%</div>
				<div class="stat-total" id="normal-attack-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Урон навыка</div>
				<div class="stat-base" id="skill-dmg">%</div>
				<div class="stat-bonus" id="skill-dmg-bonus">%</div>
				<div class="stat-total" id="skill-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Урон бонус атак</div>
				<div class="stat-base" id="attack-bonus-dmg">%</div>
				<div class="stat-bonus" id="attack-bonus-dmg-bonus">%</div>
				<div class="stat-total" id="attack-bonus-dmg-total">%</div>
			</div>
			<div class="stat-row">
				<div class="stat-name">Дот-урон</div>
				<div class="stat-base" id="dot-dmg">%</div>
				<div class="stat-bonus" id="dot-dmg-bonus">%</div>
				<div class="stat-total" id="dot-dmg-total">%</div>
			</div>
		</div>
		<div id="character_list-tab" class="content-tab">
			<h2>Мои персонажи</h2>
			<p>список персов будет здесь....</p>
		</div>
	</div>

	<!-- Right-tabs: кнопки для переключения правого контейнера -->
	<div class="right-tabs">
		<button class="tab-btn" onclick="showTab('right-container', 'character')">
			<img src="tab_char_light.png" alt="Персонажа">
		</button>
		<button class="tab-btn" onclick="showTab('right-container', 'Weapon')">
			<img src="tab_weapon_light.png" alt="Оружие">
		</button>
		<button class="tab-btn" onclick="showTab('right-container', 'artifact')">
			<img src="tab_artifact_light.png" alt="Артефакты">
		</button>
		<button class="tab-btn" onclick="showTab('right-container', 'team_congratulations')">
			<img src="tab_buffs_light.png" alt="Отряд">
		</button>
		<button class="tab-btn" onclick="showTab('right-container','rotation')">
			<img src="tab_rotation_light.png" alt="Ротация">
		</button>
	</div>

	<!-- Right-container: отображение данных -->
	<div id="right-container" class="right-container">
		<!-- Контент для персонажа -->
		<div id="character-tab" class="content-tab">
			<div class="character-image"></div>
            <a href="guide.html">Гайд персонажа</a>
			<div class="slider-container">
				<!-- Уровень -->
				<div class="slider-row">
					<input type="number" id="level-value" min="1" max="80" value="1"
						onchange="updateSlider('level')">
					<input type="range" id="level-slider" min="0" max="8" value="0" step="1"
						oninput="updateNumber('level')">
				</div>
				<!-- Базовая атака -->
				<div class="slider-row">
					<input type="number" id="attack-value" min="1" max="10" value="1"
						onchange="updateSlider('attack')">
					<input type="range" id="attack-slider" min="1" max="10" value="1"
						oninput="updateNumber('attack')">
				</div>
				<!-- Навык -->
				<div class="slider-row">
					<input type="number" id="skill-value" min="1" max="10" value="1"
						onchange="updateSlider('skill')">
					<input type="range" id="skill-slider" min="1" max="10" value="1"
						oninput="updateNumber('skill')">
				</div>
				<!-- Супер способность -->
				<div class="slider-row">
					<input type="number" id="ult-value" min="1" max="10" value="1"
						onchange="updateSlider('ult')">
					<input type="range" id="ult-slider" min="1" max="10" value="1"
						oninput="updateNumber('ult')">
				</div>
				<!-- Эйдалоны -->
				<div class="slider-row">
					<input type="number" id="eidolons-value" min="0" max="6" value="0"
						onchange="updateSlider('eidolons')">
					<input type="range" id="eidolons-slider" min="0" max="6" value="0"
						oninput="updateNumber('eidolons')">
				</div>
			</div>
		</div>
		<!-- Контент для оружия -->
		<div id="Weapon-tab" class="content-tab">
			<div class="weapon-image"></div> <!-- Картинка оружия -->
			<div class="slider-container">
				<!-- Слайдер уровня оружия -->
				<div class="slider-row">
					<input type="number" id="weapon-level-value" min="1" max="80" value="1"
						onchange="updateSlider('weapon-level')">
					<input type="range" id="weapon-level-slider" min="0" max="8" value="0" step="1"
						oninput="updateNumber('weapon-level')">
				</div>
				<!-- Слайдер уровня наложений -->
				<div class="slider-row">
					<input type="number" id="overlay-level-value" min="1" max="5" value="1"
						onchange="updateSlider('overlay-level')">
					<input type="range" id="overlay-level-slider" min="1" max="5" value="1"
						oninput="updateNumber('overlay-level')">
				</div>
			</div>
			<!-- Характеристики оружия -->
			<div class="weapon-stats">
				<h3>Характеристики оружия</h3>
				<div class="stat-row">
					<div class="stat-name">HP</div>
					<div class="stat-value" id="weapon-hp"></div>
				</div>
				<div class="stat-row">
					<div class="stat-name">Сила атаки</div>
					<div class="stat-value" id="weapon-atk"></div>
				</div>
				<div class="stat-row">
					<div class="stat-name">Защита</div>
					<div class="stat-value" id="weapon-def"></div>
				</div>
			</div>
		</div>
		<!-- Контент для артифактов ЧО МНЕ НАДО -->
		<!-- https:
		<!-- 6 артефактов на одного персонажа, делятся артефакты на 2 вида: 4 реликвии и 2 планарки-->
		<!-- 1 слот в реликвиях - голова: мэйн-стат плоское ХП (Плоское - не поддающееся %, только константа) -->
		<!-- 2 слот в реликвиях - руки: мэйн-стат плоская Сила атаки -->
		<!-- 3 слот в реликвиях - тело:
	  мэйн-стат процент ХП, процент силы Атаки, процент Защиты, крит шанс, крит урон, бонус исцеления, шанс попадания эффектов-->
		<!-- 4 слот в реликвиях - ноги: мэйн-стат процент силы атаки, защиты, хп; скорость -->

		<!-- 1 слот в планарке - осн характеристики: процент хп, процент силы атаки, защиты;
   бонус физического урона, огненого, ледянного, электро, ветрянного, квантового, мнимые -->
		<!-- 2 слот в планарке - осн характеристики: процент хп, процент силы атаки, защиты;
   эффект пробития % и скорость восстановления энергии % -->

		<!-- сет может быть собран из 2-4 реликвий; сет даёт бафф-->
		<!-- сет из планариев состоит из двух планариев; сет даёт бафф -->
		<!-- мейн характеристика не повторяется ниже реликвий, планарий и сетов -->
		<!-- в нижних статах может быть только HP(оба), сила атаки(оба), защита(оба), скорость, крит шанс, крит урон, эффект пробития,
  шанс попадания эффектов, сопротивление эффектам -->
		<!-- брать прямой пример с артефактов  https:
		<!-- вынести все унитарные бонусы в отдельное окно от артефактов(всех)((% и плоское раздельны))-->
		<!-- https:
		 
		
		
		
		
		03.12 

		Добавить два артефакта done

		Сноска в самом низу всех артефактов

		Все характеристики в начале с нулями
		Характеристики = сумма всех элементов артефактов.

		new idea idea
		[
			
		ползунок главных характеристик является уровнем артефакта (1 до 15)

		значение главной характерстики является значение за первый уровень + N * шаг из словаря

		ползунки побочных характеристик являются от 0 до X, записанного в словарь

		у нас будет два словаря:
		первый словарь <string, значение>, где string - название эффекта, а значение - ШАГ, прибавляемый за каждый уровень ползунка
		второй словарь <string, значение>, где string - название эффекта, а значение - Начальное значение эффекта (т.е. 1 уровень)

		СВЕРХУ словари связанные с главными характеристиками

		СНИЗУ словарь связанный с побочными характеристиками

		третий словарь <string, значение>, где string - название эффекта, а значение - максимальное значение побочного эффекта

		]

		Т.е. три артефакта на 3 скорости по 50 

		Дамага = 0
		Скорость = 150
		-->
		<div id="artifact-tab" class="content-tab">
			<div class="artifact-image"></div>

			<h3>Артефакт 1</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-1">Главная характеристика - Flat
					HP</h2>
				<input type="range" id="main-characteristic-1-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-1')">
				<input type="number" id="main-characteristic-1-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-1')">
				<div class = "main-characteristic-1-finalValue" id="main-characteristic-1-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-1">Тип 1</label>
					<select id="type1-1-effect">
						<option value="none"></option>
						<option value="hp-bonus-percent">HP%</option>
						<option value="atk-bonus-flat">Flat атака</option>
						<option value="atk-bonus-percent">Атака%</option>
						<option value="def-bonus-flat">Flat защита</option>
						<option value="def-bonus-percent">Защита%</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития%</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type1-1-slider" min="1" max="15" value="1" step="0.001" 
						oninput="updateNumber('type1-1')">
					<input type="number" id="type1-1-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-1')">
				</div>

				<div class="characteristic-option">
					<label for="type2-1">Тип 2</label>
					<select id="type2-1-effect">
						<option value="none"></option>
						<option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type2-1-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-1')">
					<input type="number" id="type2-1-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-1')">
				</div>

				<div class="characteristic-option">
					<label for="type3-1">Тип 3</label>
					<select id="type3-1-effect">
						<option value="none"></option>
						<option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type3-1-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-1')">
					<input type="number" id="type3-1-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-1')">
				</div>

				<div class="characteristic-option">
					<label for="type4-1">Тип 4</label>
					<select id="type4-1-effect">
						<option value="none"></option>
						<option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type4-1-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-1')">
					<input type="number" id="type4-1-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-1')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Attack') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>
			</div>

			<h3>Артефакт 2</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-2">Главная характеристика - Flat
					ATK</h2>
				<input type="range" id="main-characteristic-2-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-2')">
				<input type="number" id="main-characteristic-2-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-2')">
				<div class = "main-characteristic-2-finalValue" id="main-characteristic-2-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-2">Тип 1</label>
					<select id="type1-2-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
						<option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type1-2-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type1-2')">
					<input type="number" id="type1-2-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-2')">
				</div>

				<div class="characteristic-option">
					<label for="type2-2">Тип 2</label>
					<select id="type2-2-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type2-2-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-2')">
					<input type="number" id="type2-2-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-2')">
				</div>

				<div class="characteristic-option">
					<label for="type3-2">Тип 3</label>
					<select id="type3-2-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type3-2-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-2')">
					<input type="number" id="type3-2-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-2')">
				</div>

				<div class="characteristic-option">
					<label for="type4-2">Тип 4</label>
					<select id="type4-2-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type4-2-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-2')">
					<input type="number" id="type4-2-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-2')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Damage') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>

			</div>

			<h3>Артефакт 3</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-3">Главная характеристика</h2>
				<select id="main-characteristic-3-effect">
					<option value="none"></option>
					<option value="hp-bonus-percent">HP%</option>
					<option value="atk-bonus-percent">Атака%</option>
					<option value="def-bonus-percent">Защита%</option>
					<option value="crit-rate-bonus">Крит шанс</option>
					<option value="crit-dmg-bonus">Крит урон</option>
					<option value="healing-bonus">Бонус исцеления%</option>
					<option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
				</select>
				<input type="range" id="main-characteristic-3-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-3')">
				<input type="number" id="main-characteristic-3-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-3')">
				<div class = "main-characteristic-3-finalValue" id="main-characteristic-3-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-3">Тип 1</label>
					<select id="type1-3-effect">
						<option value="none"></option>
                        <option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type1-3-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type1-3')">
					<input type="number" id="type1-3-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-3')">
				</div>

				<div class="characteristic-option">
					<label for="type2-3">Тип 2</label>
					<select id="type2-3-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type2-3-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-3')">
					<input type="number" id="type2-3-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-3')">
				</div>

				<div class="characteristic-option">
					<label for="type3-3">Тип 3</label>
					<select id="type3-3-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type3-3-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-3')">
					<input type="number" id="type3-3-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-3')">
				</div>

				<div class="characteristic-option">
					<label for="type4-3">Тип 4</label>
					<select id="type4-3-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type4-3-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-3')">
					<input type="number" id="type4-3-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-3')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Damage') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>

			</div>

			<h3>Артефакт 4</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-4">Главная характеристика</h2>
				<select id="main-characteristic-4-effect">
					<option value="none"></option>
					<option value="atk-bonus-percent">Атака%</option>
					<option value="def-bonus-percent">Защита%</option>
					<option value="hp-bonus-percent">HP%</option>
					<option value="speed-bonus-flat">Скорость</option>
				</select>
				<input type="range" id="main-characteristic-4-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-4')">
				<input type="number" id="main-characteristic-4-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-4')">
				<div class = "main-characteristic-4-finalValue" id="main-characteristic-4-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-4">Тип 1</label>
					<select id="type1-4-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type1-4-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type1-4')">
					<input type="number" id="type1-4-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-4')">
				</div>

				<div class="characteristic-option">
					<label for="type2-4">Тип 2</label>
					<select id="type2-4-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type2-4-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-4')">
					<input type="number" id="type2-4-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-4')">
				</div>

				<div class="characteristic-option">
					<label for="type3-4">Тип 3</label>
					<select id="type3-4-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type3-4-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-4')">
					<input type="number" id="type3-4-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-4')">
				</div>

				<div class="characteristic-option">
					<label for="type4-4">Тип 4</label>
					<select id="type4-4-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Flat HP</option>
                        <option value="hp-bonus-percent">HP%</option>
                        <option value="atk-bonus-flat">Flat атака</option>
                        <option value="atk-bonus-percent">Атака%</option>
                        <option value="def-bonus-flat">Flat защита</option>
                        <option value="def-bonus-percent">Защита%</option>
                        <option value="speed-bonus-flat">Скорость</option>
                        <option value="crit-rate-bonus">Крит шанс</option>
                        <option value="crit-dmg-bonus">Крит урон</option>
                        <option value="break-effect-bonus">Эффект пробития%</option>
                        <option value="effect-hit-chance-bonus">Шанс попадания эффектов%</option>
                        <option value="effect-resistance-bonus">Сопротивление эффектам%</option>
					</select>
					<input type="range" id="type4-4-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-4')">
					<input type="number" id="type4-4-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-4')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Damage') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>

		<h3>Артефакт 5</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-5">Главная характеристика</h2>
				<select id="main-characteristic-5-effect">
					<option value="none"></option>
					<option value="atk-bonus-percent">Атака%</option>
					<option value="def-bonus-percent">Защита%</option>
					<option value="hp-bonus-percent">HP%</option>
					<option value="physical-dmg-bonus">Бонус физ. урона</option>
					<option value="pyro-dmg-bonus">Бонус огненово урона</option>
					<option value="cryo-dmg-bonus">Бонус крио урона</option>
                    <option value="electro-dmg-bonus">Бонус электро урона</option>
					<option value="anemo-dmg-bonus">Бонус ветряного урона</option>
					<option value="quantum-dmg-bonus">Бонус квантового урона</option>
					<option value="imaginary-dmg-bonus">Бонус мнимого урона</option>
				</select>
				<input type="range" id="main-characteristic-5-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-5')">
				<input type="number" id="main-characteristic-5-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-5')">
				<div class = "main-characteristic-5-finalValue" id="main-characteristic-5-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-5">Тип 1</label>
					<select id="type1-5-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type1-5-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type1-5')">
					<input type="number" id="type1-5-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-5')">
				</div>

				<div class="characteristic-option">
					<label for="type2-5">Тип 2</label>
					<select id="type2-5-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type2-5-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-5')">
					<input type="number" id="type2-5-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-5')">
				</div>

				<div class="characteristic-option">
					<label for="type3-5">Тип 3</label>
					<select id="type3-5-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type3-5-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-5')">
					<input type="number" id="type3-5-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-5')">
				</div>

				<div class="characteristic-option">
					<label for="type4-5">Тип 4</label>
					<select id="type4-5-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type4-5-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-5')">
					<input type="number" id="type4-5-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-5')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Damage') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>

			</div>

			<h3>Артефакт 6</h3>
			<div class="characteristics">
				<h2 class="main-characteristic" id="artifact-effect-6">Главная характеристика</h2>
				<select id="main-characteristic-6-effect">
					<option value="none"></option>
					<option value="atk-bonus-percent">Атака%</option>
					<option value="def-bonus-percent">Защиты%</option>
                    <option value="hp-bonus-percent">HP%</option>
					<option value="break-effect-bonus">Эффект пробития</option>
					<option value="energy-recharge-bonus">Регенерация энергии</option>
				</select>
				<input type="range" id="main-characteristic-6-slider" min="1" max="15" value="1"
					oninput="updateNumber('main-characteristic-6')">
				<input type="number" id="main-characteristic-6-value" min="1" max="15" value="1"
					onchange="updateSlider('main-characteristic-6')">
				<div class = "main-characteristic-6-finalValue" id="main-characteristic-6-finalValue"> 0 </div>

				<div class="characteristic-option">
					<label for="type1-6">Тип 1</label>
					<select id="type1-6-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type1-6-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type1-6')">
					<input type="number" id="type1-6-value" min="1" max="15" value="1"
						onchange="updateSlider('type1-6')">
				</div>

				<div class="characteristic-option">
					<label for="type2-6">Тип 2</label>
					<select id="type2-6-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type2-6-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type2-6')">
					<input type="number" id="type2-6-value" min="1" max="15" value="1"
						onchange="updateSlider('type2-6')">
				</div>

				<div class="characteristic-option">
					<label for="type3-6">Тип 3</label>
					<select id="type3-6-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type3-6-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type3-6')">
					<input type="number" id="type3-6-value" min="1" max="15" value="1"
						onchange="updateSlider('type3-6')">
				</div>

				<div class="characteristic-option">
					<label for="type4-6">Тип 4</label>
					<select id="type4-6-effect">
						<option value="none"></option>
						<option value="hp-bonus-flat">Плоское HP</option>
						<option value="hp-bonus-percent">% HP</option>
						<option value="atk-bonus-flat">Плоская атака</option>
						<option value="atk-bonus-percent">% Атака</option>
						<option value="def-bonus-flat">Плоская защита</option>
						<option value="def-bonus-percent">% Защита</option>
						<option value="speed-bonus-flat">Скорость</option>
						<option value="crit-rate-bonus">Крит шанс</option>
						<option value="crit-dmg-bonus">Крит урон</option>
						<option value="break-effect-bonus">Эффект пробития</option>
						<option value="effect-hit-chance-bonus">Шанс попадания эффектов</option>
						<option value="effect-resistance-bonus">Сопротивление эффектам</option>
					</select>
					<input type="range" id="type4-6-slider" min="1" max="15" value="1" step="0.001"
						oninput="updateNumber('type4-6')">
					<input type="number" id="type4-6-value" min="1" max="15" value="1"
						onchange="updateSlider('type4-6')">
				</div>

				<script>
					document.querySelectorAll('.characteristics').forEach((artifact) => {
						const selects = artifact.querySelectorAll('select');
						const mainCharacteristic = artifact.querySelector('.main-characteristic').textContent;
						selects.forEach(select => {
							select.addEventListener('change', () => {
								const selectedValues = Array.from(selects).map(s => s.value);
								selects.forEach(s => {
									const options = s.querySelectorAll('option');
									options.forEach(option => {
										if (option.value === 'none') {
											option.disabled = false;
										}
										else if (
											(mainCharacteristic.includes('Flat HP') && option.value === 'hp-bonus-flat') ||
											(mainCharacteristic.includes('Flat Damage') && option.value === 'atk-bonus-flat')
										) {
											
											option.disabled = true;
										}
										else if (selectedValues.includes(option.value) && option.value !== s.value) {
											option.disabled = true;
										} else {
											option.disabled = false;
										}
									});
								});
							});
						});
					});
				</script>

			</div>

			<div class='finalArtefactStats'>SMTH TEST</div>

			</div>
		</div>
		<!-- Контент для отрядов -->
		<div id="team_congratulations-tab" class="content-tab">
			<div class="support-image"></div>

		</div>
		<!-- Контент для подсчета урона/ротации -->
		<div id="rotation-tab" class="content-tab">

		</div>
	</div>

	<script src="script.js"></script>

</body>

</html>