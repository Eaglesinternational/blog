<?php 
include('partials/header.php');

// fetch the post from db if id is set
if(isset($_GET['id'])){
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);
} else{
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}

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

<!---------------   single post--------------->
<section class="singlepost">
    <div class="container singlepost__container">
        <h2><?=$post['title']?></h2>
        <div class="post__author">
            <div class="post__author__avatar">
            <?php
            $author_id = $post['author_id'];
            $author_id_query = "SELECT * FROM users WHERE id =$author_id";
            $author_id_result = mysqli_query($connection, $author_id_query);
            $author = mysqli_fetch_assoc($author_id_result);
            ?>
                <img src="images/<?= $author['avatar'] ?>" alt="">
            </div>
            <div class="post__author__info">
                <h5>By: <?="{$author['firstname']} {$author['lastname']}" ?></h5>
                <small><?=getElapsedTime($post['date_time']) ?></small>
            </div>
        </div>
        <div class="singlepost__thumbnail">
            <img src="images/<?= $post['thumbnail'] ?>"  alt="">
        </div>
        <p><?= $post['body']?></p>
    </div>
</section>
<!------------------END OF SINGLE POST------------------->

<footer>
    <div class="footer__socials">
        <a href="https://youtube.com/daniel" target="_blank"><i class="fa-brands fa-youtube"></i></a>
        <a href="https://facebook.com/ogbonnayaekebuii" target="_blank"><i class="fa-brands fa-facebook"></i></a>
        <a href="https://instagram.com/daniel4real" target="_blank"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://twitter.com/daniel" target="_blank"><i class="fa-solid fa-x"></i></a>
        <a href="https://linkedin.com/daniel4real" target="_blank"><i class="fa-brands fa-linkedin"></i></a>
    </div>
    <div class="container footer__container">
        <!-- Your footer content -->
    </div>
    <div class="footer__copyright">
        <small>
            Copyright &copy; Dantech: <?php echo date("d-m-Y"); ?>
        </small>
    </div>
</footer>
<script src="main.js"></script>
</body>
</html>
