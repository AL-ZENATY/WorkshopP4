<?php
include("./Database.php");
class Zoeken extends Database
{
    public function zoeken($zoeken)
    {
        $query = "SELECT * FROM customers WHERE firstName = '$params'";
        $params = [$zoeken];
        return parent::voerQueryUitOud($query, $params);
    }
}
?>