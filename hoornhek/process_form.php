<?php
// Include the database connection file and the Arrestanten class file
require_once './database/database.php';
require_once './classes/Arrestanten.class.php';
require_once './classes/Cellen.class.php';
require_once './classes/Zaken.class.php';
class DataProcessor
{
    private $pdo;
    private $arrestanten;
    private $cellen;
    private $zaken;
    public function __construct($pdo, $arrestanten = null, $zaken = null)
    {
        $this->pdo = $pdo;
        $this->arrestanten = $arrestanten;
        $this->cellen = new Cellen($pdo); // Instantiate the Cellen class
        $this->zaken = new Zaken($pdo); // Adjust the constructor parameters if needed
    }

    public function handleRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $action = isset($_POST["action"]) ? $_POST["action"] : 'LEEG';
            switch ($action) {
                case "AddArrestant":
                    $this->addArrestant();
                    break;
                case "EditArrestant":
                    $this->editArrestant();
                    break;
                case "EditZaak":
                    $this->processEditZaak();
                    break;
                case "addzaak":
                    $this->HandleProcessaddZaak();
                    break;
                case "addcel": // Add this case for handling add cell action
                    $this->addCellHandler();
                    break;
                case "updatecell": // Add this case for handling update cell action
                    $this->updateCellHandler();
                    break;
                case "LEEG":
                default:
                    echo "geen geldige actie...";
            }
        } else {
            header('url=index.php');
        }
    }

//     private function addArrestant()
// {
//     // Retrieve data from the form
//     $naam = isset($_POST['naam']) ? $_POST['naam'] : "";
//     $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
//     $bsn = isset($_POST['BSN_nummer']) ? $_POST['BSN_nummer'] : "";
//     $adres = isset($_POST['adres']) ? $_POST['adres'] : "";
//     $woonplaats = isset($_POST['woonplaats']) ? $_POST['woonplaats'] : "";
//     $datumarrestatie = isset($_POST['datumarrestatie']) ? $_POST['datumarrestatie'] : "";
//     $zaak_id = isset($_POST['zaak_id']) ? $_POST['zaak_id'] : "";
//     $cel_id = isset($_POST['cel_id']) ? $_POST['cel_id'] : "";
//     $insluitingsdatum = isset($_POST['insluitingsdatum']) ? $_POST['insluitingsdatum'] : "";
//     $uitsluitingsdatum = isset($_POST['uitsluitingsdatum']) ? $_POST['uitsluitingsdatum'] : "";
//     $maatschappelijke_aantekeningen = isset($_POST['maatschappelijke_aantekeningen']) ? $_POST['maatschappelijke_aantekeningen'] : "";

//      // Check if pasfoto is provided
//      if (!empty($_FILES['pasfoto']['name'])) {
//         $pasfoto = file_get_contents($_FILES['pasfoto']['tmp_name']); // Read image data
//     } else {
//         // Pasfoto not provided, set it to null
//         $pasfoto = null;
//     }
//     // Call the addArrestant method from the Arrestanten class
//     $this->arrestanten->addArrestant($naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $cel_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto);

//     // Redirect or display success message
//     // Example:
//     echo "<p>New arrestant added successfully.</p>";
//     header('location:arrestanten.php');
// }



// this is a function to add an arrestant and it will create a new cell as well and assign the arrestant to it


// private function addArrestant()
//     {
//         // Retrieve data from the form
//         $naam = isset($_POST['naam']) ? $_POST['naam'] : "";
//         $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
//         $bsn = isset($_POST['BSN_nummer']) ? $_POST['BSN_nummer'] : "";
//         $adres = isset($_POST['adres']) ? $_POST['adres'] : "";
//         $woonplaats = isset($_POST['woonplaats']) ? $_POST['woonplaats'] : "";
//         $datumarrestatie = isset($_POST['datumarrestatie']) ? $_POST['datumarrestatie'] : "";
//         $zaak_id = isset($_POST['zaak_id']) ? $_POST['zaak_id'] : "";
//         $insluitingsdatum = isset($_POST['insluitingsdatum']) ? $_POST['insluitingsdatum'] : "";
//         $uitsluitingsdatum = isset($_POST['uitsluitingsdatum']) ? $_POST['uitsluitingsdatum'] : "";
//         $maatschappelijke_aantekeningen = isset($_POST['maatschappelijke_aantekeningen']) ? $_POST['maatschappelijke_aantekeningen'] : "";

//         // Check if pasfoto is provided
//         if (!empty($_FILES['pasfoto']['name'])) {
//             $pasfoto = file_get_contents($_FILES['pasfoto']['tmp_name']); // Read image data
//         } else {
//             // Pasfoto not provided, set it to null
//             $pasfoto = null;
//         }

//         // Retrieve the selected location ID from the form
//         $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : null;

//         // Call the addCell method from the Cellen class to add a new cell on the selected location
//         $cell_id = $this->cellen->addCell($locatie_id);

//         if ($cell_id) {
//             // Call the addArrestant method from the Arrestanten class and pass the newly created cell_id
//             // $this->arrestanten->addArrestant($naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $cell_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto);
        

//                 // Call the addArrestant method from the Arrestanten class and capture the returned arrestant_id
//     $arrestant_id = $this->arrestanten->addArrestant($naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $cell_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto);

//         // After adding the arrestant, associate the arrestant with the cell
//         $this->cellen->associateArrestantWithCell($arrestant_id, $cell_id);
    
//             // Redirect or display success message
//             // Example:
//             echo "<p>New arrestant added successfully.</p>";
//             header('location:arrestanten.php');
//         } else {
//             // Handle error: Failed to add cell
//             echo "<p>Error: Failed to add cell.</p>";
//         }
//     }


private function addArrestant()
{
    // Retrieve data from the form
    $naam = isset($_POST['naam']) ? $_POST['naam'] : "";
    $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
    $bsn = isset($_POST['BSN_nummer']) ? $_POST['BSN_nummer'] : "";
    $adres = isset($_POST['adres']) ? $_POST['adres'] : "";
    $woonplaats = isset($_POST['woonplaats']) ? $_POST['woonplaats'] : "";
    $datumarrestatie = isset($_POST['datumarrestatie']) ? $_POST['datumarrestatie'] : "";
    $zaak_id = isset($_POST['zaak_id']) ? $_POST['zaak_id'] : "";
    $insluitingsdatum = isset($_POST['insluitingsdatum']) ? $_POST['insluitingsdatum'] : "";
    $uitsluitingsdatum = isset($_POST['uitsluitingsdatum']) ? $_POST['uitsluitingsdatum'] : "";
    $maatschappelijke_aantekeningen = isset($_POST['maatschappelijke_aantekeningen']) ? $_POST['maatschappelijke_aantekeningen'] : "";

    // Check if pasfoto is provided
    if (!empty($_FILES['pasfoto']['name'])) {
        $pasfoto = file_get_contents($_FILES['pasfoto']['tmp_name']); // Read image data
    } else {
        // Pasfoto not provided, set it to null
        $pasfoto = null;
    }

    // Retrieve the selected location ID from the form
    $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : null;

    // Call the addArrestant method from the Arrestanten class and pass the locatie_id
    $arrestant_id = $this->arrestanten->addArrestant(
        $naam,
        $arrestatiereden,
        $bsn,
        $adres,
        $woonplaats,
        $datumarrestatie,
        $zaak_id,
        $insluitingsdatum,
        $uitsluitingsdatum,
        $maatschappelijke_aantekeningen,
        $pasfoto,
        $locatie_id
    );

    if ($arrestant_id) {
        // Redirect or display success message
        // Example:
        echo "<p>New arrestant added successfully.</p>";
        header('location:arrestanten.php');
        exit; // Add this line to prevent further execution
    } else {
        // Handle error: Failed to add arrestant
        echo "<p>Error: Failed to add arrestant.</p>";
    }
}


private function editArrestant()
{
    // Retrieve data from the form
    $arrestant_id = isset($_POST['arrestant_id']) ? $_POST['arrestant_id'] : "";
    $naam = isset($_POST['naam']) ? $_POST['naam'] : "";
    $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
    $bsn = isset($_POST['BSN_nummer']) ? $_POST['BSN_nummer'] : "";
    $adres = isset($_POST['adres']) ? $_POST['adres'] : "";
    $woonplaats = isset($_POST['woonplaats']) ? $_POST['woonplaats'] : "";
    $datumarrestatie = isset($_POST['datumarrestatie']) ? $_POST['datumarrestatie'] : "";
    $zaak_id = isset($_POST['zaak_id']) ? $_POST['zaak_id'] : "";
    $insluitingsdatum = isset($_POST['insluitingsdatum']) ? $_POST['insluitingsdatum'] : "";
    $uitsluitingsdatum = isset($_POST['uitsluitingsdatum']) ? $_POST['uitsluitingsdatum'] : "";
    $maatschappelijke_aantekeningen = isset($_POST['maatschappelijke_aantekeningen']) ? $_POST['maatschappelijke_aantekeningen'] : "";
    $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : "";

    // Check if pasfoto is provided
    if (!empty($_FILES['pasfoto']['name'])) {
        $pasfoto = file_get_contents($_FILES['pasfoto']['tmp_name']); // Read image data
    } else {
        // Pasfoto not provided, set it to null
        $pasfoto = null;
    }

    // Call the editArrestant method from the Arrestanten class
    $this->arrestanten->editArrestant($arrestant_id, $naam, $arrestatiereden, $bsn, $adres, $woonplaats, $datumarrestatie, $zaak_id, $insluitingsdatum, $uitsluitingsdatum, $maatschappelijke_aantekeningen, $pasfoto, $locatie_id);

    // Redirect or display success message
    // Example:
    echo "<p>Arrestant updated successfully.</p>";
    header('location:arrestanten.php');
}

private function HandleProcessaddZaak()
{
    // Retrieve data from the form
    $zaaknummer = isset($_POST['zaaknummer']) ? $_POST['zaaknummer'] : "";
    $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
    $notities = isset($_POST['notities']) ? $_POST['notities'] : "";
    // $arrestanten = isset($_POST['arrestanten']) ? $_POST['arrestanten'] : array();
    $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : null;

      // Check if the 'arrestanten' field is disabled and 'arrestanten_hidden' field exists
      if (isset($_POST['arrestanten_hidden']) && !isset($_POST['arrestanten'])) {
        // Use the value of 'arrestanten_hidden'
        $arrestanten = json_decode($_POST['arrestanten_hidden'], true);
    } else {
        $arrestanten = isset($_POST['arrestanten']) ? $_POST['arrestanten'] : array();
    }
    // Call the addZaak method from the Zaken class
    $success = $this->zaken->addZaak($zaaknummer, $arrestanten, $arrestatiereden, $notities, $locatie_id);

    // Check if the operation was successful
    if ($success) {
        echo "<p>New zaak added successfully.</p>";
        header('Location: zaken.php');
        exit; // Add this line to prevent further execution
    } else {
        echo "<p>Failed to add new zaak.</p>";
        // You may choose to handle the failure differently, such as displaying an error message
    }
}

private function processEditZaak() {
    // Retrieve data from the form
    $zaak_id = isset($_POST['zaak_id']) ? $_POST['zaak_id'] : "";
    $zaaknummer = isset($_POST['zaaknummer']) ? $_POST['zaaknummer'] : "";
    $arrestatiereden = isset($_POST['arrestatiereden']) ? $_POST['arrestatiereden'] : "";
    $notities = isset($_POST['notities']) ? $_POST['notities'] : "";
    $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : "";

    $arrestanten = isset($_POST['arrestanten']) ? $_POST['arrestanten'] : array();
    
    // Call the editZaak method from the Zaken class
    $success = $this->zaken->editZaak($zaak_id, $arrestanten, $zaaknummer, $arrestatiereden, $notities, $locatie_id);

     // Check if the operation was successful
     if ($success) {
        echo "<p>zaak: " . $zaaknummer . " added successfully.</p>";
        header('Location: zaken.php');
        exit; // Add this line to prevent further execution
    } else {
        echo "<p>Failed to add new zaak.</p>";
        // You may choose to handle the failure differently, such as displaying an error message
    }
}

private function addCellHandler()
{
    // Retrieve data from the form
    $locatie_id = isset($_POST['locatie']) ? $_POST['locatie'] : null;
    $arrestant_id = isset($_POST['arrestant']) ? $_POST['arrestant'] : null;

    // Check if locatie_id is provided
    if ($locatie_id && $arrestant_id) {
        // Call the addCell method from the Cellen class
        $cell_id = $this->cellen->addCell($locatie_id, $arrestant_id);

        if ($cell_id) {
            // Associate the arrestant with the cell
            $this->cellen->associateArrestantWithCell($arrestant_id, $cell_id);

            // Redirect or display success message
            echo "<p>New cell added successfully.</p>";
            header('Location: cellen.php');
            exit; // Add this line to prevent further execution
        } else {
            // Handle error: Failed to add cell
            echo "<p>Error: Failed to add cell.</p>";
        }
    } else {
        // Handle error: Locatie or arrestant not provided
        echo "<p>Error: Locatie or arrestant not provided.</p>";
    }
}

private function updateCellHandler()
{
    // Retrieve data from the form
    $cel_id = isset($_POST['cel_id']) ? $_POST['cel_id'] : "";
    $arrestant_id = isset($_POST['arrestant']) ? $_POST['arrestant'] : null;

    // Check if cel_id and arrestant_id are provided
    if ($cel_id && $arrestant_id) {
        // Call the updateCell method from the Cellen class
        $success = $this->cellen->updateCell($cel_id, $arrestant_id);
        
        if ($success) {
            // Redirect or display success message
            echo "<p>Cell updated successfully.</p>";
            header('Location: cellen.php');
            exit; // Add this line to prevent further execution
        } else {
            // Handle error: Failed to update cell
            echo "<p>Error: Failed to update cell.</p>";
        }
    } else {
        // Handle error: cel_id or arrestant_id not provided
        echo "<p>Error: cel_id or arrestant_id not provided.</p>";
    }
}


}

// Create an instance of the Arrestanten class
$arrestanten = new Arrestanten($pdo);

// Create an instance of the DataProcessor class
$dataProcessor = new DataProcessor($pdo, $arrestanten, $zaken);

// Call the handleRequest method to handle the request
$dataProcessor->handleRequest();
?>
