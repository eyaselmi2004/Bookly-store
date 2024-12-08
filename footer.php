<?php

// Inclure le fichier de configuration pour la connexion à la base de données
include 'config.php';

// Démarrer une session pour accéder à l'ID de l'utilisateur
session_start();

// Récupérer l'ID de l'utilisateur à partir de la session
$user_id = $_SESSION['user_id'];

// Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Vérifier si le formulaire a été soumis
if (isset($_POST['send'])) {

    // Récupérer et sécuriser les données du formulaire
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = $_POST['number'];
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    // Vérifier si un message identique a déjà été envoyé
    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('Query failed');
    
    // Si un message identique existe déjà, afficher un message d'erreur
    if (mysqli_num_rows($select_message) > 0) {
        $message[] = 'Message déjà envoyé !';
    } else {
        // Si aucun message identique n'existe, insérer le nouveau message dans la base de données
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('Query failed');
        $message[] = 'Message envoyé avec succès !';
    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Librairie</title>

    <!-- Lien vers le CSS externe (style de la page) -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Lien vers la bibliothèque Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Contactez-nous</h3>
   <p><a href="home.php">Accueil</a> / Contact</p>
</div>

<section class="contact">
   
   <!-- Formulaire de contact -->
   <form action="" method="post">
      <h3>Exprimez-vous !</h3>
      
      <!-- Champ de texte pour le nom -->
      <input type="text" name="name" required placeholder="Entrez votre nom" class="box">
      
      <!-- Champ de texte pour l'email -->
      <input type="email" name="email" required placeholder="Entrez votre email" class="box">
      
      <!-- Champ de texte pour le numéro de téléphone -->
      <input type="number" name="number" required placeholder="Entrez votre numéro" class="box">
      
      <!-- Champ de texte pour le message -->
      <textarea name="message" class="box" placeholder="Entrez votre message" id="" cols="30" rows="10"></textarea>
      
      <!-- Bouton d'envoi du message -->
      <input type="submit" value="Envoyer le message" name="send" class="btn">
   </form>

   <!-- Affichage du message après soumission du formulaire -->
   <?php
   if (!empty($message)) {
       foreach ($message as $msg) {
           echo '<div class="message">'.$msg.'</div>';
       }
   }
   ?>

</section>

<!-- Footer -->
<section class="footer">

   <div class="box-container">

      <div class="box">
         <h3>Liens rapides</h3>
         <a href="home.php">Accueil</a>
         <a href="about.php">À propos</a>
         <a href="shop.php">Boutique</a>
         <a href="contact.php">Contact</a>
      </div>

      <div class="box">
         <h3>Liens supplémentaires</h3>
         <a href="login.php">Se connecter</a>
         <a href="register.php">S'inscrire</a>
         <a href="cart.php">Panier</a>
         <a href="orders.php">Commandes</a>
      </div>

      <div class="box">
         <h3>Informations de contact</h3>
         <p> <i class="fas fa-phone"></i> +21652297309 </p>
         <p> <i class="fas fa-phone"></i> +111-222-3333 </p>
         <p> <i class="fas fa-envelope"></i> projectbyEya@gmail.com </p>
         <p> <i class="fas fa-map-marker-alt"></i> Tunisie, Nabeul-8000 </p>
      </div>

      <div class="box">
         <h3>Suivez-nous</h3>
         <a href="#"> <i class="fab fa-facebook-f"></i> Facebook </a>
         <a href="#"> <i class="fab fa-twitter"></i> Twitter </a>
         <a href="#"> <i class="fab fa-instagram"></i> Instagram </a>
         <a href="#"> <i class="fab fa-linkedin"></i> LinkedIn </a>
      </div>

   </div>

   <p class="credit"> &copy; copyright <?php echo date('Y'); ?> par <span>Eya Selmi</span> </p>

</section>

<!-- Lien vers le fichier JS pour le script de la page -->
<script src="js/script.js"></script>

</body>
</html>
