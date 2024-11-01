<?php
// Include the Locaties class file
require_once './classes/Locaties.class.php';
include('check_role.php');
// Create an instance of the Locaties class
$locaties = new Locaties($pdo);

// Fetch locaties
$stmt = $locaties->getLocaties();
$locaties_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="locatie_header">
    
<header class="locatie_menu">
    <ul>
        <?php foreach ($locaties_data as $locatie) { ?>
            <li><a href="?locatie_id=<?php echo $locatie['locatie_id']; ?>"><?php echo $locatie['locatienaam']; ?></a></li>
        <?php } ?>
        <li><a href="?locatie_id=everything">Alles</a></li>
    </ul>
    <button class="hoornhek_menu-toggle" aria-label="Toggle Menu">
    <span class="icon-container-locatie">
    <i class="fa-sharp fa-solid fa-bars"></i>
    </span>
  </button>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.querySelector('.hoornhek_menu-toggle');
        const menu = document.querySelector('.locatie_menu ul');

        menuToggle.addEventListener('click', function () {
            menu.classList.toggle('active');
        });
    });
</script>
</div>