<?php
// Vérification si un message a été défini dans la variable $message
if(isset($message)){
   // Parcours de tous les messages présents dans le tableau $message
   foreach($message as $message){
      // Affichage du message avec un bouton pour supprimer le message
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <!-- Icône pour supprimer le message au clic -->
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <!-- Première section du header -->
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <!-- Liens vers les réseaux sociaux (Facebook, Twitter, Instagram, LinkedIn) -->
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <!-- Lien pour se connecter ou s'inscrire -->
         <p> new <a href="login.php">login</a> | <a href="register.php">register</a> </p>
      </div>
   </div>

   <!-- Deuxième section du header -->
   <div class="header-2">
      <div class="flex">
         <!-- Logo du site, redirige vers la page d'accueil -->
         <a href="home.php" class="logo">Bookly.</a>

         <!-- Barre de navigation avec les liens vers différentes pages du site -->
         <nav class="navbar">
            <a href="home.php">home</a>
            <a href="about.php">about</a>
            <a href="shop.php">shop</a>
            <a href="contact.php">contact</a>
            <a href="orders.php">orders</a>
         </nav>

         <div class="icons">
            <!-- Bouton pour afficher le menu de navigation sur mobile (Icône hamburger) -->
            <div id="menu-btn" class="fas fa-bars"></div>
            <!-- Icône pour accéder à la page de recherche -->
            <a href="search_page.php" class="fas fa-search"></a>
            <!-- Icône pour accéder à l'interface utilisateur -->
            <div id="user-btn" class="fas fa-user"></div>

            <?php
               // Récupération du nombre d'articles dans le panier de l'utilisateur
               $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
               // Comptage du nombre d'articles dans le panier
               $cart_rows_number = mysqli_num_rows($select_cart_number); 
            ?>
            <!-- Affichage de l'icône du panier avec le nombre d'articles -->
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span> </a>
         </div>

         <!-- Section utilisateur : affiche les informations de l'utilisateur connecté -->
         <div class="user-box">
            <!-- Affichage du nom d'utilisateur -->
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <!-- Affichage de l'email de l'utilisateur -->
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <!-- Lien pour se déconnecter (redirige vers logout.php) -->
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>
   </div>

</header>
