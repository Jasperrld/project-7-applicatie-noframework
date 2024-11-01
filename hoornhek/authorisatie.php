<?php

include("./inc/hoornhek_header.php");
include_once('inc/config.php');
include('./inc/check_login.php');

require_once('../vendor/autoload.php');

use ReCaptcha\ReCaptcha;

const SITEKEY = "6LfKzZspAAAAAL0aaGuW4fsj3kmmhcbTUlTWMa3i";
const SECRET_KEY = "6LfKzZspAAAAAERGmm9iIOVKCbofrdU9tXb2Ua0n";
$error_message = "";
$success_message = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $inlognaam = isset($_POST['inlognaam']) ? $_POST['inlognaam'] : '';
    $wachtwoord = isset($_POST['wachtwoord']) ? $_POST['wachtwoord'] : '';
    $recaptchaResponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    // Verify reCAPTCHA token
    $recaptcha = new ReCaptcha(SECRET_KEY);
    $recaptchaResult = $recaptcha->verify($recaptchaResponse);

    if (!$recaptchaResult->isSuccess()) {
        echo 'reCAPTCHA verification failed.';

        exit; // Stop further processing
    }

    // Verify user credentials
    $stmt = $pdo->prepare("SELECT gebruikers.gebruiker_id, gebruikers.inlognaam, gebruikers.wachtwoord, rollen.rolnaam, locaties.locatienaam, locaties.locatie_id FROM gebruikers
    INNER JOIN rollen ON gebruikers.rol_id = rollen.rol_id
    INNER JOIN locaties ON gebruikers.locatie_id = locaties.locatie_id
    WHERE inlognaam = :inlognaam AND wachtwoord = :wachtwoord");

    // Bind parameters
    $stmt->bindParam(':inlognaam', $inlognaam, PDO::PARAM_STR);
    $stmt->bindParam(':wachtwoord', $wachtwoord, PDO::PARAM_STR);

    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if there is a matching record
    if ($result) {
        $rol = $result['rolnaam'];
        $locatie = $result['locatienaam'];
        $locatie_id = $result['locatie_id'];
        // Set session variables
        $_SESSION['inlognaam'] = $inlognaam;
        $_SESSION['wachtwoord'] = $wachtwoord;
        $_SESSION['rol'] = $rol;
        $_SESSION['locatie'] = $locatie;
        $_SESSION['locatie_id'] = $locatie_id;
        $_SESSION['ingelogd'] = true;

        $_SESSION['success_message'] = "Login successful!";
        
        echo "capatcha and login successful!";
        header('Location: arrestanten.php?locatie_id=' . urlencode($locatie_id));
        exit;
    } else {
        echo 'Invalid credentials.';
        header('refresh: 3; url=login.php');

        exit;
    }
}

// If form was not submitted, redirect to login page
header('Location: login.php');
exit;
?>
