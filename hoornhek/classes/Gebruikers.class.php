<?php

class Gebruikers {
    private $pdo;
    private $table_name = 'gebruikers';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;  
    }

    public function getGebruikerByID($gebruiker_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE gebruiker_id = :gebruiker_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':gebruiker_id', $gebruiker_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getGebruikerRol($rol_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE rol_id = :rol_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':rol_id', $rol_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    
    public function getGebruikerNaam($inlognaam)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE inlognaam = :inlognaam";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':inlognaam', $inlognaam, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}