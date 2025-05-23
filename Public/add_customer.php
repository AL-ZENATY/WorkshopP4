<?php
// Definieer de variabele bovenaan
$klant_toegevoegd = false;

require_once '../Config/db_config.php';

try {
    // Verbinding maken
    $conn = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8", DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Als formulier verzonden is

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $voornaam = $_POST['voornaam'] ?? '';
        $tussenvoegsel = $_POST['tussenvoegsel'] ?? '';
        $achternaam = $_POST['achternaam'] ?? '';
        $emailadres = $_POST['emailadres'] ?? '';
        $telefoonnummer = $_POST['telefoonnummer'] ?? '';
        $straat = $_POST['straat'] ?? '';
        $huisnummer = $_POST['huisnummer'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $plaats = $_POST['plaats'] ?? '';
        $notities = $_POST['notities'] ?? '';

        // Combinerend adres
        $adres = trim("$straat $huisnummer, $postcode $plaats");

        // Validatie: Vul alle velden in
        if (
            !empty($voornaam) && !empty($achternaam) && !empty($emailadres) && !empty($telefoonnummer)
            && !empty($straat) && !empty($huisnummer) && !empty($postcode) && !empty($plaats) && !empty($notities)
        ) {
            // Valideer adreslengte
            if (strlen($adres) <= 255) {
                // SQL Query
                $sql = "INSERT INTO klanten 
                        (voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats, notities)
                        VALUES 
                        (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :telefoonnummer, :straat, :huisnummer, :postcode, :plaats, :notities);";
                
                // Voorbereiden en uitvoeren van de query
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
                    ':notities' => $notities
                ]);
                // Als de klant succesvol wordt toegevoegd, zet de variabele op true
                $klant_toegevoegd = true;
            } else {
                echo "<p style='color:red;'>Adres is te lang. Maximaal 255 tekens toegestaan.</p>";
            }
        } else {
            echo "<p style='color:red;'>Vul alle verplichte velden in.</p>";
        }
    }

    // Klanten ophalen
    $klanten = $conn->query("SELECT id, voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats, notities FROM klanten")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Databasefout: " . $e->getMessage();
}
?>
