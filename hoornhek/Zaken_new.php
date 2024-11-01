<?php 

include('inc/hoornhek_header.php');
include('inc/check_role.php');
include('classes/Cellen.class.php');
include('classes/Locaties.class.php');
include('classes/Zaken.class.php');
include('classes/Arrestanten.class.php');
// Check if the user's role is "maatschappelijkwerker"
if($autRol === 'maatschappelijkwerker' || $autRol === 'opnameofficier') {
    header('Location: zaken.php'); // Redirect to arrestanten.php
    exit; // Stop further execution
}
$arrestanten = new Arrestanten($pdo);
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

?>

<div class="zaken-new-container">
<button class="Terug" onclick="window.location.href='./zaken.php'">Terug</button>

    <h2>Add New Zaak</h2>
    <form action="process_form.php" class="zaken-new-form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="addzaak"/>
    <label for="zaaknummer">Zaaknummer:</label>
    <input type="text" id="zaaknummer" name="zaaknummer" required><br><br>
    <label for="arrestanten">Selecteer Arrestanten:</label><br>
    <input type="text" id="searchInput" placeholder="Search arrestanten"><br>

    <select id="arrestanten" name="arrestanten[]" multiple required style="width: 100%;">
            <?php
            // Haal arrestanten op uit de database
            $arrestantenData = $arrestanten->getArrestanten(0, 1000); // Bijvoorbeeld, paginering kan worden toegepast
            foreach ($arrestantenData as $arrestant) {
                echo "<option value='" . $arrestant['arrestant_id'] . "'>" . $arrestant['naam'] . "</option>";
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

<label for="arrestatiereden">Arrestatiereden:</label>
        <select id="arrestatiereden" name="arrestatiereden">
            <option value="Drugshandel">Drugshandel</option>
            <option value="Overval">Overval</option>
            <option value="Witwasoperatie">Witwasoperatie</option>
            <option value="Moordzaak">Moordzaak</option>
        </select><br>
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
        <label for="notities">Notities:</label><br>
        <textarea id="notities" name="notities"></textarea><br>
        <input type="submit" value="Submit">

    </form>
</div>


<?php include('inc/hoornhek_footer.php'); ?>

