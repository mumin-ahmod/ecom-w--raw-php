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
<section class="flex">
    <a href="dashboard.php" class="logo">Admin<span>Panel</span>
    </a>
    <!-- Right Side Of Navbar -->
           <!--- Navigation Bar -->

     <nav class="navbar"> <!---- admin pages -->
        <a href="dashboard.php">Home</a>  
        <a href="products.php">Products</a>
        <a href="placed_orders.php">Orders</a>
        <a href="admin_accounts.php">Admins</a>
        <a href="users_accounts.php">User</a> 
        <a href="messages.php">Messages</a>

     </nav>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="user-btn" class="fas fa-user"></div>
    </div>

     <!--- Admin profile box -->

    <div class="profile">
        <?php
        $select_profile = $connect->prepare("SELECT * FROM `admins` WHERE id 
        = ?");
        $select_profile->execute([$admin_id]);
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
       
        <p><?= $fetch_profile['name']; ?></p>
        <a href="../admin/update_profile.php" class="btn">Update Profile</a>
        <div class="flex-btn">
            <a href="../admin/register_admin.php" class="option-btn">Register</a>
            <a href="../admin/admin_login.php" class="option-btn">Log In</a>

        </div>
        <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm 
        ('Log out from your account?');">Logout</a>
    </div>

</section>


</header>