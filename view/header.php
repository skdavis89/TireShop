<!DOCTYPE html>
<html>
<head>
    <!-- Your head content here -->
</head>
<body>
    <!-- Your header content here -->

    <?php
    // Check if the user is logged in and show the logout link
    if (isset($_SESSION['username'])) {
        echo '<div><a href="logout.php">Logout</a></div>';
    }
    ?>

    <!-- Your remaining body content here -->
