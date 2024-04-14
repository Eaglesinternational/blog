<?php
error_reporting(0);
include('partial/header.php');


require 'config/database.php';

$title = $_SESSION['add-category-data']['title'] ?? null;
$description = $_SESSION['add-category-data']['description'] ?? null;

unset($_SESSION['add-category-data']);


if(isset($_POST['submit'])){
    // get form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(!$title){
        $_SESSION['add-category']= "Enter title";
    } elseif(!$description){
        $_SESSION['add-category']= "Enter description";
    } 

    // redrirect if there is a problem with the form data
    if(isset($_SESSION['add-category'])){
        $_SESSION['add-category-data']= $_POST;
        header('location:'. ROOT_URL. 'admin/add-category.php');
        die();
    } else{
        // insert category into db
        $query = "INSERT INTO categories (title, description) VALUES ('$title', '$description')";
        $result = mysqli_query($connection, $query);
        if(mysqli_errno($connection)){
            $_SESSION['add-category']= "Couldn't add category";
            header('location:'. ROOT_URL. 'admin/add-category.php');
        } else{
            $_SESSION['add-category-success']= "Category added successfully";
            header('location: '. ROOT_URL . 'admin/manage-category.php');
            die();
        }
    }

}





?>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Add Category</h2>
        <?php  if(isset($_SESSION['add-category'])) :   ?>

<div class="alert__message error ">
<p>
    <?= $_SESSION['add-category'];
    unset($_SESSION['add-category']);
    ?>
</p>
</div>
<?php endif ?>
        <form action="#" method="POST">
            <input type="text" name="title" value="<?= $title ?>" placeholder="Title">
            <textarea name="description" id=""  placeholder="Description"><?= $description ?></textarea>
           
            <button type="submit" name="submit" class="btn">Add Category</button>
            
            
            
        </form>
    </div>
</section>


<?php
    include('../partials/footer.php');
?>