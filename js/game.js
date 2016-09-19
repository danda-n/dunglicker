$(document).on("click", "#gameMonster", function(){
	attackMonster();
});

$(function() {
   $('[data-toggle="tooltip"]').tooltip({container: 'body'});
});

$("#healthSlider").slider({
	min: 0,
	max: 100,
   	disabled: true,
   	range: true,
   	values: [0, 25]
});

$("#timeSlider").slider({
	min: 0,
	max: 45,
   	disabled: true,
   	range: true,
   	values: [0, 45]
});

//temp shit
var monsterHealthCurrent;
var monsterHealthMax;
var gold = 0;
var awaitingSpawnResponse = false;
var currentRound = 1;
var currentSubRound = 1;
var damage = 1;
var attackSpeed = 1;
var criticalHitChance = 0;
var criticalHitDamage = 100;
var timerValue = 0;
var timerInterval;
var energy = 100;
var energyRegen = 1;
var emeralds = 0;
var experience = 0;
var currentEnergy = 0;
var spell1Strength = 0;
var spell2Strength = 0;
var spell3Strength = 0;
var spell1Duration = 0;
var spell2Duration = 0;
var spell3Duration = 0;

function init()
{
	spawnMonster();
	$.ajax(
	{
        type: "POST",
        url: "backend/getRoundData.php",
        dataType:"JSON",
        success: function(response)
        {
			currentRound = response.round;
			currentSubRound = response.subRound;
			updateRound();
                        if (currentRound > 50) {
                            $("#btnReawaken").show();
                        } else {
                            $("#btnReawaken").hide();
                        }
        }
	});
	upgrade(null);
	upgradeSpell(null);
	createItemTable();
}
function spawnMonster()
{
	$.ajax(
	{
        type: "POST",
        url: "backend/spawnMonster.php",
        dataType:"JSON",
        success: function(response)
        {
			$("#gameMonsterName").text(response.name);
			$("#gameMonsterImg").attr("src", response.image);
			monsterHealthCurrent = response.health;
			monsterHealthMax = response.health;
			$("#healthSlider").slider("option", "max", response.health);
			updateHealth();
			if (response.isBoss == true)
			{
				$("#timeSlider").show();
                timerValue = 46;
                timerInterval = setInterval(function(){timerTick()}, 1000);
			}
			awaitingSpawnResponse = false;
        }
	});
}

function createItemTable()
{
	$.ajax(
	{
        type: "POST",
        url: "backend/createItemTable.php",
        dataType:"JSON",
        success: function(response)
        {
			$("#tableContainer").empty();		
            $("#tableContainer").append(response.table);
        }
	});
}

function timerTick()
{
	timerValue--;
	$("#timeSlider").slider("option", "values", [0, timerValue]);
	if (timerValue == 0)
	{
		killMonster();
		awaitingSpawnResponse = true;
	}
}

function killMonster()
{
	$("#timeSlider").hide();
    clearInterval(timerInterval);
	$.ajax(
	{
        type: "POST",
        url: "backend/killMonster.php",
        dataType:"JSON",
        success: function(response)
        {
			gold = response.gold;
			emeralds = response.emeralds;
			experience = response.experience;
			currentRound = response.round;
			currentSubRound = response.subRound;
			updateGold();
			updateEmeralds();
			updateExperience();
			updateRound();
			spawnMonster();
            createItemTable();
        }
	});
}

function updateRound()
{
	$("#gameRound").text(currentRound);
	$("#gameSubRound").text(currentSubRound);
}

function updateGold()
{
	var roundedGold = Math.floor(Number(gold)*100)/100;
        var numeralGold = numeral(roundedGold).format('0.00a');
	$("#gameGold").text(numeralGold);
}

function updateEmeralds()
{
	$("#gameEmeralds").text(emeralds);
}

function updateExperience()
{
	$("#gameExperience").text(experience);
}

function updateHealth()
{       
	$("#healthSlider").slider("option", "values", [0, monsterHealthCurrent]);
	var roundedHealth = Math.ceil(Number(monsterHealthCurrent)*100)/100;
        var numRoundedHealth = numeral(roundedHealth).format('0.0a');
        var numMonsterMaxHealth = numeral(monsterHealthMax).format('0.0a');
	$("#gameMonsterHealth").text(numRoundedHealth+"/"+numMonsterMaxHealth);
}

function attackMonster()
{
	var critRand = Math.random()*100;
	var critMultiplier = 1;
	var chance = criticalHitChance;
	var critDamage = criticalHitDamage;
	if (spell2Duration>0)
	{
		chance+=spell2Strength;
	}
	if (spell3Duration>0)
	{
		critDamage+=spell3Strength;
	}
	if(chance > critRand)
	{
		critMultiplier = critDamage/100;
	}
	var spellMultiplier = 1;
	if (spell1Duration > 0)
	{
		spellMultiplier = 1+(spell1Strength/100);
	}
	monsterHealthCurrent-=(damage*attackSpeed*critMultiplier*spellMultiplier);
	if (monsterHealthCurrent <= 0)
	{
		if (awaitingSpawnResponse == false)
		{
			killMonster();
			awaitingSpawnResponse = true;
		}
	}
	else
	{
		updateHealth();
	}
}
$(document).on("click", "#btnGameDamage", function(){
	upgrade("damage");
});
$(document).on("click", "#btnGameAttackSpeed", function(){
	upgrade("attackSpeed");
});
$(document).on("click", "#btnGameCriticalHitChance", function(){
	upgrade("criticalHitChance");
});
$(document).on("click", "#btnGameCriticalHitDamage", function(){
	upgrade("criticalHitDamage");
});
$(document).on("click", "#btnGameEnergy", function(){
	upgrade("energy");
});
$(document).on("click", "#btnGameEnergyRegen", function(){
	upgrade("energyRegen");
});

function upgrade(stat)
{
    $.ajax({
        type: "POST",
        url: "backend/upgrade.php",
        data: {"type": stat},
        dataType:"JSON",
        success: function(response)
        {
        	if (response.success == true)
        	{
        		gold = response.gold;
        		damage = response.damage;
        		attackSpeed = response.attackSpeed;
        		criticalHitChance = response.criticalHitChance;
        		criticalHitDamage = response.criticalHitDamage;
        		energy = response.energy;
        		energyRegen = response.energyRegen;
        		updateGold();
        		updateStats(response.damagePrice, response.attackSpeedPrice, response.criticalHitChancePrice, response.criticalHitDamagePrice, response.energyPrice, response.energyRegenPrice);
        	}
        }
	});
}

function updateStats(damagePrice, attackSpeedPrice, criticalHitChancePrice, criticalHitDamagePrice, energyPrice, energyRegenPrice)
{
        var numDMGprice = numeral(damagePrice).format('0.0a');
        var numASprice = numeral(attackSpeedPrice).format('0.0a');
        var numCHCprice = numeral(criticalHitChancePrice).format('0.0a');
        var numCHDprice = numeral(criticalHitDamagePrice).format('0.0a');
        var numENEprice = numeral(energyPrice).format('0.0a');
        var numENEREGENprice = numeral(energyRegenPrice).format('0.0a');
    
	$("#gameDamage").text(damage);
	$("#gameAttackSpeed").text(attackSpeed);
	$("#gameCriticalHitChance").text(criticalHitChance);
	$("#gameCriticalHitDamage").text(criticalHitDamage);
	updateEnergy();
	$("#gameEnergyRegen").text(energyRegen);
        $("#btnGameDamage").attr('data-original-title', numDMGprice + " gold");
        $("#btnGameAttackSpeed").attr('data-original-title', numASprice + " gold");
        $("#btnGameCriticalHitChance").attr('data-original-title', numCHCprice + " gold");
        $("#btnGameCriticalHitDamage").attr('data-original-title', numCHDprice + " gold");
        $("#btnGameEnergy").attr('data-original-title', numENEprice + " gold");
        $("#btnGameEnergyRegen").attr('data-original-title', numENEREGENprice + " gold");
}
init();

$(document).on("click", ".itemCell", function()
{
	var id = $(this).attr("id");
	id = id.substring(4);
	$.ajax(
	{
        type: "POST",
        url: "backend/createItemDetails.php",
        dataType:"JSON",
        data: {"itemId": id},
        success: function(response)
        {
            $("#itemDetailsContainer").empty();		
            $("#itemDetailsContainer").css("border-left", "1px solid white");
            $("#itemDetailsContainer").append(response.details);
        }
	});
        
});

$(document).on("click", ".btnItemSwitch", function()
{
	var id = $(this).attr("id");
	id = id.substring(7);
	$.ajax({	
	    type: "POST",
	    url: "backend/switchItem.php",
	    dataType:"JSON",
	    data: {"itemId": id},
	    success: function(response)
	    {
			createItemTable();
                        upgrade(null);
	    }
	});
});

$(document).on("click", ".btnItemDelete", function()
{
	var id = $(this).attr("id");
	id = id.substring(13);
	$.ajax({	
	    type: "POST",
	    url: "backend/deleteItem.php",
	    dataType:"JSON",
	    data: {"itemId": id},
	    success: function(response)
	    {
			createItemTable();
                        upgrade(null);
	    }
	});
});

$(document).on("click", ".spellUpgrade", function(){
	var id = $(this).attr("id");
	id = id.substring(12);
	upgradeSpell(id);

});

function upgradeSpell(id)
{
	$.ajax({	
	    type: "POST",
	    url: "backend/upgradeSpell.php",
	    dataType:"JSON",
	    data: {"spellId": id},
	    success: function(response)
	    {
	    updateSpells(response.spell1Price, response.spell2Price, response.spell3Price, response.spell6Price, response.spell7Price, response.spell8Price);
            experience = response.experience;
            spell1Strength = response.spell1Strength;
            spell2Strength = response.spell2Strength;
            spell3Strength = response.spell3Strength;
            updateExperience();
	    }

	});
}

function updateSpells(s1Price, s2Price, s3Price, s6Price, s7Price, s8Price)
{
	$("#spellUpgrade1").attr('data-original-title', "Upgrade cost: " + s1Price + " exp");
	$("#spellUpgrade2").attr('data-original-title', "Upgrade cost: " + s2Price + " exp");
	$("#spellUpgrade3").attr('data-original-title', "Upgrade cost: " + s3Price + " exp");
	$("#spellUpgrade6").attr('data-original-title', "Upgrade cost: " + s6Price + " exp");
	$("#spellUpgrade7").attr('data-original-title', "Upgrade cost: " + s7Price + " exp");
	$("#spellUpgrade8").attr('data-original-title', "Upgrade cost: " + s8Price + " exp");
}

function updateEnergy()
{
	$("#gameEnergy").text(Math.floor(currentEnergy*100)/100+"/"+energy);
}

function regenerateEnergy()
{
	currentEnergy+=Number(energyRegen);
	if (currentEnergy>energy)
	{
		currentEnergy = energy;
	}
}
setInterval(function(){
	regenerateEnergy();
	updateEnergy();
	decreaseCooldowns();
}, 1000);

var spell1Cooldown = 300;
var spell2Cooldown = 300;
var spell3Cooldown = 300;
var spell6Cooldown = 1800;
var spell7Cooldown = 1800;
var spell8Cooldown = 3600;

function decreaseCooldowns()
{
	spell1Cooldown = spell1Cooldown == 0 ? 0 : spell1Cooldown-1;
	spell2Cooldown = spell2Cooldown == 0 ? 0 : spell2Cooldown-1;
	spell3Cooldown = spell3Cooldown == 0 ? 0 : spell3Cooldown-1;
	spell6Cooldown = spell6Cooldown == 0 ? 0 : spell6Cooldown-1;
	spell7Cooldown = spell7Cooldown == 0 ? 0 : spell7Cooldown-1;
	spell8Cooldown = spell8Cooldown == 0 ? 0 : spell8Cooldown-1;
	spell1Duration = spell1Duration == 0 ? 0 : spell1Duration-1;
	spell2Duration = spell2Duration == 0 ? 0 : spell2Duration-1;
	spell3Duration = spell3Duration == 0 ? 0 : spell3Duration-1;
	$("#spellCooldown1").text(spell1Cooldown);
	$("#spellCooldown2").text(spell2Cooldown);
	$("#spellCooldown3").text(spell3Cooldown);
	$("#spellCooldown6").text(spell6Cooldown);
	$("#spellCooldown7").text(spell7Cooldown);
	$("#spellCooldown8").text(spell8Cooldown);
}

$(document).on("click", "#spell1", function(){
	if (spell1Cooldown == 0 && currentEnergy >= 60)
	{
		spell1Cooldown = 300;
		currentEnergy -= 60;
		spell1Duration = 30;
	}
});

$(document).on("click", "#spell2", function(){
	if (spell2Cooldown == 0 && currentEnergy >= 80)
	{
		spell2Cooldown = 300;
		currentEnergy -= 80;
		spell2Duration = 30;
	}
});
$(document).on("click", "#spell3", function(){
	if (spell3Cooldown == 0 && currentEnergy >= 80)
	{
		spell3Cooldown = 300;
		currentEnergy -= 80;
		spell3Duration = 30;
	}
});
$(document).on("click", "#spell6", function(){
	if (spell6Cooldown == 0 && currentEnergy >= 150)
	{
		spell6Cooldown = 1800;
		currentEnergy -= 150;
		$.ajax({	
		    type: "POST",
		    url: "backend/spell6.php",
		    dataType:"JSON",
		    success: function(response)
		    {
		    	experience = response.experience;
		    	updateExperience();
		    }
		});
	}
});
$(document).on("click", "#spell7", function(){
	if (spell7Cooldown == 0 && currentEnergy >= 150)
	{
		spell7Cooldown = 1800;
		currentEnergy -= 150;
		$.ajax({	
		    type: "POST",
		    url: "backend/spell7.php",
		    dataType:"JSON",
		    success: function(response)
		    {
		    	gold = response.gold;
		    	updateGold();
		    }
		});
	}
});
$(document).on("click", "#spell8", function(){
	if (spell8Cooldown == 0 && currentEnergy >= 200)
	{
		spell8Cooldown = 3600;
		currentEnergy -= 200;
		$.ajax({	
		    type: "POST",
		    url: "backend/spell8.php",
		    dataType:"JSON",
		    success: function(response)
		    {
		    	emeralds = response.emeralds;
		    	updateEmeralds();
		    }
		});
	}
});
$(document).on("click", "#btnReawaken", function(){
	$.ajax({	
	    type: "POST",
	    url: "backend/ascend.php",
	    dataType:"JSON",
	    success: function(response)
	    {
	    	if (response.success == true)
	    	{
		    	gold = 0;
		    	experience = 0;
		    	damage = response.damage;
		    	attackSpeed = 1;
		    	criticalHitChance = response.criticalHitChance;
		    	criticalHitDamage = response.critialHitDamage;
		    	energy = 100;
		    	currentEnergy = 0;
		    	createItemTable();
		    	updateGold();
		    	spawnMonster();
		    	upgrade(null);
		    	upgradeSpell(null);
	    	}

	    }
	});
});