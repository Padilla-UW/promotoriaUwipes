<!-- Cierra sesión y redirecciona al login
<?php
include('includes/header.php');
include('includes/menu.php');

session_start();
session_destroy();
header("Location:index.php");
?> 