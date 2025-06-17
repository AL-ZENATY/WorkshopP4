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
        $result = parent::voerQueryUit($query, $params);
        return isset($result[0]) ? $result[0] : null;
    }

    public function updateFactuur($id, $klantId, $datum, $bedrag)
    {
        // Probeer eerst te updaten, als er geen rijen zijn, doe een insert
        $query = "UPDATE factuur SET klant_id = ?, datum = ?, bedrag = ? WHERE id = ?";
        $params = [$klantId, $datum, $bedrag, $id];
        $result = parent::voerQueryUit($query, $params);
        if ($result === 0) {
            $query = "INSERT INTO factuur (id, klant_id, datum, bedrag) VALUES (?, ?, ?, ?)";
            $params = [$id, $klantId, $datum, $bedrag];
            parent::voerQueryUit($query, $params);
        }
    }

    public function deleteFactuur($id)
    {
        $query = "DELETE FROM factuur WHERE id = ?";
        $params = [$id];
        return parent::voerQueryUit($query, $params) > 0;
    }
}