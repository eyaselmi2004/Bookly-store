<?php

// Inclut le fichier de configuration qui contient les informations de connexion à la base de données
include 'config.php';

// Démarre la session. Cela permet d'accéder aux variables de session et de les gérer
session_start();

// Supprime toutes les variables de session. Cela déconnecte effectivement l'utilisateur
session_unset();

// Détruit la session en cours. Cela supprime toute information associée à cette session.
session_destroy();

// Redirige l'utilisateur vers la page de connexion (login.php) après sa déconnexion
header('location:login.php');

?>
