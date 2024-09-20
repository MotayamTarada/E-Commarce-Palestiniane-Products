<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="view_Order_Emp.css">
    <title>View Orders</title>
</head>

<body>
    <?php
    include("header.html");
    include("nav.php");
    include("dbconfig.in.php");

    try {
        // Default sorting order
        $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'DESC';

        // Fetch orders for all customers sorted by date in the specified order
        $orderStmt = $connect->prepare('SELECT * FROM `order` ORDER BY `Order_date` ' . $orderBy);
        $orderStmt->execute();
        $orderRows = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

        if ($orderRows) {
            echo "<table>";
            echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th></tr>";
            foreach ($orderRows as $orderRow) {
                // Determine the class based on the order status
                $statusClass = ($orderRow['Status'] == 'waiting for processing') ? 'waiting for processing' : 'shipped';

                // Start the table row with the appropriate class
                echo "<tr class='" . $statusClass . "'>";
                // Hyperlink the order ID to view order details
                echo "<td><a href='OrderDetalisEmp.php?id=" . $orderRow['Order Id'] . "' target='_blank'>" . $orderRow['Order Id'] . "</a></td>";
                echo "<td>" . $orderRow['Order_date'] . "</td>";
                echo "<td>" . $orderRow['Total_amount'] . "</td>";
                // Apply different styles based on order status
                echo "<td>";
                echo "<form method='POST'>";
                echo "<input type='hidden' name='order_id' value='" . $orderRow['Order Id'] . "'>";
                echo "<select name='status'>";
                echo "<option value='waiting for processing' " . ($orderRow['Status'] == 'waiting for processing' ? 'selected' : '') . ">Waiting for Processing</option>";
                echo "<option value='shipped' " . ($orderRow['Status'] == 'shipped' ? 'selected' : '') . ">Shipped</option>";
                echo "</select>";
                echo "<input type='submit' name='update_status' value='Update'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";

            // Display form for sorting
            echo "<form action='' method='GET'>";
            echo "<label>Sort by Date: </label>";
            echo "<select name='orderBy'>";
            echo "<option value='DESC' " . ($orderBy == 'DESC' ? 'selected' : '') . ">Newest First</option>";
            echo "<option value='ASC' " . ($orderBy == 'ASC' ? 'selected' : '') . ">Oldest First</option>";
            echo "</select>";
            echo "<input type='submit' value='Sort'>";
            echo "</form>";
        } else {
            echo "No items in the order";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Handle status update
    if (isset($_POST['update_status'])) {
        try {
            $orderId = $_POST['order_id'];
            $newStatus = $_POST['status'];

            // Update the order status in the database
            $updateStmt = $connect->prepare('UPDATE `order` SET `Status` = ? WHERE `Order Id` = ?');
            $updateStmt->execute([$newStatus, $orderId]);

            // Redirect back to the same page to refresh the order list
            echo "<script>window.location.href='{$_SERVER['PHP_SELF']}'</script>";

            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    include("footer.html");
    ?>
</body>

</html>