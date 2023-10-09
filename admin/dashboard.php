<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!--- CSS Link-->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!--- Admin Dashboard section starts--->
<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <?php // Pending Order logic // adds pending order from database by total money
            $total_pendings = 0;
            $select_pendings = $connect->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>

         <h3><span>Tk </span><?= $total_pendings; ?><span>/-</span></h3>
         <p>Total Pendings</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">

        <?php // Completed Order logic // adds completed order from database by total money
            $total_completes = 0;
            $select_completes = $connect->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
        ?>

        <h3><span>TK </span><?= $total_completes; ?><span>/-</span></h3>
        <p>Completed Orders</p>
        <a href="placed_orders.php" class="btn">See Orders</a>

      </div>

       <div class="box">
        <?php /// Placed Order History /// calls from orders row and puts in variable to count using Row Count function.
            $select_orders = $connect->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
        ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>Orders Placed</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
       </div>

      <div class="box">
         <?php  // See Product list and added
            $select_products = $connect->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>Added Products</p>
         <a href="products.php" class="btn">See Products</a>
      </div>

      <div class="box">
         <?php /// See User List 
            $select_users = $connect->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>Users</p>
         <a href="users_accounts.php" class="btn">See Users</a>
      </div>

      <div class="box">
         <?php /// See Admin List 
            $select_admins = $connect->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>Admin Profiles</p>
         <a href="admin_accounts.php" class="btn">See Admins</a>
      </div>

      <div class="box">
         <?php /// See Messages 
            $select_messages = $connect->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Messages</p>
         <a href="messagess.php" class="btn">See Messages</a>
      </div>

   </div>

</section>
<!--- Dashboard section ends--->


<!---custom JS-->
<script src="../js/admin_script.js"></script>
   
</body>
</html>