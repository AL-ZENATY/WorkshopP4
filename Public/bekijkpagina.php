<?php
include "../Src/Klanten.php";

$klant = new Klanten();

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
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $huidig = $klant->getKlantById($id);
}

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
</style>

<table border="1">
    <h1>Klanten Bekijken</h1>
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
        <th>Woonplaats</th>
    </tr>
    <tr>
        <td><?php echo $klantenLijst['id']; ?></td>
        <td><?php echo $klantenLijst['voornaam']; ?></td>
        <td><?php echo $klantenLijst['tussenvoegsel']; ?></td>
        <td><?php echo $klantenLijst['achternaam']; ?></td>
        <td><?php echo $klantenLijst['email']; ?></td>
        <td><?php echo $klantenLijst['telefoonnummer']; ?></td>
        <td><?php echo $klantenLijst['straat']; ?></td>
        <td><?php echo $klantenLijst['huisnummer']; ?></td>
        <td><?php echo $klantenLijst['postcode']; ?></td>
        <td><?php echo $klantenLijst['woonplaats']; ?></td>
    </tr>
</table>
<a href="klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>