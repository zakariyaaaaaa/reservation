<?php
$host = "localhost";
$dbname = "gestion_reservation";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


} catch (PDOException $e) {

    file_put_contents("erreurs.log", $e->getMessage() . PHP_EOL, FILE_APPEND);

    die("Erreur de connexion.");
}