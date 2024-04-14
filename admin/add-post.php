<?php

error_reporting(0);
include('partial/header.php');
//require 'config/database.php';
$query = "SELECT * FROM categories";
$result = mysqli_query($connection, $query);




$title = $_SESSION['add-post-data']['title'] ?? null;
$body = $_SESSION['add-post-data']['body'] ?? null;
unset($_SESSION['add-post-data']);

if(isset($_POST['submit'])){
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];

    // set is_featured to 0 if unchecked
    $is_featured = $is_featured == 1 ? : 0;

    // validate form data
    if(!$title){
        $_SESSION['add-post'] = "Enter post title";
    } elseif(!$category_id){
        $_SESSION['add-post'] = "Select post category";
    } elseif(!$body){
         $_SESSION['add-post'] = "Enter post body";
    } elseif(!$thumbnail['name']){
         $_SESSION['add-post'] = "Choose post thumbnail";
    } else{
        // work on the thumbnail and rename the image










        
        $time = time();  // make each image unigque
        $thumbnail_name = $time. $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        // make sure the file is any image

        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if(in_array($extension, $allowed_files)){
            // make sure the image is not bigger than 2mb
            if($thumbnail['size'] < 2_000_000){
                // upload the thumbnail
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
            } else{
                $_SESSION['add-post'] = "File size is too big. should be 2mb";
            }
        } else{
            $_SESSION['add-post'] = "File should be png, jp or jpeg";
        }
    }

    // redirect back with form if any problem
    if(isset ($_SESSION['add-post'])){
        $_SESSION['add-post-data'] = $_POST;
        header('location: '. ROOT_URL . 'admin/add-post.php');
        die();
    } else {
        // set is_featured post of all post to 0 if is_fetured for this post is 1
        if($is_featured ==1){
            $zero_all_is_fetured = "UPDATE posts SET is_featured=0";
            $zero_all_is_fetured_result = mysqli_query($connection, $zero_all_is_fetured);
        }

        // insert data into the db
        $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured) 
        VALUES ('$title', '$body', '$thumbnail_destination_path', $category_id, $author_id, $is_featured)";
$result = mysqli_query($connection, $query);


        if(!mysqli_errno($connection)){
            $_SESSION['add-post-success'] = "New post added successfully";
            header('location:' . ROOT_URL . 'admin/');
            die();
        }
        else{
            echo "Error: " . mysqli_error($connection);
        }
    } 
   
}

//header('location:' . ROOT_URL . 'admin/add-post.php');






?>
<section class="form__section">
    <div class="container form__section-container">
        <h2>Add Post</h2>
        <?php  if(isset($_SESSION['add-post'])) :   ?>

<div class="alert__message error ">
<p>
    <?= $_SESSION['add-post'];
    unset($_SESSION['add-post']);
    ?>
</p>
</div>
<?php endif ?>
       <form action="#" enctype="multipart/form-data" method='POST'>
            <input type="text" name='title' value="<?= $title?>" placeholder="Title">
            <select name="category">
                <?php while($category = mysqli_fetch_assoc($result)) : ?>
                <option value="<?= $category['id'] ?>"><?= $category['title'] ?></option>
                <?php endwhile ?>
            </select>
            <textarea name="body" id="" rows="10" placeholder="Body"><?= $body ?></textarea>
            <?php if(isset($_SESSION['user_is_admin'])) : ?>
            <div class="form__control inline">
                <input type="checkbox" name="is_featured" value="1" id="is__featured" checked>
                <label for="is__featured">Featured</label>
            </div>
            <?php endif ?>
            <div class="form__control">
                <label for="thumbnail">Add Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
           
            <button type="submit" name="submit" class="btn">Add Post</button>
            
            
            
        </form>
    </div>
</section>
<?php
include('partials/footer.php');
?>