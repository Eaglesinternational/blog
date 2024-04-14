<?php
error_reporting(0);
    include('partial/header.php');


    // fetch categories from the db
    $query = "SELECT * FROM categories ORDER BY title";
    $categories = mysqli_query($connection, $query);
?>




<section class="dashboard">


<?php if (isset($_SESSION['add-category'])) : // if add category was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['add-category'];
        unset($_SESSION['add-category']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['add-category-success'])) : // if add category was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['add-category-success'];
        unset($_SESSION['add-category-success']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['edit-category'])) : // if edit category was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['add-category'];
        unset($_SESSION['add-category']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['edit-category-success'])) : // if edit category was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['edit-category-success'];
        unset($_SESSION['edit-category-success']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['delete-category'])) : // if delete category was not successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['delete-category'];
        unset($_SESSION['delete-category']);
        ?>
    </p>
</div>
<?php elseif(isset($_SESSION['delete-category-success'])) : // if delete category was  successful ?>

<div class="alert__message success container">
    <p>
        <?= $_SESSION['delete-category-success'];
        unset($_SESSION['delete-category-success']);
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
                <li><a href="index.php"><i class="fa-regular fa-address-card"></i>
                <h5>Manage Posts</h5>
                </a></li>
                <?php if(isset(($_SESSION['user_is_admin']))): ?>
                <li><a href="add-user.php"><i class="fa-regular fa-user"></i>
                <h5>Add User</h5>
                </a></li>
                <li><a href="manage-users.php"><i class="fa-solid fa-users"></i>
                <h5>Manage Users</h5>
                </a></li>
                
               <!--  -->
                <li><a href="add-category.php"><i class="fa-regular fa-pen-to-square"></i> <h5>Add Catagory</h5>
                </a></li>
                <li><a href="manage-category.php" class="active"><i class="fa-solid fa-list"></i>
                <h5>Manage Categories</h5>
                </a></li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>
                Manage Catagories
            </h2>
            <?php if(mysqli_num_rows($categories)>0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($category = mysqli_fetch_assoc($categories)) : ?>
                        <tr>
                            <td><?= $category['title'] ?></td>
                            <td><a href="<?= ROOT_URL ?>admin/edit-category.php?id=<?= $category['id']?>" class="btn sm">Edit</a></td>
                            <td><a href="<?= ROOT_URL ?>admin/delete-category.php?id=<?= $category['id']?>"  onclick="return confirm('Are you sure to delete?');" class="btn sm danger">Delete</a></td>
                        </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else :?>
                <div class="alert__message error"><?= "No categories found" ?></div>
                <?php endif ?>
        </main>
    </div>
</section>




<?php
    include('../partials/footer.php');
?>