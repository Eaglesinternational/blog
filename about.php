<?php
    include('partials/header.php');
?>
    <nav>
        <div class="container nav__container">
            <a href="index.html" class="nav__logo">Dantech</a>
            <ul class="nav__items">
                <li><a href="blog.php">Blog</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="signin.php">Sign in</a></li>
                <li class="nav__profile">
                    <div class="avatar">
                        <img src="image/user-1.png" alt="">
                    </div>
                        <ul>
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                </li>
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

<section class="empty__page">
    <h1>About Page</h1>
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
                Copyright &copy; Dantech: 2023
            </small>
        </div>
    </footer>
    <script src="main.js"></script>
</body>
</html>