<?php

require_once("connectToDatabase.php");

$response = new stdClass;
$type = mysqli_real_escape_string($connection, $_POST["type"]);

session_start();
$userId = $_SESSION["userId"];


$sql = "SELECT gold, damage, attackSpeed, criticalHitChance, criticalHitDamage, energy, energyRegen, damagePurchases, attackSpeedPurchases, criticalHitChancePurchases, criticalHitDamagePurchases, energyPurchases, energyRegenPurchases FROM gameData WHERE gameData.userId = '$userId' LIMIT 1";
$result = $connection->query($sql);
$row = $result->fetch_assoc();
$gold = $row["gold"];
$damage = $row["damage"];
$attackSpeed = $row["attackSpeed"];
$criticalHitChance = $row["criticalHitChance"];
$criticalHitDamage = $row["criticalHitDamage"];
$energy = $row["energy"];
$energyRegen = $row["energyRegen"];

$damagePurchases = $row["damagePurchases"];
$attackSpeedPurchases = $row["attackSpeedPurchases"];
$criticalHitChancePurchases = $row["criticalHitChancePurchases"];
$criticalHitDamagePurchases = $row["criticalHitDamagePurchases"];
$energyPurchases = $row["energyPurchases"];
$energyRegenPurchases = $row["energyRegenPurchases"];

$damagePrice = 25*pow(1.10, $damagePurchases);
$attackSpeedPrice = 75*pow(1.75, $attackSpeedPurchases);
$criticalHitChancePrice = 66666*pow(1.83, $criticalHitChancePurchases);
$criticalHitDamagePrice = 100000*pow(1.80, $criticalHitDamagePurchases);
$energyPrice = 25000*pow(1.40, $energyPurchases);
$energyRegenPrice = 40000*pow(1.15, $energyRegenPurchases);

switch($type)
{
    case "damage":
        if ($gold > $damagePrice)
        {        
            $gold-=$damagePrice;
            $damage+=floor($damagePurchases/10)+1;
            $damagePurchases++;
            $damagePrice = 25*pow(1.10, $damagePurchases);
        }
    break;
    case "attackSpeed":
        if ($gold > $attackSpeedPrice)
        {
            $gold-=$attackSpeedPrice;
            $attackSpeed+=0.075;
            $attackSpeedPurchases++;
            $attackSpeedPrice = 75*pow(1.75, $attackSpeedPurchases);
        }
    break;
    case "criticalHitChance":
        if ($gold > $criticalHitChancePrice)
        {
            $gold-=$criticalHitChancePrice;
            $criticalHitChance++;
            $criticalHitChancePurchases++;
            $criticalHitChancePrice = 66666*pow(1.83, $criticalHitChancePurchases);
        }
    break;
    case "criticalHitDamage":
        if ($gold > $criticalHitDamagePrice)
        {
            $gold-=$criticalHitDamagePrice;
            $criticalHitDamage+=10;
            $criticalHitDamagePurchases++;
            $criticalHitDamagePrice = 100000*pow(1.80, $criticalHitDamagePurchases);
        }
    break;
    case "energy":
        if ($gold > $energyPrice)
        {        
            $gold-=$energyPrice;
            $energy+=10;
            $energyPurchases++;
            $energyPrice = 25000*pow(1.40, $energyPurchases);
        }
    break;
    case "energyRegen":
        if ($gold > $energyRegenPrice)
        {        
            $gold-=$energyRegenPrice;
            $energyRegen+=0.15;
            $energyRegenPurchases++;
            $energyRegenPrice = 40000*pow(1.15, $energyRegenPurchases);
        }
    break;
}

$sql = "UPDATE gameData SET gold = '$gold', damage = '$damage', attackSpeed = '$attackSpeed', criticalHitChance = '$criticalHitChance', criticalHitDamage = '$criticalHitDamage', energy = '$energy', energyRegen = '$energyRegen', damagePurchases = '$damagePurchases', attackSpeedPurchases = '$attackSpeedPurchases', criticalHitChancePurchases = '$criticalHitChancePurchases', criticalHitDamagePurchases = '$criticalHitDamagePurchases', energyPurchases = '$energyPurchases', energyRegenPurchases = '$energyRegenPurchases' WHERE gameData.userId = '$userId'";
$connection->query($sql);

$sql = "SELECT id FROM gameData WHERE userId = '$userId'";
$result = $connection->query($sql);
$row = mysqli_fetch_assoc($result);
$gameDataId = $row["id"];

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 2"; //% damage
$itemPercentageDamageBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemPercentageDamageBonus += $row["value"];
}
$damage*=(1+($itemPercentageDamageBonus/100));

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 1"; //Flat damage
$itemFlatDamageBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemFlatDamageBonus += $row["value"];
}
$damage+=$itemFlatDamageBonus;

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 4"; //% AS
$itemPercentageAttackSpeedBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemPercentageAttackSpeedBonus += $row["value"];
}
$attackSpeed*=(1+($itemPercentageAttackSpeedBonus/100));

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 3"; //Flat AS
$itemFlatAttackSpeedBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemFlatAttackSpeedBonus += $row["value"];
}
$attackSpeed+=$itemFlatAttackSpeedBonus;

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 5"; //Flat critical hit chance
$itemFlatCriticalHitChanceBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemFlatCriticalHitChanceBonus += $row["value"];
}
$criticalHitChance+=$itemFlatCriticalHitChanceBonus;

$sql = "SELECT * FROM item, itemStat WHERE item.gameDataId = '$gameDataId' AND item.isEquipped = 1 AND itemStat.itemId = item.id AND itemStat.itemStatNameId = 6"; //Flat critical hit damage
$itemFlatCriticalHitDamageBonus = 0;
$result = $connection->query($sql);
while ($row = mysqli_fetch_assoc($result))
{
    $itemFlatCriticalHitDamageBonus += $row["value"];
}
$criticalHitDamage+=$itemFlatCriticalHitDamageBonus;

$response->success=true;
$response->gold=$gold;
$response->damage=$damage;
$response->attackSpeed=$attackSpeed;
$response->criticalHitChance=$criticalHitChance;
$response->criticalHitDamage=$criticalHitDamage;
$response->energy=$energy;
$response->energyRegen=$energyRegen;
$response->damagePrice=$damagePrice;
$response->attackSpeedPrice=$attackSpeedPrice;
$response->criticalHitChancePrice=$criticalHitChancePrice;
$response->criticalHitDamagePrice=$criticalHitDamagePrice;
$response->energyPrice=$energyPrice;
$response->energyRegenPrice=$energyRegenPrice;
echo(json_encode($response));

$connection->close();
?>