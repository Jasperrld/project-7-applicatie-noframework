<?php

include('./inc/hoornhek_header.php');
include('./classes/Locaties.class.php');
include('./classes/Arrestanten.class.php');
include('./classes/Cellen.class.php');
$arrestanten = new Arrestanten($pdo);
$cellen = new Cellen($pdo);

// Check if cel_id is provided for editing
if(isset($_GET['cel_id'])) {
    $cel_id = $_GET['cel_id'];

    // Fetch the details of the cell being edited
    // Assuming you have a method in your Cellen class to fetch cell details by cel_id
    $cell_details = $cellen->getCelById($cel_id); // Replace with your actual method name

    // Check if cell details are fetched successfully
    if($cell_details) {
        // Extract necessary details from $cell_details
        $locatie_id = $cell_details['locatie_id'];
        $arrestant_id = $cell_details['arrestant_id'];
?>

<div class="zaken-new-container">

<a href="cel_info.php?cel_id=<?php echo $cel_id; ?>" class="Terug">Terug</a>
        <br><br>
    <h2>Edit Cell</h2>
    <form action="process_form.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="updatecell"/>
        <input type="hidden" name="cel_id" value="<?php echo $cel_id; ?>"/>
            
        <label for="arrestant">Arrestant:</label>
        <select id="arrestant" name="arrestant">
            <?php
            // Fetch arrestanten for the selected locatie
            $selectedLocatieId = $locatie_id; // Assuming locatie_id is provided for the selected cell
            $arrestantenList = $arrestanten->getArrestantenByLocatieSelectorWithoutCell($selectedLocatieId);

            // Populate the select box with arrestanten
            foreach ($arrestantenList as $arrestant) {
                $selected = ($arrestant_id == $arrestant['arrestant_id']) ? 'selected' : '';
                echo "<option value='" . $arrestant['arrestant_id'] . "' $selected>" . $arrestant['naam'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Submit">
    </form>
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

include('./inc/hoornhek_footer.php');

?>
