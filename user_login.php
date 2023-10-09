<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if(isset($_POST['submit'])){

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
 
    $select_user = $connect->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select_user->execute([$email, $pass]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
 
    if($select_user->rowCount() > 0){
       $_SESSION['user_id'] = $row['id'];
       //header('location:home.php');
       header('location:home.php');
      $message[] = 'Login Successful';

    }else{
       $message[] = 'Incorrect Username or Password';
    }
 
 }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';?>

  <!---- User Login Section Starts -->

<section class="form-container">

    <form action="" method="POST">
        <h3>Login Now</h3>

        <input type="email" required maxlength="50" name="email" placeholder="Enter Your E-Mail" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="password" required maxlength="20" name="pass" placeholder="Enter Your Password" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="submit" value="login now" class="btn" name="submit">
       <p>Don't have an account?</p>
       <a href="user_register.php" class="option-btn">Register Now</a>
        
        
    </form>

</section> 
  <!---- User Login Section Ends -->











<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>