<?php

require_once("connectToDatabase.php");

$response = new stdClass;
$type = mysqli_real_escape_string($connection, $_POST["spellId"]);

session_start();
$userId = $_SESSION["userId"];


$sql = "SELECT spell1Purchases, spell2Purchases, spell3Purchases, spell4Purchases, spell5Purchases, spell6Purchases, spell7Purchases, spell8Purchases, experience FROM gameData WHERE gameData.userId = '$userId' LIMIT 1";
$result = $connection->query($sql);
$row = $result->fetch_assoc();

$experience = $row["experience"];
$spell1Purchases = $row["spell1Purchases"];
$spell2Purchases = $row["spell2Purchases"];
$spell3Purchases = $row["spell3Purchases"];
$spell4Purchases = $row["spell4Purchases"];
$spell5Purchases = $row["spell5Purchases"];
$spell6Purchases = $row["spell6Purchases"];
$spell7Purchases = $row["spell7Purchases"];
$spell8Purchases = $row["spell8Purchases"];

$spell1Price = 1000*pow(3.5, $spell1Purchases);
$spell2Price = 5000*pow(5.5, $spell2Purchases);
$spell3Price = 5000*pow(3.8, $spell3Purchases);
$spell4Price = 10000*pow(3.5, $spell4Purchases);
$spell5Price = 10000*pow(3.5, $spell5Purchases);
$spell6Price = 25000*pow(3.5, $spell6Purchases);
$spell7Price = 30000*pow(3.5, $spell7Purchases);
$spell8Price = 100000*pow(6.85, $spell8Purchases);

switch ($type)
{
    case "1":
    if ($experience > $spell1Price)
        {        
            $experience-=$spell1Price;
            $spell1Purchases++;
            $spell1Price = 1000*pow(3.5, $spell1Purchases);
        }
    break;
    case "2":
    if ($experience > $spell2Price)
        {        
            $experience-=$spell1Price;
            $spell2Purchases++;
            $spell2Price = 5000*pow(5.5, $spell2Purchases);
        }
    break;
    case "3":
    if ($experience > $spell3Price)
        {        
            $experience-=$spell3Price;
            $spell3Purchases++;
            $spell3Price = 5000*pow(3.8, $spell3Purchases);
        }
    break;
    case "4":
        if ($experience > $spell4Price)
        {        
            $experience-=$spell4Price;
            $spell4Purchases++;
            $spell4Price = 10000*pow(3.5, $spell4Purchases);
        }
    break;
    case "5":
        if ($experience > $spell5Price)
        {        
            $experience-=$spell5Price;
            $spell5Purchases++;
            $spell5Price = 10000*pow(3.5, $spell5Purchases);
        }
    break;
    case "6":
        if ($experience > $spell6Price)
        {        
            $experience-=$spell6Price;
            $spell6Purchases++;
            $spell6Price = 25000*pow(3.5, $spell6Purchases);
        }
    break;
    case "7":
        if ($experience > $spell7Price)
        {        
            $experience-=$spell7Price;
            $spell7Purchases++;
            $spell7Price = 30000*pow(3.5, $spell7Purchases);
        }
    break;
    case "8":
        if ($experience > $spell8Price)
        {        
            $experience-=$spell8Price;
            $spell8Purchases++;
            $spell8Price = 100000*pow(6.85, $spell8Purchases);
        }
    break;
}

$spell1StrengthArray = array(10,10,20,30,40,50,75,100,150,250,400);
$spell2StrengthArray = array(5,10,20,30,40,50,60,70,80,90,100);
$spell3StrengthArray = array(10,15,25,50,100,150,200,250,300,400,500);

$sql = "UPDATE gameData SET experience = '$experience', spell1Purchases = '$spell1Purchases', spell2Purchases = '$spell2Purchases', spell3Purchases = '$spell3Purchases', spell4Purchases = '$spell4Purchases', spell5Purchases = '$spell5Purchases', spell6Purchases = '$spell6Purchases', spell7Purchases = '$spell7Purchases', spell8Purchases = '$spell18Purchases' WHERE gameData.userId = '$userId'";
$connection->query($sql);

$response->spell1Price=$spell1Price;
$response->spell2Price=$spell2Price;
$response->spell3Price=$spell3Price;
$response->spell4Price=$spell4Price;
$response->spell5Price=$spell5Price;
$response->spell6Price=$spell6Price;
$response->spell7Price=$spell7Price;
$response->spell8Price=$spell8Price;
$response->spell1Strength=$spell1StrengthArray[$spell1Purchases];
$response->spell2Strength=$spell2StrengthArray[$spell2Purchases];
$response->spell3Strength=$spell3StrengthArray[$spell3Purchases];

echo(json_encode($response));

$connection->close();
?>