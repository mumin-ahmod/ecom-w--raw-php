<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
};

include 'components/wishlist_cart.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
</head>
<body>
    <script> /// used to turn off form submission with restart page
     if ( window.history.replaceState ) {
     window.history.replaceState( null, null, window.location.href );
     }
    </script>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';
?>

<!--- Search form starts-->
<section class="search-form">

<form action="" method="post">
    <input type="text" class="box" maxlength="100" placeholder="Search"
    required name="search_box">
    <!--    <button id="btn"><i class="fa fa-search"></i></button>-->
    <button type="submit" class="fas fa-search" name="search_btn"></button>
</form>

</section>
<!--- Search form Ends-->



<section class="products" style="padding-top: 0; min-height: 100vh;">


    <div class="box-container">
    <?php
      if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
        $search_box = $_POST['search_box'];
       // $search_btn = $_POST['search_btn'];

     $select_products = $connect->prepare("SELECT * FROM `products`
     WHERE name LIKE '%{$search_box}%'  "); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
        // USE AJAX to Search in real time wihtout pressing search button

           ////////// binding method to search
        /*********
          if (isset($_POST['search_box'])) {
                $search_box = $_POST['search_box'];

                // Prepare a statement with a placeholder
                $select_products = $connect->prepare("SELECT * FROM `products` WHERE name LIKE :search_query");

                // Bind the search query parameter
                $select_products->bindParam(':search_query', $search_query, PDO::PARAM_STR);
                $search_query = '%' . $search_box . '%';

                // Execute the query
                $select_products->execute();

                // Rest of the code to fetch and display results (within the while loop)
           }

        */ 
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
}
    
    ?>

</div>
</section>

<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>