<?php 

class Cellen {
    private $pdo;
    private $table_name = 'Cellen';

    public function __construct($pdo)
    {
        $this->pdo=$pdo;
    }

    public function getCellen()
    {
        $query = "SELECT * from " . $this->table_name ."
        ORDER BY cel_id";
        
        $stmt = $this->pdo->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    public function getCellenSelector()
    {
        try {
            $query = "SELECT c.cel_id, l.locatienaam
                      FROM " . $this->table_name . " c
                      INNER JOIN locaties l ON c.locatie_id = l.locatie_id
                      ORDER BY c.cel_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function getCelById($cel_id)
    {
        try {
            $query = "SELECT * FROM cellen WHERE cel_id = :cel_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':cel_id', $cel_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function getCellenByLocatie($start_from, $records_per_page, $locatie_id = null)
    {
        if ($locatie_id === 'everything') {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY cel_id LIMIT :start_from, :records_per_page";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $query = "SELECT * FROM " . $this->table_name;
        
            if ($locatie_id !== null) {
                $query .= " WHERE cel_id IN (SELECT cel_id FROM cellen WHERE locatie_id = :locatie_id)";
            }
        
            $query .= " ORDER BY cel_id LIMIT :start_from, :records_per_page";
        
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

    public function addCell($locatie_id, $arrestant_id)
{
    try {
        $query = "INSERT INTO " . $this->table_name . " (locatie_id, arrestant_id) VALUES (:locatie_id, :arrestant_id)";
        $stmt = $this->pdo->prepare($query);
        
        $stmt->bindParam(':locatie_id', $locatie_id, PDO::PARAM_INT);
        $stmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public function associateArrestantWithCell($arrestant_id, $cell_id)
{
    try {
        $query = "UPDATE arrestanten SET cel_id = :cell_id WHERE arrestant_id = :arrestant_id";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':cell_id', $cell_id, PDO::PARAM_INT);
        $stmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);

        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}

public function updateCell($cel_id, $arrestant_id)
    {
        try {
            $this->pdo->beginTransaction();

            $updateCellQuery = "UPDATE {$this->table_name} SET arrestant_id = :arrestant_id WHERE cel_id = :cel_id";
            $updateCellStmt = $this->pdo->prepare($updateCellQuery);
            $updateCellStmt->bindParam(':cel_id', $cel_id, PDO::PARAM_INT);
            $updateCellStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
            $updateCellStmt->execute();

            $updateArrestantQuery = "UPDATE arrestanten SET cel_id = :cel_id WHERE arrestant_id = :arrestant_id";
            $updateArrestantStmt = $this->pdo->prepare($updateArrestantQuery);
            $updateArrestantStmt->bindParam(':cel_id', $cel_id, PDO::PARAM_INT);
            $updateArrestantStmt->bindParam(':arrestant_id', $arrestant_id, PDO::PARAM_INT);
            $updateArrestantStmt->execute();

            $this->pdo->commit();

            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getTotalRows() {
        $query = "SELECT COUNT(cel_id) as aantal FROM " . $this->table_name;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['aantal'];
    }

}