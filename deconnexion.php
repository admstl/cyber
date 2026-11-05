<?php
session_start();

unset($_SESSION['login']);
unset($_SESSION['role']);
unset($_SESSION['idClient']);

header("location:index.php?page=accueil");
exit();