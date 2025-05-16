<?php
include_once "Database.php";
class Klanten extends Database
{
    public function ZoekKlanten()
    {
        $query = "SELECT * FROM klanten ";
        return parent::voerQueryUit($query);
    }
    public function getAllKlanten()
    {
        $query = "SELECT * FROM klanten ";
        return parent::voerQueryUit($query);
    }

    public function getklantById($id)
    {
        $query = "SELECT * FROM klanten WHERE id = ? ";
        $params = [$id];
        return parent::voerQueryUit($query, $params)[0];
    }
    public function updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $plaats)
    {
        $query = "UPDATE klanten SET klantnaam = ?, email = ?, telefoonnumer = ?, straat = ?, huisnummer = ?, postcode = ?, plaats = ? WHERE id = ?";
        $params = [$voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $plaats, $id];
        return parent::voerQueryUit($query, $params);
    }
    
    public function deleteKlant($id)
    {
        $query = "DELETE FROM klanten WHERE id = ? ";
        $params = [$id];
        return parent::voerQueryUit($query, $params) > 0;
    }
}