<?php 
	require_once("connectToDatabase.php");

	session_start();

	$userId = $_SESSION["userId"];
	$sql = "SELECT currentRound, currentSubRound FROM gameData WHERE userId = '$userId' LIMIT 1";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);

	$currentRound = $row["currentRound"];
	$currentSubRound = $row["currentSubRound"];

	$response = new stdClass();
	$response->round = $currentRound;
	$response->subRound = $currentSubRound;
	echo(json_encode($response));
 ?>