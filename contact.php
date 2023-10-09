<?php 
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
};

if(isset($_POST['send'])){
   /// send message and user info to db via sql query post method
   
  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  
  $email = $_POST['email'];
  $email = filter_var($email, FILTER_SANITIZE_STRING);

  $number = $_POST['number'];
  $number = filter_var($number, FILTER_SANITIZE_STRING);

  $msg = $_POST['msg'];
  $msg = filter_var($msg, FILTER_SANITIZE_STRING);

  $select_message = $connect->prepare("SELECT * FROM `messages` WHERE
  name = ? AND email = ? AND number = ? AND message = ?");
  $select_message ->execute([$name, $email, $number, $msg]); /// error potential without [];

  if($select_message->rowCount()>0){
    $message[] = 'Message already sent';

  }else{
    $send_message = $connect->prepare("INSERT INTO `messages`(name, email, number, message) 
    VALUES(?,?,?,?)");
    $send_message->execute([$name, $email, $number, $msg]);
    $message[] = 'Message Sent';

  }

}

//// try addining contact link on footer 
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';
?>

<!---Contact Section starts---->
<section class="form-container">
 <!--- <h1 class="heading">Contact Us</h1> -->
<form action="" class="box" method="post">
    <h3>Send us a message</h3>

   <input type="text" name="name" required placeholder="Enter your name"
   maxlength="20" class="box">
   <!--- try with actual phone tag <tel> -->
   <input type="tel" name="number" required placeholder="Enter your number"
   max="9999999999" min="0" class="box" onkeypress="if(this.value.length == 10) return false;">
   
   <input type="email" name="email" required placeholder="Enter your E-mail"
   maxlength="50" class="box">

   <textarea name="msg" class="box" cols="30" rows="10"
   required placeholder="Enter your message"></textarea>

   <input type="submit" value="send message" name="send" class="btn">
</form>

</section>

<!---Contact Section Ends---->

<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>