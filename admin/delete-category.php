<?php 

require 'config/database.php';

if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // fetch user from database

    $query = "SELECT * FROM categories WHERE id =$id";
    $result = mysqli_query($connection, $query);
    $category = mysqli_fetch_assoc($result);

    // make only user was fetch
    if(mysqli_num_rows($result)== 1){
        /* $avatar_name = $user['avatar'];
        $avatar_path = '../images/' . $avatar_name; */
        // delete image if available
        /* if($avatar_path){
            unlink($avatar_path);
        } */
    }
        // for later
        // fetch all user's posts and delete

        $update_query ="UPDATE posts SET category_id=13 WHERE category_id=$id";
        $update_result = mysqli_query($connection, $update_query);

        if(!mysqli_errno($connection)){
           
       



        // delete Category from db
        $delete_category_query = "DELETE FROM categories WHERE id=$id";
        $delete_category_result = mysqli_query($connection, $delete_category_query);
        if(mysqli_errno($connection)){
            $_SESSION['delete-category'] = "Couldn't delete user '{$category['title']}  '{$category['description']}'";
        } else {
            $_SESSION['delete-category-success'] = "  {$category['title']}  and description deleted successfully";
        }
    }
}

header('location: ' .ROOT_URL . 'admin/manage-category.php');
die();