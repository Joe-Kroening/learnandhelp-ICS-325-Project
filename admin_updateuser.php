<?php
session_start();

// Include the database configuration file
require 'db_configuration.php';

$connection = mysqli_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);

// Check if the connection is established
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the User_ID from the query parameter
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    // Handle the case where the User_ID is not provided in the URL
    // You can set a default value or handle it as needed
    $user_id = '';
}

// Retrieve user data based on the User_ID (you should add error handling here)
$selectQuery = "SELECT * FROM users WHERE User_ID = '$user_id'";
$result = mysqli_query($connection, $selectQuery);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $firstname = $row['First_Name'];
    $lastname = $row['Last_Name'];
    $email = $row['Email'];
    $phone = $row['Phone'];
    // Assuming 'Hash' is not stored in plain text in the database
    $password = ''; // You should handle the password retrieval appropriately
    $status = $row['Active'];
    $UserRole = $row['Role'];
    $modified_time = $row['Modified_Time'];
} else {
    // Handle the case where the user data is not found (invalid User_ID)
    // You can redirect or display an error message as needed
    echo "User data not found";
    exit;
}

// Close the result set
mysqli_free_result($result);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $firstname = $_POST['First_Name'];
    $lastname = $_POST['Last_Name'];
    $email = $_POST['email'];
    $phone = $_POST['Phone'];
    $password = $_POST['Hash'];
    $hash = sha1($password);
    $status = $_POST['Active'];
    $UserRole = $_POST['Role'];
    $modified_time = $_POST['Modified_Time'];

    // SQL query to update user data in the "users" table
    $updateQuery = "UPDATE users
                    SET
                        First_Name = '$firstname',
                        Last_Name = '$lastname',
                        Email = '$email',
                        Phone = '$phone',
                        Hash = '$hash',
                        Active = '$status',
                        Role = '$UserRole',
                        Modified_Time = '$modified_time'
                    WHERE
                        User_ID = '$user_id'"; // Use the User_ID to specify the user to update

    // Perform the query
    $updateResult = mysqli_query($connection, $updateQuery);

    // Check if the query was successful
    if ($updateResult) {
        // Redirect back to the main PHP file or any desired page after successful update
        header("Location: admin_usersList.php");
        exit;
    } else {
        // If there was an error, display the error message
        echo "Error updating user: " . mysqli_error($connection);
    }
}

// Close the database connection (you should do this at the end of your PHP script)
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="images/icon_logo.png" type="image/icon type">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;900&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <title>Update User</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            text-align: left !important;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
        }

        main {
            padding: 20px;
            max-width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            color: #333;
        }

        form {
            width: 400px;
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="password"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        select {
            width: 100%;
            padding: 5px;
            font-size: 16px;
        }

        .updateBtn {
            padding: 10px 20px;
            margin-top: 10px;
            display: block;
            font-size: 16px;
            background-color: #99D930;
            color: #000;
            border: none;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php 
    include 'show-navbar.php'; 
    ?>
    <?php
    // Central Daylight Time
    date_default_timezone_set('America/Chicago');

    $currentDateTime = date("Y-m-d H:i:s"); // Format: YYYY-MM-DD HH:MM:SS
    ?>

    <main>
        <h1>Update User</h1>
        <form method="POST" action="" autocomplete="on">
            <!-- Include a hidden field for User_ID to specify the user to update -->
            <input type="hidden" name="User_ID" value="<?php echo $user_id; ?>">
            
            <label for="First_Name">First Name</label>
            <input type="text" name="First_Name" id="First_Name" required placeholder="First Name" value="<?php echo $firstname; ?>">
            
            <label for="Last_Name">Last Name</label>
            <input type="text" name="Last_Name" id="Last_Name" required placeholder="Last Name" value="<?php echo $lastname; ?>">
            
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required placeholder="Email" value="<?php echo $email; ?>">
            
            <label for="Phone">Phone</label>
            <input type="tel" name="Phone" id="Phone" required placeholder="Phone" value="<?php echo $phone; ?>">
            
            <label for="Hash">Password</label>
            <input type="password" name="Hash" id="Hash" required placeholder="Password" value="<?php echo $password; ?>">
            
            <!-- Active -->
            <label for="active_yes">
                <input type="radio" name="Active" id="active_yes" value="Yes" <?php if ($status == 'Yes') { echo 'checked'; } ?>>
                Yes
            </label>
            
            <label for="active_no">
                <input type="radio" name="Active" id="active_no" value="No" <?php if ($status == 'No') { echo 'checked'; } ?>>
                No
            </label>
            
            <!-- Role -->
            <label for="Role">User Role</label>
            <select name="Role" id="Role" required>
                <option value="admin" <?php if ($UserRole == 'admin') { echo 'selected'; } ?>>Admin</option>
                <option value="student" <?php if ($UserRole == 'student') { echo 'selected'; } ?>>Student</option>
                <option value="instructor" <?php if ($UserRole == 'instructor') { echo 'selected'; } ?>>Instructor</option>
            </select>
            
            <!-- Modified Time (Auto-generated) -->
            <label for="Modified_Time">Modified Time</label>
            <input type="datetime-local" name="Modified_Time" id="Modified_Time" value="<?php echo $currentDateTime; ?>" readonly>
            
            <button class="updateBtn" type="submit">Update User</button>
        </form>
    </main>
</body>
</html>
