<?php
include('inc/hoornhek_header.php');
include('inc/locaties_header.php');
include('./classes/Detentiehistorie.class.php');
include('./classes/Paginering.class.php');
include('./classes/LocationPaginering.class.php');
include('./classes/SearchLocationPaginering.class.php');
include('./classes/SearchPaginationNoId.class.php');
$detentiehistorie = new DetentieHistorie($pdo);
$locaties = new Locaties($pdo);

if (isset($_GET["page"])) {
    $page = $_GET["page"];
} else {
    $page = 1;
}

$start_from = ($page - 1) * RECORDS_PER_PAGE;
$records_per_page = 10; // Number of records to fetch per page
$locatie_id = isset($_GET['locatie_id']) ? $_GET['locatie_id'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';


if (!empty($search_query)) {
    if ($locatie_id !== null) {
        $stmt = $detentiehistorie->searchDetentieHistorie($search_query, $locatie_id, $start_from, $records_per_page);
        $total_rows = $detentiehistorie->getTotalRowsOfSearch($search_query, $locatie_id);
    } else {
        // If no locatie is selected, fetch all detentiehistorie
        $stmt = $detentiehistorie->searchDetentieHistorieNoId($search_query, $start_from, $records_per_page);
        // Get total rows for pagination
        $total_rows = $detentiehistorie->getTotalRowsOfSearchNoId($search_query);
    }
} else {
    if ($locatie_id !== null) {
    if ($locatie_id == 'everything') {
        // Fetch all detentiehistorie
        $stmt = $detentiehistorie->getDetentiehistorie($start_from, $records_per_page);
        // Get total rows for pagination
        $total_rows = $detentiehistorie->getTotalRows();
    } else {
        // Fetch detentiehistorie by specific location
        $stmt = $detentiehistorie->getDetentiehistorieByLocatie($start_from, $records_per_page, $locatie_id);
        $total_rows = $detentiehistorie->getTotalRowsOfLocatie($locatie_id);
    }
} else {
    // If no locatie is selected, fetch all detentiehistorie
    $stmt = $detentiehistorie->getDetentiehistorie($start_from, $records_per_page);
    // Get total rows for pagination
    $total_rows = $detentiehistorie->getTotalRows();
}
}


?>

<header class="head">
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



</header>

<div class="arrestanten_container">
    <table id="customers">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Arrestatiereden</th>
                <th>Cel_id</th>
                <th>Insluitingsdatum</th>
                <th>Uitsluitingsdatum</th>
                <th>Locatie</th>
                <th class="actie">Actie</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // $location_id = $locaties->getLocationIDByCellID($row['cel_id']);

                echo "<tr>";
                echo "<td>" . $row['naam'] . "</td>";
                echo "<td>" . $row['arrestatiereden'] . "</td>";
                echo "<td>" . $row['cel_id'] . "</td>";
                echo "<td>" . $row['insluitingsdatum'] . "</td>";
                echo "<td>" . $row['uitsluitingsdatum'] . "</td>";
                echo "<td>" . $row['locatie_id'] . "</td>";

                // echo "<td>" . ($location_id !== null ? $location_id : 'N/A') . "</td>";
                echo "  <td>
                    <a href='historie_delete.php?id={$row['detentie_id']}' class='btn-delete'><i class='material-icons md-24'>delete</i></a>
                    <a href='historie_info.php?id={$row['detentie_id']}' class='btn-info'><i class='material-icons' style='font-size: 24px; width: 24px; height: 24px;'>info</i></a>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
$page_url = "detentiehistorie.php";

// Determine which pagination class to use based on locatie_id
if ($locatie_id !== null) {
    echo LocationPagination::generatePagination($total_rows, RECORDS_PER_PAGE, $page_url, $page, $locatie_id);
} else {
        echo Pagination::generatePagination($total_rows, RECORDS_PER_PAGE, $page_url, $page);

} 






include('../hoornhek/inc/hoornhek_footer.php');
?>
