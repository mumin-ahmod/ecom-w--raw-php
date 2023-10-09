<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image_01 = $_FILES['image_01']['name'];
   $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
   $image_01_size = $_FILES['image_01']['size'];
   $image_01_tmp_name = $_FILES['image_01']['tmp_name'];
   $image_01_folder = '../uploaded_img/'.$image_01;

   $image_02 = $_FILES['image_02']['name'];
   $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
   $image_02_size = $_FILES['image_02']['size'];
   $image_02_tmp_name = $_FILES['image_02']['tmp_name'];
   $image_02_folder = '../uploaded_img/'.$image_02;

   $image_03 = $_FILES['image_03']['name'];
   $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
   $image_03_size = $_FILES['image_03']['size'];
   $image_03_tmp_name = $_FILES['image_03']['tmp_name'];
   $image_03_folder = '../uploaded_img/'.$image_03;

   $select_products = $connect->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product Name already exists';
   }else{

      $insert_products = $connect->prepare("INSERT INTO `products`(name, details, price, image_01, image_02, image_03) VALUES(?,?,?,?,?,?)");
      $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

      if($insert_products){
         if($image_01_size > 2000000 OR $image_02_size > 2000000 OR $image_03_size > 2000000){
            $message[] = 'Image size cannot be more than 2 MB';
         }else{
            move_uploaded_file($image_01_tmp_name, $image_01_folder);
            move_uploaded_file($image_02_tmp_name, $image_02_folder);
            move_uploaded_file($image_03_tmp_name, $image_03_folder);
            $message[] = 'New Product: '.$name.' added';
         }
            // Redirect to a different page or back to the same page
            // Add AJAX Here to prevent  the default form submission, 
            //send an AJAX request to the PHP script, 
            //and then displays the success message 
            //or error messages returned by the server in the 
            //messageContainer element on the same page without reloading it.
                          #code to be tried 
      }

   }  

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $connect->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
   unlink('../uploaded_img/'.$fetch_delete_image['image_03']);
   $delete_product = $connect->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $connect->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   $delete_wishlist = $connect->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   header('location:products.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
 <!---CSS Link-->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!----Add Product Section Starts-->
<section class="add-products">

   <h1 class="heading">Add Prdocut</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
            <span>Product Name (required)</span>
            <input type="text" class="box" required maxlength="100" placeholder="enter product name" name="name">
         </div>

         <div class="inputBox">
            <span>Product Price (required)</span>
            <input type="number" min="0" class="box" required max="9999999999" placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" name="price">
         </div>

        <div class="inputBox">
            <span>Image 01 (required)</span>
            <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>

        <div class="inputBox">
            <span>Image 02 (required)</span>
            <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>

        <div class="inputBox">
            <span>Image 03 (required)</span>
            <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
        </div>
         <div class="inputBox">
            <span>Product Details (required)</span>
            <textarea name="details" placeholder="enter product details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
         </div>
      </div>
      
      <input type="submit" value="add product" class="btn" name="add_product">
   </form>

</section>
<!----Add Product Section Ends-->


 <!--- Show Product section starts--->
<section class="show-products">

   <h1 class="heading">Products Added</h1>

   <div class="box-container">

   <?php
      $select_products = $connect->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <div class="box">
      <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="price">Tk <span><?= $fetch_products['price']; ?></span>/-</div>
      <div class="details"><span><?= $fetch_products['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
         <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No Products Added Yet</p>';
      }
   ?>
   <!--- Show Product section ends--->
   </div>

</section>







<!---- custom js link-->
<script src="../js/admin_script.js"></script>
   
</body>
</html>