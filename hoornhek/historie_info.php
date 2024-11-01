<?php 
include('inc/hoornhek_header.php'); 
include('classes/Detentiehistorie.class.php');

$detentiehistorie = new DetentieHistorie($pdo);

// Get the arrestant ID from the URL
$detentie_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($detentie_id !== null) {
    // Fetch arrestant details by ID
    $detentiehistorie = $detentiehistorie->getDetentieHistorieById($detentie_id);
    
    // Display arrestant details
    if ($detentiehistorie) {
        echo "<div class='detentiehistorie-info'>";
        echo "<button class='Terug' onclick=\"window.location.href='./detentiehistorie.php'\">Terug</button>";
        echo "<h2>Detentiehistorie Information</h2>";
        echo "<div class='detentiehistorie-details'>";
        echo "<p><strong>detentie_id:</strong> " . $detentiehistorie['detentie_id'] . "</p>";
        echo "<p><strong>locatie_id:</strong> " . $detentiehistorie['locatie_id'] . "</p>";
        echo "<p><strong>arrestatiereden:</strong> " . $detentiehistorie['arrestatiereden'] . "</p>";
        echo "<p><strong>notities:</strong> " . $detentiehistorie['maatschappelijke_aantekeningen'] . "</p>";
        // Add more details as needed
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
