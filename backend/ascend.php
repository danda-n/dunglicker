<?php

require_once("connectToDatabase.php");

$response = new stdClass;

session_start();
$userId = $_SESSION["userId"];

$sql = "SELECT id, currentRound, reawakeningCount FROM gameData WHERE userId = '$userId'";
$result = $connection->query($sql);
$row = mysqli_fetch_assoc($result);
$gameDataId = $row["id"];
$reawakeningCount = $row["reawakeningCount"];
$reawakeningCount++;
$damage = 10+10*$reawakeningCount;
$chc = 2*$reawakeningCount;
$chd = 150+10*$reawakeningCount;
$expBonus = 10*$reawakeningCount;
$emeraldsBonus = $reawakeningCount;

$currentRound = $row["currentRound"];
if ($currentRound >= 50)
{
    $sql = "DELETE FROM item WHERE gameDataId = '$gameDataId'";
    $connection->query($sql);

    $sql = "UPDATE gameData SET currentRound = 1, currentSubRound = 1, gold = 0, goldBonus = 0, experience = 0, experienceBonus = '$expBonus', emeraldBonus = '$emeraldsBonus', damage = '$damage', damagePurchases = 0, attackSpeed = 1, attackSpeedPurchases = 0, criticalHitChance = '$chc', criticalHitChancePurchases = 0, criticalHitDamage = '$chd', criticalHitDamagePurchases = 0, energy = 100, energyPurchases = 0, energyRegen = 1, energyRegenPurchases = 0, spell1Purchases = 0, spell2Purchases = 0, spell3Purchases = 0, spell6Purchases = 0, spell7Purchases = 0, spell8Purchases = 0, reawakeningCount = '$reawakeningCount' WHERE gameData.userId = '$userId'";
    $connection->query($sql);

    $response->success=true;
    $response->damage = $damage;
    $response->criticalHitChance = $chc;
    $response->criticalHitDamage = $chd;
}
else
{
    $response->success=false;
}   
echo(json_encode($response));

$connection->close();
?>