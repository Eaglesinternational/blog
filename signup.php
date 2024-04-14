<?php

session_start();

require 'config/database.php';

// give back form if there was a registration error

$firstname = $_SESSION['signup-data']['firstname'] ?? null;
$lastname = $_SESSION['signup-data']['lastname']?? null;
$username = $_SESSION['signup-data']['username'] ?? null;
$email = $_SESSION['signup-data']['email'] ?? null;
$password = $_SESSION['signup-data']['password'] ?? null;
$cpassword = $_SESSION['signup-data']['cpassword'] ?? null;
/* $firstname = $_SESSION['signup-data']['firstname']; */

//delete the session
unset($_SESSION['signup-data']);

if(isset($_POST['submit'])){
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];

   // validate input values

   if(!$firstname){
    $_SESSION['signup']= "Please, enter your first Name";
   } elseif(!$lastname){
    $_SESSION['signup']= "Please, enter your last Name";
   } elseif(!$username){
    $_SESSION['signup']= "Please, enter your Username";
   } elseif(!$email){
    $_SESSION['signup']= "Please, enter a valid email";
   } elseif(strlen($password) < 8 || strlen($cpassword) < 8){
    $_SESSION['signup']= "Password should be 8+ characters";
   }elseif(!$avatar['name']){
    $_SESSION['signup']= "Please, add an avatar";
   } else{
    if($password !== $cpassword){
        $_SESSION['signup']= "Passwords do not match";
    } else{
        // hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // check if username or email already exist in the db
        $user_check_query = "SELECT * FROM users WHERE username= '$username' OR email = '$email'";
        $user_check_result = mysqli_query($connection, $user_check_query);
        if(mysqli_num_rows($user_check_result)>0){
            $_SESSION['signup']= "Username or Email already exists";
        } else{
            // work on the avatar

            // rename avatar

            $time = time(); // make each image unique using current timestamp
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name']; 
            $avatar_destination_path = 'images/'.$avatar_name;

            // make sure if file is an image

            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $avatar_name);
            $extension = end($extension);
            if(in_array($extension, $allowed_files)){
                // make sure the image is not too large(1mb)
                if($avatar['size'] < 1000000){
                    // upload avatar
                    move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                } else{
                    $_SESSION['signup']= "File size too big. Should be less than 1mb";
                } 

            }  else{
                $_SESSION['signup']= "File should be png, jpg or jpeg";
            } 

        }
    }
   }

// Redirect to signup if any problem

if($_SESSION['signup']){
    // post form data back to the signup page
    $_SESSION['signup-data']=$_POST;
    header('location:signup.php');
    die();
} else{
    // Insert new user into the db

    $insert_user_query = "INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES('$firstname', '$lastname', '$username', '$email', '$hashed_password', '$avatar_name', 0)";

    $insert_result= mysqli_query($connection, $insert_user_query);
    if(!mysqli_errno($connection)){
        // redirect to login page with success message
        $_SESSION['signup-success'] = 'Registration successful. Please, log in';
        header('location: ' .ROOT_URL . 'signin.php');
        die();
    }
}

} else {

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
        <h2>Sign Up</h2>
        <!-- <div class="alert__message error">
            <p>This is an error message</p>
        </div> -->
        <?php
        
        if(isset($_SESSION['signup'])) :?>

        <div class="alert__message error">
            <p>
                <?= $_SESSION['signup'];
                unset($_SESSION['signup'] );
                ?>
            </p>
            
        </div>
        
       <?php endif ?>
        <form action="#" enctype="multipart/form-data" method='POST'>
            <input type="text" name="firstname" value='<?=$firstname?>' placeholder="First Name">
            <input type="text" name="lastname" value="<?=$lastname?>" placeholder="Last Name">
            <input type="text" name="username" value="<?= $username?>" placeholder="Username">
            <input type="email" name="email" value="<?= $email?>" placeholder="Email">
            <input type="password" name="password" value="<?= $password?>" placeholder="Create password">
            <input type="password" name="cpassword" value="<?= $cpassword?>" placeholder="Confirm password">
            <div class="form__control">
                <label for="avatar">User Avatar </label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn">Sign Up</button>
            <small class="already">Already have an account?  <a href="signin.php">Sign In</a></small>
            
            
        </form>
    </div>
</section>
</body>