<?php

require_once("connectToDatabase.php");

$response = new stdClass;

session_start();
$userId = $_SESSION["userId"];


$sql = "SELECT emeralds, spell8Purchases FROM gameData WHERE gameData.userId = '$userId' LIMIT 1";
$result = $connection->query($sql);
$row = $result->fetch_assoc();

$emeralds = $row["emeralds"];
$spell8Purchases = $row["spell8Purchases"];

$bonusEmeraldsPercent = 0;
switch ($spell8Purchases) 
{
    case "0":
        $bonusEmeraldsPercent=5;
        break;
    case "1":
        $bonusEmeraldsPercent=10;
        break;
    case "2":
        $bonusEmeraldsPercent=15;
        break;
    case "3":
        $bonusEmeraldsPercent=20;
        break;
    case "4":
        $bonusEmeraldsPercent=25;
        break;
    case "5":
        $bonusEmeraldsPercent=30;
        break;
    case "6":
        $bonusEmeraldsPercent=35;
        break;
    case "7":
        $bonusEmeraldsPercent=40;
        break;
    case "8":
        $bonusEmeraldsPercent=45;
        break;
    case "9":
        $bonusEmeraldsPercent=50;
        break;
    case "10":
        $bonusEmeraldsPercent=75;
        break;
    default:
        $bonusEmeraldsPercent=75;
        break;
}

$emeralds*=($bonusEmeraldsPercent/100+1);

$sql = "UPDATE gameData SET emeralds = '$emeralds' WHERE gameData.userId = '$userId'";
$connection->query($sql);

$response->emeralds=$emeralds;

echo(json_encode($response));

$connection->close();
?>