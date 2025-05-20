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
}

$id = $_GET['id'];
$huidig = $klant->getKlantById($id);
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
    <h1>Cijfer Bekijken</h1>
    <tr>
        <th>Veld</th>
        <th>Waarde</th>
    </tr>
    <tr>
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
    </tr>
</table>
<a href="Klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>