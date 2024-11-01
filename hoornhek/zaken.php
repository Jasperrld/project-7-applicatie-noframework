<?php

include('inc/hoornhek_header.php');
include('inc/locaties_header.php');
// include('./classes/Locaties.class.php');
include('./classes/Zaken.class.php');
include('./classes/Paginering.class.php');
include('./classes/LocationPaginering.class.php');
include('./classes/SearchLocationPaginering.class.php');
$zaken = new Zaken($pdo);
// $locaties = new Locaties($pdo);

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

$start_from = ($page - 1) * RECORDS_PER_PAGE;
$records_per_page = 10; // Number of records to fetch per page
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;

$search_query = isset($_GET['search']) ? $_GET['search'] : null;

// Fetch zaken based on search keyword if provided
if ($search_query !== null) {
    // Perform search and retrieve filtered zaken
    $stmt = $zaken->searchZaak($search_query, $locatie_id, $start_from, $records_per_page);
    // Get total rows for pagination
    $total_rows = $zaken->getTotalRowsOfSearch($search_query, $locatie_id);
} else {
    // Fetch zaken based on locatie_id if provided
    if ($locatie_id !== null) {
        if ($locatie_id == 'everything') {
            // Fetch all zaken
            $stmt = $zaken->getZaken($start_from, $records_per_page);
            // Get total rows for pagination
            $total_rows = $zaken->getTotalRows();
        } else {
            // Fetch zaken by specific location
            $stmt = $zaken->getZakenByLocatie($start_from, $records_per_page, $locatie_id);
            $total_rows = $zaken->getTotalRowsOfLocatie($locatie_id);
            if (isset($_GET["page"])) {
                $page = $_GET["page"];
            } else {
                $page = 1;
            }
        }
    } else {
        // If no locatie is selected and no search keyword provided, fetch all zaken
        $stmt = $zaken->getZaken($start_from, $records_per_page);
        // Get total rows for pagination
        $total_rows = $zaken->getTotalRows();
        $selected_location_name = 'everything';
    }
}



?>

<header class="head">
    <a href="./Zaken_new.php" class='btn-new'><i class='material-icons md-24'>add zaak</i></a>
    
    <?php if($locatie_id !== null) {
        ?>
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="zoekbalk">
            <label for="search">Zoek:</label>
            <input type="text" id="search" name="search">
            <?php if(isset($_GET['locatie_id'])): ?>
                <input type="hidden" name="locatie_id" value="<?php echo $_GET['locatie_id']; ?>">
            <?php endif; ?>
            <button type="submit" class="filterbutton">Zoeken</button>
        </div>
    </form>
        <?php
    } else {
        ?>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="zoekbalk">
                <label for="search">Zoek:</label>
                <input type="text" id="search" name="search">
                <button type="submit" class="filterbutton">Zoeken</button>
            </div>
        </form>
        <?php
    } ?>

</header>

<div class="arrestanten_container">
        <table id="customers">
            <thead>
                <tr>
                <!-- <th>Pasfoto</th> -->

                    <th>Zaak_id</th>
                    <th>Zaaknummer</th>
                    <th>Arrestatiereden</th>
                    <th>Locatie_id  </th>
                    <th class="actie">Actie</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // dit is wat ik eerst had locatie van cel die gekoppeld is aan arrestant
                    // $location_id = $locaties->getLocationIDByCellID($row['cel_id']);

                    echo "<tr>";
                    // echo "<td><img src='data:image/png;base64," . base64_encode($row['pasfoto']) . "' alt='Pasfoto' style='width: 100px; height: auto;'></td>";
                    echo "<td>" . $row['zaak_id'] . "</td>";
                    echo "<td>" . $row['zaaknummer'] . "</td>";
                    echo "<td>" . $row['arrestatiereden'] . "</td>";
                    // echo "<td>" . ($location_id !== null ? $location_id : 'N/A') . "</td>";
                    echo "<td>" . $row['locatie_id'] . "</td>";

                    echo "  <td>
                    <a href='Zaak_edit.php?id={$row['zaak_id']}' class='btn-edit'><i class='material-icons md-24'>edit</i></a>
                    <a href='Zaak_delete.php?id={$row['zaak_id']}' class='btn-delete'><i class='material-icons md-24'>delete</i></a>
                    <a href='Zaak_info.php?id={$row['zaak_id']}' class='btn-info'><i class='material-icons' style='font-size: 24px; width: 24px; height: 24px;'>info</i></a>

                </td>";
                    echo "</tr>";
                    
                }
                ?>
            </tbody>
        </table>
</div>

<?php 

$page_url = "zaken.php";

// Determine which pagination class to use based on locatie_id
if ($locatie_id !== null) {
    echo LocationPagination::generatePagination($total_rows, RECORDS_PER_PAGE, $page_url, $page, $locatie_id);
} else {
        echo Pagination::generatePagination($total_rows, RECORDS_PER_PAGE, $page_url, $page);
} 




include('./inc/hoornhek_footer.php');
