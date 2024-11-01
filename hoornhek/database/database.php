<?php

$host = '127.0.0.1';
$db = 'hoornhek';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dbconn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dbconn, $user, $pass, $options);


//functie om de database te sluiten
function fnCloseDb($conn){
    if (!$conn == false)
    {
        mysqli_close($conn)
        or die('Sluiten MySQL-db niet gelukt...');
    }
} //end of fnCloseDb


