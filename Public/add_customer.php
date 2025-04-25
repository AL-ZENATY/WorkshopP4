<?php
$host = "localhost";
$db = "jouw_database_naam";
$user = "root";
$pass = "";

// Connectie maken
$conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

// Data ophalen
$voornaam = $_POST['voornaam'];
$tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
$achternaam = $_POST['achternaam'];
$emailadres = $_POST['emailadres'];
$telefoonnummer = $_POST['telefoonnummer'];
$adres = $_POST['adres'];

// Insert query
$sql = "INSERT INTO customers (voornaam, tussenvoegsel, achternaam, emailadres, telefoonnummer, adres)
        VALUES (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :telefoonnummer, :adres)";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':voornaam' => $voornaam,
    ':tussenvoegsel' => $tussenvoegsel,
    ':achternaam' => $achternaam,
    ':emailadres' => $emailadres,
    ':telefoonnummer' => $telefoonnummer,
    ':adres' => $adres
]);

echo "Klant succesvol toegevoegd!";
?>
