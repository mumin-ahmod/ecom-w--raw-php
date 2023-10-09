<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

include 'components/wishlist_cart.php';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';
?>

<!---catetgory section starts -->
 <section class="products">
    <h1 class="heading"> Browse <?php $category = $_GET['category']; 
        echo $category?></h1> <!--GET method is used to here (Remove this for error) to use dynamic category name from database-->
    <div class="box-container">
<?php
$fetch_products = '';
     $category = $_GET['category']; 
     $select_products = $connect->prepare("SELECT * FROM `products` WHERE name LIKE '%{$category}%'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
  
    <form action="" method="post" class="box">
      
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_products['image_01']; ?>">
      
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
      
      <img src="uploaded_img/<?= $fetch_products['image_01']; ?>" alt="" class="image">
      
      <div class="name"><?= $fetch_products['name']; ?></div>
      
      <div class="flex">
         <div class="price"><span>Tk </span><?= $fetch_products['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
        </div>
      
        <input type="submit" value="add to cart" class="btn" name="add_to_cart" class="btn">
      </form>
    
    <?php
       }
    }else{
        echo '<p class="empty">No Products Found</p>';
    }
    
    ?>

</div>
</section>

<!---category sections ends -->

<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>