<?php

class DetentieHistorie {
    private $pdo;
    private $table_name = 'detentiehistorie';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

     public function getDetentieHistorie($start_from, $records_per_page)
     {
         $query = "SELECT * FROM {$this->table_name} ORDER BY uitsluitingsdatum DESC LIMIT :start_from, :records_per_page";
         $stmt = $this->pdo->prepare($query);
         $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
         $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
         $stmt->execute();
 
         return $stmt;
     }
 
      public function getDetentieHistorieById($detentie_id)
      {
          $query = "SELECT * FROM " . $this->table_name . " WHERE detentie_id = :detentie_id";
          $stmt = $this->pdo->prepare($query);
          $stmt->bindParam(':detentie_id', $detentie_id, PDO::PARAM_INT);
          $stmt->execute();
          return $stmt->fetch(PDO::FETCH_ASSOC);
      }
public function getDetentieHistorieByLocatie($start_from, $records_per_page, $locatie_id = null)
{
    if ($locatie_id === 'everything') {
        $query = "SELECT * FROM {$this->table_name} ORDER BY uitsluitingsdatum DESC LIMIT :start_from, :records_per_page";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $query = "SELECT * FROM " . $this->table_name;

          if ($locatie_id !== null) {
            $query .= " WHERE locatie_id = :locatie_id";
        }
    
        $query .= " ORDER BY naam LIMIT :start_from, :records_per_page";
        
        $stmt = $this->pdo->prepare($query);
        if ($locatie_id !== null) {
            $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        }
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        
        $stmt->execute();
    }

    return $stmt;
}


public function getTotalRows() {
    $query = "SELECT COUNT(detentie_id) as aantal FROM " . $this->table_name;
    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['aantal'];
}

public function getTotalRowsOfLocatie($locatie_id = null) {
    $query = "SELECT COUNT(detentie_id) as aantal FROM " . $this->table_name;

    if ($locatie_id !== null) {
        $query .= " WHERE locatie_id = :locatie_id";

    }

    $stmt = $this->pdo->prepare($query);

    if ($locatie_id !== null) {
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row['aantal'];
}



    public function addToDetentiehistorie($arrestant_id, $cel_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $arrestatiereden)
    {
        try {
            $query = "INSERT INTO detentiehistorie (arrestant_id, cel_id, insluitingsdatum, uitsluitingsdatum, maatschappelijke_aantekeningen, arrestatiereden) 
                      VALUES (:arrestant_id, :cel_id, :insluitingsdatum, :uitsluitingsdatum, :maatschappelijke_aantekeningen, :arrestatiereden)";
            $stmt = $this->pdo->prepare($query);
            
            $stmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
            $stmt->bindParam(':cel_id', $cel_id, PDO::PARAM_INT);
            $stmt->bindParam(':insluitingsdatum', $insluitingsdatum);
            $stmt->bindParam(':uitsluitingsdatum', $uitsluitingsdatum);
            $stmt->bindParam(':maatschappelijke_aantekeningen', $maatschappelijke_aantekeningen);
            $stmt->bindParam(':arrestatiereden', $arrestatiereden);
    
            $stmt->execute();
    
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    

    public function getDetentieHistorieForArrestant($arrestant_id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE arrestant_id = :arrestant_id ORDER BY uitsluitingsdatum DESC";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function deleteDetentieHistorieById($detentie_id)
    {
        try {
            $query = "DELETE FROM {$this->table_name} WHERE detentie_id = :detentie_id";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindParam(':detentie_id', $detentie_id, PDO::PARAM_INT);

            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function searchDetentieHistorie($search_query, $locatie_id, $start_from, $records_per_page) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE naam LIKE :search_query";
            $params = [':search_query' => "%$search_query%"];
    
            if ($locatie_id !== null && $locatie_id !== 'everything') {
                $query .= " AND locatie_id = :locatie_id";
                $params[':locatie_id'] = $locatie_id;
            }
    
            $query .= " LIMIT $start_from, $records_per_page";
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
    
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getTotalRowsOfSearch($search_query, $locatie_id) {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM {$this->table_name} WHERE naam LIKE :search_query";
            $params = [':search_query' => "%$search_query%"];
    
            if ($locatie_id !== null && $locatie_id !== 'everything') {
                $query .= " AND locatie_id = :locatie_id";
                $params[':locatie_id'] = $locatie_id;
            }
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];
    
            return $total_rows;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    public function getTotalRowsOfSearchNoId($search_query) {
        try {
            $query = "SELECT COUNT(*) AS total_rows FROM {$this->table_name} WHERE naam LIKE :search_query";
            $params = [':search_query' => "%$search_query%"];
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];
    
            return $total_rows;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    
    public function searchDetentieHistorieNoId($search_query, $start_from, $records_per_page) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE naam LIKE :search_query";
            $params = [':search_query' => "%$search_query%"];
    
            $query .= " LIMIT $start_from, $records_per_page";
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
    
            return $stmt;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }

    

    
}
?>
