<?php

require_once("connectToDatabase.php");

$response = new stdClass;

session_start();
$userId = $_SESSION["userId"];


$sql = "SELECT gold, spell7Purchases FROM gameData WHERE gameData.userId = '$userId' LIMIT 1";
$result = $connection->query($sql);
$row = $result->fetch_assoc();

$gold = $row["gold"];
$spell7Purchases = $row["spell7Purchases"];

$bonusGoldPercent = 0;
switch ($spell7Purchases) 
{
    case "0":
        $bonusGoldPercent=20;
        break;
    case "1":
        $bonusGoldPercent=25;
        break;
    case "2":
        $bonusGoldPercent=50;
        break;
    case "3":
        $bonusGoldPercent=75;
        break;
    case "4":
        $bonusGoldPercent=100;
        break;
    case "5":
        $bonusGoldPercent=125;
        break;
    case "6":
        $bonusGoldPercent=150;
        break;
    case "7":
        $bonusGoldPercent=175;
        break;
    case "8":
        $bonusGoldPercent=200;
        break;
    case "9":
        $bonusGoldPercent=250;
        break;
    case "10":
        $bonusGoldPercent=300;
        break;
    default:
        $bonusGoldPercent=300;
        break;
}

$gold*=($bonusGoldPercent/100+1);

$sql = "UPDATE gameData SET gold = '$gold' WHERE gameData.userId = '$userId'";
$connection->query($sql);

$response->gold=$gold;

echo(json_encode($response));

$connection->close();
?>