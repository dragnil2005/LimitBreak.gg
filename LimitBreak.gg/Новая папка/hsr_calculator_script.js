
let hpBase = 0;
let atkBase = 0;
let defBase = 0;
let speedBase = 0;


function showTab(containerId, tabName) {
    const tabs = document.querySelectorAll(`#${containerId} .content-tab`);
    tabs.forEach(tab => tab.style.display = 'none');
    document.getElementById(`${tabName}-tab`).style.display = 'block';
}


function initializeDefaultTabs() {
    showTab('left-container', 'stats'); 
    showTab('right-container', 'character'); 
}

const levelSteps = [1, 10, 20, 30, 40, 50, 60, 70, 80];

const effectMaxValuesForBaseEffects = {
    'hp-bonus-flat': 254.0251,
    'hp-bonus-percent': 25.920,
    'atk-bonus-flat': 127.013,
    'atk-bonus-percent': 25.920,
    'def-bonus-flat': 127.013,
    'def-bonus-percent': 32.400,
    'speed-bonus-flat': 15.600,
    'crit-rate-bonus': 19.440,
    'crit-dmg-bonus': 38.880,
    'break-effect-bonus': 38.880,
    'effect-hit-chance-bonus': 25.920,
    'effect-resistance-bonus': 25.920,
};

const effectStepValuesForMainEffects = {
    'hp-bonus-flat': 39.5136,
    'hp-bonus-percent': 2.4192,
    'atk-bonus-flat': 19.7568,
    'atk-bonus-percent': 2.4192,
    'def-bonus-percent': 3.024,
    'speed-bonus-flat': 1.4,
    'crit-rate-bonus': 1.8144,
    'crit-dmg-bonus': 3.6288,
    'break-effect-bonus': 3.6277,
    'effect-hit-chance-bonus': 2.4192,
    'energy-recharge-bonus': 1.0886,
    'healing-bonus': 1.9354,
    'physical-dmg-bonus': 2.1773,
    'pyro-dmg-bonus': 2.1773,
    'cryo-dmg-bonus': 2.1773,
    'electro-dmg-bonus': 2.1773,
    'anemo-dmg-bonus': 2.1773,
    'quantum-dmg-bonus': 2.1773,
    'imaginary-dmg-bonus': 2.1773,
};

const effectStartValuesForMainEffects = {
    'hp-bonus-flat': 112.896,
    'hp-bonus-percent': 6.912,
    'atk-bonus-flat': 56.448,
    'atk-bonus-percent': 6.912,
    'def-bonus-percent': 8.64,
    'speed-bonus-flat': 4.032,
    'crit-rate-bonus': 5.184,
    'crit-dmg-bonus': 10.368,
    'break-effect-bonus': 10.368,
    'effect-hit-chance-bonus': 6.912,
    'energy-recharge-bonus': 3.1104,
    'healing-bonus': 5.5296,
    'physical-dmg-bonus': 6.2208,
    'pyro-dmg-bonus': 6.2208,
    'cryo-dmg-bonus': 6.2208,
    'electro-dmg-bonus': 6.2208,
    'anemo-dmg-bonus': 6.2208,
    'quantum-dmg-bonus': 6.2208,
    'imaginary-dmg-bonus': 6.2208,
};

function updateNumber(field) {
    const slider = document.getElementById(`${field}-slider`);
    const number = document.getElementById(`${field}-value`);
    const sliderValue = parseInt(slider.value, 10);

    if (field === 'level') {
        const level = levelSteps[sliderValue];
        number.value = level;
        updateLevelData(level); 
    } else if (field === 'weapon-level') {
        const weaponLevel = levelSteps[sliderValue];
        number.value = weaponLevel;
        updateWeaponLevelData(weaponLevel); 
    } 
    
    else if (field.includes('type') || (field.includes('main-characteristic'))) {
        
        const slider = document.getElementById(`${field}-slider`);
        const select = document.getElementById(`${field}-effect`);

        console.log(field);
    
        if (!slider || !select && (!field.includes('main-characteristic'))) {
            console.log(`Не удалось найти slider или select для ${field}`);
            return;
        }
        
        if (field.includes('main-characteristic'))
            {
                switch(field)
                {
                    case 'main-characteristic-1':
                        selectedEffect = 'hp-bonus-flat';
                        break;
                    case 'main-characteristic-2':
                        selectedEffect = 'atk-bonus-flat';
                        break;
                    default:
                        selectedEffect = select.value;
                        break;
                }
            }
            else{
                selectedEffect = select.value;
            }
    
        
        if(!(field.includes('main-characteristic')))
        {
            if (effectMaxValuesForBaseEffects.hasOwnProperty(selectedEffect)){
                slider.max = effectMaxValuesForBaseEffects[selectedEffect];
            } else {
                console.log(`Эффект ${selectedEffect} отсутствует в словаре effectMaxValues`);
                slider.max = 0; 
                console.log("почемуто не вижу!!!!!!!");
            }
        }

        number.value = slider.value; 

        calculateEffectSum();
    }
    else {
        number.value = slider.value; 
        calculateEffectSum();
    }
}


function updateSlider(field) {
    const slider = document.getElementById(`${field}-slider`);
    const number = document.getElementById(`${field}-value`);
    const numberValue = parseInt(number.value, 10);

    if (field === 'level') {
        const closestIndex = levelSteps.indexOf(numberValue);
        if (closestIndex !== -1) {
            slider.value = closestIndex;
            updateLevelData(levelSteps[closestIndex]); 
        } else {
            console.log('Указанный уровень отсутствует в levelSteps');
        }
    } else if (field === 'weapon-level') {
        const closestIndex = levelSteps.indexOf(numberValue);
        if (closestIndex !== -1) {
            slider.value = closestIndex;
            updateWeaponLevelData(levelSteps[closestIndex]); 
        } else {
            console.log('Указанный уровень оружия отсутствует в levelSteps');
        }
    } else if (field.includes('type') || (field.includes('main-characteristic'))) {
        
        const slider = document.getElementById(`${field}-slider`);
        const select = document.getElementById(`${field}-effect`);

        console.log(`ЙОХООО`);
    
        if (!slider || !select && (!field.includes('main-characteristic'))) {
            console.log(`Не удалось найти slider или select для ${field}`);
            return;
        }


        if (field.includes('main-characteristic'))
            {
                switch(field)
                {
                    case 'main-characteristic-1':
                        selectedEffect = 'hp-bonus-flat';
                        break;
                    case 'main-characteristic-2':
                        selectedEffect = 'atk-bonus-flat';
                        break;
                    default:
                        selectedEffect = select.value;
                        break;
                }
            }
            else {
                selectedEffect = select.value;
            }
    
        

        if(!(field.includes('main-characteristic')))
        {
            if (effectMaxValuesForBaseEffects.hasOwnProperty(selectedEffect)) {
                slider.max = effectMaxValuesForBaseEffects[selectedEffect];
             } else {
                 console.log(`Эффект ${selectedEffect} отсутствует в словаре effectMaxValues`);
                slider.max = 0; 
             }
        }
       
        calculateEffectSum();
    }
    
    else {
        slider.value = number.value; 
        calculateEffectSum();
    }
}


let characterData = null;


function loadCharacterData() {
    return fetch('character-stats.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            characterData = data;
            console.log('Данные персонажа загружены:', characterData);
        })
        .catch(error => console.error('Ошибка при загрузке данных:', error));
}

let isFirstRun = true; 


function updateLevelData(level) {
    if (!characterData) {
        console.error('Данные персонажа не загружены');
        return null;
    }

    console.log('Доступные уровни:', characterData.levels.map(lvl => lvl.level));
    const levelData = characterData.levels.find(lvl => lvl.level === level);
    if (!levelData) {
        console.error(`Данные для уровня ${level} не найдены`);
        return null;
    }

    console.log('Обновляем данные для уровня:', levelData);

    
    characterStats = {
        hp: levelData.hp,
        atk: levelData.atk,
        def: levelData.def,
        speed: levelData.speed
    };

    
    if (!isFirstRun) {
        updateCombinedBaseStats(); 
        updateTotalStats(); 
    } else {
        console.log('Пропуск пересчётов при первом запуске');
        isFirstRun = false; 
    }

    return levelData;
}



function updateTotalStats() {

    
    
    const hpBonusPercent = typeof window.hpBonusPercent !== 'undefined' ? window.hpBonusPercent : 0;
    const hpBonusFlat = typeof window.hpBonusFlat !== 'undefined' ? window.hpBonusFlat : 0;

    const hpWithPercent = (hpBase || 0) * (1 + hpBonusPercent);
    const hpTotal = hpWithPercent + hpBonusFlat;
    document.getElementById('hp-bonus-percent').textContent = `+${(hpBonusPercent * 100).toFixed(0)}%`;
    document.getElementById('hp-bonus-flat').textContent = `+${hpBonusFlat}`;
    document.getElementById('hp-total').textContent = hpTotal.toFixed(0);

    
    const atkWithPercent = (atkBase || 0) * (1 + atkBonusPercent);
    const atkTotal = atkWithPercent + atkBonusFlat;
    document.getElementById('atk-bonus-percent').textContent = `+${(atkBonusPercent * 100).toFixed(0)}%`;
    document.getElementById('atk-bonus-flat').textContent = `+${atkBonusFlat}`;
    document.getElementById('atk-total').textContent = atkTotal.toFixed(0);

    
    const defWithPercent = (defBase || 0) * (1 + defBonusPercent);
    const defTotal = defWithPercent + defBonusFlat;
    document.getElementById('def-bonus-percent').textContent = `+${(defBonusPercent * 100).toFixed(0)}%`;
    document.getElementById('def-bonus-flat').textContent = `+${defBonusFlat}`;
    document.getElementById('def-total').textContent = defTotal.toFixed(0);

    
    const speedWithPercent = (speedBase || 0) * (1 + speedBonusPercent);
    const speedTotal = speedWithPercent + speedBonusFlat;
    document.getElementById('speed-bonus-percent').textContent = `+${(speedBonusPercent * 100).toFixed(0)}%`;
    document.getElementById('speed-bonus-flat').textContent = `+${speedBonusFlat}`;
    document.getElementById('speed-total').textContent = speedTotal.toFixed(0);

    console.log('Обновлены характеристики:', { hpTotal, atkTotal, defTotal, speedTotal });
}


let weaponData = null;


function loadWeaponData() {
    return fetch('weapon-stats.json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            weaponData = data;
            console.log('Данные оружия загружены:', weaponData);
        })
        .catch(error => console.error('Ошибка при загрузке данных оружия:', error));
}


function updateWeaponLevelData(level) {
    if (!weaponData) {
        console.error('Данные оружия не загружены');
        return null;
    }

    const weaponLevelData = weaponData.levels.find(lvl => lvl.level === level);
    console.log('Доступные уровни оружия:', weaponData.levels.map(lvl => lvl.level));
    if (!weaponLevelData) {
        console.error(`Данные для уровня оружия ${level} не найдены`);
        return null;
    }

    console.log('Обновляем данные оружия для уровня:', weaponLevelData);

    
    weaponStats = {
        hp: weaponLevelData.hp,
        atk: weaponLevelData.atk,
        def: weaponLevelData.def
    };

    updateCombinedBaseStats(); 
    updateTotalStats();

    return weaponLevelData;
}


function updateCombinedBaseStats() {
    
    const combinedHp = (characterStats?.hp || 0) + (weaponStats?.hp || 0);
    const combinedAtk = (characterStats?.atk || 0) + (weaponStats?.atk || 0);
    const combinedDef = (characterStats?.def || 0) + (weaponStats?.def || 0);
    const combinedSpeed = (characterStats?.speed || 0) + (weaponStats?.speed || 0);

    
    hpBase = combinedHp;
    atkBase = combinedAtk;
    defBase = combinedDef;
    speedBase = combinedSpeed;

    console.log('Суммарные базовые характеристики обновлены:');
    console.log(`HP Base: ${hpBase}, Atk Base: ${atkBase}, Def Base: ${defBase}`);

    
    document.getElementById('hp-base').textContent = hpBase;
    document.getElementById('atk-base').textContent = atkBase;
    document.getElementById('def-base').textContent = defBase;
    document.getElementById('speed-base').textContent = speedBase;
}

function updateArtifactOptions(artifactId) {
	const artifact = document.querySelector(`[data-artifact="${artifactId}"]`);
	const selects = artifact.querySelectorAll('select');

	
	artifact.querySelectorAll('option').forEach(option => option.disabled = false);

	
	selects.forEach(select => {
		const selectedValue = select.value;
		if (selectedValue !== 'none') {
			artifact.querySelectorAll(`option[value="${selectedValue}"]`).forEach(option => {
				if (option.parentElement !== select) {
					option.disabled = true;
				}
			});
		}
	});
}


function calculateEffectSum() {
    const finalStatsDiv = document.querySelector('.finalArtefactStats');
    const effectsSum = {}; 

    
    for (let artifactId = 1; artifactId <= 4; artifactId++) {
        
        for (let effectId = 1; effectId <= 6; effectId++) {
            const selectId = `type${artifactId}-${effectId}-effect`; 
            const rangeId = `type${artifactId}-${effectId}-slider`; 

            const select = document.getElementById(selectId);
            const slider = document.getElementById(rangeId);

            if (select && slider) {
                const effectName = select.value;
                const effectValue = parseInt(slider.value, 10);

                if (effectName !== 'none') {
                    if (!effectsSum[effectName]) {
                        effectsSum[effectName] = 0;
                    }
                    effectsSum[effectName] += effectValue;
                }
            }
        }
    }

    
    for (let mainArtId = 1; mainArtId <= 6; mainArtId++) {
        const selectId = `main-characteristic-${mainArtId}-effect`; 
        const rangeId = `main-characteristic-${mainArtId}-slider`; 
        const finalValueId = `main-characteristic-${mainArtId}-finalValue`; 

        const select = document.getElementById(selectId);
        const slider = document.getElementById(rangeId);
        const finalValueElement = document.getElementById(finalValueId);

        
        let effectName = "0"
        if (!select)
        {
            switch (mainArtId) {
                case 1:
                    effectName = "hp-bonus-flat"
                    break;
                case 2:
                    effectName = "atk-bonus-flat";
                    break;
                default:
                    effectName = select.value;
                    break;
            }
            
        }
        else
        {
            effectName = select.value;
        }

        if (slider && finalValueElement) {
            const sliderValue = parseInt(slider.value, 10);

            if (effectName !== 'none') {
                
                const startValue = effectStartValuesForMainEffects[effectName] || 0;
                const stepValue = effectStepValuesForMainEffects[effectName] || 0;
                const calculatedValue = startValue + sliderValue * stepValue;

                
                if (!effectsSum[effectName]) {
                    effectsSum[effectName] = 0;
                }
                effectsSum[effectName] += calculatedValue;

                
                finalValueElement.textContent = calculatedValue.toFixed(2);
            }
        }
    }

    for (let [effect, value] of Object.entries(effectsSum)) {
        let bonusElement = document.getElementById(`${effect}`);

        const hasPercentage = bonusElement.textContent.trim().endsWith('%');

        
        bonusElement.textContent = hasPercentage
            ? `${value.toFixed(2)}%`
            : `${value.toFixed(2)}`;
    }

    
    finalStatsDiv.innerHTML = '';

    
    for (const [effect, value] of Object.entries(effectsSum)) {
        const statText = document.createElement('p');
        statText.textContent = `${effect}: ${value.toFixed(2)}`;
        finalStatsDiv.appendChild(statText);
    }

    console.log('Суммарные эффекты артефактов:', effectsSum);
    return effectsSum;
}



function initializeApp() {
    console.log('Инициализация приложения начата');
    initializeDefaultTabs();
    
    Promise.all([loadCharacterData(), loadWeaponData()])
        .then(() => {
            updateLevelData(1); 
            updateWeaponLevelData(1); 
            console.log('Данные успешно загружены:', { characterData, weaponData });
        })
        .catch(error => console.error('Ошибка при инициализации приложения:', error));
}


initializeApp();
