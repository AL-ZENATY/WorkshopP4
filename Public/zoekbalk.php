<?php
include("./zoekendata.php");
$zoekennaarnaam= new Zoeken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="POST"> 
    Zoeken: <input type="text" name="zoeken">
    <input type="submit" value="zoeken" name="zoekenknop">
</form>
</body>
</html>
<?php
if (isset($_POST["zoekenknop"]))
{
    $zoeken = $_POST['zoeken'];
    $detail = $zoekennaarnaam->zoeken($zoeken);
    echo "<table>";
    foreach($zoekennaarnaam as $naam)
    {
        echo "<tr>";
        echo "<td>";
        echo $naam['firstName'];
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>