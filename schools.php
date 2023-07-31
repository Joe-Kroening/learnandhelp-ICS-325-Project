<?php
  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

	function get_profile_image($id) {
		$image_name = glob('schools/' . $id . '/profile_image.*');
		// should only be one file found, if there are two profile_image files
		// with different extensions something is wrong.  If there is no profile
		// image or more than one default to the admin_icons school icon.
		if(count($image_name) == 1) {
	 		return $image_name[0];
		} else {
			return "images/admin_icons/school.png";
		}
	}
?>

<!DOCTYPE html>
<script>
</script>
<html>
  <head>
    <link rel="icon" href="images/icon_logo.png" type="image/icon type">
    <title>Learn and Help</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
  </head>
  <body>
    <?php include 'show-navbar.php'; ?>
    <?php show_navbar(); ?>
    <header class="inverse">
      <div class="container">
        <h1><span class="accent-text">Schools</span></h1>
      </div>
    </header>
    <div style="padding-top: 10px; padding-bottom: 30px; width:90%; margin:auto; overflow:auto">
      <table id="school_icons">
        <?php
          // Pull Cause data from the databases and create a Jquery Datatable
          require 'db_configuration.php';
          $connection = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
          if ($connection === false) {
            die("Failed to connect to database: " . mysqli_connect_error());
          }
          $sql = "SELECT id FROM schools";
          $result = mysqli_query($connection, $sql);
          if ($result->num_rows > 0) {
			$counter = 0;  
			// Create table with data from each row
            while($row = $result->fetch_assoc()) {
				$counter++;
				if($counter == 0) {
					echo "<tr>";
				}
				$id = $row["id"];
				// if a profile image was not created use the admin_icons school.png as a default fallback image
				echo  "<td class=\"school_icon\">
							<a href=\"school_details.php?School_Id=$id&target=_blank\">";
				$profile_image = get_profile_image($id); 
				echo "			<img src=\"$profile_image\" alt=\"school image\"><br><label>$id</label>
							</a>
						</td>";
				if($counter % 5 == 0 && $counter > 0) {
					echo "</tr>";
					if($counter < $result->num_rows) {
						echo "<tr>";
					}
				}
            }
          }
        ?>
      </table>
    </div>
  </body>
</html>
