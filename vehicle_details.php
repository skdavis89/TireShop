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

$query = "SELECT * FROM vehicle WHERE vehicle_ID=:vehicle_id";
$statement = $db->prepare($query);
$statement->bindValue(':vehicle_id', $vehicle_id);
$statement->execute();
$vehicle = $statement->fetch();

if (!$vehicle) {
    header("Location: customer_search.php");
    exit();
}

$query = "SELECT * FROM tire WHERE vehicle_ID=:vehicle_id";
$statement = $db->prepare($query);
$statement->bindValue(':vehicle_id', $vehicle_id);
$statement->execute();
$tires = $statement->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $tire_id = $_POST['tire_id'];

    $delete_query = "DELETE FROM tire WHERE tire_ID=:tire_id";
    $delete_statement = $db->prepare($delete_query);
    $delete_statement->bindValue(':tire_id', $tire_id);
    $delete_statement->execute();

    header("Location: vehicle_details.php?vehicle_id=$vehicle_id");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vehicle Details</title>
</head>
<body>
    <?php include('view/header.php'); ?>

    <h2>Vehicle Details</h2>
    <p>VIN: <?php echo $vehicle['vin']; ?></p>
    <p>Year: <?php echo $vehicle['year']; ?></p>
    <p>Make: <?php echo $vehicle['make']; ?></p>
    <p>Model: <?php echo $vehicle['model']; ?></p>
    <p>Mileage: <?php echo $vehicle['mileage']; ?></p>

    <h3>Tires</h3>
    <table>
        <tr>
            <th>Position</th>
            <th>Tire Code</th>
            <th>Tire Name</th>
            <th>Install Date</th>
            <th>Service Count</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php foreach ($tires as $tire) { ?>
            <tr>
                <td><?php echo $tire['tire_position']; ?></td>
                <td><?php echo $tire['tire_code']; ?></td>
                <td><?php echo $tire['tire_name']; ?></td>
                <td><?php echo $tire['install_date']; ?></td>
                <td><?php echo $tire['service_count']; ?></td>
                <td>
                    <form method="get" action="edit_tire.php"> <!-- Change the form action URL -->
                        <input type="hidden" name="tire_id" value="<?php echo $tire['tire_ID']; ?>">
                        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                        <input type="submit" value="Edit">
                    </form>
                </td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="tire_id" value="<?php echo $tire['tire_ID']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            
        <?php } ?>
    </table>
    <form method="get" action="add_tire.php">
        <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
        <input type="submit" value="Add Tire">
    </form>
</body>
<?php include('view/footer.php'); ?>
</html>
