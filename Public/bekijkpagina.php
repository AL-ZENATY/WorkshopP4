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

// Ophalen van de geselecteerde producten
$gekozen_materialen = isset($_POST['materiaal']) ? (array) $_POST['materiaal'] : ['Verf'];
$aantallen_materialen = isset($_POST['aantal_materiaal']) ? $_POST['aantal_materiaal'] : [];

$regels = [
    ['aantal' => isset($_POST['aantal_rij']) ? intval($_POST['aantal_rij']) : 1, 'omschrijving' => 'Rij Kosten', 'prijs' => 12.50]
];

foreach ($gekozen_materialen as $materiaal) {
    if (!isset($materialen[$materiaal]))
        continue;
    $regels[] = [
        'aantal' => isset($aantallen_materialen[$materiaal]) ? intval($aantallen_materialen[$materiaal]) : 0,
        'omschrijving' => $materiaal,
        'prijs' => $materialen[$materiaal]
    ];
}

$regels[] = ['aantal' => isset($_POST['aantal_uur']) ? intval($_POST['aantal_uur']) : 0, 'omschrijving' => 'Uurloon', 'prijs' => 7.99];

$totaal = 0;
$btw = 0;
$incl = 0;
$melding = "";

// Bon verwijderen
if (isset($_POST['verwijder_factuur']) && is_numeric($_POST['verwijder_factuur'])) {
    $factuurObj->deleteFactuur($_POST['verwijder_factuur']);
}

// Factuur aanmaken en opslaan (elke keer een nieuwe bon)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bereken_factuur'])) {
    $gekozen_regels = array_filter($regels, function ($regel) {
        return $regel['aantal'] > 0;
    });

    if (count($gekozen_regels) === 0) {
        $melding = "Kies minimaal één product en vul het aantal in voordat je de factuur berekent.";
    } else {
        // Genereer nieuw factuurnummer (auto increment)
        $result = $conn->query("SELECT MAX(id) as maxid FROM factuur");
        $row = $result->fetch_assoc();
        $nieuw_factuur_id = $row && $row['maxid'] ? $row['maxid'] + 1 : 1;

        $totaal = 0;
        foreach ($gekozen_regels as $regel) {
            $totaal += $regel['aantal'] * $regel['prijs'];
        }
        $btw = $totaal * 0.21;
        $incl = $totaal + $btw;

        $datum = date('Y-m-d');
        $factuurObj->updateFactuur($nieuw_factuur_id, $id, $datum, $incl);

        foreach ($gekozen_regels as $regel) {
            $omschrijving = $conn->real_escape_string($regel['omschrijving']);
            $prijs = floatval($regel['prijs']);
            $aantal = intval($regel['aantal']);
            $conn->query("INSERT INTO factuur_regels (factuur_id, omschrijving, prijs, aantal) VALUES ($nieuw_factuur_id, '$omschrijving', $prijs, $aantal)");
        }

        // Reset formulier na bon aanmaken
        $_POST = [];
        $gekozen_materialen = ['Verf'];
        $aantallen_materialen = [];
        $regels = [
            ['aantal' => 1, 'omschrijving' => 'Rij Kosten', 'prijs' => 12.50]
        ];
        $regels[] = ['aantal' => 0, 'omschrijving' => 'Uurloon', 'prijs' => 7.99];
    }
}

// Haal alle facturen van deze klant op
$alle_facturen = [];
$result = $conn->query("SELECT * FROM factuur WHERE klant_id = $id ORDER BY id DESC");
while ($factuur = $result->fetch_assoc()) {
    $regels_uit_db = [];
    $regels_result = $conn->query("SELECT * FROM factuur_regels WHERE factuur_id = " . intval($factuur['id']));
    while ($regel = $regels_result->fetch_assoc()) {
        $regels_uit_db[] = [
            'omschrijving' => $regel['omschrijving'],
            'aantal' => $regel['aantal'],
            'prijs' => $regel['prijs']
        ];
    }
    $totaal = 0;
    foreach ($regels_uit_db as $regel) {
        $totaal += $regel['prijs'] * $regel['aantal'];
    }
    $btw = $totaal * 0.21;
    $incl = $totaal + $btw;
    $alle_facturen[] = [
        'id' => $factuur['id'],
        'klant' => $huidig['Voornaam'] . ' ' . $huidig['Tussenvoegsel'] . ' ' . $huidig['Achternaam'],
        'regels' => $regels_uit_db,
        'totaal' => $totaal,
        'btw' => $btw,
        'incl' => $incl,
        'datum' => $factuur['datum']
    ];
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

        th,
        td {
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
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

        .bon-info-table th,
        .bon-info-table td {
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

        .bon-details-table th,
        .bon-details-table td {
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

        .bon-product-table th,
        .bon-product-table td {
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

        .add-materiaal-btn {
            margin: 8px 0 16px 0;
            background: #4caf50;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            cursor: pointer;
        }

        @media screen and (max-width: 768px) {
            
        }
    </style>
    <script>
        function updateFactuur() {
            let rijAantal = parseFloat(document.getElementById('aantal_rij').value) || 0;
            let rijPrijs = parseFloat(document.getElementById('prijs_rij').dataset.prijs);
            document.getElementById('bedrag_rij').innerText = "€ " + (rijAantal * rijPrijs).toFixed(2).replace('.', ',');

            let subtotaal = rijAantal * rijPrijs;

            let materiaalRows = document.querySelectorAll('.materiaal-row');
            materiaalRows.forEach(function (row) {
                let aantal = parseFloat(row.querySelector('.aantal-materiaal').value) || 0;
                let prijs = parseFloat(row.querySelector('.prijs-materiaal').dataset.prijs);
                row.querySelector('.bedrag-materiaal').innerText = "€ " + (aantal * prijs).toFixed(2).replace('.', ',');
                subtotaal += aantal * prijs;
            });

            let uurAantal = parseFloat(document.getElementById('aantal_uur').value) || 0;
            let uurPrijs = parseFloat(document.getElementById('prijs_uur').dataset.prijs);
            document.getElementById('bedrag_uur').innerText = "€ " + (uurAantal * uurPrijs).toFixed(2).replace('.', ',');
            subtotaal += uurAantal * uurPrijs;

            let btw = subtotaal * 0.21;
            let incl = subtotaal + btw;
            document.getElementById('subtotaal').innerText = "€ " + subtotaal.toFixed(2).replace('.', ',');
            document.getElementById('btw').innerText = "€ " + btw.toFixed(2).replace('.', ',');
            document.getElementById('incl').innerText = "€ " + incl.toFixed(2).replace('.', ',');
        }

        function addMateriaalRow() {
            let container = document.getElementById('materialen-container');
            let materialen = <?= json_encode($materialen) ?>;
            let row = document.createElement('tr');
            row.className = 'materiaal-row';

            let select = document.createElement('select');
            select.name = 'materiaal[]';
            select.required = true;
            select.className = 'materiaal-select';
            select.onchange = function () {
                let prijs = materialen[this.value];
                prijsTd.innerHTML = '€ ' + prijs.toFixed(2).replace('.', ',');
                prijsTd.dataset.prijs = prijs;
                aantalInput.value = 1;
                aantalInput.name = 'aantal_materiaal[' + this.value + ']';
                updateFactuur();
            };
            for (let naam in materialen) {
                let option = document.createElement('option');
                option.value = naam;
                option.text = naam;
                select.appendChild(option);
            }

            let aantalInput = document.createElement('input');
            aantalInput.type = 'number';
            aantalInput.min = 0;
            aantalInput.value = 1;
            aantalInput.className = 'aantal-materiaal';
            aantalInput.name = 'aantal_materiaal[' + select.value + ']';
            aantalInput.oninput = updateFactuur;

            let prijsTd = document.createElement('td');
            prijsTd.className = 'prijs-materiaal';
            prijsTd.dataset.prijs = materialen[select.value];
            prijsTd.innerHTML = '€ ' + materialen[select.value].toFixed(2).replace('.', ',');

            let bedragTd = document.createElement('td');
            bedragTd.className = 'bedrag-materiaal';
            bedragTd.innerHTML = '€ ' + materialen[select.value].toFixed(2).replace('.', ',');

            let tdAantal = document.createElement('td');
            tdAantal.appendChild(aantalInput);

            let tdSelect = document.createElement('td');
            tdSelect.appendChild(select);

            row.appendChild(tdAantal);
            row.appendChild(tdSelect);
            row.appendChild(prijsTd);
            row.appendChild(bedragTd);

            container.appendChild(row);

            updateFactuur();
        }

        window.addEventListener('DOMContentLoaded', function () {
            updateFactuur();
            document.querySelectorAll('.factuur-table input, .factuur-table select').forEach(function (el) {
                el.addEventListener('input', updateFactuur);
                el.addEventListener('change', updateFactuur);
            });
            document.getElementById('add-materiaal-btn').addEventListener('click', function (e) {
                e.preventDefault();
                addMateriaalRow();
            });
        });
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
            <?php if (!empty($melding)): ?>
                <div style="color: red; font-weight: bold; margin-bottom: 10px;">
                    <?= htmlspecialchars($melding) ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <table class="factuur-table">
                    <tr>
                        <th>Aantal</th>
                        <th>Omschrijving</th>
                        <th>Prijs</th>
                        <th>Bedrag</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" min="0" id="aantal_rij" name="aantal_rij"
                                value="<?= isset($_POST['aantal_rij']) ? htmlspecialchars($_POST['aantal_rij']) : 1 ?>" />
                        </td>
                        <td>Rij Kosten</td>
                        <td id="prijs_rij" data-prijs="12.50">€ 12,50</td>
                        <td id="bedrag_rij">€ 0,00</td>
                    </tr>
                    <tbody id="materialen-container">
                        <?php
                        if (!empty($gekozen_materialen)) {
                            foreach ($gekozen_materialen as $materiaal) {
                                if (!isset($materialen[$materiaal]))
                                    continue;
                                $aantal = isset($aantallen_materialen[$materiaal]) ? intval($aantallen_materialen[$materiaal]) : 1;
                                ?>
                                <tr class="materiaal-row">
                                    <td>
                                        <input type="number" min="0" class="aantal-materiaal"
                                            name="aantal_materiaal[<?= htmlspecialchars($materiaal) ?>]"
                                            value="<?= $aantal ?>" />
                                    </td>
                                    <td>
                                        <select name="materiaal[]" required
                                            onchange="this.parentNode.parentNode.querySelector('.aantal-materiaal').name='aantal_materiaal['+this.value+']';">
                                            <?php foreach ($materialen as $naam => $prijs): ?>
                                                <option value="<?= $naam ?>" <?= $materiaal == $naam ? 'selected' : '' ?>><?= $naam ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td class="prijs-materiaal" data-prijs="<?= $materialen[$materiaal] ?>">€
                                        <?= number_format($materialen[$materiaal], 2, ',', '.') ?>
                                    </td>
                                    <td class="bedrag-materiaal">€
                                        <?= number_format($aantal * $materialen[$materiaal], 2, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tr>
                        <td colspan="4">
                            <button id="add-materiaal-btn" class="add-materiaal-btn">+ Product toevoegen</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="number" min="0" id="aantal_uur" name="aantal_uur"
                                value="<?= isset($_POST['aantal_uur']) ? htmlspecialchars($_POST['aantal_uur']) : 0 ?>" />
                        </td>
                        <td>Uurloon</td>
                        <td id="prijs_uur" data-prijs="7.99">€ 7,99</td>
                        <td id="bedrag_uur">€ 0,00</td>
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

    <?php if (!empty($alle_facturen)): ?>
        <h2>Facturen van deze klant</h2>
        <?php foreach ($alle_facturen as $factuur_bon): ?>
            <div class="bon-container" style="margin-bottom: 40px;">
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
                    <button type="submit" class="btn"
                        onclick="return confirm('Weet je zeker dat je deze bon wilt verwijderen?')">Verwijder bon</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
    <?php endif; ?>
    <p>
        <a href="klanten toevoegen en overzicht.php">
            <svg style="width: 10px; height: 10px;" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path
                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
            </svg>
            Terug naar overzicht</a>
    </p>
</body>

</html>