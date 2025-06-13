<?php
require_once '../Config/db_config.php';
class Database
{
    private $connectie;

    public function __construct()
    {
        $this->connectie = new PDO("mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    }
    
    public function voerQueryUitOud($query, $params = [])
    {
        $stmt = $this->connectie->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows; 
    }

    public function voerQueryUit($query, $params = [])
    {
        $statement = $this->connectie->prepare($query);
        $statement->execute($params);
        if (str_contains($query, 'SELECT')) {
            // Query is a SELECT, return the selection from the table (=array)
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } else {
            // Query is not a SELECT, return the rows affected (=number)
            $result = $statement->rowCount();
            return $result;
        }
    }

    public function sluitVerbinding()
    {
        $this->connectie = null;
    }

    public function testVerbinding()
    {
        return (bool) $this->connectie;
    }

    public function __dostruct()
    {
        $this->sluitVerbinding();
    }
}
