<?php 
include 'components/connect.php';
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
    header('location:home.php');

}

if(isset($_POST['submit'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
 
    $update_profile = $connect->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update_profile->execute([$name, $email, $user_id]);
 
    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $prev_pass = $_POST['prev_pass'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   /* $select_prev_pass->execute([$user_id]);
    $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['password'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);
     */



 
    if($old_pass == $empty_pass){
       $message[] = 'Please enter current password!';
    }elseif($old_pass != $prev_pass){
       $message[] = 'current password does not match';
    }elseif($new_pass != $cpass){
       $message[] = 'Passwords doe not match';
    }else{
       if($new_pass != $empty_pass){
          $update_pass = $connect->prepare("UPDATE `users` SET password = ? WHERE id = ?");
          $update_pass->execute([$cpass, $user_id]);
          $message[] = 'Password updated successfully!';
       }else{
          $message[] = 'Please enter a new password!';
       }
    }
    
 }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update</title>
</head>
<body>
    <!--- Font Awesome Plug-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!--- CSS Link-->
    <link rel="stylesheet" href="css/style.css">

<?php 
  include 'components/user_header.php';
?>


<!---- User Profile Update Section Starts -->

<section class="form-container">

   <form action="" method="post">
      <h3>Update Your Profile</h3>
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">

      <input type="text" name="name" required placeholder="enter your username" maxlength="20"  class="box" value="<?= $fetch_profile["name"]; ?>">

      <input type="email" name="email" required placeholder="enter your email" maxlength="50"  class="box" oninput="this.value = 
      this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">

      <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20"  class="box" 
      oninput="this.value = this.value.replace(/\s/g, '')">

      <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20"  class="box" 
      ="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20"  class="box" 
      oninput="this.value = this.value.replace(/\s/g, '')">

      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>

  <!----  User Profile Update Ends -->


















<?php include 'components/footer.php'; ?>

   <!----- JS Link -->
   <script src="js/script.js"></script> 


</body>
</html>