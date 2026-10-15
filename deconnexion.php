<?php
session_start();

unset($_SESSION['login']);
unset($_SESSION['role']);
unset($_SESSION['idClient']);

// session_destroy();

header("Location: index.php?page=accueil");
exit();