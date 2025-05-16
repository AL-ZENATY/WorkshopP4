<?php
include "Database.php";

class overzicht extends Database
{
    public function GetAllGebruikers()
    {
        $query = "SELECT Voornaam, Achternaam FROM klanten";

        return parent::voerQueryUit($query);
    }

    
}