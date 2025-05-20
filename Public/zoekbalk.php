<?php
include "../src/Zoeken.php";
$zoekennaar= new zoeken();
?>
<form method="POST"> 
    <input type="radio" id="zoekennaar" name="zoekennaar" class="naam"> naam
    <input type="radio" id="zoekennaar" name="zoekennaar" class="woonplaats"> woonplaats<br>
    zoeken: <input type="text" name="zoekennaarr">
    <input type="submit" value="zoeken" name="zoeken">
</form>
<?php

if (isset($_POST["zoeken"]))
{
    $zoekennaam = $_POST['zoekennaar'];
    echo $zoekennaam;
    $zoekennaars = $_POST['zoekennaarr'];
if ($zoekennaam == "naam")
{
    echo "naam";
    $zoeken = $zoekennaar->getKlantenMetNaam($zoekennaars);
}
elseif ($zoekennaam == "woonplaats")
{
    echo "woonplaats";
    $zoeken = $zoekennaar->getKlantenMetWoonplaats($zoekennaars);
}
else{
    echo "error";
}
echo $zoekennaars;
echo $zoekennaam;
}
?>
