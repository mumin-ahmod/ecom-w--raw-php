<?php
require_once '../components/connect.php';
session_start();


 $admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

if(isset($_GET['delete'])){
    //// Query to delete all user info with upon account deletion
    $delete_id = $_GET['delete'];
    $delete_users = $connect->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);

    $delete_id = $_GET['delete'];
    $delete_order = $connect->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->execute([$delete_id]);

    $delete_cart = $connect->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);

    $delete_wishlist = $connect->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    $delete_wishlist->execute([$delete_id]);

    $delete_messages = $connect->prepare("DELETE FROM `messages` WHERE user_id = ?");
    $delete_messages->execute([$delete_id]);

    header('location:users_accounts.php');
 }

?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>User Accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- CSS link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
<?php include '../components/admin_header.php'; ?>

<!--- Users Account Section Starts --->
<section class="accounts">

   <h1 class="heading">User Accounts</h1>

   <div class="box-container">

   <?php /// Query for obtaining admin account info
      $select_accounts = $connect->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <!--Accounts Box-->
   <div class="box">
      <p> User ID: <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> User Name: <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn"> 
        
         <!--Delete Button with account id query in url to be deleted--->
          <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" 
         onclick="return confirm('Delete This Account?')" 
         class="delete-btn">Delete</a>

      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No Accounts Available!</p>';
      }
   ?>

   </div>

</section>
<!--- Users Account Section Ends --->



<!-- custom js file -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>