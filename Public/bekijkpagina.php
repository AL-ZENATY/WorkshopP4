<?php
include "../src/Klanten.php";

$klant = new Klanten();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $voornaam = $_POST['voornaam'];
    $tussenvoegsel = $_POST['tussenvoegsel'];
    $achternaam = $_POST['achternaam'];
    $email = $_POST['email'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $adres = $_POST['adres'];

    $klant->updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres);
    header("Location: index.php");
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
    <h1>Klanten Bekijken</h1>
    <tr>
        <th>Id</th>
        <th>Voornaam</th>
        <th>Tussenvoegsel</th>
        <th>Achternaam</th>
        <th>Email</th>
        <th>Telefoonnummer</th>
        <th>Adres</th>
    </tr>
    <tr>
        <td><?php echo $huidig['id']; ?></td>
        <td><?php echo $huidig['voornaam']; ?></td>
        <td><?php echo $huidig['tussenvoegsel']; ?></td>
        <td><?php echo $huidig['achternaam']; ?></td>
        <td><?php echo $huidig['email']; ?></td>
        <td><?php echo $huidig['telefoonnummer']; ?></td>
        <td><?php echo $huidig['adres']; ?></td>
    </tr>
</table>
<a href="overzicht pagina.php">Terug naar overzicht pagina</a>