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

    $klant->addKlant($voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres);
    header("Location: overzicht pagina.php");
    exit;
}
?>

<h1>Klant Toevoegen</h1>
<form method="post">
    <label>voornaam: <input type="text" name="voornaam" required></label><br>
    <label>tussenvoegsel: <input type="text" name="tussenvoegsel" required></label><br>
    <label>achternaam: <input type="text" name="achternaam" required></label><br>
    <label>email: <input type="text" name="email" required></label><br>
    <label>telefoonnummer: <input type="text" name="telefoonnummer" required></label><br>
    <label>adres: <input type="text" name="adres" required></label><br>
    <button type="submit">Toevoegen</button>
</form>