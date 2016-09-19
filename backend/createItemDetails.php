<?php 
	require_once("connectToDatabase.php");
	$response = new stdClass();
	session_start();

	$itemId = $_POST["itemId"];

	$sql = "SELECT name FROM item WHERE id = '$itemId' LIMIT 1";
	$result = $connection->query($sql);
	$row = $result->fetch_assoc();
	$name = $row["name"];

	$itemStatArray = array();

	$sql = "SELECT itemStat.itemStatNameId, itemStat.value, itemStatName.name, itemStatName.id FROM itemStat, itemStatName WHERE itemStat.itemId = '$itemId' AND itemStatName.id = itemStat.itemStatNameId";
	$result = $connection->query($sql);
	while($row = $result->fetch_row())
	{
		array_push($itemStatArray, $row);
	}

	$response->details="<div>";
        $response->details.='<h4 class="font_cGothic">'.$name.'</h4>';
        $response->details.='<div>';

	for ($i=0; $i < count($itemStatArray); $i++) 
	{ 
		$itemStat = $itemStatArray[$i];
		$name = $itemStat[2];
		$value = $itemStat[1];

                $response->details.='<p>+' . $value . " " . $name . "</p>";
	}

        $response->details.="</div></div>";
	$response->details.='<button id="btnItem'.$itemId.'" class="btn btn-default btnItemSwitch">Switch</button>';
        $response->details.='<button id="btnItemDelete'.$itemId.'" class="btn btn-default btnItemDelete">Delete</button>';
	$response->details.="</div>";

	echo(json_encode($response));
 ?>