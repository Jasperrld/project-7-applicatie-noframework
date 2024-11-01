<?php
// Include necessary files
include 'inc/hoornhek_header.php';
include('./classes/Detentiehistorie.class.php');

include('inc/check_role.php');
// Check if the user's role is "maatschappelijkwerker"
if($autRol === 'maatschappelijkwerker' || $autRol === 'portier') {
    header('Location: detentiehistorie.php'); // Redirect to arrestanten.php
    exit; // Stop further execution
}
// Check if ID is provided in the URL
if (isset($_GET["id"])) {
    $detentie_id = $_GET["id"];
} else {
    // If no ID is provided, redirect the user
    header('Location: detentiehistorie.php');
    exit();
}

// Instantiate the Arrestanten class with the PDO instance
$detentie = new DetentieHistorie($pdo);

// Get arrestant details by ID
$detentie_details = $detentie->getDetentieHistorieById($detentie_id);

// Check if confirmation is received
if (isset($_GET["confirm"])) {
    if ($_GET["confirm"] === "true") {
        // Instantiate the Arrestanten class with the PDO instance
        $detentie = new DetentieHistorie($pdo);

        // Delete the arrestant record by ID
        $result = $detentie->deleteDetentieHistorieById($detentie_id);

        // Check if deletion is successful
        if ($result) {
            // If successful, redirect to the page with a success message
            header('Location: detentiehistorie.php?delete_success=true');
            exit();
        } else {
            // If deletion fails, redirect to the page with an error message
            header('Location: detentiehistorie.php?delete_error=true');
            exit();
        }
    } else if ($_GET["confirm"] === "false") {
        // If confirmation is false, redirect to the previous page
        header('Location: detentiehistorie.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verwijder record</title>
</head>
<body>
    <div class="main-content">
    <h3>Weet je zeker dat je het record van <?php echo $detentie_details['naam'] ?> wilt verwijderen?</h3>
    <form action="" method="GET">
        <input type="hidden" name="id" value="<?php echo $detentie_id; ?>">
        <input type="hidden" name="confirm" value="true">
        <button type="submit">Ja, verwijder</button>
        <button type="button" onclick="window.location.href='detentiehistorie.php?confirm=false'">Nee, ga terug</button>
    </form>
    </div>
</body>
</html>

<?php
include("inc/hoornhek_footer.php");
?>
