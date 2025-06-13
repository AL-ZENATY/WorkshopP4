<?php
include "../Src/Klanten.php";
include "../Src/Factuur.php";
$klant = new Klanten();
$factuurObj = new Factuur();

$conn = new mysqli("127.0.0.1", "root", "", "klusjesman");
if ($conn->connect_error) {
    die("Verbindingsfout: " . $conn->connect_error);
}

$id = $_GET['id'];
$huidig = $klant->getKlantById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notitie_inhoud'])) {
    $inhoud = $conn->real_escape_string($_POST['notitie_inhoud']);
    $conn->query("INSERT INTO klant_notities (klant_id, inhoud) VALUES ($id, '$inhoud')");
    header("Location: bekijkpagina.php?id=$id");
    exit;
}

if (isset($_GET['delete_note'])) {
    $note_id = intval($_GET['delete_note']);
    $conn->query("DELETE FROM klant_notities WHERE id = $note_id AND klant_id = $id");
    header("Location: bekijkpagina.php?id=$id");
    exit;
}

$notities = $conn->query("SELECT * FROM klant_notities WHERE klant_id = $id ORDER BY datum_toegevoegd DESC");

// Materialenlijst
$materialen = [
    'Verf' => 15.00,
    'Cement' => 12.50,
    'Bakstenen' => 0.80,
    'Hout' => 7.50,
    'Tegels' => 2.00,
    'Schroeven' => 0.10,
    'Lijm' => 4.00,
    'Isolatie' => 9.00,
    'PVC-buis' => 3.50,
    'Gipsplaat' => 6.00
];

// Factuurregels
$regels = [
    ['aantal' => 0, 'omschrijving' => 'Rij Kosten', 'prijs' => 12.50],
    // Materiaalregel wordt hieronder dynamisch toegevoegd
    ['aantal' => 0, 'omschrijving' => 'Uurloon', 'prijs' => 7.99],
];

// Materiaalkeuze verwerken
$gekozen_materiaal = isset($_POST['materiaal']) ? $_POST['materiaal'] : array_key_first($materialen);
$materiaal_prijs = $materialen[$gekozen_materiaal];

// Haal de aantallen uit POST, of zet op 0 als niet aanwezig
$aantal_rij = isset($_POST['aantal'][0]) ? intval($_POST['aantal'][0]) : 0;
$aantal_materiaal = isset($_POST['aantal_materiaal']) ? intval($_POST['aantal_materiaal']) : 0;
$aantal_uur = isset($_POST['aantal'][2]) ? intval($_POST['aantal'][2]) : 0;

// Voeg materiaalregel toe
array_splice($regels, 1, 0, [[
    'aantal' => $aantal_materiaal,
    'omschrijving' => $gekozen_materiaal,
    'prijs' => $materiaal_prijs
]]);
$regels[0]['aantal'] = $aantal_rij;
$regels[2]['aantal'] = $aantal_uur;

// Factuur berekenen en opslaan
$totaal = 0;
$btw = 0;
$incl = 0;
$factuur_id = null;
$factuur_bon = null;

// Bon verwijderen
if (isset($_POST['verwijder_factuur']) && is_numeric($_POST['verwijder_factuur'])) {
    $factuurObj->deleteFactuur($_POST['verwijder_factuur']);
    $factuur_bon = null;
}

// Factuur berekenen en opslaan (alleen als op "Berekenen" is gedrukt)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereken_factuur'])) {
    foreach ($regels as $regel) {
        $totaal += $regel['aantal'] * $regel['prijs'];
    }
    $btw = $totaal * 0.21;
    $incl = $totaal + $btw;

    // Alleen regels met aantal > 0 tonen op de bon
    $gekozen_regels = array_filter($regels, function($regel) {
        return $regel['aantal'] > 0;
    });

    // Factuur opslaan in database
    $datum = date('Y-m-d');
    $factuurObj->updateFactuur($id, $id, $datum, $incl);

    // Bon genereren
    $factuur_bon = [
        'id' => $id,
        'klant' => $huidig['Voornaam'] . ' ' . $huidig['Tussenvoegsel'] . ' ' . $huidig['Achternaam'],
        'regels' => $gekozen_regels,
        'totaal' => $totaal,
        'btw' => $btw,
        'incl' => $incl,
        'datum' => $datum
    ];

    // Factuurvelden resetten na berekenen
    $aantal_rij = 0;
    $aantal_materiaal = 0;
    $aantal_uur = 0;
    $regels[0]['aantal'] = 0;
    $regels[1]['aantal'] = 0;
    $regels[2]['aantal'] = 0;
}
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
        .flex-container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .column {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
        }
        table {
            width: 100%;
            max-width: 825px;
            background-color: white;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4caf50;
            color: white;
        }
        textarea {
            width: 100%;
            max-width: 800px;
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
            max-width: 790px;
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
        .bon-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
            padding: 40px 40px 60px 40px;
            margin-top: 30px;
            max-width: 900px;
            font-family: Arial, sans-serif;
        }
        .bon-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }
        .bon-logo {
            width: 120px;
            height: 120px;
            border: 2px dashed #bbb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #aaa;
            font-weight: bold;
            background-image: url('images.jpg');
        }
        .bon-title {
            font-size: 48px;
            font-weight: bold;
            color: #444;
            margin-bottom: 20px;
        }
        .bon-info-table {
            width: 100%;
            margin-bottom: 18px;
        }
        .bon-info-table th, .bon-info-table td {
            text-align: left;
            padding: 2px 8px 2px 0;
            font-size: 15px;
        }
        .bon-info-table th {
            font-weight: bold;
            color: white;
            width: 140px;
        }
        .bon-details-table {
            width: 100%;
            margin-bottom: 18px;
        }
        .bon-details-table th, .bon-details-table td {
            text-align: left;
            padding: 2px 8px 2px 0;
            font-size: 15px;
        }
        .bon-details-table th {
            font-weight: bold;
            color: white;
            width: 120px;
        }
        .bon-product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
            margin-bottom: 18px;
        }
        .bon-product-table th, .bon-product-table td {
            border-bottom: 1px solid #eee;
            padding: 10px 8px;
            font-size: 16px;
        }
        .bon-product-table th {
            background: #f5f7fa;
            color: #222;
            font-weight: bold;
        }
        .bon-product-table tfoot td {
            font-weight: bold;
            background: #f5f7fa;
        }
        .bon-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 14px;
            color: #555;
        }
        .factuur-table input[type="number"] {
            width: 60px;
        }
    </style>
    <script>
        // Live prijsberekening in de factuur
        function updateFactuur() {
            // Rij kosten
            let rijAantal = parseFloat(document.getElementById('aantal_rij').value) || 0;
            let rijPrijs = parseFloat(document.getElementById('prijs_rij').dataset.prijs);
            document.getElementById('bedrag_rij').innerText = "€ " + (rijAantal * rijPrijs).toFixed(2).replace('.', ',');

            // Materiaal
            let matAantal = parseFloat(document.getElementById('aantal_materiaal').value) || 0;
            let matPrijs = parseFloat(document.getElementById('prijs_materiaal').dataset.prijs);
            document.getElementById('bedrag_materiaal').innerText = "€ " + (matAantal * matPrijs).toFixed(2).replace('.', ',');

            // Uurloon
            let uurAantal = parseFloat(document.getElementById('aantal_uur').value) || 0;
            let uurPrijs = parseFloat(document.getElementById('prijs_uur').dataset.prijs);
            document.getElementById('bedrag_uur').innerText = "€ " + (uurAantal * uurPrijs).toFixed(2).replace('.', ',');

            // Totaal
            let subtotaal = (rijAantal * rijPrijs) + (matAantal * matPrijs) + (uurAantal * uurPrijs);
            let btw = subtotaal * 0.21;
            let incl = subtotaal + btw;
            document.getElementById('subtotaal').innerText = "€ " + subtotaal.toFixed(2).replace('.', ',');
            document.getElementById('btw').innerText = "€ " + btw.toFixed(2).replace('.', ',');
            document.getElementById('incl').innerText = "€ " + incl.toFixed(2).replace('.', ',');
        }
        window.addEventListener('DOMContentLoaded', function() {
            updateFactuur();
            document.querySelectorAll('.factuur-table input, .factuur-table select').forEach(function(el) {
                el.addEventListener('input', updateFactuur);
                el.addEventListener('change', updateFactuur);
            });
        });
        // Voorkom resetten van andere aantallen bij materiaalkeuze
        function materiaalChange(sel) {
            document.getElementById('hidden_aantal_rij').value = document.getElementById('aantal_rij').value;
            document.getElementById('hidden_aantal_uur').value = document.getElementById('aantal_uur').value;
            sel.form.submit();
        }
    </script>
</head>
<body>
    <h1><?= htmlspecialchars($huidig['Voornaam'] . ' ' . $huidig['Tussenvoegsel'] . ' ' . $huidig['Achternaam']) ?></h1>
    <h2>Gegevens</h2>
    <table>
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
            <td><?= $huidig['Straat'] . ' ' . $huidig['Huisnummer'] . ', ' . $huidig['Postcode'] . ' ' . $huidig['Plaats'] ?></td>
        </tr>
    </table>

    <div class="flex-container">
        <div class="column">
            <h2>Gegevens</h2>
            <table class="klantgegevens">
                <tr><th>ID</th><td><?= $huidig['Id'] ?></td></tr>
                <tr><th>Email</th><td><?= $huidig['Email'] ?></td></tr>
                <tr><th>Telefoonnummer</th><td><?= $huidig['Telefoonnummer'] ?></td></tr>
                <tr><th>Adres</th><td><?= $huidig['Straat'] . ' ' . $huidig['Huisnummer'] . ', ' . $huidig['Postcode'] . ' ' . $huidig['Plaats'] ?></td></tr>
            </table>

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
                            <a class="verwijder" href="?id=<?= $id ?>&delete_note=<?= $note['id'] ?>" onclick="return confirm('Verwijder deze notitie?')">Verwijder</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="column">
            <h2>Factuur</h2>
            <form method="post">
                <input type="hidden" id="hidden_aantal_rij" name="aantal[0]" value="<?= htmlspecialchars($aantal_rij) ?>">
                <input type="hidden" id="hidden_aantal_uur" name="aantal[2]" value="<?= htmlspecialchars($aantal_uur) ?>">
                <table class="factuur-table">
                    <tr>
                        <th>Aantal</th>
                        <th>Omschrijving</th>
                        <th>Prijs</th>
                        <th>Bedrag</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" min="0" id="aantal_rij" name="aantal[0]" value="<?= htmlspecialchars($aantal_rij) ?>" />
                        </td>
                        <td><?= htmlspecialchars($regels[0]['omschrijving']) ?></td>
                        <td id="prijs_rij" data-prijs="<?= $regels[0]['prijs'] ?>">€ <?= number_format($regels[0]['prijs'], 2, ',', '.') ?></td>
                        <td id="bedrag_rij">€ <?= number_format($regels[0]['aantal'] * $regels[0]['prijs'], 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" min="0" id="aantal_materiaal" name="aantal_materiaal" value="<?= htmlspecialchars($aantal_materiaal) ?>" />
                        </td>
                        <td>
                            <select name="materiaal" id="materiaal" onchange="materiaalChange(this)">
                                <?php foreach ($materialen as $naam => $prijs): ?>
                                    <option value="<?= $naam ?>" <?= $gekozen_materiaal == $naam ? 'selected' : '' ?>>
                                        <?= $naam ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td id="prijs_materiaal" data-prijs="<?= $materiaal_prijs ?>">€ <?= number_format($materiaal_prijs, 2, ',', '.') ?></td>
                        <td id="bedrag_materiaal">€ <?= number_format($regels[1]['aantal'] * $materiaal_prijs, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" min="0" id="aantal_uur" name="aantal[2]" value="<?= htmlspecialchars($aantal_uur) ?>" />
                        </td>
                        <td><?= htmlspecialchars($regels[2]['omschrijving']) ?></td>
                        <td id="prijs_uur" data-prijs="<?= $regels[2]['prijs'] ?>">€ <?= number_format($regels[2]['prijs'], 2, ',', '.') ?></td>
                        <td id="bedrag_uur">€ <?= number_format($regels[2]['aantal'] * $regels[2]['prijs'], 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Subtotaal</strong></td>
                        <td id="subtotaal"><strong>€ 0,00</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>BTW (21%)</strong></td>
                        <td id="btw"><strong>€ 0,00</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;"><strong>Totaal</strong></td>
                        <td id="incl"><strong>€ 0,00</strong></td>
                    </tr>
                </table>
                <input type="submit" class="btn" name="bereken_factuur" value="Berekenen">
            </form>
        </div>
    </div>

    <?php if ($factuur_bon): ?>
        <div class="bon-container">
            <div class="bon-header">
                <div>
                    <div class="bon-title">Factuur</div>
                    <table class="bon-info-table">
                        <tr>
                            <th>Van</th>
                            <td><strong>Timmerman</strong><br>Middelweg 10,<br>1139 Brussel, Belgie.</td>
                        </tr>
                        <tr>
                            <th>Naar</th>
                            <td>
                                <strong><?= htmlspecialchars($factuur_bon['klant']) ?></strong><br>
                                <?= htmlspecialchars($huidig['Straat'] . ' ' . $huidig['Huisnummer']) ?>,<br>
                                <?= htmlspecialchars($huidig['Postcode'] . ' ' . $huidig['Plaats']) ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="bon-logo"></div>
            </div>
            <table class="bon-details-table">
                <tr>
                    <th>Factuurnummer</th>
                    <td><?= htmlspecialchars($factuur_bon['id']) ?></td>
                    <th>Factuurdatum</th>
                    <td><?= htmlspecialchars($factuur_bon['datum']) ?></td>
                </tr>
                <tr>
                    <th>Relatienummer</th>
                    <td><?= htmlspecialchars($huidig['Id']) ?></td>
                    <th>Vervaldag</th>
                    <td><?= date('d.m.Y', strtotime($factuur_bon['datum'] . ' +30 days')) ?></td>
                </tr>
            </table>
            <div style="margin-bottom:18px;">
                <strong>Informatie over de opdracht of bestelling</strong><br>
                Extra instructies of informatie.
            </div>
            <table class="bon-product-table">
                <thead>
                    <tr>
                        <th>Beschrijving</th>
                        <th>Aantal</th>
                        <th>Eenheid</th>
                        <th>Tarief</th>
                        <th>Totaal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($factuur_bon['regels'] as $regel): ?>
                        <tr>
                            <td><?= htmlspecialchars($regel['omschrijving']) ?></td>
                            <td><?= htmlspecialchars($regel['aantal']) ?></td>
                            <td><?= ($regel['omschrijving'] === 'Uurloon' ? 'Uur' : 'Stuk') ?></td>
                            <td>€ <?= number_format($regel['prijs'], 2, ',', '.') ?></td>
                            <td>€ <?= number_format($regel['aantal'] * $regel['prijs'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;">Totaalbedrag</td>
                        <td>€ <?= number_format($factuur_bon['incl'], 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
            <div class="bon-footer">
                <div>
                    <strong>Timmerman</strong><br>
                    Middelweg 10<br>
                    1139 Brussel<br>
                    Ondernemingsnummer: 0123 456 789<br>
                    BTW nummer: BE123456789
                </div>
                <div>
                    <strong>Contact information</strong><br>
                    Contactpersoon<br>
                    Telefoon: 02 123 45 67<br>
                    Email: contactpersoon@bedrijf.be<br>
                    www.bedrijfsnaam.be
                </div>
                <div>
                    <strong>Betaalgegevens</strong><br>
                    Bank: ING Belgium NV<br>
                    SWIFT/BIC: BBRUBEBB<br>
                    IBAN: BE00 0000 0000 0000
                </div>
            </div>
            <form method="post" style="margin-top: 20px;">
                <input type="hidden" name="verwijder_factuur" value="<?= htmlspecialchars($factuur_bon['id']) ?>">
                <button type="submit" class="btn" onclick="return confirm('Weet je zeker dat je deze bon wilt verwijderen?')">Verwijder bon</button>
            </form>
        </div>
    <?php endif; ?>

    <p><a href="klanten toevoegen en Overzicht.php">← Terug naar overzicht pagina</a></p>
</body>
</html>
