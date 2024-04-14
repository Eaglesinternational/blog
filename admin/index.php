<?php
error_reporting(E_ALL);
    include('partial/header.php');
    // fetch currenr user's post from db
    $current_user_id = $_SESSION['user-id'];
    $query = "SELECT id, title, category_id FROM posts  WHERE author_id=$current_user_id ORDER BY id DESC";
    $posts = mysqli_query($connection, $query);
    
?>
<section class="dashboard">

<?php if(isset($_SESSION['add-post'])) : // if delete category was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['add-post'];
        unset($_SESSION['add-post']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['add-post-success'])) : // if delete category was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['add-post-success'];
        unset($_SESSION['add-post-success']);
        ?>
    </p>
</div>

<?php endif ?>






<?php if(isset($_SESSION['delete-post'])) : // if delete post was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['delete-post'];
        unset($_SESSION['delete-post']);
        ?>
    </p>
</div>
</div>

<?php elseif(isset($_SESSION['delete-post-success'])) : // if delete post was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['delete-post-success'];
        unset($_SESSION['delete-post-success']);
        ?>
    </p>
</div>

<?php endif ?>


<?php if(isset($_SESSION['edit-post'])) : // if delete post was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['edit-post'];
        unset($_SESSION['edit-post']);
        ?>
    </p>
</div>
</div>

<?php elseif(isset($_SESSION['edit-post-success'])) : // if edit post was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['edit-post-success'];
        unset($_SESSION['edit-post-success']);
        ?>
    </p>
</div>

<?php endif ?>

    <div class="container dashboard__container">
        <button class="sidebar__toggle" id="show__sidebar-btn"><i class="fa-solid fa-chevron-right"></i></button>
        <button class="sidebar__toggle" id="hide__sidebar-btn"><i class="fa-solid fa-chevron-left"></i></button>
        <aside>
            <ul>
                <li><a href="add-post.php"><i class="fa-solid fa-pencil"></i>
                <h5>Add Post</h5>
                </a></li>
                <li><a href="index.php" class="active"><i class="fa-regular fa-address-card"></i>
                <h5>Manage Posts</h5>
                </a></li>
                
                <?php 
                
                if(isset($_SESSION['user_is_admin'])) :
                
                ?>
                <li><a href="add-user.php"><i class="fa-regular fa-user"></i>
                <h5>Add User</h5>
                </a></li>
                <li><a href="manage-users.php" ><i class="fa-solid fa-users"></i>
                <h5>Manage Users</h5>
                </a></li>
                <li><a href="add-category.php"><i class="fa-regular fa-pen-to-square"></i> <h5>Add Catagory</h5>
                </a></li>
                <li><a href="manage-category.php" ><i class="fa-solid fa-list"></i>
                <h5>Manage Categories</h5>
                </a></li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>
                Manage Posts
                <?php if (mysqli_num_rows($posts) > 0) : ?>
            </h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($post = mysqli_fetch_assoc($posts)) :?>
                           <!-------get category title from each post from category table-------->
                           <?php 
                                $category_id = $post['category_id'];
                                $category_query = "SELECT title FROM categories WHERE id=$category_id";
                                $category_result = mysqli_query($connection, $category_query);
                                $category = mysqli_fetch_assoc($category_result);
                           ?>
                        <tr>
                            <td><?= $post['title'] ?></td>
                            <td><?= $category['title'] ?></td>
                            <td><a href="<?= ROOT_URL ?>admin/edit-post.php?id=<?= $post['id'] ?>" class="btn sm">Edit</a></td>
                            <td><a href="<?= ROOT_URL ?>admin/delete-post.php?id=<?= $post['id'] ?>" onclick="return confirm('Are you sure to delete?')" class="btn sm danger">Delete</a></td>
                            
                        </tr>
                      <?php endwhile ?>
                        
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert__message error"><?= "No post found" ?></div>
            <?php endif ?>
        </main>
    </div>
</section>




<?php
    include('../partials/footer.php');
?>