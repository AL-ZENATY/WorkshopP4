<?php
// Definieer de variabelen bovenaan
$klant_toegevoegd = false;
$foutmelding = "";

require_once '../Config/db_config.php';

try {
    // Verbinding maken
    $conn = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controleer of het formulier specifiek bedoeld is voor klant toevoegen
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["actie"]) && $_POST["actie"] === "klant_toevoegen") {
        $voornaam = $_POST['voornaam'] ?? '';
        $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
        $achternaam = $_POST['achternaam'] ?? '';
        $emailadres = $_POST['emailadres'] ?? '';
        $telefoonnummer = $_POST['telefoonnummer'] ?? '';
        $straat = $_POST['straat'] ?? '';
        $huisnummer = $_POST['huisnummer'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $plaats = $_POST['plaats'] ?? '';

        // Combinerend adres
        $adres = trim("$straat $huisnummer, $postcode $plaats");

        // Validatie: Vul alle velden in
        if (
            !empty($voornaam) && !empty($achternaam) && !empty($emailadres) && !empty($telefoonnummer)
            && !empty($straat) && !empty($huisnummer) && !empty($postcode) && !empty($plaats)
        ) {
            if (strlen($adres) <= 255) {
                $sql = "INSERT INTO klanten 
                        (voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats)
                        VALUES 
                        (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :telefoonnummer, :straat, :huisnummer, :postcode, :plaats);";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':voornaam' => $voornaam,
                    ':tussenvoegsel' => $tussenvoegsel,
                    ':achternaam' => $achternaam,
                    ':emailadres' => $emailadres,
                    ':telefoonnummer' => $telefoonnummer,
                    ':straat' => $straat,
                    ':huisnummer' => $huisnummer,
                    ':postcode' => $postcode,
                    ':plaats' => $plaats,
                ]);
                $klant_toegevoegd = true;
            } else {
                $foutmelding = "Adres is te lang. Maximaal 255 tekens toegestaan.";
            }
        } else {
            $foutmelding = "Vul alle verplichte velden in.";
        }
    }
} catch (PDOException $e) {
    $foutmelding = "Databasefout: " . $e->getMessage();
}
?>
