<?php
session_start();
require_once('model/database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['tire_id']) || !isset($_GET['vehicle_id'])) {
    header("Location: customer_search.php");
    exit();
}

$tire_id = $_GET['tire_id'];
$vehicle_id = $_GET['vehicle_id'];

// Query to get the selected tire's details
$query = "SELECT * FROM tire WHERE tire_ID=:tire_id";
$statement = $db->prepare($query);
$statement->bindValue(':tire_id', $tire_id);
$statement->execute();
$tire = $statement->fetch();

if (!$tire) {
    header("Location: vehicle_details.php?vehicle_id=$vehicle_id");
    exit();
}

// Handle form submission for updating tire details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tire_position = $_POST['tire_position'];
    $tire_code = $_POST['tire_code'];
    $tire_name = $_POST['tire_name'];
    $install_date = $_POST['install_date'];
    $service_count = $_POST['service_count'];

    // Update the tire details in the database
    $update_query = "UPDATE tire
                     SET tire_position=:tire_position, tire_code=:tire_code, tire_name=:tire_name,
                         install_date=:install_date, service_count=:service_count
                     WHERE tire_ID=:tire_id";
    $update_statement = $db->prepare($update_query);
    $update_statement->bindValue(':tire_position', $tire_position);
    $update_statement->bindValue(':tire_code', $tire_code);
    $update_statement->bindValue(':tire_name', $tire_name);
    $update_statement->bindValue(':install_date', $install_date);
    $update_statement->bindValue(':service_count', $service_count);
    $update_statement->bindValue(':tire_id', $tire_id);
    $update_statement->execute();

    // Redirect back to vehicle_details.php after updating
    header("Location: vehicle_details.php?vehicle_id=$vehicle_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tire</title>
</head>
<body>
    <?php include('view/header.php'); ?>

    <h2>Edit Tire</h2>
    <form method="post" action="">
        <!-- Use the tire's details as placeholder values -->
        <label for="tire_position">Tire Position:</label>
        <input type="text" id="tire_position" name="tire_position" value="<?php echo $tire['tire_position']; ?>" required><br>
        <label for="tire_code">Tire Code:</label>
        <input type="text" id="tire_code" name="tire_code" value="<?php echo $tire['tire_code']; ?>" required><br>
        <label for="tire_name">Tire Name:</label>
        <input type="text" id="tire_name" name="tire_name" value="<?php echo $tire['tire_name']; ?>" required><br>
        <label for="install_date">Install Date:</label>
        <input type="date" id="install_date" name="install_date" value="<?php echo $tire['install_date']; ?>"><br>
        <label for="service_count">Service Count:</label>
        <input type="number" id="service_count" name="service_count" value="<?php echo $tire['service_count']; ?>" required><br>
        <input type="submit" value="Save">
    </form>
</body>
<?php include('view/footer.php'); ?>
</html>
