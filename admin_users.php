<?php

// Include the configuration file for database connection
include 'config.php';

// Start the session to manage logged-in admin
session_start();

// Get the admin ID from the session to ensure the admin is logged in
$admin_id = $_SESSION['admin_id'];

// If the admin ID is not set, meaning the admin is not logged in, redirect to the login page
if(!isset($admin_id)){
   header('location:login.php');
}

// Deleting a user when the "delete" link is clicked
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete']; // Get the user ID to delete from the URL
   // Delete the user from the database based on the provided ID
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   // After deletion, refresh the page to reflect the changes
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <!-- Link to Font Awesome CDN for using icons like delete buttons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- Link to custom CSS file for admin panel styling -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>

<?php include 'admin_header.php'; ?> <!-- Include the header of the admin panel -->

<!-- User accounts section -->
<section class="users">

   <h1 class="title">User Accounts</h1>

   <div class="box-container">

      <?php
         // Query to fetch all users from the "users" table in the database
         $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
         
         // Loop through all the fetched users and display them
         while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <!-- Display user details: ID, Name, Email, and User Type -->
         <p> User ID: <span><?php echo $fetch_users['id']; ?></span> </p>
         <p> Username: <span><?php echo $fetch_users['name']; ?></span> </p>
         <p> Email: <span><?php echo $fetch_users['email']; ?></span> </p>
         <!-- Display user type with color styling for 'admin' type -->
         <p> User Type: <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--orange)'; } ?>"><?php echo $fetch_users['user_type']; ?></span> </p>
         <!-- Provide a delete option for each user with a confirmation dialog -->
         <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete User</a>
      </div>
      <?php
         };
      ?>

   </div>

</section>

<!-- Custom JavaScript file link for admin panel functionality -->
<script src="js/admin_script.js"></script>

</body>
</html>
