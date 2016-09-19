<?php 
	require_once("connectToDatabase.php");
	$response = new stdClass();
	session_start();
        
    $userId = $_SESSION["userId"];

	$sql = "SELECT id FROM gameData WHERE userId = '$userId'";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);
	$gameDataId = $row["id"];

	$itemId = $_POST["itemId"];

	$sql = "SELECT isEquipped FROM item WHERE id = '$itemId'";
	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	$isEquipped = $row["isEquipped"];
	$switchEquipped = 1 - $isEquipped;

	$sql = "SELECT COUNT(*) AS count FROM item WHERE gameDataId = '$gameDataId' AND isEquipped = 1";
	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	$numberOfEquippedItems = $row["count"];

	if ($switchEquipped == 1 && $numberOfEquippedItems > 4)
	{
		$response->success=false;
	}
	else
	{
		$sql = "UPDATE item SET isEquipped = '$switchEquipped' WHERE id = '$itemId'";
		$connection->query($sql);
		$response->success=true;
	}
	echo(json_encode($response));
 ?>