<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update'])){
 /// Update Product Info
   $pid = $_POST['pid'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $update_product = $connect->prepare("UPDATE `products` SET name = ?, price = ?, details = ? WHERE id = ?");
   $update_product->execute([$name, $price, $details, $pid]);

   $message[] = 'Product Details Updated';


 /// Update Product Image 01
   $old_image_01 = $_POST['old_image_01'];
   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_01_size = $_FILES['image_01']['size'];
   $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
   $image_01_folder = '../uploaded_img/'.$image_01;

   if(!empty($image_01)){
      if($image_01_size > 2000000){
         $message[] = 'Image size cannot be greater than 2 MB';
      }else{
         $update_image_01 = $connect->prepare("UPDATE `products` SET image_01 = ? WHERE id = ?");
         $update_image_01->execute([$image_01, $pid]);
         move_uploaded_file($image_01_tmp_name, $image_01_folder);
         /// Delete Old Image
         if (file_exists('../uploaded_img/'.$old_image_01)) {
            unlink('../uploaded_img/'.$old_image_01);
            $message[] = 'Image 01 updated';
        } else {
            // Handle the case where the file does not exist.
            echo 'The file does not exist.';
        }
      }
   }

 /// Update Product Image 02
   $old_image_02 = $_POST['old_image_02'];
   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_02_size = $_FILES['image_02']['size'];
   $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
   $image_02_folder = '../uploaded_img/'.$image_02;

   if(!empty($image_02)){
      if($image_02_size > 2000000){
         $message[] = 'Image size cannot be greater than 2 MB';
      }else{
         $update_image_02 = $connect->prepare("UPDATE `products` SET image_02 = ? WHERE id = ?");
         $update_image_02->execute([$image_02, $pid]);
         move_uploaded_file($image_2_tmp_name, $image_02_folder);
          /// Delete Old Image
          if (file_exists('../uploaded_img/'.$old_image_02)) {
            unlink('../uploaded_img/'.$old_image_02);
            $message[] = 'Image 02 updated';
        } else {
            // Handle the case where the file does not exist.
            echo 'The file does not exist.';
        }
      }
   }

    /// Update Product Image 03
   $old_image_03 = $_POST['old_image_03'];
   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_03_size = $_FILES['image_03']['size'];
   $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
   $image_03_folder = '../uploaded_img/'.$image_03;

   if(!empty($image_03)){
      if($image_03_size > 2000000){
         $message[] = 'Image size cannot be greater than 2 MB';
      }else{
         $update_image_03 = $connect->prepare("UPDATE `products` SET image_03 = ? WHERE id = ?");
         $update_image_03->execute([$image_03, $pid]);
         move_uploaded_file($image_03_tmp_name, $image_03_folder);
          /// Delete Old Image
          if (file_exists('../uploaded_img/'.$old_image_03)) {
            unlink('../uploaded_img/'.$old_image_03);
            $message[] = 'Image 03 updated';
        } else {
            // Handle the case where the file does not exist.
            echo 'The file does not exist.';
        }
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
   <title>Update Product</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!---  CSS Link-->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

  <!--- Update Product Section Starts-->
<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = $_GET['update'];
      $select_products = $connect->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image_01" value="<?= $fetch_products['image_01']; ?>">
      <input type="hidden" name="old_image_02" value="<?= $fetch_products['image_02']; ?>">
      <input type="hidden" name="old_image_03" value="<?= $fetch_products['image_03']; ?>">

      <div class="image-container">
         <div class="main-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
         </div>
         <div class="sub-image">
            <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_02']; ?>" alt="">
            <img src="../uploaded_img/<?= $fetch_products['image_03']; ?>" alt="">
         </div>
      </div>
      <!--- Update Name-->
      <span>Update Name</span>
      <input type="text" name="name" required class="box" maxlength="100" placeholder="enter product name" value="<?= $fetch_products['name']; ?>">
      
      <!--- Update Price-->
      <span>Update Price</span>
      <input type="number" name="price" required class="box" min="0" max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?= $fetch_products['price']; ?>">
      
      <!--- Update Details-->
      <span>Update Details</span>
      <textarea name="details" class="box" required cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
     
      <!--- Update Images-->
      <span>Update Image 01</span>
      <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <span>Update Image 02</span>
      <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <span>Update Image 03</span>
      <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box">
      
      <div class="flex-btn">
         <input type="submit" name="update" class="btn" value="update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No Product Added</p>';
      }
   ?>
</section>
  <!--- Update Product Section Ends-->



    <!---  Custom JS Link-->
<script src="../js/admin_script.js"></script>
   
</body>
</html>