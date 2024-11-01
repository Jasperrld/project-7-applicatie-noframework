<?php
//bestandsnaam ophalen door de dirnaam te vervangen door '' in het totale path.
$file=str_replace(dirname($_SERVER['PHP_SELF']).'/','',$_SERVER['PHP_SELF'] );
//controle of er is ingelogd
$ingelogd=isset($_SESSION['ingelogd']) ? $_SESSION['ingelogd'] : false ;
if (($file!='login.php') AND ($file!='authorisatie.php'))  {
    if (!$ingelogd) {
        session_destroy();
        header("location: login.php");
        exit;
    }
}
