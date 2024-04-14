<?php 
error_reporting(0);
include('partial/header.php');
//require 'config/database.php';


if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM categories WHERE id=$id";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) === 1){
    $category = mysqli_fetch_assoc($result);
}
} else {
    header('location:' .ROOT_URL . 'admin/manage-category.php');
    die();
}

if(isset($_POST['submit'])){
    // get updated form data
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   

    // check for valid input
    if(!$title || !$description){
        $_SESSION['edit-category'] = "Invalid form input on edit page.";
    } else {
        // update user
        $query = "UPDATE categories SET title='$title', description='$description' WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);

        if(mysqli_errno($connection)){
            $_SESSION['edit-category']= "Failed to update user.";
        } else{
            $_SESSION['edit-category-success'] = " $title  and description were updated successfully";
        }
    }

    
header('location:' . ROOT_URL . 'admin/manage-category.php');
die();

}


?>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Edit Category</h2>
        
        <form action="#" method="POST">
            <input type="hidden" name='id' value="<?= $category['id'] ?>" placeholder="id">
            <input type="text" name='title' value="<?= $category['title'] ?>" placeholder="Title">
            <textarea name="description" rows="4"  placeholder="Description"><?= $category['description'] ?></textarea>
           
            <button type="submit" name='submit' class="btn">Update Category</button>
            
            
            
        </form>
    </div>
</section>
<?php
    include('../partials/footer.php');
?>