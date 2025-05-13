<?php
require_once '../Config/db_config.php';

$klant_toegevoegd = false;

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
        $straatnaam = $_POST['straatnaam'] ?? '';
        $huisnummer = $_POST['huisnummer'] ?? '';
        $postcode = $_POST['postcode'] ?? '';
        $plaats = $_POST['plaats'] ?? '';

        $adres = trim("$straatnaam $huisnummer, $postcode $plaats");

        if (
            !empty($voornaam) && !empty($achternaam) && !empty($emailadres) && !empty($telefoonnummer)
            && !empty($straatnaam) && !empty($huisnummer) && !empty($postcode) && !empty($plaats)
        ) {
            if (strlen($adres) <= 255) {
                $sql = "INSERT INTO klanten 
                        (voornaam, tussenvoegsel, achternaam, email, telefoonnummer, adres)
                        VALUES 
                        (:voornaam, :tussenvoegsel, :achternaam, :emailadres, :telefoonnummer, :adres)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':voornaam' => $voornaam,
                    ':tussenvoegsel' => $tussenvoegsel,
                    ':achternaam' => $achternaam,
                    ':emailadres' => $emailadres,
                    ':telefoonnummer' => $telefoonnummer,
                    ':adres' => $adres
                ]);
                $klant_toegevoegd = true;
            } else {
                echo "<p style='color:red;'>Adres is te lang. Maximaal 255 tekens toegestaan.</p>";
            }
        } else {
            echo "<p style='color:red;'>Vul alle verplichte velden in.</p>";
        }
    }

    // Klanten ophalen
    $klanten = $conn->query("SELECT id, voornaam, tussenvoegsel, achternaam, email, telefoonnummer, adres FROM klanten")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Databasefout: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Pagina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        h1, h2 {
            color: #333;
        }
        form {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 24px;
            max-width: 900px;
            width: 90%;
            margin-top: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: rgb(70, 229, 112);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: rgb(56, 180, 89);
        }
        .adres-container {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 16px;
        }
        .adres-container input {
            flex: 1 1 200px;
            min-width: 120px;
        }
        table {
            margin: 32px auto;
            border-collapse: collapse;
            width: 95%;
            max-width: 1100px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 14px 18px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4caf50;
            color: white;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        .success-msg {
            color: green;
            font-weight: bold;
            margin-top: 16px;
        }
    </style>
    <script>
        function formatPhoneNumber(input) {
            let phone = input.value.replace(/\D/g, '');
            if (phone.length > 1) {
                phone = phone.substring(0, 1) + ' ' + phone.substring(1, 3) + ' ' + phone.substring(3, 6) + ' ' + phone.substring(6, 9);
            }
            input.value = phone;
        }
    </script>
</head>
<body>
    <h1>Overzicht Pagina</h1>

    <?php if ($klant_toegevoegd): ?>
        <p class="success-msg">✅ Klant succesvol toegevoegd!</p>
    <?php endif; ?>

    <form method="post">
        <label for="voornaam">Voornaam:</label>
        <input type="text" id="voornaam" name="voornaam" required>

        <label for="tussenvoegsel">Tussenvoegsel:</label>
        <input type="text" id="tussenvoegsel" name="tussenvoegsel">

        <label for="achternaam">Achternaam:</label>
        <input type="text" id="achternaam" name="achternaam" required>

        <label for="emailadres">Emailadres:</label>
        <input type="email" id="emailadres" name="emailadres" required>

        <label for="telefoonnummer">Telefoonnummer:</label>
        <input type="text" id="telefoonnummer" name="telefoonnummer" oninput="formatPhoneNumber(this)" required>

        <label>Adres:</label>
        <div class="adres-container">
            <input type="text" name="straatnaam" placeholder="Straatnaam" required>
            <input type="text" name="huisnummer" placeholder="Huisnummer" required>
            <input type="text" name="postcode" placeholder="Postcode" required>
            <input type="text" name="plaats" placeholder="Plaats" required>
        </div>

        <input type="submit" value="Klant toevoegen">
    </form>

    <?php if (!empty($klanten)): ?>
        <h2>Toegevoegde klanten</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Voornaam</th>
                    <th>Tussenvoegsel</th>
                    <th>Achternaam</th>
                    <th>Email</th>
                    <th>Telefoonnummer</th>
                    <th>Adres</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($klanten as $klant): ?>
                    <tr>
                        <td><?= htmlspecialchars($klant['id']) ?></td>
                        <td><?= htmlspecialchars($klant['voornaam']) ?></td>
                        <td><?= htmlspecialchars($klant['tussenvoegsel']) ?></td>
                        <td><?= htmlspecialchars($klant['achternaam']) ?></td>
                        <td><?= htmlspecialchars($klant['email']) ?></td>
                        <td><?= htmlspecialchars($klant['telefoonnummer']) ?></td>
                        <td><?= htmlspecialchars($klant['adres']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>