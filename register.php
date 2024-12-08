<?php

// Inclusion du fichier de configuration contenant les informations de connexion à la base de données
include 'config.php';

// Vérifie si le formulaire a été soumis (lorsque le bouton "submit" est cliqué)
if(isset($_POST['submit'])){

   // Récupère les données soumises dans le formulaire et les échappe pour éviter les injections SQL
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, md5($_POST['password']));  // Mot de passe crypté avec md5
   $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));  // Confirmation du mot de passe cryptée également
   $user_type = $_POST['user_type'];  // Type d'utilisateur (admin ou user)

   // Vérifie si un utilisateur avec le même email et mot de passe existe déjà dans la base de données
   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

   // Si un utilisateur existe déjà avec cet email et mot de passe, affiche un message d'erreur
   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'user already exist!';  // L'utilisateur existe déjà
   }else{
      // Si les mots de passe ne correspondent pas, affiche un message d'erreur
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';  // Les mots de passe ne correspondent pas
      }else{
         // Insère le nouvel utilisateur dans la base de données si tout est correct (pas de doublon et mots de passe correspondants)
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$cpass', '$user_type')") or die('query failed');
         $message[] = 'registered successfully!';  // Inscription réussie
         header('location:login.php');  // Redirige vers la page de connexion après l'inscription
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
   <title>register</title>

   <!-- Lien vers la bibliothèque Font Awesome pour les icônes -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Lien vers le fichier CSS personnalisé pour styliser la page -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- Affichage des messages d'erreur ou de succès -->
<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>  <!-- Affiche le message -->
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>  <!-- Icône pour fermer le message -->
      </div>
      ';
   }
}
?>

<!-- Formulaire d'inscription -->
<div class="form-container">
   <form action="" method="post">  <!-- Le formulaire envoie les données à la même page -->
      <h3>register now</h3>  <!-- Titre de la page "S'inscrire maintenant" -->
      <input type="text" name="name" placeholder="enter your name" required class="box">  <!-- Champ pour le nom -->
      <input type="email" name="email" placeholder="enter your email" required class="box">  <!-- Champ pour l'email -->
      <input type="password" name="password" placeholder="enter your password" required class="box">  <!-- Champ pour le mot de passe -->
      <input type="password" name="cpassword" placeholder="confirm your password" required class="box">  <!-- Champ pour confirmer le mot de passe -->
      
      <!-- Liste déroulante pour sélectionner le type d'utilisateur (admin ou user) -->
      <select name="user_type" class="box">
         <option value="user">user</option>  <!-- Option pour un utilisateur normal -->
         <option value="admin">admin</option>  <!-- Option pour un administrateur -->
      </select>
      
      <!-- Bouton pour soumettre le formulaire -->
      <input type="submit" name="submit" value="register now" class="btn">  
      <!-- Lien vers la page de connexion pour les utilisateurs qui ont déjà un compte -->
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>
</div>

</body>
</html>
