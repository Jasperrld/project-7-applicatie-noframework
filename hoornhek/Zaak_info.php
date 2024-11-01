<?php 
include('inc/hoornhek_header.php'); 
include('classes/Zaken.class.php');
include('classes/Arrestanten.class.php');

$zaken = new Zaken($pdo);
$arrestanten = new Arrestanten( $pdo );
// Get the zaak ID from the URL
$zaak_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($zaak_id !== null) {
    // Fetch zaak details by ID
    $zaak = $zaken->getZaakById($zaak_id);
    
    // Display zaak details
    if ($zaak) {
        echo "<div class='zaak-info'>";
        echo "<button class='Terug' onclick=\"window.location.href='./zaken.php'\">Terug</button>";

        echo "<h2>Zaak Information</h2>";
        echo "<div class='zaak-details'>";
        echo "<p><strong>zaak_id:</strong> " . $zaak['zaak_id'] . "</p>";
        echo "<p><strong>locatie_id:</strong> " . $zaak['locatie_id'] . "</p>";
        echo "<p><strong>zaaknummer:</strong> " . $zaak['zaaknummer'] . "</p>";
        echo "<p><strong>arrestatiereden:</strong> " . $zaak['arrestatiereden'] . "</p>";
        echo "<p><strong>notities:</strong> " . $zaak['notities'] . "</p>";
        // Add more details as needed
        echo "</div>";

        // Fetch arrestants associated with this zaak
        $arrestants = $arrestanten->getArrestantByZaak($zaak_id);
        
        // Display arrestant details
        echo "<div class='arrestants-list'>";
        echo "<h2>Arrestanten</h2>";
        echo "<ul>";
        while ($arrestant = $arrestants->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>" . $arrestant['arrestant_id'] . ": " . $arrestant['naam'] . "</li>";
            // Display more arrestant details as needed
        }
        echo "</ul>";
        echo "</div>";

        echo "</div>";

    } else {
        echo "Zaak not found.";
    }
} else {
    echo "Invalid zaak ID.";
}

include('inc/hoornhek_footer.php'); 
?>
