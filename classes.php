<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
    <link rel="icon" href="images/icon_logo.png" type="image/icon type">
    <title>Learn and Help</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function () {
      $('#classes thead tr').clone(true).appendTo( '#classes thead' );
      $('#classes thead tr:eq(1) th').each(function () {
      var title = $(this).text();
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
      });

      var table = $('#classes').DataTable({
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
        <h1><span class="accent-text">Classes</span></h1>
    </div>
</header>
<div class="toggle_columns">
  Toggle column: <a class="toggle-vis" data-column="0">Class</a>
    - <a class="toggle-vis" data-column="1">Description</a>
</div>
<div style="padding-top: 10px; padding-bottom: 30px; width:90%; margin:auto; overflow:auto">
    <table id="classes" class="display compact">
        <thead>
        <tr>
            <th>Class</th>
            <th>Description</th>
        </tr>
        </thead>
        <?php
        // Pull Cause data from the databases and create a Jquery Datatable
        require 'db_configuration.php';
        $connection = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
        if ($connection === false) {
            die("Failed to connect to database: " . mysqli_connect_error());
        }

        // Select view of available classes for users from accessing the page 
        // Admin's can see all classes regardless of status
        if ((isset($_SESSION['email'])) &&  $_SESSION['role'] == 'admin'){
            $sql = "SELECT Class_Id, Class_Name, Description, Status
                FROM classes;";                                                  
        }
        
        //Non-Admin's and users not logged in can only see "Approved" Classes
        else {
            $sql = "SELECT Class_Id, Class_Name, Description, Status
                FROM classes
                WHERE Status = 'Approved';";
        }
        
        
        $result = mysqli_query($connection, $sql);
        if ($result->num_rows > 0) {
            // Create table with data from each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                      <td>' . $row['Class_Name'] . '</td>
                      <td><p style="text-align: left; word-wrap: break-word;">' . $row['Description'] . '</p></td>
                    </tr>';
            }
        }
        ?>
    </table>
</div>
</body>
</html>
