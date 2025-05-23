<?php
include "../Src/Klanten.php";
$klant = new Klanten();

// Verbinden met database
$conn = new mysqli("localhost", "root", "", "klusjesman");
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
        }

        h1 {
            font-size: 28px;
            color: #333;
        }

        table {
            width: 100%;
            max-width: 900px;
            margin: 20px 0;
            background-color: white;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
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
            max-width: 880px;
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

        .notitie small {
            color: gray;
        }

        .verwijder {
            color: red;
            float: right;
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
    </style>
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
                <a class="verwijder" href="?id=<?= $id ?>&delete_note=<?= $note['id'] ?>"
                    onclick="return confirm('Verwijder deze notitie?')">Verwijder</a>
                <p><?= nl2br(htmlspecialchars($note['inhoud'])) ?></p>
                <small>Toegevoegd op: <?= $note['datum_toegevoegd'] ?></small>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <p><a href="klanten toevoegen en Overzicht.php">← Terug naar overzicht pagina</a></p>

</body>

</html>