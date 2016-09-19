<?php 
	require_once("connectToDatabase.php");
	$response = new stdClass();
	session_start();

	$sql = "SELECT imageLink FROM monsterImage ORDER BY RAND() LIMIT 1";
	$result = $connection->query($sql);
	$row = mysqli_fetch_row($result);
	$image = $row[0];

	$userId = $_SESSION["userId"];
	$sql = "SELECT currentRound, currentSubRound, gold FROM gameData WHERE userId = '$userId' LIMIT 1";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);

	$currentRound = $row["currentRound"];
	$currentSubRound = $row["currentSubRound"];

	$monsterHealth = 100*pow(2, floor(($currentRound-1)/5));
	$response->isBoss = false;
	if ($currentRound%5==0)
	{
		$response->isBoss = true;
		$monsterHealth = 300*pow(2, floor(($currentRound-1)/5));
	}

	$sql = "UPDATE gameData SET lastSpawnTime = CURRENT_TIMESTAMP WHERE gameData.userId = '$userId'";
	$connection->query($sql);

	$response->image = $image;
	$response->health = $monsterHealth;
	echo(json_encode($response));
 ?>