<?php
include_once "Database.php";
class Klanten extends Database
{
    public function ZoekKlanten()
    {
        $query = "SELECT * FROM Klanten ";
        return parent::voerQueryUit($query);
    }
    public function getAllKlanten()
    {
        $query = "SELECT * FROM Klanten ";
        return parent::voerQueryUit($query);
    }

    public function getKlantById($id)
    {
        $query = "SELECT * FROM klanten WHERE id = ? ";
        $params = [$id];
        return parent::voerQueryUit($query, $params)[0] ?? null;
    }

    public function addKlant($voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres)
    {
        $query = "INSERT INTO klanten (klantnaam, email, telefoonnumer, adres) VALUES (?, ?, ?, ?)";
        $params = [$voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres];
        return parent::voerQueryUit($query, $params);
    }
    public function updateKlant($id, $voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres)
    {
        $query = "UPDATE klanten SET klantnaam = ?, email = ?, telefoonnumer = ?, adres = ? WHERE id = ?";
        $params = [$voornaam, $tussenvoegsel, $achternaam, $email, $telefoonnummer, $adres, $id];
        return parent::voerQueryUit($query, $params);
    }

    public function deleteKlant($id)
    {
        $query = "DELETE FROM klanten WHERE id = ? ";
        $params = [$id];
        return parent::voerQueryUit($query, $params) > 0;
    }
}