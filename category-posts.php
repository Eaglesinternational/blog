<?php 
include('partials/header.php');

// fetch the post from db if id is set
if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE category_id=$id ORDER BY date_time DESC";
    $posts = mysqli_query($connection, $query);
} else{
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}

// Function to get elapsed time
function getElapsedTime($dateTime) {
    $now = time();
    $postTime = strtotime($dateTime);
    $difference = $now - $postTime;
    if ($difference < 60) {
        return "Less than a minute ago";
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return "$minutes minutes ago";
    } elseif ($difference < 86400) {
        $hours = floor($difference / 3600);
        return "$hours hours ago";
    } elseif ($difference < 2592000) {
        $days = floor($difference / 86400);
        return "$days days ago";
    } else {
        return date("M d, Y - H:i", $postTime);
    }
}
?>

<header class="category__title">
    <?php
    // fetch category from the categories table using cateries_id
    $category_id =$id;
    $category_query = "SELECT * FROM categories WHERE id=$category_id";
    $category_result = mysqli_query($connection, $category_query);
    $category = mysqli_fetch_assoc($category_result);
    ?>
    <h2><?= $category['title'] ?></h2>
</header>

<?php if(mysqli_num_rows($posts) > 0)  : ?>

<section class="posts">
    <div class="container posts__container">
        <?php while($post = mysqli_fetch_assoc($posts)) : ?>
        <article class="post">
            <div class="post__thumbnail">
                <img src="images/<?=$post['thumbnail']?>" alt="">
            </div>
            <div class="post__info">
                <h3 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= $post['id']?>"><?= $post['title'] ?></a></h3>
                <p class="post__body"><?= substr($post['body'], 0, 150) ?> ... </p>
                <div class="post__author">
                    <?php
                    // fetch author from the users table using the author_id
                    $author_id = $post['author_id'];
                    $author_id_query = "SELECT * FROM users WHERE id =$author_id";
                    $author_id_result = mysqli_query($connection, $author_id_query);
                    $author = mysqli_fetch_assoc($author_id_result);
                    ?>
                    <div class="post__author__avatar">
                        <img src="images/<?= $author['avatar'] ?>" alt="">
                    </div>
                    <div class="post__author__info">
                        <h5>By:  <?="{$author['firstname']} {$author['lastname']}" ?></h5>
                        <small><?= getElapsedTime($post['date_time']) ?></small>
                    </div>
                </div>
            </div>
        </article>
        <?php endwhile ?>
    </div>
</section>

<?php else : ?>
<div class="alert__message error lg">
    <p>No post found for this category</p>
</div>
<?php endif ?>

<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php 
        $all_categories_query = "SELECT * FROM categories";
        $all_categories_result = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while($all_category = mysqli_fetch_assoc($all_categories_result)) : ?>
        <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $all_category['id'] ?>" class="category__button"><?= $all_category['title']?></a>
        <?php endwhile ?>
    </div>
</section>

<footer>
    <div class="footer__socials">
        <a href="https://youtube.com/daniel" target="_blank"><i class="fa-brands fa-youtube"></i></a>
        <a href="https://facebook.com/ogbonnayaekebuii" target="_blank"><i class="fa-brands fa-facebook"></i></a>
        <a href="https://instagram.com/daniel4real" target="_blank"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://twitter.com/daniel" target="_blank"><i class="fa-solid fa-x"></i></a>
        <a href="https://linkedin.com/daniel4real" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
    </div>
    <div class="container footer__container">
        <article>
            <h4>Categories</h4>
            <ul>
                <li><a href="">Art</a></li>
                <li><a href="">Travel</a></li>
                <li><a href="">Science & Technology</a></li>
                <li><a href="">Food</a></li>
                <li><a href="">Wild Life</a></li>
                <li><a href="">Music</a></li>
            </ul>
        </article>
        <article>
            <h4>Support</h4>
            <ul>
                <li><a href="">Online Support</a></li>
                <li><a href="">Location</a></li>
                <li><a href="">Call Numbers</a></li>
                <li><a href="">Email</a></li>
                <li><a href="">Social Support</a></li>
            </ul>
        </article>
        <article>
            <h4>Blogs</h4>
            <ul>
                <li><a href="">Repairs</a></li>
                <li><a href="">Popular</a></li>
                <li><a href="">Recent</a></li>
                <li><a href="">Categories</a></li>
                <li><a href="">Safety</a></li>
            </ul>
        </article>
        <article>
            <h4>Permalinks</h4>
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="">About</a></li>
                <li><a href="">Services</a></li>
                <li><a href="">Contact</a></li>
                <li><a href="">Blog</a></li>
            </ul>
        </article>
    </div>
    <div class="footer__copyright">
        <small>
            Copyright &copy; Dantech: <?php echo date("Y"); ?>
        </small>
    </div>
</footer>
<script src="main.js"></script>
</body>
</html>
