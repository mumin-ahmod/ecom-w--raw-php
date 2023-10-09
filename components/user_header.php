<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">
<section class="flex"> <!--try by adding home.php in href instead of dashboard-->
    <a href="dashboard.php" class="logo">Power<span>Pulse</span>
    </a>
    <!-- Right Side Of Navbar -->
           <!--- Navigation Bar -->

     <nav class="navbar">
        <a href="home.php">Home</a>  
        <a href="about.php">About</a>
        <a href="orders.php">Orders</a>
        <a href="Shop.php">Shop</a>
        <a href="Contact.php">Contact</a>

     </nav>

    <div class="icons">
    
        <?php         // Wishlist processing -->
        $count_wishlist_items = $connect->prepare("SELECT * FROM `wishlist` 
        WHERE user_id =?");
        $count_wishlist_items->execute([$user_id]);
        $total_wishlist_counts = $count_wishlist_items->rowCount(); 
        
        /// Cart Processing
        $count_cart_items = $connect->prepare("SELECT * FROM `cart` 
        WHERE user_id =?");
        $count_cart_items->execute([$user_id]);
        $total_cart_counts = $count_cart_items->rowCount();

        ?>

         <!-- Navbar buttons -->
        <div id="menu-btn" class="fas fa-bars"></div>

        <a href="search_page.php"><i class="fas fa-search"></i></a>
        
        <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts;?>)</span></a>

        <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts;?>)</span></a> 
        
        <div id="user-btn" class="fas fa-user"></div>
    </div>

     <!--- User profile box -->

     <div class="profile">
         <?php          
            $select_profile = $connect->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <!-- When signed in  -->
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="update_user.php" class="btn">Update Profile</a>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">Register</a>
            <a href="user_login.php" class="option-btn">Login</a>
         </div>
         <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('Logout from your account?');">Logout</a>
         <!--<a href="user_login.php" class="delete-btn" onclick="return confirm('Logout from your account?');">Logout</a>-->
         <?php
            }else{
         ?>
                  <!-- When logged out  -->
          <p>Log in to your account</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">Register</a>
            <a href="user_login.php" class="option-btn">Login</a>
         </div>
         <?php
            }
         ?>      
         
         
      </div>

      
</section>


</header>