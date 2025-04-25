<?php
session_start();
// Assuming you saved the admin name in session
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Pagina</title>
</head>

<body>
    <h1>Welkom, <?= htmlspecialchars($adminName) ?>!</h1>
    <h2>Dit is de gegevens pagina.</h2>

    <form method="post" action="add_customer.php">
        <label>Voornaam:</label>
        <input type="text" name="voornaam" required><br>

        <label>Tussenvoegsel:</label>
        <input type="text" name="tussenvoegsel"><br>

        <label>Achternaam:</label>
        <input type="text" name="achternaam" required><br>

        <label>Emailadres:</label>
        <input type="email" name="emailadres" required><br>

        <label>Telefoonnummer:</label>
        <input type="text" name="telefoonnummer" required><br>

        <label>Adres:</label>
        <input type="text" name="adres" required><br>

        <input type="submit" value="Klant toevoegen">
    </form>
</body>

</html>