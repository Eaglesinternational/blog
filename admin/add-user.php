<?php
error_reporting(0);
include('partial/header.php');

require 'config/database.php';


$firstname = $_SESSION['add-user-data']['firstname'] ?? null;
$lastname = $_SESSION['add-user-data']['lastname']?? null;
$username = $_SESSION['add-user-data']['username'] ?? null;
$email = $_SESSION['add-user-data']['email'] ?? null;
$password = $_SESSION['add-user-data']['password'] ?? null;
$cpassword = $_SESSION['add-user-data']['cpassword'] ?? null;
$userrole = $_SESSION['add-user-data']['userrole'] ?? null;
/* $firstname = $_SESSION['signup-data']['firstname']; */

//delete the session
unset($_SESSION['add-user-data']);


// get user form if submit button is clicked
if(isset($_POST['submit'])){
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpassword = filter_var($_POST['cpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];

   // validate input values

   if(!$firstname){
    $_SESSION['add-user']= "Please, enter your first Name";
   } elseif(!$lastname){
    $_SESSION['add-user']= "Please, enter your last Name";
   } elseif(!$username){
    $_SESSION['add-user']= "Please, enter your Username";
   } elseif(!$email){
    $_SESSION['add-user']= "Please, enter a valid email";
   }/*  elseif(!$is_admin){
    $_SESSION['add-user']= "Please, select your user role";
   } */ elseif(strlen($password) < 8 || strlen($cpassword) < 8){
    $_SESSION['add-user']= "Password should be 8+ characters";
   }elseif(!$avatar['name']){
    $_SESSION['add-user']= "Please, add an avatar";
   } else{
    if($password !== $cpassword){
        $_SESSION['add-user']= "Passwords do not match";
    } else{
        // hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // check if username or email already exist in the db
        $user_check_query = "SELECT * FROM users WHERE username= '$username' OR email = '$email'";
        $user_check_result = mysqli_query($connection, $user_check_query);
        if(mysqli_num_rows($user_check_result)>0){
            $_SESSION['add-user']= "Username or Email already exists";
        } else{
            // work on the avatar

            // rename avatar

            $time = time(); // make each image unique using current timestamp
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name']; 
            $avatar_destination_path = '../images/'.$avatar_name;

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
                    $_SESSION['add-user']= "File size too big. Should be less than 1mb";
                } 

            }  else{
                $_SESSION['add-user']= "File should be png, jpg or jpeg";
            } 

        }
    }
   }

// Redirect to add-user if any problem

if($_SESSION['add-user']){
    // post form data back to the add-user page
    $_SESSION['add-user-data']=$_POST;
    header('location:add-user.php');
    die();
} else{
    // Insert new user into the db

    $insert_user_query = "INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES('$firstname', '$lastname', '$username', '$email', '$hashed_password', '$avatar_name', $is_admin)";

    $insert_result= mysqli_query($connection, $insert_user_query);
    if(!mysqli_errno($connection)){
        // redirect to login page with success message
        $_SESSION['add-user-success'] = "New user $firstname $lastname added successfully";
        header('location: ' .ROOT_URL . 'admin/manage-users.php');
        die();
    }
}

} else {
    
}





?>

<section class="form__section">
    <div class="container form__section-container">
        <h2 class='mess'>Add User</h2>
        <!-- <div class="alert__message error ">
            <p>This is an error message</p>
        </div> -->
        <?php  if(isset($_SESSION['add-user'])) :   ?>

            <div class="alert__message error ">
            <p>
                <?= $_SESSION['add-user'];
                unset($_SESSION['add-user']);
                ?>
            </p>
        </div>
            <?php endif ?>
        <form action="#" enctype="multipart/form-data" method='POST'>
            <input type="text" name='firstname' value="<?= $firstname ?>" placeholder="First Name">
            <input type="text" name='lastname' value="<?= $lastname ?>" placeholder="Last Name">
            <input type="text" name='username' value="<?= $username ?>" placeholder="Username">
            <input type="email" name='email' value="<?= $email ?>" placeholder="Email">
            <input type="password" name='password' value="<?= $password ?>" placeholder="Create password">
            <input type="password" name='cpassword' value="<?= $cpassword ?>" placeholder="Confirm password">
            <select name="userrole" value="<?= $userrole ?>" id="">
                <option value="0">Author</option>
                <option value="1">Admin</option>
            </select>
            <div class="form__control">
                <label for="avatar">User Avatar </label>
                <input type="file" name='avatar' id="avatar">
            </div>
            <button type="submit" name='submit' class="btn join">Add User</button>
            <!------<small class="add__user">Already have an account?  <a href="signin.html">Sign In</a></small> -->
            
            
        </form>
    </div>
</section>
<?php
    include('../partials/footer.php');
?>
