<?php

class Zaken {
    private $pdo;
    private $table_name = 'Zaken';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;  
    }

    public function getZaken($start_from, $records_per_page)
    {
        $query = "SELECT * from " . $this->table_name ."
        ORDER BY zaak_id
        LIMIT :start_from, :records_per_page";

        $stmt = $this->pdo->prepare( $query );
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    public function deleteZaakById($zaak_id)
        {
            try {
                $query = "DELETE FROM {$this->table_name} WHERE zaak_id = :zaak_id";

                $stmt = $this->pdo->prepare($query);

                $stmt->bindParam(':zaak_id', $zaak_id, PDO::PARAM_INT);

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

    public function getZakenByLocatie($start_from, $records_per_page, $locatie_id = null)
    {
        if ($locatie_id === 'everything') {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY zaak_id LIMIT :start_from, :records_per_page";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $query = "SELECT * FROM " . $this->table_name;
        
            if ($locatie_id !== null) {
                $query .= " WHERE locatie_id = :locatie_id";
            }
        
            $query .= " ORDER BY zaak_id, zaaknummer LIMIT :start_from, :records_per_page";
        
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
    

       public function getZaakById($zaak_id)
       {
           $query = "SELECT * FROM " . $this->table_name . " WHERE zaak_id = :zaak_id";
           $stmt = $this->pdo->prepare($query);
           $stmt->bindParam(':zaak_id', $zaak_id, PDO::PARAM_INT);
           $stmt->execute();
           return $stmt->fetch(PDO::FETCH_ASSOC);
       }

       public function addZaak($zaaknummer, $arrestanten, $arrestatiereden, $notities, $locatie_id)
       {
           try {
               $this->pdo->beginTransaction();
       
               $insertZaakQuery = "INSERT INTO {$this->table_name} (zaaknummer, arrestatiereden, notities, locatie_id) VALUES (:zaaknummer, :arrestatiereden, :notities, :locatie_id)";
               $stmt = $this->pdo->prepare($insertZaakQuery);
               $stmt->bindParam(':zaaknummer', $zaaknummer);
               $stmt->bindParam(':arrestatiereden', $arrestatiereden);
               $stmt->bindParam(':notities', $notities);
               $stmt->bindParam(':locatie_id', $locatie_id);
               $stmt->execute();
       
               $zaak_id = $this->pdo->lastInsertId();
       
               $updateArrestantQuery = "UPDATE Arrestanten SET zaak_id = :zaak_id WHERE arrestant_id = :arrestant_id";
               $stmt = $this->pdo->prepare($updateArrestantQuery);
               $stmt->bindParam(':zaak_id', $zaak_id); 
               foreach ($arrestanten as $arrestant_id) {
                   $stmt->bindParam(':arrestant_id', $arrestant_id);
                   $stmt->execute();
               }
       
               $this->pdo->commit();
       
               return true; 
           } catch (PDOException $e) {
               $this->pdo->rollback();
               echo "Error: " . $e->getMessage();
               return false; 
           }
       }
       
    
       public function getTotalRows() {
        $query = "SELECT COUNT(zaak_id) as aantal FROM " . $this->table_name;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['aantal'];
    }

    public function getTotalRowsOfLocatie($locatie_id = null) {
        $query = "SELECT COUNT(zaak_id) as aantal FROM " . $this->table_name;
    
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
    public function editZaak($zaak_id, $arrestanten, $zaaknummer, $arrestatiereden, $notities, $locatie_id) {
        try {
            $this->pdo->beginTransaction();
            
            $query = "UPDATE {$this->table_name} SET zaaknummer=:zaaknummer, arrestatiereden=:arrestatiereden, notities=:notities, locatie_id=:locatie_id WHERE zaak_id=:zaak_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':zaaknummer', $zaaknummer);
            $stmt->bindParam(':arrestatiereden', $arrestatiereden);
            $stmt->bindParam(':notities', $notities);
            $stmt->bindParam(':locatie_id', $locatie_id);
            $stmt->bindParam(':zaak_id', $zaak_id);
            $stmt->execute();
            
            $clearAssociationsQuery = "UPDATE Arrestanten SET zaak_id = NULL WHERE zaak_id = :zaak_id";
            $stmt = $this->pdo->prepare($clearAssociationsQuery);
            $stmt->bindParam(':zaak_id', $zaak_id);
            $stmt->execute();
            
            $updateArrestantQuery = "UPDATE Arrestanten SET zaak_id = :zaak_id WHERE arrestant_id = :arrestant_id";
            $stmt = $this->pdo->prepare($updateArrestantQuery);
            $stmt->bindParam(':zaak_id', $zaak_id); 
            foreach ($arrestanten as $arrestant_id) {
                $stmt->bindParam(':arrestant_id', $arrestant_id);
                $stmt->execute();
            }
            
            $this->pdo->commit();
            
            return true; // Success
        } catch (PDOException $e) {
            $this->pdo->rollback();
            echo "Error: " . $e->getMessage();
            return false; 
        }
    }
    
    public function searchZaak($search_query, $locatie_id, $start_from, $records_per_page) {
        try {
            $query = "SELECT * FROM {$this->table_name} WHERE zaaknummer LIKE :search_query";
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
            $query = "SELECT COUNT(*) AS total_rows FROM {$this->table_name} WHERE zaaknummer LIKE :search_query";
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
    
    
}