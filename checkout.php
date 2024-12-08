<?php

// Inclure la configuration pour la base de données
include 'config.php';

// Démarrer la session pour l'utilisateur
session_start();

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if(!isset($user_id)){
   header('location:login.php');
}

// Placer une commande
if(isset($_POST['order_btn'])){
   // Sécuriser les données du formulaire
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $number = $_POST['number'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $method = mysqli_real_escape_string($conn, $_POST['method']);
   $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $placed_on = date('d-M-Y');

   // Calculer le total de la commande
   $cart_total = 0;
   $cart_products[] = '';

   // Récupérer les produits du panier
   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   // Créer une chaîne des produits
   $total_products = implode(',',$cart_products);

   // Vérifier si la commande a déjà été placée
   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   // Si le total du panier est égal à zéro, afficher un message d'erreur
   if($cart_total == 0){
      $message[] = 'Votre panier est vide';
   }else{
      // Si la commande existe déjà, afficher un message d'alerte
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'Commande déjà passée !'; 
      }else{
         // Insérer la commande dans la base de données
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'Commande placée avec succès !';

         // Supprimer les produits du panier après la commande
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Lien vers la feuille de style Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'header.php'; ?>

<!-- Section de l'en-tête -->
<div class="heading">
   <h3>Checkout</h3>
   <p><a href="home.php">Accueil</a> / Checkout</p>
</div>

<!-- Section de l'affichage des produits dans la commande -->
<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">Votre panier est vide</p>';
   }
   ?>
   <div class="grand-total"> Total général : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<!-- Section du formulaire de commande -->
<section class="checkout">

   <form action="" method="post">
      <h3>Passer votre commande</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Nom complet :</span>
            <input type="text" name="name" required placeholder="Entrez votre nom">
         </div>
         <div class="inputBox">
            <span>Numéro de téléphone :</span>
            <input type="number" name="number" required placeholder="Entrez votre numéro">
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" required placeholder="Entrez votre email">
         </div>
         <div class="inputBox">
            <span>Méthode de paiement :</span>
            <select name="method">
               <option value="cash on delivery">Paiement à la livraison</option>
               <option value="credit card">Carte de crédit</option>
               <option value="paypal">PayPal</option>
               <option value="paytm">PayTM</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Ligne 01 d'adresse :</span>
            <input type="number" min="0" name="flat" required placeholder="e.g. appartement no.">
         </div>
         <div class="inputBox">
            <span>Rue :</span>
            <input type="text" name="street" required placeholder="Nom de la rue">
         </div>
         <div class="inputBox">
            <span>Ville :</span>
            <input type="text" name="city" required placeholder="e.g. Paris">
         </div>
         <div class="inputBox">
            <span>État :</span>
            <input type="text" name="state" required placeholder="e.g. Île-de-France">
         </div>
         <div class="inputBox">
            <span>Pays :</span>
            <input type="text" name="country" required placeholder="e.g. France">
         </div>
         <div class="inputBox">
            <span>Code postal :</span>
            <input type="number" min="0" name="pin_code" required placeholder="e.g. 75001">
         </div>
      </div>
      <input type="submit" value="Commander maintenant" class="btn" name="order_btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
