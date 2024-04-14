<?php

session_start();
// Report all errors except notices and deprecated warnings
error_reporting(0);


require 'config/database.php';
require 'config/constants.php';

$username_email = $_SESSION['signin-data']['username_email'] ?? null;
$password = $_SESSION['signin-data']['password'] ?? null;
unset($_SESSION['signin-data']);



if(isset($_POST['submit'])){

$username_email = filter_var($_POST['username_email'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if(!$username_email){
    $_SESSION['signin']='Username or Email is required';
} elseif(!$password){
    $_SESSION['signin']='Password is required';
} else{
    //fetch user from the db
    $fetch_user_query = "SELECT * FROM users WHERE username = '$username_email' OR email='$username_email'";
    $fetch_user_result = mysqli_query($connection, $fetch_user_query);

    if(mysqli_num_rows($fetch_user_result) ==1){
        // convert the record into assoc array
        $user_record = mysqli_fetch_assoc($fetch_user_result);
        $db_password = $user_record['password'];
        // compare user password and db password
        if(password_verify($password, $db_password)){
            // proceed to set session for access control
            $_SESSION['user-id']= $user_record['id'];
            // set session if user is an admin
            if($user_record['is_admin']==1){
                $_SESSION['user_is_admin']= true;
            }
            // log user in
            header('location:' .ROOT_URL . 'admin/');
        } else{
            $_SESSION['signin']='Please check your inputs';
        }
    }else{
        $_SESSION['signin']='User not found';
    }
}

// if any problem redirect to login page
if(isset($_SESSION['signin'])){
    $_SESSION['signin-data']=$_POST;
    header('location: ' . ROOT_URL . 'signin.php');
    die();
}




} else{
    /* header('location: signin.php');
    die(); */
    
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Multipage Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign In</h2>
       <?php if(isset($_SESSION['signup-success'])) : ?>
        <div class="alert__message success">
            <p>
                <?= $_SESSION['signup-success'];
                unset($_SESSION['signup-success']);
                ?>
            </p>
        </div>
       

       <?php elseif (isset($_SESSION['signin'])) : ?>
        <div class="alert__message error">
            <p>
                <?= $_SESSION['signin'];
                unset($_SESSION['signin']);
                ?>
            </p>
        </div>
        <?php endif ?>
        <form action="#" method="POST">
            <input type="text" name="username_email" value="<?= $username_email?>" placeholder="Username or Password">
            <input type="password" name="password" value="<?= $password?>" placeholder=" Password">
           
            <button type="submit" name="submit" class="btn">Sign In</button>
            <small>Don't have an account?  <a href="signup.php">Sign Up</a></small>
            
            
        </form>
    </div>
</section>
</body>