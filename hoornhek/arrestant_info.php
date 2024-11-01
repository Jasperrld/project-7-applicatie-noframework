<?php 
include('inc/hoornhek_header.php'); 
include('classes/Arrestanten.class.php');

$arrestanten = new Arrestanten($pdo);

// Get the arrestant ID from the URL
$arrestant_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($arrestant_id !== null) {
    // Fetch arrestant details by ID
    $arrestant = $arrestanten->getArrestantById($arrestant_id);
    
    // Display arrestant details
    if ($arrestant) {
        echo "<div class='arrestanten-info'>";
        echo "<button class='Terug' onclick=\"window.location.href='./arrestanten.php'\">Terug</button>";
        echo "<h2>Arrestant Information</h2>";
        echo "<div class='arrestanten-info-container'>";
        echo "<img src='data:image/png;base64," . base64_encode($arrestant['pasfoto']) . "' alt='Pasfoto'>";
        echo "</div>";

        echo "<div class='arrestanten-details'>";
        echo "<div><strong>Naam:</strong> " . $arrestant['naam'] . "</div>";
        echo "<div><strong>Arrestatiereden:</strong> " . $arrestant['arrestatiereden'] . "</div>";
        echo "<div><strong>BSN Nummer:</strong> " . $arrestant['BSN_nummer'] . "</div>";
        echo "<div><strong>Adres:</strong> " . $arrestant['adres'] . "</div>";
        echo "<div><strong>Woonplaats:</strong> " . $arrestant['woonplaats'] . "</div>";
        echo "<div><strong>Datum arrestatie:</strong> " . $arrestant['datumarrestatie'] . "</div>";
        echo "<div><strong>Zaak ID:</strong> " . $arrestant['zaak_id'] . "</div>";
        echo "<div><strong>Cel ID:</strong> " . $arrestant['cel_id'] . "</div>";
        echo "<div><strong>Insluitingsdatum:</strong> " . $arrestant['insluitingsdatum'] . "</div>";
        echo "<div><strong>Uitsluitingsdatum:</strong> " . $arrestant['uitsluitingsdatum'] . "</div>";
        echo "<div><strong>Maatschappelijke aantekeningen:</strong> " . $arrestant['maatschappelijke_aantekeningen'] . "</div>";
        echo "</div>";

        echo "</div>";

    } else {
        echo "Arrestant not found.";
    }
} else {
    echo "Invalid arrestant ID.";
}

include('inc/hoornhek_footer.php'); 
?>
