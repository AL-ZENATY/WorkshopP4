<?php
include_once "Database.php";
class Factuur extends Database
{
    public function getAllFacturen()
    {
        $query = "SELECT * FROM factuur";
        return parent::voerQueryUit($query);
    }

    public function getFactuurById($id)
    {
        $query = "SELECT * FROM factuur WHERE id = ?";
        $params = [$id];
        return parent::voerQueryUit($query, $params)[0];
    }

    public function updateFactuur($id, $klantId, $datum, $bedrag)
    {
        $query = "UPDATE factuur SET id = ?, datum = ?, bedrag = ? WHERE id = ?";
        $params = [$klantId, $datum, $bedrag, $id];
        return parent::voerQueryUit($query, $params);
    }

    public function deleteFactuur($id)
    {
        $query = "DELETE FROM factuur WHERE id = ?";
        $params = [$id];
        return parent::voerQueryUit($query, $params) > 0;
    }
}