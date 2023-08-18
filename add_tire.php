<?php
session_start();
require_once('model/database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['vehicle_id'])) {
    header("Location: customer_search.php");
    exit();
}

$vehicle_id = $_GET['vehicle_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tire_position = $_POST['tire_position'];
    $tire_code = $_POST['tire_code'];
    $tire_name = $_POST['tire_name'];
    $install_date = $_POST['install_date'];
    $service_count = $_POST['service_count'];

    $insert_query = "INSERT INTO tire (vehicle_ID, tire_position, tire_code, tire_name, install_date, service_count)
                     VALUES (:vehicle_id, :tire_position, :tire_code, :tire_name, :install_date, :service_count)";
    $insert_statement = $db->prepare($insert_query);
    $insert_statement->bindValue(':vehicle_id', $vehicle_id);
    $insert_statement->bindValue(':tire_position', $tire_position);
    $insert_statement->bindValue(':tire_code', $tire_code);
    $insert_statement->bindValue(':tire_name', $tire_name);
    $insert_statement->bindValue(':install_date', $install_date);
    $insert_statement->bindValue(':service_count', $service_count);
    $insert_statement->execute();

    header("Location: vehicle_details.php?vehicle_id=$vehicle_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Tire</title>
</head>
<body>
    <?php include('view/header.php'); ?>

    <h2>Add Tire</h2>
    <form method="post" action="add_tire.php?vehicle_id=<?php echo $vehicle_id; ?>">
        <label for="tire_position">Tire Position:</label>
        <select id="tire_position" name="tire_position" required>
            <option value="FR">FR</option>
            <option value="FL">FL</option>
            <option value="RR">RR</option>
            <option value="RL">RL</option>
            <option value="S">S</option>
            <option value="F">F</option>
            <option value="R">R</option>
        </select><br>
        <label for="tire_code">Tire Code:</label>
        <input type="text" id="tire_code" name="tire_code" required><br>
        <label for="tire_name">Tire Name:</label>
        <input type="text" id="tire_name" name="tire_name" required><br>
        <label for="install_date">Install Date:</label>
        <input type="date" id="install_date" name="install_date"><br>
        <label for="service_count">Service Count:</label>
        <input type="number" id="service_count" name="service_count" required><br>
        <input type="submit" value="Add Tire">
    </form>
</body>
<?php include('view/footer.php'); ?>
</html>
