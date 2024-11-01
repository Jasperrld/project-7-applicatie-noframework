<?php
// Include necessary files
include('./inc/hoornhek_header.php');
require_once './classes/Arrestanten.class.php';
include('./inc/check_role.php');
include('./classes/Locaties.class.php');

// Create an instance of the Arrestanten class
$arrestanten = new Arrestanten($pdo);

// Check if the arrestant ID is provided
if(isset($_GET['id'])) {
    // Get the arrestant details by ID
    $arrestant_id = $_GET['id'];
    $arrestant = $arrestanten->getArrestantById($arrestant_id);

    // Check if the arrestant exists
    if($arrestant) {
        $naam = $arrestant['naam'];
        $arrestatiereden = $arrestant['arrestatiereden'];
        $bsn = $arrestant['BSN_nummer'];
        $adres = $arrestant['adres'];
        $woonplaats = $arrestant['woonplaats'];
        $datumarrestatie = $arrestant['datumarrestatie'];
        $zaak_id = $arrestant['zaak_id'];
        $cel_id = $arrestant['cel_id'];
        $insluitingsdatum = $arrestant['insluitingsdatum'];
        $uitsluitingsdatum = $arrestant['uitsluitingsdatum'];
        $maatschappelijke_aantekeningen = $arrestant['maatschappelijke_aantekeningen'];
        $pasfoto = $arrestant['pasfoto'];
        $locatie_id = $arrestant['locatie_id'];
    } else {
        echo "<p>Arrestant not found.</p>";
        exit();
    }
} else {
    echo "<p>Arrestant ID not provided.</p>";
    exit();
}
?>


<div class="arrestanten-edit-container">
<button class="Terug" onclick="window.location.href='./arrestanten.php'">Terug</button>

    <h2>Edit Arrestant</h2>
    <form action="process_form.php" class="arrestanten-edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="EditArrestant">
        <input type="hidden" name="arrestant_id" value="<?php echo $arrestant_id; ?>">
        <input type="hidden" name="arrestatiereden" value="<?php echo $arrestatiereden; ?>">
        <input type="hidden" name="locatie" value="<?php echo $locatie_id; ?>">


        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" value="<?php echo $naam; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="arrestatiereden">Arrestatiereden:</label>
        <select id="arrestatiereden" name="arrestatiereden" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'disabled' : ''; ?>>
            <option value="Option 1"<?php if ($arrestatiereden === "Option 1") echo " selected"; ?>>Option 1</option>
            <option value="Option 2"<?php if ($arrestatiereden === "Option 2") echo " selected"; ?>>Option 2</option>
            <option value="Option 3"<?php if ($arrestatiereden === "Option 3") echo " selected"; ?>>Option 3</option>
            <option value="Option 4"<?php if ($arrestatiereden === "Option 4") echo " selected"; ?>>Option 4</option>
        </select><br>

        <label for="bsn">BSN-nummer:</label>
        <input type="text" id="bsn" name="BSN_nummer" value="<?php echo $bsn; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="adres">Adres:</label>
        <input type="text" id="adres" name="adres" value="<?php echo $adres; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="woonplaats">Woonplaats:</label>
        <input type="text" id="woonplaats" name="woonplaats" value="<?php echo $woonplaats; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="datumarrestatie">Datum Arrestatie:</label>
        <input type="text" id="datumarrestatie" name="datumarrestatie" value="<?php echo $datumarrestatie; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="zaak_id">Zaak ID:</label>
        <input type="text" id="zaak_id" name="zaak_id" value="<?php echo $zaak_id; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="insluitingsdatum">Insluitingsdatum:</label>
        <input type="text" id="insluitingsdatum" name="insluitingsdatum" value="<?php echo $insluitingsdatum; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="uitsluitingsdatum">Uitsluitingsdatum:</label>
        <input type="date" id="uitsluitingsdatum" name="uitsluitingsdatum" value="<?php echo $uitsluitingsdatum; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="maatschappelijke_aantekeningen">Maatschappelijke Aantekeningen:</label>
        <textarea id="maatschappelijke_aantekeningen" name="maatschappelijke_aantekeningen" <?php echo ($autRol == 'portier') ? 'readonly' : ''; ?>><?php echo $maatschappelijke_aantekeningen; ?></textarea><br>

        <label for="pasfoto">Pasfoto:</label>
        <input type="file" id="pasfoto" name="pasfoto" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'disabled' : ''; ?>><br>

        <label for="locatie">Locatie:</label>
        <select id="locatie" name="locatie" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'disabled' : ''; ?>>
            <?php
            // Fetch locaties from the database
            $locaties = new Locaties($pdo);
            $locatiesList = $locaties->getLocatiesSelector(); 

            // Iterate through each locatie and populate the select box
            foreach ($locatiesList as $locatie) {
                $selected = ($locatie['locatie_id'] == $locatie_id) ? 'selected' : '';
                echo "<option value='" . $locatie['locatie_id'] . "' $selected>" . $locatie['locatienaam'] . "</option>";
            }
            ?>
        </select><br>


        <input type="submit" value="Update" <?php echo ($autRol == 'portier') ? 'readonly' : ''; ?>>
    </form>
</div>
<?php include('./inc/hoornhek_footer.php'); ?>


