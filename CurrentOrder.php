<?php if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

<?php include("header.html"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CurrentOrder.css">
    <title>About Us</title>
</head>
<?php include("nav.php"); ?>
<body>
    <?php
    include("dbconfig.in.php");
    


    if(isset($_SESSION['role']) && $_SESSION['role'] == 'customer') {

        $customerId = $_SESSION['customerID'];
        $currentDate = date("Y-m-d");
        $stmt = $connect->prepare('SELECT * FROM `order` WHERE `Customer Id` = ? AND `Order_date` = ?');
        $stmt->execute([$customerId, $currentDate]);
        $orderRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orderRows) {
            echo "<table border='1'>";
            echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th></tr>";
            foreach ($orderRows as $orderRow) {
                $statusClass = ($orderRow['Status'] == 'waiting for processing') ? 'waiting-for-processing' : 'shipped';
                echo "<tr class='$statusClass'>";
                // Hyperlink the order ID to view order details
                echo "<td><a href='OrderDetails.php?id=" . $orderRow['Order Id'] . "' target='_blank'>" . $orderRow['Order Id'] . "</a></td>";
                echo "<td>" . $orderRow['Order_date'] . "</td>";
                echo "<td>" . $orderRow['Total_amount'] . "</td>";
                echo "<td >" . $orderRow['Status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No current orders for today.";
        }

    } elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'employee') {
        // For employees, retrieve all orders for all customers
        $currentDate = date("Y-m-d");
        $stmt = $connect->prepare('SELECT * FROM `order` WHERE `Order_date` = ?');
        $stmt->execute([$currentDate]);
        $orderRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orderRows) {
            echo "<table border='1'>";
            echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th></tr>";
            foreach ($orderRows as $orderRow) {
                $statusClass = ($orderRow['Status'] == 'waiting for processing') ? 'waiting-for-processing' : 'shipped';
                echo "<tr class='$statusClass'>";
                // Hyperlink the order ID to view order details
                echo "<td><a href='OrderDetails.php?id=" . $orderRow['Order Id'] . "' target='_blank'>" . $orderRow['Order Id'] . "</a></td>";
                echo "<td>" . $orderRow['Order_date'] . "</td>";
                echo "<td>" . $orderRow['Total_amount'] . "</td>";
                echo "<td >" . $orderRow['Status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No current orders for today.";
        }

    } else {
        echo "Please log in to view your information.";
    }
    ?>
</body>
</html>
<?php include("footer.html"); ?>
