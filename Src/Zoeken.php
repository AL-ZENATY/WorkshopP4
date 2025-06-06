<?php
include_once "Database.php";
class zoeken extends Database
{
    public function zoekennaar()
    {
        $query = "SELECT * FROM Klanten ";
        return parent::voerQueryUit($query);
    }
    public function getKlantenMetNaam($Naam)
    {
        $query = "SELECT * FROM klanten WHERE Voornaam = ? ";
        $params = [$Naam];
        return parent::voerQueryUit($query, $params);
    }
    public function getKlantenMetWoonplaats($Woonplaats)
    {
        $query = "SELECT * FROM klanten WHERE Woonplaats = ? ";
        $params = [$Woonplaats];
        return parent::voerQueryUit($query, $params);
    }
}
