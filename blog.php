<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html>

<head>
  <style>
    #causes {
      margin-left: auto;
      margin-right: auto;
      width: 80%
    }

    th,
    td {
      border: solid black 1px;
    }

    .search-container {
      text-align: center;
      margin-bottom: 20px;
    }

    .search-input {
      width: 300px;
      padding: 10px;
      font-size: 16px;
    }

    .search-button {
      padding: 10px 20px;
      background-color: #99D930;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    .school-icon {
      text-align: center;
      vertical-align: top;
      padding: 10px;
    }

    .school-icon img {
      max-width: 100px;
      max-height: 100px;
    }

    .school-info p {
      font-size: 14px;
      margin: 0;
      color: #333;
    }

    .dot {
      cursor: pointer;
      height: 10px;
      width: 10px;
      margin: 0 2px;
      background-color: #FFFFFF;
      border-radius: 50%;
      display: inline-block;
      transition: background-color 0.6s ease;
    }

    .active,
    .dot:hover {
      background-color: #717171;
    }

    .slideshow-container {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      overflow: hidden;
    }

    .inverse {
      position: relative;
      background-size: cover;
      height: 300px;
      overflow: hidden;
    }

    .inverse h1 {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 3;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
      color: white;
      font-size: 3em;
      text-align: center;
      width: 100%;
    }

    .banner_slide {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: none;
    }

    .banner_slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .dots-container {
      position: absolute;
      bottom: 20px;
      left: 0;
      right: 0;
      text-align: center;
      z-index: 2;
    }
  </style>
  <link rel="icon" href="images/icon_logo.png" type="image/icon type">
  <title>Learn and Help</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  <script type="text/javascript" src="js/blog_functions.js"></script>
</head>

<body>
  <?php include 'show-navbar.php'; ?>
  <?php include 'blog_fill.php'; ?>
  <?php show_navbar(); ?>
  <header class="inverse">
    <div class="slideshow-container">
      <?php
      //Get images from that dir
      $images_dir = "./images/banner_images/Blog/";
      $images = glob($images_dir . "*.{jpg,png}", GLOB_BRACE);
      //Putting the images into a individual slide
      foreach ($images as $index => $image) {
        $safe_image_path = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
        echo "<div class='banner_slide'>
<img src='{$safe_image_path}' alt='School banner image'>
</div>";
      }
      ?>
      <div class="container">
        <h1><span class="accent-text">Blog</span></h1>
      </div>
      <div class="dots-container">
        <?php
        //Creating navigation dots for each image
        foreach ($images as $index => $image) {
          $slide_number = $index + 1;
          echo "<span class='dot' onclick='currentSlide($slide_number)'></span>";
        }
        ?>
      </div>
  </header>
  <script>
    let show_form = () => {
      let form = document.getElementById("blog_creation_form");
      let show_button = document.getElementById("form_show_button");
      form.removeAttribute("hidden");
      show_button.setAttribute("hidden", "hidden");
    }
  </script>
  <?php
  if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
      echo '<button id="form_show_button" onclick="show_form();">Create Post</button>';
    }
  }
  ?>
  <form id="blog_creation_form" action="create_post.php" method="POST" enctype="multipart/form-data" hidden="hidden">
    <div id=blog_creation_left>
      <label>Blog Title</label>
      <br>
      <input type="text" name="title" maxlength=100 required>
      <br>
      <label for="description">Description</label>
      <br>
      <textarea name="description" rows=9 cols=50 required></textarea>
    </div>
    <div id=blog_creation_right>
      <label for="author">Author</label>
      <br>
      <input type="text" name="author" maxlength=50 required>
      <br>
      <label>Image(s)</label>
      <br>
      <input type="file" name="file[]" accept="image/*" multiple="multiple">
      <br>
      <label>Video Link</label>
      <br>
      <input type="text" name="video_link" maxlength=100 placeholder="Optional">
    </div>
    <br>
    <input type="submit" name="create_post" value="Publish">
  </form>
  <div>
    <div id="blog_TOC">
      <h3 id="TOC_title">Table of Contents</h3>
      <ul>
        <?php fill_TOC(); ?>
      </ul>
    </div>
    <?php fill_blog(); ?>
    <div id="blog_buttons">
      <button id="blog_previous" onclick="handlePageButton('previous')" hidden="hidden">Previous</button>
      <button id="blog_next" onclick="handlePageButton('next')">Next</button>
    </div>
    <script>
      //Setting slide index and displaying current slide
      let slideIndex = 1;
      showSlides(slideIndex);
      //Moving between slides
      function plusSlides(n) {
        showSlides(slideIndex += n);
      }

      function currentSlide(n) {
        showSlides(slideIndex = n);
      }
      //Displaying slides
      function showSlides(n) {
        let i;
        let slides = document.getElementsByClassName("banner_slide");
        let dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
          slideIndex = 1
        }
        if (n < 1) {
          slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
      }
    </script>
</body>

</html>