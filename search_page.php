<?php

// Inclusion du fichier de configuration qui contient la connexion à la base de données
include 'config.php';

// Démarre une nouvelle session ou récupère une session existante
session_start();

// Récupération de l'ID de l'utilisateur connecté à partir de la session
$user_id = $_SESSION['user_id'];

// Si l'ID de l'utilisateur n'est pas défini dans la session, cela signifie qu'aucun utilisateur n'est connecté
// On redirige alors l'utilisateur vers la page de connexion
if(!isset($user_id)){
   header('location:login.php');  // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
};

// Vérifie si le formulaire d'ajout au panier a été soumis
if(isset($_POST['add_to_cart'])){

   // Récupération des informations du produit à ajouter au panier
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   // Vérification si le produit a déjà été ajouté au panier de cet utilisateur
   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   // Si le produit est déjà dans le panier, afficher un message d'erreur
   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';  // Message indiquant que le produit est déjà dans le panier
   }else{
      // Si le produit n'est pas encore dans le panier, on l'ajoute à la table 'cart'
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';  // Message indiquant que le produit a été ajouté avec succès au panier
   }

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- Lien vers la bibliothèque Font Awesome pour les icônes (utilisé pour les icônes dans la page) -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé pour appliquer le style à la page -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>  <!-- Inclusion de l'en-tête de la page (menu et informations générales) -->

<div class="heading">
   <h3>search page</h3>  <!-- Titre de la page : "Page de recherche" -->
   <p> <a href="home.php">home</a> / search </p>  <!-- Fil d'Ariane : Accueil / Recherche -->
</div>

<!-- Section pour le formulaire de recherche -->
<section class="search-form">
   <form action="" method="post">  <!-- Formulaire de recherche -->
      <input type="text" name="search" placeholder="search products..." class="box">  <!-- Champ de recherche pour les produits -->
      <input type="submit" name="submit" value="search" class="btn">  <!-- Bouton pour soumettre la recherche -->
   </form>
</section>

<!-- Section pour afficher les résultats de recherche -->
<section class="products" style="padding-top: 0;">

   <div class="box-container">
   <?php
      // Si le formulaire de recherche a été soumis
      if(isset($_POST['submit'])){
         // Récupère l'élément recherché
         $search_item = $_POST['search'];

         // Exécute une requête pour rechercher les produits dont le nom contient le terme recherché
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_item}%'") or die('query failed');

         // Si des produits sont trouvés, on les affiche
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_product = mysqli_fetch_assoc($select_products)){
   ?>
   <!-- Formulaire pour chaque produit trouvé dans la recherche -->
   <form action="" method="post" class="box">
      <!-- Affiche l'image du produit -->
      <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
      <!-- Affiche le nom du produit -->
      <div class="name"><?php echo $fetch_product['name']; ?></div>
      <!-- Affiche le prix du produit -->
      <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
      <!-- Champ pour sélectionner la quantité du produit -->
      <input type="number"  class="qty" name="product_quantity" min="1" value="1">
      <!-- Champs cachés pour passer les informations du produit au formulaire -->
      <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
      <!-- Bouton pour ajouter le produit au panier -->
      <input type="submit" class="btn" value="add to cart" name="add_to_cart">
   </form>
   <?php
            }
         }else{
            // Si aucun produit n'est trouvé, afficher un message indiquant qu'aucun résultat n'a été trouvé
            echo '<p class="empty">no result found!</p>';
         }
      }else{
         // Si le formulaire n'a pas été soumis, afficher un message demandant à l'utilisateur de faire une recherche
         echo '<p class="empty">search something!</p>';
      }
   ?>
   </div>
  

</section>

<?php include 'footer.php'; ?>  <!-- Inclusion du pied de page -->

<!-- Lien vers le fichier JavaScript personnalisé pour ajouter des fonctionnalités supplémentaires -->
<script src="js/script.js"></script>

</body>
</html>
