<?php

// Inclusion du fichier de configuration, qui contient les paramètres de connexion à la base de données
include 'config.php';

// Démarre la session PHP, permettant l'accès aux variables de session
session_start();

// Récupère l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Si l'ID de l'utilisateur n'est pas défini dans la session (i.e., l'utilisateur n'est pas connecté),
// redirige vers la page de connexion (login.php)
if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- Lien vers la feuille de style Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers la feuille de style CSS personnalisée pour la mise en page -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Inclusion du fichier d'en-tête (header.php) qui contient la navigation et la barre de menu -->
<?php include 'header.php'; ?>

<!-- Section de titre de la page -->
<div class="heading">
   <h3>your orders</h3> <!-- Titre affiché "your orders" -->
   <p> <a href="home.php">home</a> / orders </p> <!-- Fil d'Ariane qui permet de naviguer -->
</div>

<!-- Section contenant la liste des commandes passées -->
<section class="placed-orders">

   <!-- Titre de la section des commandes passées -->
   <h1 class="title">placed orders</h1>

   <!-- Conteneur pour afficher les commandes -->
   <div class="box-container">

      <?php
         // Requête pour récupérer toutes les commandes de l'utilisateur courant, basé sur son ID
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         
         // Si l'utilisateur a passé des commandes, affiche chaque commande
         if(mysqli_num_rows($order_query) > 0){
            // Parcourt toutes les commandes récupérées
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>
      <!-- Affichage des détails de chaque commande -->
      <div class="box">
         <p> placed on : <span><?php echo $fetch_orders['placed_on']; ?></span> </p> <!-- Date de la commande -->
         <p> name : <span><?php echo $fetch_orders['name']; ?></span> </p> <!-- Nom du client -->
         <p> number : <span><?php echo $fetch_orders['number']; ?></span> </p> <!-- Numéro de téléphone -->
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p> <!-- Email du client -->
         <p> address : <span><?php echo $fetch_orders['address']; ?></span> </p> <!-- Adresse de livraison -->
         <p> payment method : <span><?php echo $fetch_orders['method']; ?></span> </p> <!-- Méthode de paiement -->
         <p> your orders : <span><?php echo $fetch_orders['total_products']; ?></span> </p> <!-- Nombre d'articles -->
         <p> total price : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p> <!-- Prix total de la commande -->
         
         <!-- Statut de paiement, affiché en rouge si en attente, vert si payé -->
         <p> payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; } ?>;">
            <?php echo $fetch_orders['payment_status']; ?></span> </p>
      </div>
      <?php
            }
         // Si l'utilisateur n'a pas de commandes, un message est affiché
         }else{
            echo '<p class="empty">no orders placed yet!</p>';
         }
      ?>
   </div>

</section>

<!-- Inclusion du pied de page (footer.php) contenant les informations de copyright et les liens de bas de page -->
<?php include 'footer.php'; ?>

<!-- Lien vers le fichier JavaScript personnalisé pour les interactions sur la page -->
<script src="js/script.js"></script>

</body>
</html>
