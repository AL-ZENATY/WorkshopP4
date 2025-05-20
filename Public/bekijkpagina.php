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
    $straat = $_POST['straat'];
    $huisnummer = $_POST['huisnummer'];
    $postcode = $_POST['postcode'];
    $woonplaats = $_POST['woonplaats'];
    
    $klant->updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $woonplaats);
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
    </tr>

    <?php foreach ($klantenLijst as $huidig): ?>
    <tr>
        <td><?php echo $huidig['id']; ?></td>
        <td><?php echo $huidig['voornaam']; ?></td>
        <td><?php echo $huidig['tussenvoegsel']; ?></td>
        <td><?php echo $huidig['achternaam']; ?></td>
        <td><?php echo $huidig['email']; ?></td>
        <td><?php echo $huidig['telefoonnummer']; ?></td>
        <td><?php echo $huidig['straat']; ?></td>
        <td><?php echo $huidig['huisnummer']; ?></td>
        <td><?php echo $huidig['postcode']; ?></td>
        <td><?php echo $huidig['plaats']; ?></td>
        <td><a href="bekijkpagina.php?id=<?php echo $huidig['id']; ?>">Meer</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>
