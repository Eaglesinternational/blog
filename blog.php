<?php
include('partials/header.php');

// fetch all posts from db
$query = "SELECT * FROM posts ORDER BY date_time DESC";
$posts = mysqli_query($connection, $query);

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

<section class="search__bar">
    <form action="<?= ROOT_URL ?>search.php" class="container search__bar-container" method="GET">
        <div>
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" name="search" placeholder="Search">
        </div>
        <button type="submit" name="submit" class="btn">Go</button>
    </form>
</section>
<!---------------   SEARCH POST--------------->

<section class="posts <?= $featured ? '' : 'section__extra-margin' ?>">
    <div class="container posts__container">
        <?php while($post = mysqli_fetch_assoc($posts)) : ?>
            <article class="post">
                <div class="post__thumbnail">
                    <img src="images/<?= $post['thumbnail'] ?>" alt="">
                </div>
                <div class="post__info">
                    <?php
                    // fetch category from the categories table using categories_id
                    $category_id = $post['category_id'];
                    $category_query = "SELECT * FROM categories WHERE id=$category_id";
                    $category_result = mysqli_query($connection, $category_query);
                    $category = mysqli_fetch_assoc($category_result);
                    ?>
                    <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category__button"><?= $category['title'] ?></a>
                    <h3 class="post__title"><a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a></h3>
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
                            <h5>By: <?= "{$author['firstname']} {$author['lastname']}" ?></h5>
                            <small><?= getElapsedTime($post['date_time']) ?></small>
                        </div>
                    </div>
                </div>
            </article>
        <?php endwhile ?>
    </div>
</section>
<!------------------END OF POST------------------->

<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php
        $all_categories_query = "SELECT * FROM categories";
        $all_categories_result = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($all_category = mysqli_fetch_assoc($all_categories_result)) : ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $all_category['id'] ?>" class="category__button"><?= $all_category['title'] ?></a>
        <?php endwhile ?>
    </div>
</section>
<!----------------END OF CATEGORY BUTTONS----------->

<?php include('partials/footer.php'); ?>
