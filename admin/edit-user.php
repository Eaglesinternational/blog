<?php
error_reporting(0);
include('partial/header.php');
require 'config/database.php';

if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM users WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);
} else {
    header('location:' .ROOT_URL . 'admin/manage-users.php');
    die();
}

if(isset($_POST['submit'])){
    // get updated form data
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);

    // check for valid input
    if(!$firstname || !$lastname){
        $_SESSION['edit-user'] = "Invalid form input on edit page.";
    } else {
        // update user
        $query = "UPDATE users SET firstname='$firstname', lastname='$lastname', is_admin='$is_admin' WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);

        if(mysqli_errno($connection)){
            $_SESSION['edit-user']= "Failed to update user.";
        } else{
            $_SESSION['edit-user-success'] = "User $firstname  $lastname updated successfully";
        }
    }

    
header('location:' . ROOT_URL . 'admin/manage-users.php');
die();

}
/* 
header('location:' . ROOT_URL . 'admin/manage-users.php');
die(); */



?>
<section class="form__section ">
    <div class="container form__section-container edit">
        <h2>Edit User</h2>
        
        <form action="#" enctype="multipart/form-data" method="POST">
            <input type="hidden" name='id' value="<?= $user['id'] ?>" >
            <input type="text" name='firstname' value="<?= $user['firstname'] ?>" placeholder="First Name">
            <input type="text" name='lastname' value="<?= $user['lastname'] ?>" placeholder="Last Name">
             <select name="userrole" id="">
                <option value="0">Author</option>
                <option value="1">Admin</option>
            </select>
            
            <button type="submit" name='submit' class="btn">Update User</button>
            <!-- <small class="add__user">Already have an account?  <a href="signin.html">Sign In</a></small>
             -->
            
        </form>
    </div>
</section>


<?php
    include('../partials/footer.php');
?>