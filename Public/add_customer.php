<?php
require_once '../Config/db_config.php';

try {
    // Verbinden met database
    $conn = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Data ophalen uit formulier
    $voornaam = $_POST['voornaam'] ?? '';
    $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $emailadres = $_POST['emailadres'] ?? '';
    $telefoonnummer = $_POST['telefoonnummer'] ?? '';

    // Adresdelen ophalen
    $straatnaam = $_POST['straatnaam'] ?? '';
    $huisnummer = $_POST['huisnummer'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $plaats = $_POST['plaats'] ?? '';

    // Samenvoegen tot volledig adres
    $adres = trim("$straatnaam $huisnummer, $postcode $plaats");

    // Controle: alle verplichte velden ingevuld?
    if (
        empty($voornaam) ||
        empty($achternaam) ||
        empty($emailadres) ||
        empty($telefoonnummer) ||
        empty($straatnaam) ||
        empty($huisnummer) ||
        empty($postcode) ||
        empty($plaats)
    ) {
        die("⚠️ Vul alle verplichte velden in.");
    }

    // Extra controle: adres mag maximaal 255 tekens zijn
    if (strlen($adres) > 255) {
        die("⚠️ Het adres is te lang (max 255 tekens). Verkort het adres.");
    }

    // SQL voorbereiden
    $sql = "INSERT INTO klanten 
            (voornaam, tussenvoegsel, achternaam, email, telefoonnummer, adres)
            VALUES 
            (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :telefoonnummer, :adres)";

    // Voorbereiden en uitvoeren
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':voornaam' => $voornaam,
        ':tussenvoegsel' => $tussenvoegsel,
        ':achternaam' => $achternaam,
        ':emailadres' => $emailadres,
        ':telefoonnummer' => $telefoonnummer,
        ':adres' => $adres
    ]);

    echo "✅ Klant succesvol toegevoegd!";

} catch (PDOException $e) {
    echo "❌ Fout bij toevoegen klant: " . $e->getMessage();
}
?>
