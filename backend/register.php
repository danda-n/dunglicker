<?php 
require_once("connectToDatabase.php");

$response = new stdClass;
if (!isset($_POST["registerEmail"]) || !isset($_POST["registerUsername"]) || !isset($_POST["registerPassword"]))
{
	$response->success = false;
    $response->message = 'Not all required data was supplied.';
    echo(json_encode($response));
}
else
{
	$email = $_POST["registerEmail"];
	$email = mysqli_real_escape_string($connection, $email);
	$username = $_POST["registerUsername"];
	$username = mysqli_real_escape_string($connection, $username);
	$password = $_POST["registerPassword"];
	$password = mysqli_real_escape_string($connection, $password);
	if(isset($_POST['g-recaptcha-response']))
	$captcha=$_POST['g-recaptcha-response'];
	$captchaResponse=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcCmyETAAAAAMWGwH-6OLD-diq-lZLvZQeXRSX4&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	$sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
	$result = $connection->query($sql);
	if ($result != null && $result->num_rows == 1 || $captchaResponse["success"]==false)
	{
		$response->success = false;
	    $response->message = 'Username already taken.';
	    echo(json_encode($response));
	}
	else
	{
		$sql = "INSERT INTO user (email, username, password, creation_date) VALUES ('$email', '$username', '$hashedPassword', CURRENT_TIMESTAMP)";
		if ($connection->query($sql) === TRUE) 
		{

            session_start();
            $_SESSION["userId"] = $connection->insert_id;
            session_regenerate_id(true);

            $sql = "INSERT INTO gameData (userId, currentRound, gold, damage, attackSpeed, criticalHitChance, criticalHitDamage, lastSpawnTime, currentSubRound, damagePurchases, attackSpeedPurchases, criticalHitChancePurchases, criticalHitDamagePurchases, experience, emeralds, energy, energyPurchases, energyRegen, energyRegenPurchases, goldBonus, experienceBonus, emeraldBonus, itemDropBonus, itemRarityBonus, spell1Purchases, spell2Purchases, spell3Purchases, spell4Purchases, spell5Purchases, spell6Purchases, spell7Purchases, spell8Purchases, reawakeningCount) VALUES ('$connection->insert_id', 1, 0, 10, 1, 0, 150, CURRENT_TIMESTAMP, 1, 0, 0, 0, 0, 0, 0, 100, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)";
            $connection->query($sql);

		    $response->success = true;
		    $response->message = 'Account created.';
		    echo(json_encode($response));
		} 
		else 
		{
		    $response->success = false;
		    $response->message = $connection->error;
		    echo(json_encode($response));
		}
		
	}
}

$connection->close();
?>