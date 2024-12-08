<?php

// Inclusion du fichier de configuration pour établir la connexion avec la base de données
include 'config.php';

// Démarre ou récupère une session existante
session_start();

// Récupération de l'ID de l'utilisateur connecté depuis la session
$user_id = $_SESSION['user_id'];

// Si l'ID de l'utilisateur n'est pas défini (l'utilisateur n'est pas connecté), on le redirige vers la page de connexion
if(!isset($user_id)){
   header('location:login.php');  // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
}

// Vérification si le formulaire d'ajout au panier a été soumis
if(isset($_POST['add_to_cart'])){

   // Récupération des informations du produit ajouté au panier
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Vérification si le produit est déjà dans le panier de cet utilisateur
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   // Si le produit est déjà dans le panier, afficher un message d'erreur
   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';  // Message indiquant que le produit est déjà dans le panier
   }else{
      // Si le produit n'est pas encore dans le panier, on l'ajoute à la base de données
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';  // Message indiquant que le produit a été ajouté au panier
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">  <!-- Définition du charset pour une bonne gestion des caractères spéciaux -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">  <!-- Déclaration de la compatibilité avec Internet Explorer -->
   <meta name="viewport" content="width=device-width, initial-scale=1.0">  <!-- Pour rendre la page responsive (adaptée à différents écrans) -->
   <title>shop</title>  <!-- Titre de la page -->

   <!-- Lien vers la bibliothèque Font Awesome pour les icônes (ex. icônes de panier, recherche) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé pour styliser la page -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>  <!-- Inclusion de l'en-tête de la page (menu et informations générales) -->

<!-- Section d'en-tête pour afficher le titre de la page "our shop" -->
<div class="heading">
   <h3>our shop</h3>  <!-- Titre de la section de la boutique -->
   <p> <a href="home.php">home</a> / shop </p>  <!-- Fil d'Ariane (permet de revenir à la page d'accueil) -->
</div>

<!-- Section contenant la liste des produits de la boutique -->
<section class="products">

   <h1 class="title">latest products</h1>  <!-- Titre pour la liste des derniers produits -->

   <div class="box-container">

      <?php  
         // Exécution de la requête pour sélectionner tous les produits de la base de données
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         
         // Si des produits sont trouvés dans la base de données
         if(mysqli_num_rows($select_products) > 0){
            // Boucle pour afficher chaque produit trouvé
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <!-- Formulaire pour chaque produit affiché -->
     <form action="" method="post" class="box">
        <!-- Affiche l'image du produit -->
        <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
        <!-- Affiche le nom du produit -->
        <div class="name"><?php echo $fetch_products['name']; ?></div>
        <!-- Affiche le prix du produit -->
        <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
        <!-- Champ pour entrer la quantité du produit -->
        <input type="number" min="1" name="product_quantity" value="1" class="qty">
        <!-- Champs cachés pour transmettre les informations du produit (nom, prix, image) au formulaire -->
        <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
        <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
        <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
        <!-- Bouton pour ajouter le produit au panier -->
        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         // Si aucun produit n'est trouvé dans la base de données, afficher un message
         echo '<p class="empty">no products added yet!</p>';  // Message indiquant qu'aucun produit n'a été ajouté
      }
      ?>
   </div>

</section>

<!-- Inclusion du pied de page de la page -->
<?php include 'footer.php'; ?>

<!-- Lien vers le fichier JavaScript personnalisé pour ajouter des fonctionnalités (interactions) -->
<script src="js/script.js"></script>

</body>
</html>
