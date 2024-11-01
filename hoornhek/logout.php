<?php
include("./inc/hoornhek_header.php");
session_destroy();
session_unset();
header('refresh: 1; url=login.php');
include ("./inc/hoornhek_footer.php");
