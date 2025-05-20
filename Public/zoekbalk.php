<?php
include "../src/Zoeken.php";
$zoekenNaarObject= new zoeken();
?>
<form method="POST"> 
    <input type="radio" id="naam" name="keuze" value="naam" class="naam"> Naam
    <input type="radio" id="Adres" name="keuze" value="Adres" class="Adres"> Adres<br>
    zoeken: <input type="text" name="zoekwaarde">
    <input type="submit" value="zoeken" name="zoeken">
</form>
<?php


if (isset($_POST["zoeken"]))
{
    print_r($_POST);
    $naamOfAdress = $_POST['keuze'];
    $naamZoeken = $_POST['zoekwaarde'];
if ($naamOfAdress == "naam")
{
    $gezochtItems = $zoekenNaarObject->getKlantenMetNaam($naamZoeken);
}
elseif ($naamOfAdress == "Adres")
{
    $gezochtItems = $zoekenNaarObject->getKlantenMetWoonplaats($naamZoeken);
}
else{
    echo "error";
}
}
?>
