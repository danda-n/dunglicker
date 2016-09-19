<?php 
	require_once("connectToDatabase.php");
	$response = new stdClass();

	$itemId = $_POST["itemId"];

	$sql = "DELETE FROM itemStat WHERE itemId = '$itemId'";
	$connection->query($sql);
        
    $sql = "DELETE FROM item WHERE id = '$itemId'";
	$connection->query($sql);

	echo(json_encode($response));
 ?>