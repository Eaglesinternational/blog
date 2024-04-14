<?php
// Enable error reporting to catch any potential issues
error_reporting(E_ALL);
include('partial/header.php');

// Check if a post ID is provided
if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    // Redirect if no post ID is provided
    header('location:' . ROOT_URL . 'admin/');
    die();
}

// Fetch categories
$query_categories = "SELECT * FROM categories";
$category_result = mysqli_query($connection, $query_categories);

// Fetch post data from database
$query_post = "SELECT * FROM posts WHERE id=?";
$stmt_post = mysqli_prepare($connection, $query_post);
mysqli_stmt_bind_param($stmt_post, "i", $id);
mysqli_stmt_execute($stmt_post);
$result_post = mysqli_stmt_get_result($stmt_post);

if(mysqli_num_rows($result_post) === 0) {
    // Redirect if post ID doesn't exist
    header('location:' . ROOT_URL . 'admin/');
    die();
}

$user = mysqli_fetch_assoc($result_post);

// Check if form is submitted
if(isset($_POST['submit'])){
    // Get updated form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Check if checkbox is checked
    $thumbnail = $_FILES['thumbnail'];

    // Validate form data
    if(!$title || !$category_id || !$body){
        $_SESSION['edit-post'] = "Invalid form input on edit page.";
    } else {
        // Handle thumbnail upload
        $thumbnail_to_insert = $user['thumbnail']; // Default to existing thumbnail
        if($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK){
            // Delete previous thumbnail
            if(file_exists('../images/' . $user['thumbnail'])){
                unlink('../images/' . $user['thumbnail']);
            }
            // Upload new thumbnail
            $thumbnail_name = time() . '_' . $_FILES['thumbnail']['name'];
            $thumbnail_destination_path = '../images/' . $thumbnail_name;
            // Make sure the file is an image
            $allowed_extensions = ['png', 'jpg', 'jpeg'];
            $extension = strtolower(pathinfo($thumbnail_name, PATHINFO_EXTENSION));
            if(in_array($extension, $allowed_extensions)){
                // Make sure the image is not bigger than 2MB
                if($thumbnail['size'] < 2_000_000){
                    // Upload the thumbnail
                    if(move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_destination_path)){
                        $thumbnail_to_insert = $thumbnail_name;
                    } else {
                        $_SESSION['edit-post'] = "Failed to upload thumbnail";
                    }
                } else {
                    $_SESSION['edit-post'] = "File size is too big. It should be 2MB or less";
                }
            } else {
                $_SESSION['edit-post'] = "File should be PNG, JPG, or JPEG";
            }
        }

        if(!$_SESSION['edit-post']){
            // Update post
            $query_update = "UPDATE posts SET title=?, body=?, thumbnail=?, category_id=? WHERE id=?";
            $stmt_update = mysqli_prepare($connection, $query_update);
            mysqli_stmt_bind_param($stmt_update, "sssii", $title, $body, $thumbnail_to_insert, $category_id, $id);
            if(mysqli_stmt_execute($stmt_update)){
                // If the post is marked as featured, unmark all other posts as not featured
                if($is_featured) {
                    $query_unmark_featured = "UPDATE posts SET is_featured=0 WHERE id != ?";
                    $stmt_unmark_featured = mysqli_prepare($connection, $query_unmark_featured);
                    mysqli_stmt_bind_param($stmt_unmark_featured, "i", $id);
                    mysqli_stmt_execute($stmt_unmark_featured);
                }
                // Update is_featured
                $query_featured = "UPDATE posts SET is_featured=? WHERE id=?";
                $stmt_featured = mysqli_prepare($connection, $query_featured);
                mysqli_stmt_bind_param($stmt_featured, "ii", $is_featured, $id);
                mysqli_stmt_execute($stmt_featured);
                $_SESSION['edit-post-success'] = "Post updated successfully.";
                header('location:' . ROOT_URL . 'admin/');
                exit;
            } else {
                $_SESSION['edit-post'] = "Failed to update post.";
            }
        }
    }
}

?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Edit Post</h2>
        
        <?php if(isset($_SESSION['edit-post'])) : ?>

        <div class="alert__message error ">
            <p><?= $_SESSION['edit-post']; unset($_SESSION['edit-post']); ?></p>
        </div>
        <?php endif ?>

        <form action="<?= ROOT_URL ?>admin/edit-post.php?id=<?= $id ?>" enctype="multipart/form-data" method="POST">
            <input type="text" name="title" value="<?= $user['title'] ?>" placeholder="Title">
            <select name="category">
                <?php while ($category=mysqli_fetch_assoc($category_result)) : ?>
                    <option value="<?= $category['id']?>" <?= $category['id'] == $user['category_id'] ? 'selected' : '' ?>><?= $category['title']?></option>
                <?php endwhile ?>
            </select>
            <textarea name="body" id="" rows="10" placeholder="Body"><?= $user['body']?></textarea>
            <?php 
                
                if(isset($_SESSION['user_is_admin'])) :
                
                ?>
            <div class="form__control inline">
                <input type="checkbox" name="is_featured" id="is__featured" value="1" <?= $user['is_featured'] == 1 ? 'checked' : '' ?>>
                <label for="is__featured" >Featured</label>
            </div>
            <?php endif ?>
            <div class="form__control">
                <label for="thumbnail">Change Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <input type="hidden" name="hidden" value="<?= $id ?>">
            <input type="hidden" name="previous_thumbnail_name" value="<?= $user['thumbnail'] ?>">
            <button type="submit" name='submit' class="btn">Update Post</button>
        </form>
    </div>
</section>

<?php include('../partials/footer.php'); ?>
