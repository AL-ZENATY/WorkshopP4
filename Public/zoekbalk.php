<?php
include "../src/Zoeken.php";
$zoekenNaarObject= new zoeken();
?>
<form method="POST"> 
    <input type="radio" id="Naam" name="keuze" value="Naam" class="Naam"> Naam
    <input type="radio" id="Woonplaats" name="keuze" value="Woonplaats" class="Woonplaats"> Woonplaats<br>
    zoeken: <input type="text" name="zoekwaarde">
    <input type="submit" value="zoeken" name="zoeken">
</form>
<?php


if (isset($_POST["zoeken"]))
{
    print_r($_POST);
    $naamOfWoonplaats = $_POST['keuze'];
    $naamZoeken = $_POST['zoekwaarde'];
if ($naamOfWoonplaats == "Naam")
{
    $gezochtItems = $zoekenNaarObject->getKlantenMetNaam($naamZoeken);
}
elseif ($naamOfWoonplaats == "Woonplaats")
{
    $gezochtItems = $zoekenNaarObject->getKlantenMetWoonplaats($naamZoeken);
}
else{
    echo "error";
}
}
?>
