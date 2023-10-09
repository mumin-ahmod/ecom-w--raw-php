<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
 
    $select_user = $connect->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
 
    if($select_user->rowCount() > 0){
        $message[] = 'User already exists';
     }else{
        if($pass != $cpass){
           $message[] = 'Password does not match';
        }else{
           $insert_user = $connect->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
           $insert_user->execute([$name, $email, $cpass]);
           $message[] = 'Registered Successfully';
        }
     }
 
 }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';
?>

<!---- User Register Section Starts -->

<section class="form-container">

    <form action="" method="POST">
        <h3>Register Now</h3>

        <input type="text" required maxlength="20" name="name" placeholder="Enter Your Name" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="email" required maxlength="50" name="email" placeholder="Enter Your E-Mail" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="password" required maxlength="20" name="pass" placeholder="Enter Your Password" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" required maxlength="20" name="cpass" placeholder="Confirm Your Password" class="box"
        oninput="this.value = this.value.replace(/\s/g, '')">

        <input type="submit" value="register now" class="btn" name="submit">
       <p>Already have an account?</p>
       <a href="user_login.php" class="option-btn">Login Now</a>
        
        
    </form>

</section>

  <!----  User Register Section Ends -->

















<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>