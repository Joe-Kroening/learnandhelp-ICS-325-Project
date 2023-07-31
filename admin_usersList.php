<?php
  $status = session_status();
  if ($status == PHP_SESSION_NONE) {
    session_start();
  }

  // Block unauthorized users from accessing the page
  if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'admin') {
      http_response_code(403);
      die('Forbidden');
    }
  } else {
    http_response_code(403);
    die('Forbidden');
  }


 ?>

<!DOCTYPE html>
<script>
</script>
<html>
  <head>
    <link rel="icon" href="images/icon_logo.png" type="image/icon type">
    <title>Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function () {
      $('#Blog_table thead tr').clone(true).appendTo( '#Blog_table thead' );
      $('#Blog_table thead tr:eq(1) th').each(function () {
      var title = $(this).text();
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
      });

      var table = $('#Blog_table').DataTable({
         initComplete: function () {
             // Apply the search
             this.api()
                 .columns()
                 .every(function () {
                     var that = this;

                     $('input', this.header()).on('keyup change clear', function () {
                         if (that.search() !== this.value) {
                             that.search(this.value).draw();
                         }
                     });
                 });
             },
         });

      $('a.toggle-vis').on('click', function (e) {
      e.preventDefault();

      // Get the column API object
      var column = table.column($(this).attr('data-column'));

      // Toggle the visibility
      column.visible(!column.visible());
      });
     });
    </script>
  </head>
  <body>
  <?php include 'show-navbar.php'; ?>
  <?php show_navbar(); ?>
    <header class="inverse">
      <div class="container">
        <h1><span class="accent-text">Users List</span></h1>
      </div>
    </header>
	<!-- Jquery Data Table -->

    <div class="toggle_columns">
      Toggle column: 
          <a class="toggle-vis" data-column="0">User_Id</a>
        - <a class="toggle-vis" data-column="1">First_Name</a>
        - <a class="toggle-vis" data-column="2">Last_Name</a>
        - <a class="toggle-vis" data-column="3">Email</a>
        - <a class="toggle-vis" data-column="4">Phone</a>
        - <a class="toggle-vis" data-column="6">Active</a>
        - <a class="toggle-vis" data-column="7">Role</a>
        - <a class="toggle-vis" data-column="8">Created_Time</a>
        - <a class="toggle-vis" data-column="9">ModifiedDateTime </a>
        
    </div>
    <div style="padding-top: 10px; padding-bottom: 30px; width:90%; margin:auto; overflow:auto">
      <table id="Blog_table" class="display compact">
        <thead>
          <tr>
            <th>User_Id </th>
            <th>First_Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Active</th>
            <th>Role</th>
            <th>Created DateTime</th>
            <th>Modified DateTime</th>
          </tr>
        </thead>
        <tbody>
          <!-- Populating table with data from the database-->
          <?php
            require 'db_configuration.php';
            // Create connection
            $conn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
            // Check connection
            if ($conn->connect_error) 
            {
              die("Connection failed: " . $conn->connect_error);
            }
            $sql = "SELECT * FROM `users`";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) 
            {
              // Create table with data from each row
              while($row = $result->fetch_assoc()) 
              {
                  echo "<tr>
                    <td>". $row["User_Id"]."</td>
                    <td>". $row["First_Name"]."</td> 
                    <td>". $row["Last_Name"]."</td> 
                    <td>". $row["Email"]."</td> 
                    <td>". $row["Phone"]."</td> 
                    <td>". $row["Active"]."</td> 
                    <td>". $row["Role"]."</td> 
                    <td>". $row["Modified_Time"]."</td> 
                    <td>". $row["Created_Time"]."</td> 
                    <td>
                  
                    </tr>";
                    //  <a href='#'><input type='button' style='width:200px; height:44px; background-color:blue; color:white; border:solid 0px; border-radius:5px' value='Edit' title='".$Editable."' onclick='EditFun(this.title)'/></a>
                    //<form action='admin_addassignschooluserrole.php' method='POST'>
                    //<input type='hidden' name='SchoolUser_Id' value='". $row["SchoolUser_Id"] . "'>
                    //<input type='submit' id='admin_buttons' name='delete' value='Delete'/>
                    //</form>
              }
            } else {
              echo "0 results";
            }
            $conn->close();
    		?>
        </tbody>
      </table>
</div>
  </body>
</html>
