<?php
include "../Src/Klanten.php";

$klant = new Klanten();

// Handle POST (update) - als dat nog nodig is
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $voornaam = $_POST['voornaam'];
    $tussenvoegsel = $_POST['tussenvoegsel'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $straat = $_POST['straat'] ?? '';
    $huisnummer = $_POST['huisnummer'] ?? '';
    $postcode = $_POST['postcode'] ?? '';
    $plaats = $_POST['plaats'] ?? '';

    $klant->updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $plaats);
    header("Location: klanten toevoegen en Overzicht.php");
    exit;
}

// Haal alle klanten op voor overzicht
$klantenLijst = $klant->getAllKlanten();
?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    a {
        color: black;
    }

    a:hover {
        text-decoration: underline;
        color: rgb(70, 229, 112);
    }
</style>

<h1>Klanten Overzicht</h1>

<table border="1">
<<<<<<< HEAD
    <tr>
        <th>Id</th>
        <th>Voornaam</th>
        <th>Tussenvoegsel</th>
        <th>Achternaam</th>
        <th>Email</th>
        <th>Telefoonnummer</th>
        <th>Straat</th>
        <th>Huisnummer</th>
        <th>Postcode</th>
        <th>Plaats</th>
        <th>Details</th>
=======
    <h1>Cijfer Bekijken</h1>
    <tr>
        <th>Veld</th>
        <th>Waarde</th>
>>>>>>> 33ae85f58082cda8c00e91681d0eaffb6749dd9f
    </tr>

    <?php foreach ($klantenLijst as $huidig): ?>
    <tr>
<<<<<<< HEAD
        <td><?php echo htmlspecialchars($huidig['id']); ?></td>
        <td><?php echo htmlspecialchars($huidig['voornaam']); ?></td>
        <td><?php echo htmlspecialchars($huidig['tussenvoegsel']); ?></td>
        <td><?php echo htmlspecialchars($huidig['achternaam']); ?></td>
        <td><?php echo htmlspecialchars($huidig['email']); ?></td>
        <td><?php echo htmlspecialchars($huidig['telefoonnummer']); ?></td>
        <td><?php echo htmlspecialchars($huidig['straat']); ?></td>
        <td><?php echo htmlspecialchars($huidig['huisnummer']); ?></td>
        <td><?php echo htmlspecialchars($huidig['postcode']); ?></td>
        <td><?php echo htmlspecialchars($huidig['plaats']); ?></td>
        <td><a href="bekijkpagina.php?id=<?php echo urlencode($huidig['id']); ?>">Meer</a></td>
=======
        <td>Id</td>
        <td><?php echo $huidig['id']; ?></td>
    </tr>
    <tr>
        <td>Voornaam</td>
        <td><?php echo $huidig['voornaam']; ?></td>
    </tr>
    <tr>
        <td>Tussenvoegsel</td>
        <td><?php echo $huidig['tussenvoegsel']; ?></td>
    </tr>
    <tr>
        <td>Achternaam</td>
        <td><?php echo $huidig['achternaam']; ?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?php echo $huidig['email']; ?></td>
    </tr>
    <tr>
        <td>Telefoonnummer</td>
        <td><?php echo $huidig['telefoonnummer']; ?></td>
    </tr>
    <tr>
        <td>Straat</td>
        <td><?php echo $huidig['straat']; ?></td>
    </tr>
    <tr>
        <td>Huisnummer</td>
        <td><?php echo $huidig['huisnummer']; ?></td>
    </tr>
    <tr>
        <td>Postcode</td>
        <td><?php echo $huidig['postcode']; ?></td>
    </tr>
    <tr>
        <td>Woonplaats</td>
        <td><?php echo $huidig['woonplaats']; ?></td>
    </tr>
    <tr>
        <td>Notities</td>
        <td><textarea style="width:200px; height:100px;" placeholder="Notities"></textarea></td>
>>>>>>> 33ae85f58082cda8c00e91681d0eaffb6749dd9f
    </tr>
    <?php endforeach; ?>
</table>
<<<<<<< HEAD

<a href="klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>
=======
<a href="Klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>
>>>>>>> 33ae85f58082cda8c00e91681d0eaffb6749dd9f
