<?php
// Include necessary files
include('./inc/hoornhek_header.php');
require_once './classes/Zaken.class.php';
include('./inc/check_role.php');
include('./classes/Locaties.class.php');
include('classes/Arrestanten.class.php');

include('inc/check_role.php');
// Check if the user's role is "maatschappelijkwerker"
if($autRol === 'maatschappelijkwerker' || $autRol === 'opnameofficier') {
    header('Location: zaken.php'); // Redirect to arrestanten.php
    exit; // Stop further execution
}
// Create an instance of the Arrestanten class
$zaken = new Zaken($pdo);
$arrestanten = new Arrestanten($pdo);

$arrestantenData = [];

// Check if the arrestant ID is provided
if(isset($_GET['id'])) {
    // Get the arrestant details by ID
    $zaak_id = $_GET['id'];
    $zaak = $zaken->getZaakById($zaak_id);

    // Check if the arrestant exists
    if($zaak) {
        $notities = $zaak['notities'];
        $arrestatiereden = $zaak['arrestatiereden'];
        $zaaknummer = $zaak['zaaknummer'];
        $locatie_id = $zaak['locatie_id'];
        $arrestantenData = $arrestanten->getArrestantenByLocatie(0, 1000, $locatie_id);

    } else {
        echo "<p>Zaak not found.</p>";
        exit();
    }
} else {
    echo "<p>Zaak ID not provided.</p>";
    exit();
}
?>


<div class="arrestanten-edit-container">
<button class="Terug" onclick="window.location.href='./zaken.php'">Terug</button>

    <h2>Edit Zaak</h2>
    <form action="process_form.php" class="arrestanten-edit-form" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="EditZaak">
        <input type="hidden" name="zaak_id" value="<?php echo $zaak_id; ?>">
        <input type="hidden" name="arrestatiereden" value="<?php echo $arrestatiereden; ?>">
        <input type="hidden" name="locatie" value="<?php echo $locatie_id; ?>">
        <input type="hidden" name="zaaknummer" value="<?php echo $zaaknummer; ?>">
        <input type="hidden" name="arrestanten_hidden" value="<?php echo htmlspecialchars(json_encode($arrestantenData)); ?>">


        <label for="zaaknummer">Zaaknummer:</label>
        <input type="text" id="zaaknummer" name="zaaknummer" value="<?php echo $zaaknummer; ?>" <?php echo ($autRol == 'maatschappelijkwerker' || $autRol == 'portier') ? 'readonly' : ''; ?>><br>

        <label for="arrestatiereden">Arrestatiereden:</label>
        <select id="arrestatiereden" name="arrestatiereden" <?php echo ($autRol == 'maatschappelijkwerker') ? 'disabled' : ''; ?>>
            <option value="Drugshandel"<?php if ($arrestatiereden === "Drugshandel") echo " selected"; ?>>Drugshandel</option>
            <option value="Overval"<?php if ($arrestatiereden === "Overval") echo " selected"; ?>>Overval</option>
            <option value="Witwasoperatie"<?php if ($arrestatiereden === "Witwasoperatie") echo " selected"; ?>>Witwasoperatie</option>
            <option value="Moordzaak"<?php if ($arrestatiereden === "Moordzaak") echo " selected"; ?>>Moordzaak</option>
        </select><br>

        <label for="notities">notities</label>
        <input type="text" id="notities" name="notities" value="<?php echo $notities; ?>" ><br>

        <label for="locatie">Locatie:</label>
        <select id="locatie" name="locatie" <?php echo ($autRol == 'maatschappelijkwerker') ? 'disabled' : ''; ?>>
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

        <label for="arrestanten">Selecteer Arrestanten:</label><br>
        <input type="text" id="searchInput" placeholder="Search arrestanten"><br>

        <select id="arrestanten" name="arrestanten[]" multiple required style="width: 100%;"<?php echo ($autRol == 'maatschappelijkwerker') ? 'disabled' : ''; ?>>
    <?php
    // Haal arrestanten op uit de database
    $arrestantenData = $arrestanten->getArrestantenByLocatie(0, 1000, $locatie_id);
    foreach ($arrestantenData as $arrestant) {
        // Check if the arrestant is associated with the current case and location
        $selected = '';
        if ($arrestant['zaak_id'] == $zaak_id && $arrestant['locatie_id'] == $locatie_id) {
            $selected = 'selected';
        }
        echo "<option value='" . $arrestant['arrestant_id'] . "' $selected>" . $arrestant['naam'] . "</option>";
    }
    ?>
</select><br><br>

<!-- Include Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Include jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>

    document.getElementById('searchInput').addEventListener('input', function() {
        var input = this.value.toLowerCase();
        var options = document.getElementById('arrestanten').options;

        for (var i = 0; i < options.length; i++) {
            var text = options[i].text.toLowerCase();
            var option = options[i];

            if (text.indexOf(input) !== -1) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    });

    $(document).ready(function() {
        $('#arrestanten').select2({
            placeholder: 'Select arrestanten',
            allowClear: true,
            closeOnSelect: false,
            width: '50%'
        });
    });
    </script>

        <input type="submit" value="Update">
    </form>
</div>
<?php include('./inc/hoornhek_footer.php'); ?>


