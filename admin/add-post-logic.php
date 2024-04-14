<?php
// Enable error reporting to catch any potential issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include('partial/header.php');

// Check if the form is submitted
if(isset($_POST['submit'])){
    // Retrieve form data and sanitize inputs
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0; // Check if checkbox is checked

    // Validate form data
    if(!$title){
        $_SESSION['add-post'] = "Enter post title";
    }
    if(!$category_id){
        $_SESSION['add-post'] = "Select post category";
    }
    if(!$body){
        $_SESSION['add-post'] = "Enter post body";
    }
    if(!$_FILES['thumbnail']['name']){
        $_SESSION['add-post'] = "Choose post thumbnail";
    }

    // Handle thumbnail upload
    $thumbnail = $_FILES['thumbnail'];
    $thumbnail_destination = '../images/' . time() . '_' . $thumbnail['name'];

    // Check if the file is an image
    $allowed_extensions = ['png', 'jpg', 'jpeg'];
    $file_extension = strtolower(pathinfo($thumbnail['name'], PATHINFO_EXTENSION));
    if(!in_array($file_extension, $allowed_extensions)){
        $_SESSION['add-post'] = "File should be PNG, JPG, or JPEG";
    }

    // Debugging: Print out the uploaded file information
    //echo "Uploaded File Information: <pre>";
    //print_r($thumbnail);
    //echo "</pre>";

    // Debugging: Print out the destination path
   // echo "Destination Path: $thumbnail_destination";

    if(!move_uploaded_file($thumbnail['tmp_name'], $thumbnail_destination)){
       // echo "<div class='alert__message error'>Failed to upload thumbnail</div>";
       $_SESSION['add-post'] = "File should be png, jp or jpeg";
        include('partial/footer.php'); // Include footer to exit gracefully
        exit;
    }

    // Insert data into the database
    $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured) 
              VALUES ('$title', '$body', '$thumbnail_destination', $category_id, $author_id, $is_featured)";
    $result = mysqli_query($connection, $query);

    // Check if insertion was successful
    if($result){
        $_SESSION['add-post-success'] = "New post added successfully";
        header('Location: ' . ROOT_URL . 'admin/');
        exit;
    } else {
        echo "<div class='alert__message error'>Failed to add new post</div>";
    }
}

// Fetch categories for dropdown
$query = "SELECT * FROM categories";
$result = mysqli_query($connection, $query);

// Display form to add post
include('partial/add-post-form.php');

// Include footer
include('partial/footer.php');
?>
