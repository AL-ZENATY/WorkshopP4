<?php
include("../Src/Database.php");
class gebruikerdatabase extends Database
{
    public function controleerLogin($username, $password)
    {
        $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = ?";
        $params = [$username];
        $resultaat = (parent::voerQueryUit($query, $params));
        if ($resultaat > 0) {
            if (password_verify($password, $resultaat[0]['wachtwoord'])) {
                return true;
            }
        }
        return false;
    }
    public function insertGebruiker($username, $password, $email)
    {
        if ($username == "" || $password == "" || $email == "") {
            return false;
        }
        $query = "INSERT INTO gebruikers (gebruikersnaam, wachtwoord, email) VALUES (?, ?, ?)";
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $params = [$username, $hashpassword, $email];
        return parent::voerQueryUit($query, $params) > 0;
    }
}
?>