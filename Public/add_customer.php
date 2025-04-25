<?php
require_once '../Config/db_config.php';

try {
    // Verbinden met database
    $conn = new PDO(
        "mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USERNAME,
        DB_PASSWORD
    );

    // Zet PDO in error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Data ophalen uit formulier
    $voornaam = $_POST['voornaam'] ?? '';
    $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
    $achternaam = $_POST['achternaam'] ?? '';
    $emailadres = $_POST['emailadres'] ?? '';
    $telefoonnummer = $_POST['telefoonnummer'] ?? '';
    $adres = $_POST['adres'] ?? '';

    // Controle: alle verplichte velden ingevuld?
    if (empty($voornaam) || empty($achternaam) || empty($emailadres) || empty($telefoonnummer) || empty($adres)) {
        die("Vul alle verplichte velden in.");
    }

    // SQL voorbereiden
    $sql = "INSERT INTO customers 
            (voornaam, tussenvoegsel, achternaam, emailadres, telefoonnummer, adres)
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
    echo "Fout bij toevoegen klant: " . $e->getMessage();
}
?>
