<!DOCTYPE html>
<html>
<head>
</head>
<body>

    <?php
    if (isset($_SESSION['username'])) {
        echo '<div><a href="logout.php">Logout</a></div>';
    }
    ?>
