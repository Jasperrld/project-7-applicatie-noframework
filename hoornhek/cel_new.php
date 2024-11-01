<?php

include('./inc/hoornhek_header.php');
include('./classes/Locaties.class.php');
include('./classes/Arrestanten.class.php');

$arrestanten = new Arrestanten($pdo);

?>
<div class="zaken-new-container">

    <button class="Terug" onclick="window.location.href='./cellen.php'">Terug</button>

    <h2>Add Cell</h2>
    <form action="process_form.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="addcel" />
        <label for="locatie">Locatie:</label>
        <select id="locatie" name="locatie">
            <?php
            // Fetch locaties from the database
            $locaties = new Locaties($pdo);
            $locatiesList = $locaties->getLocatiesSelector();

            // Get the user's locatie_id
            $userLocatieId = isset($_SESSION['locatie_id']) ? $_SESSION['locatie_id'] : null;

            // Iterate through each locatie and populate the select box
            foreach ($locatiesList as $locatie) {
                $selected = ($userLocatieId && $locatie['locatie_id'] == $userLocatieId) ? 'selected' : '';
                echo "<option value='" . $locatie['locatie_id'] . "' $selected>" . $locatie['locatienaam'] . "</option>";
            }
            ?>
        </select>

        <label for="arrestant">Arrestant:</label>
        <select id="arrestant" name="arrestant">
            <?php
            // Fetch arrestanten for the selected locatie
            $selectedLocatieId = isset($_POST['locatie']) ? $_POST['locatie'] : $userLocatieId;
            $arrestantenList = $arrestanten->getArrestantenByLocatieSelectorWithoutCell($selectedLocatieId);

            // Populate the select box with arrestanten
            foreach ($arrestantenList as $arrestant) {
                echo "<option value='" . $arrestant['arrestant_id'] . "'>" . $arrestant['naam'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Submit">

    </form>

</div>

<?php

include('./inc/hoornhek_footer.php');

?>