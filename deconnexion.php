<?php
session_start();
// Suppression des données mises en session
unset($_SESSION['login']);
unset($_SESSION['role']);
unset($_SESSION['idClient']);
// Redirection vers la page d'accueil
header("location:index.php?page=accueil");
exit();