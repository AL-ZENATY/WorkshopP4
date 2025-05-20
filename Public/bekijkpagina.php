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
    $notities = $_POST['notities'];
    
    $klant->updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $woonplaats, $notities);
    header("Location: klanten toevoegen en Overzicht.php");
    exit;
}

$id = $_GET['id'];
$huidig = $klant->getKlantById($id);
?>

<style>
    table {
        border-collapse: collapse;
        max-width: 1000px;
    }
    th, td {
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


<h1>Klantgegevens</h1>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $huidig['Id']; ?>">
    <input type="hidden" name="voornaam" value="<?php echo $huidig['Voornaam']; ?>">
    <input type="hidden" name="tussenvoegsel" value="<?php echo $huidig['Tussenvoegsel']; ?>">
    <input type="hidden" name="achternaam" value="<?php echo $huidig['Achternaam']; ?>">
    <input type="hidden" name="email" value="<?php echo $huidig['Email']; ?>">
    <input type="hidden" name="telefoonnummer" value="<?php echo $huidig['Telefoonnummer']; ?>">
    <input type="hidden" name="straat" value="<?php echo $huidig['Straat']; ?>">
    <input type="hidden" name="huisnummer" value="<?php echo $huidig['Huisnummer']; ?>">
    <input type="hidden" name="postcode" value="<?php echo $huidig['Postcode']; ?>">
    <input type="hidden" name="woonplaats" value="<?php echo $huidig['Plaats']; ?>">

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
            <th>Notities</th>
        </tr>
        <tr>
            <td><?php echo $huidig['Id']; ?></td>
            <td><?php echo $huidig['Voornaam']; ?></td>
            <td><?php echo $huidig['Tussenvoegsel']; ?></td>
            <td><?php echo $huidig['Achternaam']; ?></td>
            <td><?php echo $huidig['Email']; ?></td>
            <td><?php echo $huidig['Telefoonnummer']; ?></td>
            <td><?php echo $huidig['Straat']; ?></td>
            <td><?php echo $huidig['Huisnummer']; ?></td>
            <td><?php echo $huidig['Postcode']; ?></td>
            <td><?php echo $huidig['Plaats']; ?></td>
            <td>
                <textarea name="notities" style="width: 100px; height: 100px;"><?php echo $huidig['Notities']; ?></textarea>
                <button type="submit" class="NotitieOpslaan" style="width: 100px;">Opslaan</button>
            </td>
        </tr>
    </table>
</form>
<a href="klanten toevoegen en Overzicht.php">Terug naar overzicht pagina</a>