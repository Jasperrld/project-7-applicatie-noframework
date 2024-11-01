<?php

include('./classes/Gebruikers.class.php');
include('check_role.php');
?>

<header class="header">
    <img class="logo" src="../img/logo.png" alt="">
    <?php
    // controle ingelogd

    

    // samenstellen menu
    $menu = '';
// Get the locatie_id from the session
$locatie_id = isset($_SESSION['locatie_id']) ? $_SESSION['locatie_id'] : null;

// op basis van rol menu tonen
switch ($autRol) {
    case 'portier':
    case 'opnameofficier':
        $menu = '<nav class="hoornhek_menu">
                    <ul>
                        <li><a href="./arrestanten.php?locatie_id=' . $locatie_id . '">Detentie</a></li>
                        <li><a href="./cellen.php?locatie_id=' . $locatie_id . '">Cellen</a></li>
                        <li><a href="./zaken.php?locatie_id=' . $locatie_id . '">Lopende zaken</a></li>
                        <li><a href="./detentiehistorie.php?locatie_id=' . $locatie_id . '">Detentiehistorie</a></li>
                        <li><a href="./logout.php">Logout</a></li>
                    </ul>
                </nav>';
        break;
    case 'maatschappelijkwerker':
        $menu = '<nav class="hoornhek_menu">
                    <ul>
                        <li><a href="./arrestanten.php?locatie_id=' . $locatie_id . '">Detentie</a></li>
                        <li><a href="./zaken.php?locatie_id=' . $locatie_id . '">Lopende zaken</a></li>
                        <li><a href="./logout.php">Logout</a></li>
                    </ul>
                </nav>';
        break;
    default:
        $menu = '<nav class="hoornhek_menu">
                    <ul>
                        <li><a href="../index.php">Terug naar klantenportaal</a></li>
                    </ul>
                </nav>';
}

echo $menu;
?>

    <button class="menu-toggle" aria-label="Toggle Menu">
    <span class="icon-container">
    <i class="fa-sharp fa-solid fa-bars"></i>
    </span>
</button>
</header>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.querySelector('.menu-toggle');
        const menu = document.querySelector('.hoornhek_menu ul');

        menuToggle.addEventListener('click', function () {
            menu.classList.toggle('active');
        });
    });
</script>
