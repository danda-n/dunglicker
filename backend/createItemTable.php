<?php 
	require_once("connectToDatabase.php");
	$response = new stdClass();
	session_start();

	$userId = $_SESSION["userId"];

	$sql = "SELECT id FROM gameData WHERE userId = '$userId'";
	$result = $connection->query($sql);
	$row = mysqli_fetch_assoc($result);
	$gameDataId = $row["id"];


	$equippedItems = array();

	$sql = "SELECT id, itemRarityId, name, itemImageId FROM item WHERE gameDataId = '$gameDataId' AND isEquipped = 1";
	$result = $connection->query($sql);
	while($row = $result->fetch_assoc())
	{
		array_push($equippedItems, $row);
	}

	$table = "<h3 class='font_cGothic'>Equipment</h3>";
	$table.='<table class="table-bordered">';
	$table.="<tr>";

	$numberOfEquippedItems = count($equippedItems);

	for ($j=0; $j < $numberOfEquippedItems; $j++) 
	{ 
		$item = $equippedItems[$j];
		$itemImageId = $item["itemImageId"];

		$sql = "SELECT imageLink FROM itemImage WHERE id = '$itemImageId' LIMIT 1";
		$result = $connection->query($sql);
		$row = mysqli_fetch_assoc($result);
		$imageLink = $row["imageLink"];

		$cellId = "item".$item["id"];
		$rarityClass = "rarityBorder".$item["itemRarityId"];

		$table.='<td id="'.$cellId.'" class="itemCell '.$rarityClass.'">';
		$table.='<img src="'.$imageLink.'">';
		$table.="</td>";
	}

	if ($numberOfEquippedItems < 5)
	{
		for ($i=0; $i < 5-$numberOfEquippedItems; $i++) 
		{ 
			$emptyImageLink = "http://i.imgur.com/kbV55ZV.png";
			$table.='<td class="itemCell rarityBorder1">';
			$table.='<img src="'.$emptyImageLink.'">';
			$table.="</td>";
		}
	}
	$table.="</tr>";
	$table.="</table>";

	$inventoryItems = array();

	$sql = "SELECT id, itemRarityId, name, itemImageId FROM item WHERE gameDataId = '$gameDataId' AND isEquipped = 0";
	$result = $connection->query($sql);
	while($row = $result->fetch_assoc())
	{
		array_push($inventoryItems, $row);
	}

	$numberOfInventoryItems = count($inventoryItems);
	if ($numberOfInventoryItems > 20)
	{
		$numberOfInventoryItems = 20;
	}

	$table.="<h3 class='font_cGothic'>Inventory</h3>";
	$table.='<table style="margin-top: 10px" class="table-bordered">';

    $table.="<tr>";
	for ($j=0; $j < $numberOfInventoryItems; $j++) 
	{ 
		if ($j%5==0 && $j!=0)
		{
			$table.="</tr>";
            $table.="<tr>";
		}	
            
        $item = $inventoryItems[$j];
		$itemImageId = $item["itemImageId"];

		$sql = "SELECT imageLink FROM itemImage WHERE id = '$itemImageId' LIMIT 1";
		$result = $connection->query($sql);
		$row = mysqli_fetch_assoc($result);
		$imageLink = $row["imageLink"];

		$cellId = "item".$item["id"];
		$rarityClass = "rarityBorder".$item["itemRarityId"];

		$table.='<td id="'.$cellId.'" class="itemCell '.$rarityClass.'">';
		$table.='<img src="'.$imageLink.'">';
		$table.="</td>";
	}
    $table.="</tr>";
	$table .="</table>";

	$response->table=$table;

	echo(json_encode($response));
 ?>