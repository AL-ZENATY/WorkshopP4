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
    public function updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $plaats, $notities)
    {
        $query = "UPDATE klanten SET voornaam = ?, tussenvoegsel = ?, achternaam = ?, email = ?, telefoonnummer = ?, straat = ?, huisnummer = ?, postcode = ?, plaats = ?, notities = ? WHERE id = ?";
        $params = [$voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $straat, $huisnummer, $postcode, $plaats, $notities, $id];
        return parent::voerQueryUit($query, $params);
    }
    
    public function deleteKlant($id)
    {
        $query = "DELETE FROM klanten WHERE id = ? ";
        $params = [$id];
        return parent::voerQueryUit($query, $params) > 0;
    }
    public function getklantByAchternaam($achternaam)
    {
        $query = "SELECT id, voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats FROM klanten WHERE Achternaam = ? ";
        $params = [$achternaam];
        return parent::voerQueryUit($query, $params);
    }
    public function getklantByPlaats($plaats)
    {
        $query = "SELECT id, voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats FROM klanten WHERE Plaats = ? ";
        $params = [$plaats];
        return parent::voerQueryUit($query, $params);
    }
    public function getKlantByAchternaamEnWoonplaats($achternaam, $woonplaats)
    {
        $query = "SELECT id, voornaam, tussenvoegsel, achternaam, email, telefoonnummer, straat, huisnummer, postcode, plaats FROM klanten WHERE Achternaam = ? AND Plaats = ?";
        $params = [$achternaam, $woonplaats];
        return parent::voerQueryUit($query, $params);
    }
}