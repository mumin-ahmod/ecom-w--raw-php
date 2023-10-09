<?php
require_once '../components/connect.php';
session_start();


 $admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
    header('location:admin_login.php');
}

/// query for deleting messages
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_messages = $connect->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_messages->execute([$delete_id]);
    header('location:messages.php');
 }

?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- CSS link -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
<?php include '../components/admin_header.php' ?>

<!--- Messages Section Starts --->

<section class="messages">
     <h1 class="heading">Messages</h1>
    <div class="box-container">
    <?php 
    $select_messages = $connect->prepare("SELECT * FROM `messages` ");
    $select_messages->execute();
    if ($select_messages->rowCount()>0) {
       while ($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)) {
    ?>

     <div class="box">
       <p>User ID: <span><?= $fetch_messages['user_id']; ?></span></p>
       <p>Name: <span><?= $fetch_messages['name']; ?></span></p>
       <p>Number: <span><?= $fetch_messages['number']; ?></span></p>
       <p>Email: <span><?= $fetch_messages['email']; ?></span></p>
       <p>Message: <span><?= $fetch_messages['message']; ?></span></p>
         <!--Delete Button with account id query in url to be deleted--->
         <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" 
         onclick="return confirm('Delete This Message?')" 
         class="delete-btn">Delete</a>



     </div>
    <?php
         }
        }else{
            echo '<p class="empty">You have no messages</p>';
        }
    ?>


    </div>

</section>

<!--- Messages Section Ends --->


<!-- custom js file -->
<script src="../js/admin_script.js"></script>
   
</body>
</html>