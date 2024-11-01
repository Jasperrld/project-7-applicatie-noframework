<?php

include('inc/hoornhek_header.php'); 
include('inc/locaties_header.php');

include('classes/Cellen.class.php');
$locaties = new Locaties($pdo);
$cellen = new Cellen($pdo);
$start_from = 0; // Starting point for fetching records
$records_per_page = 30; // Number of records to fetch per page
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

if ($locatie_id !== null) {
    if ($locatie_id == 'everything') {
        // Fetch all arrestants
        $stmt = $cellen->getCellen();
        // Get total rows for pagination
        $total_rows = $cellen->getTotalRows();
    } else {
        // Fetch arrestants by specific location
        $stmt = $cellen->getCellenByLocatie($start_from, $records_per_page, $locatie_id);
    }
} else {
    // If no locatie is selected, fetch all arrestants
    $stmt = $cellen->getCellen();
    // Get total rows for pagination
    $total_rows = $cellen->getTotalRows();
    $selected_location_name = 'everything';

}

// $stmt = $cellen->getCellen($start_from, $records_per_page);

?>
<header class="head">
    <a href="./cel_new.php" class='btn-new'><i class='material-icons md-24'>add</i></a>
 
</header>

<div class="cellen-container">

<?php foreach ($stmt as $cel) : ?>
    <?php
    // Determine the CSS class based on whether the cell has an assigned arrestant
    $cellClass = ($cel['arrestant_id'] > 0) ? 'cel cel-red' : 'cel';
    ?>
      <a href="./cel_info.php?cel_id=<?php echo $cel['cel_id']; ?>">
        <div class="<?php echo $cellClass; ?>" id="cel_<?php echo $cel['cel_id']; ?>">
            <?php echo $cel['cel_id']; ?>
        </div>
    </a>
<?php endforeach; ?>
</div>




<?php

include('../hoornhek/inc/hoornhek_footer.php'); 