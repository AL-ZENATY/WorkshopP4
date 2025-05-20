<?php
include_once "Database.php";
class zoeken extends Database
{
    public function zoekennaar()
    {
        $query = "SELECT * FROM Klanten ";
        return parent::voerQueryUit($query);
    }
    public function getKlantenMetNaam($naam)
    {
        $query = "SELECT * FROM klanten WHERE Voornaam = ? ";
        $params = [$naam];
        return parent::voerQueryUit($query, $params);
    }
    public function getKlantenMetWoonplaats($Woonplaats)
    {
        $query = "SELECT * FROM klanten WHERE Woonplaats = ? ";
        $params = [$Adres];
        return parent::voerQueryUit($query, $params);
    }
}
