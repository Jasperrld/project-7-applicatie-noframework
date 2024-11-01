
<header class="header">
<a href="./index.php"><img class="logo" src="./img/logo.png" alt=""></a>

    <nav class="klantenportaal_menu">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./bezoekregeling.php">Bezoekregeling</a></li>
            <li><a href="./historie.php">Historie</a></li>
            <li><a href="./arrestantencomplex.php">Arrestantencomplex</a></li>
            <li><a href="./hoornhek/login.php">Login To Hoornhek</a></li>
        </ul>
    </nav>
    <button class="menu-toggle" aria-label="Toggle Menu">
    <span class="icon-container">
    <i class="fa-sharp fa-solid fa-bars"></i>
    </span>    </button>
</header>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.klantenportaal_menu ul');

            menuToggle.addEventListener('click', function () {
                menu.classList.toggle('active');
            });
        });

        
    </script>
    
