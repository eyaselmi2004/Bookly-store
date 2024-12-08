<?php

// Inclut le fichier de configuration contenant la connexion à la base de données.
include 'config.php';

// Démarre une session pour gérer les données utilisateur.
session_start();

// Récupère l'ID de l'utilisateur connecté depuis la session.
$user_id = $_SESSION['user_id'];

// Vérifie si l'utilisateur est connecté. Si non, redirige vers la page de connexion.
if(!isset($user_id)){
   header('location:login.php');
}

// Vérifie si le bouton "add_to_cart" a été cliqué.
if(isset($_POST['add_to_cart'])){

   // Récupère les données du produit envoyées via le formulaire.
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Vérifie si le produit est déjà dans le panier de cet utilisateur.
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   // Si le produit est déjà dans le panier, affiche un message.
   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      // Sinon, ajoute le produit au panier dans la base de données.
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Définit l'encodage des caractères. -->
   <meta charset="UTF-8">
   <!-- Assure la compatibilité avec les anciennes versions d'Internet Explorer. -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <!-- Rend la page réactive pour les appareils mobiles. -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- Lien vers la bibliothèque d'icônes Font Awesome. -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé. -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- Inclut l'en-tête de la page. -->
<?php include 'header.php'; ?>

<section class="home">
   <div class="content">
      <h3>Hand Picked Book to your door.</h3>
      <!-- Présentation courte de l'entreprise. -->
      <p>Bookly est au service du livre et de la culture depuis plus de 50 ans en Tunisie, et maintenant dans le monde grâce au web.</p>
      <a href="about.php" class="white-btn">discover more</a>
   </div>
</section>

<section class="products">
   <h1 class="title">latest products</h1>

   <div class="box-container">
      <?php  
         // Récupère les 6 derniers produits ajoutés à la base de données.
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <!-- Formulaire pour chaque produit permettant de l'ajouter au panier. -->
      <form action="" method="post" class="box">
         <!-- Image du produit. -->
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <!-- Nom du produit. -->
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <!-- Prix du produit. -->
         <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
         <!-- Champ pour la quantité désirée. -->
         <input type="number" min="1" name="product_quantity" value="1" class="qty">
         <!-- Données cachées pour transmettre les informations du produit. -->
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <!-- Bouton pour ajouter le produit au panier. -->
         <input type="submit" value="add to cart" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      }else{
         // Message affiché s'il n'y a pas de produits disponibles.
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

   <!-- Bouton pour charger plus de produits. -->
   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">load more</a>
   </div>
</section>

<section class="about">
   <div class="flex">
      <!-- Image d'illustration. -->
      <div class="image">
         <img src="images/about2-img.jpg" alt="">
      </div>
      <div class="content">
         <!-- Présentation de la section "À propos". -->
         <h3>about us</h3>
         <p> One glance at a book and you hear the voice of another person, perhaps someone dead for 1,000 years. To read is to voyage through time.</p>
         <a href="about.php" class="btn">read more</a>
      </div>
   </div>
</section>

<section class="home-contact">
   <div class="content">
      <!-- Section de contact pour poser des questions. -->
      <h3>have any questions?</h3>
      <p>Bookly offers quality used and new books, accurately graded, at everyday low prices, delivered directly to our cherished customers. If, for any reason you are not satisfied with your purchase, please contact us and we will do our best to ensure your satisfaction.</p>
      <a href="contact.php" class="white-btn">contact us</a>
   </div>
</section>

<!-- Inclut le pied de page. -->
<?php include 'footer.php'; ?>

<!-- Lien vers le fichier JavaScript personnalisé. -->
<script src="js/script.js"></script>

</body>
</html>

