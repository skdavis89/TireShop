<?php
session_start();
require_once('model/database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //$phone = $_POST['phone'];
    $filterPhone = $_POST['phone'];

    $phone = preg_replace('/\D/', '', $filterPhone);

    $query = "SELECT * FROM customer WHERE phone=:phone";
    $statement = $db->prepare($query);
    $statement->bindValue(':phone', $phone);
    $statement->execute();
    $customer = $statement->fetch();
    
    if ($customer) {
        $query = "SELECT * FROM vehicle WHERE customer_id=:customer_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':customer_id', $customer['customer_ID']);
        $statement->execute();
        $vehicles = $statement->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Search Page</title>
</head>
<body>
    <?php include('view/header.php'); ?>

    <h2>Customer Search</h2>
    <form method="post" action="">
        <label for="phone">Search by Telephone Number:</label>
        <input type="text" id="phone" name="phone" required>
        <input type="submit" value="Search">
    </form>

    <?php if (isset($customer)) { ?>
        <h3>Customer Details</h3>
        <p>ID: <?php echo $customer['customer_ID']; ?></p>
        <p>Name: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></p>
        <p>Phone: <?php echo $customer['phone']; ?></p>
        <p>Email: <?php echo $customer['email']; ?></p>

        <h3>Vehicles Owned</h3>
        <table>
            <tr>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
            </tr>
            <?php foreach ($vehicles as $vehicle) { ?>
                <tr>
                    <td><?php echo $vehicle['year']; ?></td>
                    <td><?php echo $vehicle['make']; ?></td>
                    <td><?php echo $vehicle['model']; ?></td>
                    <td>
                        <form method="get" action="vehicle_details.php">
                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_ID']; ?>">
                            <input type="submit" value="Select">
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</body>
<?php include('view/footer.php'); ?>
</html>
