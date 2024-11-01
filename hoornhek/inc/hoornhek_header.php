<?php
session_start();
// toevoegen database.php
require_once './database/database.php';
include_once('./inc/config.php');
include('./inc/check_login.php');
require_once('../vendor/autoload.php');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <title>HoornHek</title>
    <!-- hier komen de css-bestanden -->
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/hoornhek_menu.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/hoornhek_footer.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/arrestanten.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/cellen.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/login.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/zaken.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/arrestanten_new.css">
    <link rel="stylesheet" type="text/css" href="./hoornhekcss/detentiehistorie.css">

    <script src="https://kit.fontawesome.com/ae11820d03.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="hoornhek_body">
    <!-- <div class="head-container"> -->
        <?php
        include('inc/hoornhek_menu.php');
