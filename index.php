<?php

session_start();

if(isset($_SESSION) && isset($_SESSION['session_user'])){
    header("location: /cldd/loan/");
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLDD LMS</title> 
    <link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon">
    <!-- STYLES -->
    <link rel="stylesheet" href="./libraries/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./libraries/fonts/Poppins-Regular.ttf">
    <link rel="stylesheet" href="./libraries/fa/css/all.min.css">
    <link rel="stylesheet" href="./libraries/owl/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="./libraries/owl/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="./css/styles.css">
    <!-- SCRIPTS -->
    <script src="./js/jquery.min.js"></script>
    <script src="./libraries/bootstrap5/js/bootstrap.min.js"></script>
    <script src="./js/swal.js"></script>
    <script src="./libraries/owl/dist/owl.carousel.min.js"></script>
    <script src="./js/jquery-validation.min.js"></script>
</head>
<body>
    <?php require('./database/connect.php');?>
    <div class="container">
        <div class="form_holder">
            <div class="left-banner">
                <img src="./images/cover.jpg" class="banner"/>
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <img src="./images/photo1.jpg" alt="photo 1">
                    </div>
                    <div class="item">
                        <img src="./images/photo2.jpg" alt="photo 2">
                    </div>
                    <div class="item">
                        <img src="./images/photo3.jpg" alt="photo 3">
                    </div>
                    <div class="item">
                        <img src="./images/photo5.jpg" alt="photo 4">
                    </div>
                    <div class="item">
                        <img src="./images/photo7.jpg" alt="photo 4">
                    </div>
                    <div class="item">
                        <img src="./images/photo6.jpg" alt="photo 4">
                    </div>
                    <div class="item">
                        <img src="./images/photo4.jpg" alt="photo 4">
                    </div>
                </div>
            </div>
            <?php
            $page= isset($_GET['page']) ? $_GET['page'] : 'signin';
            require_once('./layouts/'.$page.'.php');
            ?>
        </div>
    </div>
</body>
<script src="./js/indexx.js"></script>
</html>