<?php 

// ophalen rol gebruiker
$autRol = isset($_SESSION['rol']) ? strtolower($_SESSION['rol']) : '';
$inlognaam = isset($_SESSION['inlognaam']) ? $_SESSION['inlognaam'] : '';
$locatie = isset($_SESSION['locatie']) ? $_SESSION['locatie'] : '';

?>