<?php

class Arrestanten {
    private $pdo;
    private $table_name = 'Arrestanten';

    public function __construct($pdo)
    {
        $this->pdo = $pdo;  
    }

    public function getArrestanten($start_from, $records_per_page)
    {
        $query = "SELECT * from " . $this->table_name ."
        ORDER BY naam, woonplaats
        LIMIT :start_from, :records_per_page";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

      public function getArrestantById($arrestant_id)
      {
          $query = "SELECT * FROM " . $this->table_name . " WHERE arrestant_id = :arrestant_id";
          $stmt = $this->pdo->prepare($query);
          $stmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
          $stmt->execute();
          return $stmt->fetch(PDO::FETCH_ASSOC);
      }

      public function getArrestantByZaak($zaak_id)
      {
          $query = "SELECT * FROM " . $this->table_name . " WHERE zaak_id = :zaak_id";
          $stmt = $this->pdo->prepare($query);
          $stmt->bindParam(':zaak_id', $zaak_id, PDO::PARAM_INT);
          $stmt->execute();
          return $stmt;
      }
    

    public function deleteArrestantById($arrestant_id)
{
    try {
        $this->pdo->beginTransaction();

        $selectQuery = "SELECT * FROM {$this->table_name} WHERE arrestant_id = :arrestant_id";
        $selectStmt = $this->pdo->prepare($selectQuery);
        $selectStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
        $selectStmt->execute();
        $arrestant = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if ($arrestant) {
            $arrestant_id = $arrestant['arrestant_id'];
            $naam = $arrestant['naam'];
            $cel_id = $arrestant['cel_id'];
            $locatie_id = $arrestant['locatie_id'];
            $insluitingsdatum = $arrestant['insluitingsdatum'];
            $uitsluitingsdatum = $arrestant['uitsluitingsdatum'];
            $maatschappelijke_aantekeningen = $arrestant['maatschappelijke_aantekeningen'];
            $arrestatiereden = $arrestant['arrestatiereden'];

            $insertQuery = "INSERT INTO detentiehistorie (arrestant_id, naam, cel_id, locatie_id, insluitingsdatum, uitsluitingsdatum, maatschappelijke_aantekeningen, arrestatiereden) 
                            VALUES (:arrestant_id, :naam, :cel_id, :locatie_id, :insluitingsdatum, :uitsluitingsdatum, :maatschappelijke_aantekeningen, :arrestatiereden)";
            $insertStmt = $this->pdo->prepare($insertQuery);
            $insertStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':naam', $naam);
            $insertStmt->bindParam(':cel_id', $cel_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
            $insertStmt->bindParam(':insluitingsdatum', $insluitingsdatum);
            $insertStmt->bindParam(':uitsluitingsdatum', $uitsluitingsdatum);
            $insertStmt->bindParam(':maatschappelijke_aantekeningen', $maatschappelijke_aantekeningen);
            $insertStmt->bindParam(':arrestatiereden', $arrestatiereden);
            $insertStmt->execute();
        } else {
            echo "Error: Arrestant details not found.";
        }

        $updateQuery = "UPDATE cellen SET arrestant_id = NULL WHERE arrestant_id = :arrestant_id";
        $updateStmt = $this->pdo->prepare($updateQuery);
        $updateStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
        $updateStmt->execute();

        
        $deleteQuery = "DELETE FROM {$this->table_name} WHERE arrestant_id = :arrestant_id";

        
        $deleteStmt = $this->pdo->prepare($deleteQuery);

        
        $deleteStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);

       
        $deleteStmt->execute();

        
        $this->pdo->commit();

        return true;
    } catch (PDOException $e) {
        
        $this->pdo->rollBack();
        echo "Error: " . $e->getMessage();
        return false;
    }
}

      
        
        public function getArrestantenSelector()
        {
            $query = "SELECT arrestant_id, naam FROM " . $this->table_name . " ORDER BY naam";
    
            $stmt = $this->pdo->query($query);
    
            $arrestanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return $arrestanten;
        }
    


      public function searchArrestants($search_query, $locatie_id, $start_from, $records_per_page) {
        try {
            $query = "SELECT * FROM arrestanten WHERE naam LIKE :search_query";
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
            $query = "SELECT COUNT(*) AS total_rows FROM arrestanten WHERE naam LIKE :search_query";
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
    
    public function getArrestantenByLocatie($start_from, $records_per_page, $locatie_id = null)
{
    if ($locatie_id === 'everything') {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY naam, woonplaats LIMIT :start_from, :records_per_page";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $query = "SELECT * FROM " . $this->table_name;
    
        if ($locatie_id !== null) {
            $query .= " WHERE locatie_id = :locatie_id";
        }
    
        $query .= " ORDER BY naam, woonplaats LIMIT :start_from, :records_per_page";
    
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
        $query = "SELECT COUNT(arrestant_id) as aantal FROM " . $this->table_name;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['aantal'];
    }

    public function getTotalRowsOfLocatie($locatie_id = null) {
        $query = "SELECT COUNT(arrestant_id) as aantal FROM " . $this->table_name;
    
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
    
    public function addArrestant($naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto, $locatie_id)
{
    $query = "INSERT INTO {$this->table_name} (naam, arrestatiereden, BSN_nummer, adres, woonplaats, datumarrestatie, zaak_id, insluitingsdatum, uitsluitingsdatum, maatschappelijke_aantekeningen, pasfoto, locatie_id) VALUES (:naam, :arrestatiereden, :bsn, :adres, :woonplaats, :datumarrestatie, :zaak_id, :insluitingsdatum, :uitsluitingsdatum, :maatschappelijke_aantekeningen, :pasfoto, :locatie_id)";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':naam', $naam);
    $stmt->bindParam(':arrestatiereden', $arrestatiereden);
    $stmt->bindParam(':bsn', $bsn);
    $stmt->bindParam(':adres', $adres);
    $stmt->bindParam(':woonplaats', $woonplaats);
    $stmt->bindParam(':datumarrestatie', $datumarrestatie);
    $stmt->bindParam(':zaak_id', $zaak_id);
    $stmt->bindParam(':insluitingsdatum', $insluitingsdatum);
    $stmt->bindParam(':uitsluitingsdatum', $uitsluitingsdatum);
    $stmt->bindParam(':maatschappelijke_aantekeningen', $maatschappelijke_aantekeningen);
    $stmt->bindParam(':pasfoto', $pasfoto, PDO::PARAM_LOB); 
    $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
    $stmt->execute();

    $arrestant_id = $this->pdo->lastInsertId();

    return $arrestant_id;
}



public function editArrestant($arrestant_id, $naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto, $locatie_id)
{
    $query = "UPDATE {$this->table_name} SET naam=:naam, arrestatiereden=:arrestatiereden, BSN_nummer=:bsn, adres=:adres, woonplaats=:woonplaats, datumarrestatie=:datumarrestatie, zaak_id=:zaak_id, insluitingsdatum=:insluitingsdatum, uitsluitingsdatum=:uitsluitingsdatum, maatschappelijke_aantekeningen=:maatschappelijke_aantekeningen, locatie_id=:locatie_id";
    
    if (!empty($pasfoto)) {
        $query .= ", pasfoto=:pasfoto";
    }

    $query .= " WHERE arrestant_id=:arrestant_id";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':naam', $naam);
    $stmt->bindParam(':arrestatiereden', $arrestatiereden);
    $stmt->bindParam(':bsn', $bsn);
    $stmt->bindParam(':adres', $adres);
    $stmt->bindParam(':woonplaats', $woonplaats);
    $stmt->bindParam(':datumarrestatie', $datumarrestatie);
    $stmt->bindParam(':zaak_id', $zaak_id);
    $stmt->bindParam(':insluitingsdatum', $insluitingsdatum);
    $stmt->bindParam(':uitsluitingsdatum', $uitsluitingsdatum);
    $stmt->bindParam(':maatschappelijke_aantekeningen', $maatschappelijke_aantekeningen);
    $stmt->bindParam(':locatie_id', $locatie_id);
    $stmt->bindParam(':arrestant_id', $arrestant_id);
    
    if (!empty($pasfoto)) {
        $stmt->bindParam(':pasfoto', $pasfoto, PDO::PARAM_LOB);
    }

    $stmt->execute();
}


public function moveArrestantsToDetentiehistorie() {
    $currentDate = date('Y-m-d');

    try {
            $query = "SELECT * FROM arrestanten WHERE uitsluitingsdatum <= :currentDate";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':currentDate', $currentDate);
            $stmt->execute();
            $arrestants = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($arrestants) {
            foreach ($arrestants as $arrestant) {
                $insertQuery = "INSERT INTO detentiehistorie (arrestant_id, naam, cel_id, locatie_id, insluitingsdatum, uitsluitingsdatum, maatschappelijke_aantekeningen, arrestatiereden) 
                                VALUES (:arrestant_id, :naam, :cel_id, :locatie_id, :insluitingsdatum, :uitsluitingsdatum, :maatschappelijke_aantekeningen, :arrestatiereden)";
                $insertStmt = $this->pdo->prepare($insertQuery);
                $insertStmt->bindParam(':arrestant_id', $arrestant['arrestant_id']);
                $insertStmt->bindParam(':naam', $arrestant['naam']);
                $insertStmt->bindValue(':cel_id', ''); 
                $insertStmt->bindParam(':locatie_id', $arrestant['locatie_id']);
                $insertStmt->bindParam(':insluitingsdatum', $arrestant['insluitingsdatum']);
                $insertStmt->bindParam(':uitsluitingsdatum', $arrestant['uitsluitingsdatum']);
                $insertStmt->bindParam(':maatschappelijke_aantekeningen', $arrestant['maatschappelijke_aantekeningen']);
                $insertStmt->bindParam(':arrestatiereden', $arrestant['arrestatiereden']);
                $insertStmt->execute();


                   
                $updateQuery = "UPDATE cellen SET arrestant_id = NULL WHERE arrestant_id = :arrestant_id";
                $updateStmt = $this->pdo->prepare($updateQuery);
                $updateStmt->bindParam(':arrestant_id', $arrestant['arrestant_id']);
                $updateStmt->execute();

                $deleteQuery = "DELETE FROM arrestanten WHERE arrestant_id = :arrestant_id";
                $deleteStmt = $this->pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':arrestant_id', $arrestant['arrestant_id']);
                $deleteStmt->execute();
            }
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

public function getArrestantenByLocatieSelector($locatie_id)
{
    try {
        $query = "SELECT arrestant_id, naam FROM " . $this->table_name . " WHERE locatie_id = :locatie_id ORDER BY naam";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $arrestanten = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $arrestanten;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

public function getArrestantenByLocatieSelectorWithoutCell($locatie_id)
{
    try {
        $query = "SELECT arrestant_id, naam FROM " . $this->table_name . " WHERE locatie_id = :locatie_id AND (cel_id IS NULL OR cel_id = 0)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        $stmt->execute();

        $arrestanten = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $arrestanten;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}



}