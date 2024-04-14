<?php 

require 'config/database.php';

if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // fetch user from database

    $query = "SELECT * FROM posts WHERE id =$id";
    $result = mysqli_query($connection, $query);
    //$user = mysqli_fetch_assoc($result);

    // make only one record was fetch
    if(mysqli_num_rows($result)== 1){
        $post = mysqli_fetch_assoc($result);
        $thumbnail_name = $post['thumbnail'];
        $thumbnail_path = '../images/' . $thumbnail_name;
        // delete image if available
        if($thumbnail_path){
            unlink($thumbnail_path);
        }
    }
        // for later
        // fetch all user's posts and delete




        // delete user from db
        $delete_post_query = "DELETE FROM posts WHERE id=$id LIMIT 1";
        $delete_post_result = mysqli_query($connection, $delete_post_query);
        if(mysqli_errno($connection)){
            $_SESSION['delete-post'] = "Couldn't delete post";
        } else {
            $_SESSION['delete-post-success'] = " Post deleted successfully";
        }

}

header('location: ' .ROOT_URL . 'admin/');
die();