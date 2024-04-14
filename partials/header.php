<?php

require 'config/database.php';
require 'config/constants.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dantech Multipage Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <nav>
        <div class="container nav__container">
            <a href="<?php echo ROOT_URL?>" class="nav__logo">Dantech</a>
            <ul class="nav__items">
                <li><a href="<?php echo ROOT_URL?>blog.php">Blog</a></li>
                <li><a href="<?php echo ROOT_URL?>about.php">About</a></li>
                <li><a href="<?php echo ROOT_URL?>services.php">Services</a></li>
                <li><a href="<?php echo ROOT_URL?>contact.php">Contact</a></li>
                <li><a href="<?php echo ROOT_URL?>signin.php">Sign in</a></li>
                <?php
                
                if(isset($_SESSION['user-id'])) : ?>
                
                
                
                <li class="nav__profile">
                    <div class="avatar">
                    <img src="<?=ROOT_URL . 'images/' . $avatar['avatar'] ?>" alt="user2">
                    </div>
                        <ul>
                            <li><a href="<?= ROOT_URL ?>admin/index.php">Dashboard</a></li>
                            <li><a href="<?= ROOT_URL ?>logout.php">Logout</a></li>
                        </ul>
                </li>
                <?php else : ?>
                <!-----    <li><a href="signin.php">Sign in</a></li> --->
                <?php endif ?>
            </ul>

            <button id="open__nav-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <button id="close__nav-btn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </nav>
    <!----------------------------END OF NAV------------->
