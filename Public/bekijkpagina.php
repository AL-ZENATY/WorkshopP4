<?php
include "../Src/Klanten.php";
$klant = new Klanten();

// Verbinden met database
$conn = new mysqli("127.0.0.1", "root", "", "klusjesman");
if ($conn->connect_error) {
    die("Verbindingsfout: " . $conn->connect_error);
}

// Ophalen klantgegevens
$id = $_GET['id'];
$huidig = $klant->getKlantById($id);

// Notitie toevoegen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notitie_inhoud'])) {
    $inhoud = $conn->real_escape_string($_POST['notitie_inhoud']);
    $conn->query("INSERT INTO klant_notities (klant_id, inhoud) VALUES ($id, '$inhoud')");
    header("Location: bekijkpagina.php?id=$id");
    exit;
}

// Notitie verwijderen
if (isset($_GET['delete_note'])) {
    $note_id = intval($_GET['delete_note']);
    $conn->query("DELETE FROM klant_notities WHERE id = $note_id AND klant_id = $id");
    header("Location: bekijkpagina.php?id=$id");
    exit;
}

// Notities ophalen
$notities = $conn->query("SELECT * FROM klant_notities WHERE klant_id = $id ORDER BY datum_toegevoegd DESC");

// Factuurregels
$regels = [
    ['aantal' => 0, 'omschrijving' => 'Rij Kosten', 'prijs' => 12.50],
    ['aantal' => 0, 'omschrijving' => 'Materiaal kosten', 'prijs' => 25.00],
    ['aantal' => 0, 'omschrijving' => 'Uurloon', 'prijs' => 7.99],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aantal'])) {
    foreach ($_POST['aantal'] as $i => $nieuwAantal) {
        if (isset($regels[$i]) && is_numeric($nieuwAantal)) {
            $regels[$i]['aantal'] = intval($nieuwAantal);
        }
    }
}

$totaal = 0;
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Klantgegevens</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f7fa;
        padding: 20px;
        margin: 0;
    }

    h1 {
        font-size: 28px;
        color: #333;
    }

    h2 {
        color: #333;
    }

    .flex-container {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        align-items: stretch; /* Zorgt voor gelijke hoogte van kolommen */
    }

    .column {
        flex: 1;
        min-width: 300px;
        display: flex;
        flex-direction: column;
    }

    table.klantgegevens {
        width: 100%;
        max-width: 500px;
        background-color: white;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    table.klantgegevens th,
    table.klantgegevens td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table.klantgegevens th {
        background-color: #4caf50;
        color: white;
    }

    textarea {
        width: 100%;
        max-width: 875px;
        min-height: 100px;
        padding: 10px;
        font-size: 16px;
        border-radius: 8px;
        border: 1px solid #ccc;
    }

    .btn {
        padding: 10px 16px;
        background-color: rgb(70, 229, 112);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: rgb(56, 180, 89);
    }

    .notitie {
        background: white;
        width: 100%;
        max-width: 870px;
        padding: 15px;
        border-left: 4px solid #4caf50;
        border-radius: 8px;
        margin-top: 12px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .notitie-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 8px;
    }

    .verwijder {
        color: red;
        font-size: 14px;
    }

    a {
        text-decoration: none;
        color: black;
    }

    a:hover {
        color: #4caf50;
        text-decoration: underline;
    }

    table.factuur {
        width: 100%;
        background-color: white;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        flex-grow: 1;
    }

    table.factuur th,
    table.factuur td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table.factuur th {
        background-color: #4caf50;
        color: white;
    }

    input[type="number"] {
        width: 60px;
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* ✅ Responsive design for tablets (portrait & landscape) */
    @media screen and (max-width: 1024px) and (min-width: 600px) {
        .flex-container {
            flex-direction: column;
            gap: 20px;
        }

        .column {
            max-width: 100%;
        }

        textarea,
        .notitie,
        table.klantgegevens,
        table.factuur {
            max-width: 100%;
        }

        input[type="number"] {
            width: 100%;
        }
    }
</style>

</head>

<body>
    <h1><?= htmlspecialchars($huidig['Voornaam'] . ' ' . $huidig['Tussenvoegsel'] . ' ' . $huidig['Achternaam']) ?></h1>
    <h2>Gegevens</h2>
    <table class="klantgegevens">

        <tr>
            <th>ID</th>
            <td><?= $huidig['Id'] ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= $huidig['Email'] ?></td>
        </tr>
        <tr>
            <th>Telefoonnummer</th>
            <td><?= $huidig['Telefoonnummer'] ?></td>
        </tr>
        <tr>
            <th>Adres</th>
            <td><?= $huidig['Straat'] . ' ' . $huidig['Huisnummer'] . ', ' . $huidig['Postcode'] . ' ' . $huidig['Plaats'] ?>
            </td>
        </tr>
    </table>

    <div class="flex-container">
        <div class="column">
            <h2>Notitie toevoegen</h2>
            <form method="post">
                <textarea name="notitie_inhoud" required></textarea>
                <br>
                <button type="submit" class="btn">Voeg toe</button>
            </form>

            <?php if ($notities->num_rows > 0): ?>
                <h2>Notities</h2>
                <?php while ($note = $notities->fetch_assoc()): ?>
                    <div class="notitie">
                        <p><?= nl2br(htmlspecialchars($note['inhoud'])) ?></p>
                        <div class="notitie-footer">
                            <small>Toegevoegd op: <?= $note['datum_toegevoegd'] ?></small>
                            <a class="verwijder" href="?id=<?= $id ?>&delete_note=<?= $note['id'] ?>"
                                onclick="return confirm('Verwijder deze notitie?')">Verwijder</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="column">
            <h2>Factuur</h2>
            <form method="post">
                <table class="factuur">
                    <tr>
                        <th>Aantal</th>
                        <th>Omschrijving</th>
                        <th>Prijs</th>
                        <th>Bedrag</th>
                    </tr>
                    <?php foreach ($regels as $i => $regel):
                        $bedrag = $regel['aantal'] * $regel['prijs'];
                        $totaal += $bedrag;
                        ?>
                        <tr>
                            <td><input type="number" min="0" name="aantal[<?= $i ?>]"
                                    value="<?= htmlspecialchars($regel['aantal']) ?>" /></td>
                            <td><?= htmlspecialchars($regel['omschrijving']) ?></td>
                            <td>€ <?= number_format($regel['prijs'], 2, ',', '.') ?></td>
                            <td>€ <?= number_format($bedrag, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php
                    $btw = $totaal * 0.21;
                    $incl = $totaal + $btw;
                    ?>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Subtotaal</strong></td>
                        <td><strong>€ <?= number_format($totaal, 2, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>BTW (21%)</strong></td>
                        <td><strong>€ <?= number_format($btw, 2, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Totaal</strong></td>
                        <td><strong>€ <?= number_format($incl, 2, ',', '.') ?></strong></td>
                    </tr>
                </table>
                <input type="submit" class="btn" value="Berekenen">
            </form>
        </div>
    </div>

    <p><a href="klanten toevoegen en Overzicht.php">← Terug naar overzicht pagina</a></p>
</body>

</html>