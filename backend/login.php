<?php

require_once("connectToDatabase.php");

$response = new stdClass;
if (!isset($_POST["loginUsername"]) || !isset($_POST["loginPassword"])) 
{
    $response->success = false;
    $response->message = 'Not all required data was supplied.';
    echo(json_encode($response));
} 
else 
{
    $username = $_POST["loginUsername"];
    $username = mysqli_real_escape_string($connection, $username);
    $password = $_POST["loginPassword"];
    $password = mysqli_real_escape_string($connection, $password);
    $sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
    $result = $connection->query($sql);
    if ($result->num_rows == 1) 
    {
        $row = $result->fetch_assoc();
        $isPasswordCorrect = password_verify($password, $row["password"]);
        if ($isPasswordCorrect == true) 
        {
            $response->success = true;
            $response->message = 'Login successful.';
            echo(json_encode($response));
            session_start();
            $_SESSION["userId"] = $row["id"];
            session_regenerate_id(true);
        } 
        else 
        {
            $response->success = false;
            $response->message = 'Login failed.';
            echo(json_encode($response));
        }
    } 
    else 
    {
        $response->success = false;
        $response->message = 'Login failed.';
        echo(json_encode($response));
    }
}

$connection->close();
?>