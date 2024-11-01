<?php 

include('inc/hoornhek_header.php');
include('inc/check_role.php');
include('classes/Cellen.class.php');
include('classes/Locaties.class.php');
include('classes/Zaken.class.php');
include('classes/Arrestanten.class.php');
$arrestanten = new Arrestanten($pdo);
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

?>

<div>
    <h2>Add New Zaak</h2>
    <form action="process_form.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="addzaak"/>
    <label for="zaaknummer">Zaaknummer:</label>
    <input type="text" id="zaaknummer" name="zaaknummer" required><br><br>
    <label for="arrestanten">Selecteer Arrestanten:</label><br>
    <input type="text" id="searchInput" placeholder="Search arrestanten"><br>

<select id="arrestanten" name="arrestanten" multiple required>
    <?php
    // Haal arrestanten op uit de database
    $arrestantenData = $arrestanten->getArrestanten(0, 1000); 
    foreach ($arrestantenData as $arrestant) {
        echo "<option value='" . $arrestant['arrestant_id'] . "'>" . $arrestant['naam'] . "</option>";
    }
    ?>
</select><br><br>
<label for="arrestatiereden">Arrestatiereden:</label>
        <select id="arrestatiereden" name="arrestatiereden">
            <option value="Option 1">Option 1</option>
            <option value="Option 2">Option 2</option>
            <option value="Option 3">Option 3</option>
            <option value="Option 4">Option 4</option>
        </select><br>
        <label for="notities">Notities:</label><br>
        <textarea id="notities" name="notities"></textarea><br>
        <input type="submit" value="Submit">

    </form>
</div>


<?php include('inc/hoornhek_footer.php'); ?>

