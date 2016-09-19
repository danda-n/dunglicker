<?php 
	require_once("connectToDatabase.php");

	session_start();
	$response = new stdClass();
	$userId = $_SESSION["userId"];
	$sql = "SELECT id, currentRound, currentSubRound, gold, emeralds, experience, lastSpawnTime, damage, attackSpeed, criticalHitDamage FROM gameData WHERE userId = '$userId' LIMIT 1";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);

	$gameDataId = $row["id"];

	$currentRound = $row["currentRound"];
	$currentSubRound = $row["currentSubRound"];
	$lastSpawnTime = $row["lastSpawnTime"];

	$damage = $row["damage"];
	$attackSpeed = $row["attackSpeed"];
	$criticalHitDamage = $row["criticalHitDamage"];
	$criticalHitMultiplier = $criticalHitDamage/100;

	$currentGold = $row["gold"];
	$currentEmeralds = $row["emeralds"];
	$experience = $row["experience"];

	$minGold = 2.25*pow(1.4, $currentRound-1);
	$maxGold = 5*pow(1.4, $currentRound-1);
	$randGold = mt_rand($minGold, $maxGold);

	$minExp = 1.05*pow(1.05, $currentRound-1);
	$maxExp = 1.4*pow(1.05, $currentRound-1);
	$randExp = mt_rand($minExp, $maxExp);

	$sql = "SELECT id FROM gameData WHERE userId = '$userId'";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);
	$gameDataId = $row["id"];

	$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 7"; //% gold bonus
	$itemGoldBonus = 0;
	$result = $connection->query($sql);
	while ($row = mysqli_fetch_assoc($result))
	{
	    $itemGoldBonus += $row["value"];
	}
	$randGold*=(1+($itemGoldBonus/100));

	$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 8"; //% experience bonus
	$itemExperienceBonus = 0;
	$result = $connection->query($sql);
	while ($row = mysqli_fetch_assoc($result))
	{
	    $itemExperienceBonus += $row["value"];
	}
	$randExp*=(1+($itemExperienceBonus/100));

	$emeraldChance = 1;

	$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 9"; //emerald chance bonus
	$emeraldChanceBonus = 0;
	$result = $connection->query($sql);
	while ($row = mysqli_fetch_assoc($result))
	{
	    $emeraldChanceBonus += $row["value"];
	}
	$emeraldChance+=$emeraldChanceBonus;


	$emeraldRand = mt_rand(1, 100);
	$isEmeraldDrop = false;
	if ($emeraldRand <= $emeraldChance)
	{
		$isEmeraldDrop = true;
	}


	$lastSpawnTime = new Datetime($lastSpawnTime);
	$nowTime = new Datetime();
	$timeDifference = $nowTime->getTimestamp() - $lastSpawnTime->getTimestamp();

	if ($currentRound%5==0)
	{
		$monsterHealth = 300*pow(2, floor(($currentRound-1)/5));
		$minSeconds = $monsterHealth/($damage*$attackSpeed*14*$criticalHitMultiplier*100);
		if ($minSeconds<$timeDifference)
		{
			if ($timeDifference <= 20)
			{
				$currentGold+=($randGold*3);
				$experience+=$randExp;
				if ($isEmeraldDrop == true)
				{
					$currentEmeralds++;
				}
				$currentRound++;
				$currentSubRound=1;
			}
			else
			{
				$currentRound--;
				$currentSubRound=1;
			}
		}
		else
		{
			$response->message="Autoclicker detected.";
		}	
	}
	else
	{
		$monsterHealth = 100*pow(2, floor(($currentRound-1)/5));
		$minSeconds = $monsterHealth/($damage*$attackSpeed*14*$criticalHitMultiplier*100);
		if ($minSeconds<$timeDifference)
		{
			$currentGold+=$randGold;
			$experience+=$randExp;
			$currentSubRound++;
			if ($isEmeraldDrop == true)
			{
				$currentEmeralds++;
			}
			if($currentSubRound == 11)
			{
				$currentRound++;
				$currentSubRound=1;
			}
		}
		else
		{
			$response->message="Autoclicker detected.";
		}
	}

	$lootRand = mt_rand(1, 100);
	if ($lootRand > 95)
	{
		$rarityRand = mt_rand(1, 10000);
		$rarityId = 1;
		if ($rarityRand < 25)
		{
			$rarityId = 5;
		}
		else if($rarityRand >= 25 && $rarityRand < 125)
		{
			$rarityId = 4;
		}
		else if($rarityRand >= 125 && $rarityRand < 350)
		{
			$rarityId = 3;
		}
		else if($rarityRand >= 350 && $rarityRand < 850)
		{
			$rarityId = 2;
		}
		else
		{
			$rarityId = 1;
		}

		$sql = "SELECT name FROM itemPrefix WHERE itemRarityId = '$rarityId' ORDER BY RAND() LIMIT 1";
		$result = $connection->query($sql);
		$row = mysqli_fetch_assoc($result);

		$prefix = $row["name"];

		$sql = "SELECT id, name FROM itemStatName ORDER BY RAND() LIMIT $rarityId";
		$result = $connection->query($sql);

		$affix = "";

		$itemStatArray = array();
		$itemStatArray2 = array();
	    while ($row = $result->fetch_assoc()) 
	    {
        	if ($affix == "")
        	{
        		$affix = $row["name"];
        	}
        	$itemStatNameId = $row["id"];
        	$sql = "SELECT min, max FROM itemStatBounds WHERE itemStatNameId = '$itemStatNameId' AND itemRarityId = '$rarityId' LIMIT 1";
        	$innerResult = $connection->query($sql);
        	$innerRow = $innerResult->fetch_assoc();
        	$min = $innerRow["min"];
        	$max = $innerRow["max"];
        	$value = mt_rand($min*100, $max*100)/100;
        	array_push($itemStatArray, $itemStatNameId);
        	array_push($itemStatArray2, $value);
    	}
    	$result->free();

		$sql = "SELECT id, name FROM itemImage ORDER BY RAND() LIMIT 1";
		$result = $connection->query($sql);
		$row = mysqli_fetch_assoc($result);
		$imageId = $row["id"];
		$imageCoreName = $row["name"];

    	$name = $prefix . " " . $imageCoreName . " of " . $affix;
    	$sql = "INSERT INTO item (gameDataId, name, isEquipped, itemImageId, itemRarityId) VALUES ('$gameDataId', '$name', 0, '$imageId', '$rarityId')";
    	$connection->query($sql);
    	$itemId = $connection->insert_id;

    	for ($i=0; $i < count($itemStatArray); $i++) 
    	{ 
	    	$statNameId = $itemStatArray[$i];
	    	$statValue = $itemStatArray2[$i];
	    	$sql = "INSERT INTO itemStat (itemId, itemStatNameId, value) VALUES ('$itemId', '$statNameId', '$statValue')";
    		$connection->query($sql);
    	}
	}

	$sql = "UPDATE gameData SET currentRound = '$currentRound', currentSubRound = '$currentSubRound', gold = '$currentGold', emeralds = '$currentEmeralds', experience = '$experience' WHERE gameData.userId = '$userId'";
	$connection->query($sql);

	$response->round = $currentRound;
	$response->subRound = $currentSubRound;
	$response->gold = $currentGold;
	$response->emeralds = $currentEmeralds;
	$response->experience = $experience;
	echo(json_encode($response));
 ?>