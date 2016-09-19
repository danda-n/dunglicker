<?php

require_once("connectToDatabase.php");

$response = new stdClass;

session_start();
$userId = $_SESSION["userId"];


$sql = "SELECT experience, spell6Purchases FROM gameData WHERE gameData.userId = '$userId' LIMIT 1";
$result = $connection->query($sql);
$row = $result->fetch_assoc();

$experience = $row["experience"];
$spell6Purchases = $row["spell6Purchases"];

$bonusExperiencePercent = 0;
switch ($spell6Purchases) 
{
    case "0":
        $bonusExperiencePercent=10;
        break;
    case "1":
        $bonusExperiencePercent=15;
        break;
    case "2":
        $bonusExperiencePercent=25;
        break;
    case "3":
        $bonusExperiencePercent=50;
        break;
    case "4":
        $bonusExperiencePercent=75;
        break;
    case "5":
        $bonusExperiencePercent=100;
        break;
    case "6":
        $bonusExperiencePercent=125;
        break;
    case "7":
        $bonusExperiencePercent=150;
        break;
    case "8":
        $bonusExperiencePercent=175;
        break;
    case "9":
        $bonusExperiencePercent=200;
        break;
    case "10":
        $bonusExperiencePercent=300;
        break;
    default:
        $bonusExperiencePercent=300;
        break;
}

$experience*=($bonusExperiencePercent/100+1);

$sql = "UPDATE gameData SET experience = '$experience' WHERE gameData.userId = '$userId'";
$connection->query($sql);

$response->experience=$experience;

echo(json_encode($response));

$connection->close();
?>