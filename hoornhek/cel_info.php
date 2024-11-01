<?php
// Include necessary files
include('./inc/hoornhek_header.php');
include('./classes/Cellen.class.php');
include('./classes/Arrestanten.class.php');

// Create instances of Cellen and Arrestanten classes
$cellen = new Cellen($pdo);
$arrestanten = new Arrestanten($pdo);

// Check if cel_id is provided in the URL
if (isset($_GET['cel_id'])) {
    // Get the cel_id from the URL
    $cel_id = $_GET['cel_id'];

    // Fetch cell details by cel_id
    $cell_details = $cellen->getCelById($cel_id);

    // Check if cell details are fetched successfully
    if ($cell_details) {
        // Extract necessary details from $cell_details
        $locatie_id = $cell_details['locatie_id'];
        $arrestant_id = $cell_details['arrestant_id'];

        // Fetch arrestant details by arrestant_id
        $arrestant_details = $arrestanten->getArrestantById($arrestant_id);

        // Display cell information and associated arrestant
        ?>
        <div class="cel-info-container">
        <button class="Terug" onclick="window.location.href='./cellen.php'">Terug</button>
            <br><br>
            <h2>Cell Information</h2>
            <p><strong>Cell ID:</strong> <?php echo $cel_id; ?></p>
            <p><strong>Locatie ID:</strong> <?php echo $locatie_id; ?></p>
            <h3>Associated Arrestant</h3>
            <?php if ($arrestant_details) { ?>
                <p><strong>Arrestant ID:</strong> <?php echo $arrestant_details['arrestant_id']; ?></p>
                <p><strong>Naam:</strong> <?php echo $arrestant_details['naam']; ?></p>
                <!-- Display other arrestant details as needed -->
            <?php } else { ?>
                <p>No arrestant associated with this cell.</p>
            <?php } ?>

            <br>
            <a href="cel_edit.php?cel_id=<?php echo $cel_id; ?>" class="edit-link">
            <button class="edit-button">Edit Cell</button>
              </a>

        </div>
        <?php
    } else {
        // Error: Failed to fetch cell details
        echo "<p>Error: Failed to fetch cell details.</p>";
    }
} else {
    // Error: cel_id not provided
    echo "<p>Error: cel_id not provided.</p>";
}

// Include footer file
include('./inc/hoornhek_footer.php');
?>
