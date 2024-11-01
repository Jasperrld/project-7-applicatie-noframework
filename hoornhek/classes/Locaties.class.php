<?php

class Locaties {
    private $pdo;
    private $table_name = 'locaties';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;  
    }

    public function getLocaties()
    {
        $query = "SELECT * from " . $this->table_name ."
        ORDER BY locatie_id";

        $stmt = $this->pdo->prepare( $query );
        $stmt->execute();

        return $stmt;
    }

    public function getLocatiesSelector()
    {
        $query = "SELECT locatie_id, locatienaam FROM " . $this->table_name . " ORDER BY locatienaam";

        $stmt = $this->pdo->query($query);

        // Fetch all locaties as an associative array
        $locaties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $locaties;
    }

    // Method to get location ID by cell ID
    public function getLocationIDByCellID($cell_id)
    {
        $query = "SELECT locatie_id FROM cellen WHERE cel_id = :cell_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':cell_id', $cell_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['locatie_id'] : null;
    }

    public function getLocationName($locatie_id)
    {
        $query = "SELECT locatienaam FROM " . $this->table_name ." WHERE locatie_id = :locatie_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['locatienaam'] : "Unknown Location";
    }
}