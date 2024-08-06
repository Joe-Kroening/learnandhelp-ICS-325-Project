<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
  session_start();
}

?>

<!DOCTYPE html>
<html lang="en-us">

<head>
  <link rel="icon" href="images/icon_logo.png" type="image/icon type">
  <title>Suggest a School</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
</head>
<style>
  .from-container {
    max-width: 600px;
    margin: 3rem auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  h2 {
    text-align: center;
  }

  .form-group {
    margin-bottom: 15px;
  }

  label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
  }

  input[type="text"],
  input[type="tel"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  textarea {
    width: 100%;
    height: 100px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
    box-sizing: border-box;
  }

  input[type="submit"]:hover {
    background-color: #0056b3;
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

<body>
  <?php include 'show-navbar.php'; ?>
  <?php show_navbar(); ?>
  <header class="inverse">
    <div class="slideshow-container">
      <?php
      //Get images from that dir
      $images_dir = "./images/banner_images/Suggest School/";
      $images = glob($images_dir . "*.{jpg,png}", GLOB_BRACE);
      //Putting the images into a individual slide
      foreach ($images as $index => $image) {
        $safe_image_path = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
        echo "<div class='banner_slide'>
<img src='{$safe_image_path}' alt='School banner image'>
</div>";
      }
      ?>
      <div>
        <h1><span class="accent-text">Suggest a School</span></h1>
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
  <div class="from-container">
    <form action="process_suggestion.php" method="post">
      <div class="form-group">
        <label for="school_name">School Name *</label>
        <input type="text" id="school_name" name="school_name" required />
      </div>
      <div class="form-group">
        <label for="contact_name">Name of Contact at School (Teacher or Head Master) *</label>
        <input type="text" id="contact_name" name="contact_name" required />
      </div>
      <div class="form-group">
        <label for="contact_mobile">Mobile Number of Contact</label>
        <input type="tel" id="contact_mobile" name="contact_mobile" />
      </div>
      <div class="form-group">
        <label for="commitment_statement">Statement of Commitment (Indicate how you want to administer the
          library)</label>
        <textarea id="commitment_statement" name="commitment_statement"></textarea>
      </div>
      <input type="submit" value="Submit" />
    </form>
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