<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gegevens Pagina</title>
    <style>
        /* Basis styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 24px;
            width: 100%; /* Zorgt ervoor dat het formulier 100% breed is van zijn container */
            max-width: 900px; /* Maximaal 900px breed, verhoog dit voor grotere schermen */
            box-sizing: border-box;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 14px;
            background-color: rgb(70, 229, 112);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: rgb(56, 180, 89);
        }

        /* Tablet-specifieke styling */
        @media (min-width: 768px) and (max-width: 1024px) {
            form {
                width: 90%; /* Maak het formulier breder op tablets */
                max-width: 950px; /* Kan groter zijn dan 900px, hier naar 950px */
                padding: 32px;
            }
            label {
                margin-top: 12px;
            }
            input[type="text"],
            input[type="email"] {
                width: 100%; /* Veldbreedte op 100% */
            }
            input[type="submit"] {
                grid-column: 1 / -1;
                max-width: 80%;
                justify-self: center;
                margin-top: 16px;
            }
        }

        /* Desktop-specifieke styling (extra breed voor grotere schermen) */
        @media (min-width: 1025px) {
            form {
                max-width: 1100px; /* Nog breder formulier op desktop */
                width: 100%; /* Zorgt ervoor dat de breedte 100% is van zijn container */
            }
        }
    </style>
    <script>
        // JavaScript om telefoonnummer te formatteren naar 6 28 696 475
        function formatPhoneNumber(input) {
            let phone = input.value.replace(/\D/g, ''); // Verwijder alles behalve cijfers
            if (phone.length > 1) {
                phone = phone.substring(0, 1) + ' ' + phone.substring(1, 3) + ' ' + phone.substring(3, 6) + ' ' + phone.substring(6, 9);
            } else if (phone.length > 3) {
                phone = phone.substring(0, 1) + ' ' + phone.substring(1, 3) + ' ' + phone.substring(3, 6) + ' ' + phone.substring(6, 9);
            }
            input.value = phone;
        }
    </script>
</head>
<body>
    <div>
        <h1>Overzicht Pagina</h1>
        <form method="post" action="add_customer.php">
            <div>
                <label for="voornaam">Voornaam:</label>
                <input type="text" id="voornaam" name="voornaam" placeholder="Voer je voornaam in" required>

                <label for="tussenvoegsel">Tussenvoegsel:</label>
                <input type="text" id="tussenvoegsel" name="tussenvoegsel" placeholder="Tussenvoegsel (optioneel)">

                <label for="achternaam">Achternaam:</label>
                <input type="text" id="achternaam" name="achternaam" placeholder="Voer je achternaam in" required>

                <label for="emailadres">Emailadres:</label>
                <input type="email" id="emailadres" name="emailadres" placeholder="Voer je emailadres in" required>

                <label for="telefoonnummer">Telefoonnummer:</label>
                <input type="text" id="telefoonnummer" name="telefoonnummer" placeholder="1 23 456 789" oninput="formatPhoneNumber(this)" required>

                <label for="adres">Adres:</label>
                <input type="text" id="adres" name="adres" placeholder="Voer je adres in" required>
            </div>
            <input type="submit" value="Klant toevoegen">
        </form>
    </div>
</body>
</html>