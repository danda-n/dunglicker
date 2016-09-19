<?php
$servername = "localhost";
$username = "aniellh9_dunger";
$password = "=yTJ;,g7veve";
$dbname = "aniellh9_dunglicker";

$connection = new mysqli($servername, $username, $password, $dbname);

$connection->query($sql);

if ($connection->connect_error) 
{
    die("Connection failed: " . $connection->connect_error);
}
?>