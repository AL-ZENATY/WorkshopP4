<?php
include "../src/Klanten.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $klant = new Klanten();
    $klant->deleteKlant($id);

    echo json_encode(['status' => 'success']);
    header("Location: overzicht pagina.php");
    exit;
}


?>
