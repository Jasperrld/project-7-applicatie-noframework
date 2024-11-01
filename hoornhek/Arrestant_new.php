<?php 

include('inc/hoornhek_header.php');
include('inc/check_role.php');
include('classes/Cellen.class.php');
include('classes/Locaties.class.php');
include('inc/check_role.php');
// Check if the user's role is "maatschappelijkwerker"
if($autRol === 'maatschappelijkwerker' || $autRol === 'portier') {
    header('Location: arrestanten.php'); // Redirect to arrestanten.php
    exit; // Stop further execution
}

$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

?>

<div class="arrestanten-new-container">
<button class="Terug" onclick="window.location.href='./arrestanten.php'">Terug</button>
    <h2>Add New Arrestant</h2>
    <form action="process_form.php" class="arrestanten-new-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="AddArrestant">
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam"><br>

        <label for="arrestatiereden">Arrestatiereden:</label>
        <select id="arrestatiereden" name="arrestatiereden">
            <option value="Rijden onder invloed">Rijden onder invloed</option>
            <option value="Moord">Moord</option>
            <option value="Gestolen">Gestolen</option>
            <option value="Geweld">Geweld</option>
        </select><br>

        <label for="bsn">BSN-nummer:</label>
        <input type="text" id="bsn" name="BSN_nummer"><br>

        <label for="adres">Adres:</label>
        <input type="text" id="adres" name="adres"><br>

        <label for="woonplaats">Woonplaats:</label>
        <input type="text" id="woonplaats" name="woonplaats"><br>

        <label for="datumarrestatie">Datum Arrestatie:</label>
        <input type="date" id="datumarrestatie" name="datumarrestatie"><br>

        <label for="zaak_id">Zaak ID:</label>
        <input type="text" id="zaak_id" name="zaak_id"><br>

        <!-- <label for="cel_id">Cel ID:</label>
        <select id="cel_id" name="cel_id">
            <?php
            // Create an instance of the Cellen class
            $cellen = new Cellen($pdo);

            // Fetch cellen from the database
            $cellenList = $cellen->getCellenSelector(); 

            // Iterate through each cel and populate the select box
            foreach ($cellenList as $cel) {
                echo "<option value='" . $cel['cel_id'] . "'>" . $cel['cel_id'] . " - " . $cel['locatienaam'] . "</option>";
            }
            ?>
        </select><br> -->
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
</select><br>

        <label for="insluitingsdatum">Insluitingsdatum:</label>
        <input type="date" id="insluitingsdatum" name="insluitingsdatum"><br>

        <label for="uitsluitingsdatum">Uitsluitingsdatum:</label>
        <input type="date" id="uitsluitingsdatum" name="uitsluitingsdatum"><br>

        <label for="maatschappelijke_aantekeningen">Maatschappelijke Aantekeningen:</label>
        <textarea id="maatschappelijke_aantekeningen" name="maatschappelijke_aantekeningen"></textarea><br>

        <label for="pasfoto">Pasfoto:</label>
        <input type="file" id="pasfoto" name="pasfoto"><br>

        <input type="submit" value="Submit">
    </form>
</div>


<?php include('inc/hoornhek_footer.php'); ?>
