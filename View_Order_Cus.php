<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add_Order</title>
    <link rel="stylesheet" href="view_Order_Cus.css">
</head>
<body>
<?php
include("header.html");
include("nav.php");
include("dbconfig.in.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $orderId = $_POST['order_id'];
        $newOrderDate = $_POST['new_order_date'];

        // Update the order date in the database
        $updateStmt = $connect->prepare('UPDATE `order` SET `Order_date` = ? WHERE `Order Id` = ?');
        $updateStmt->execute([$newOrderDate, $orderId]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

try {
    // Check if 'username' is set in the session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        $stmt = $connect->prepare('SELECT `Customer Id` FROM customer WHERE `Name` = ?');
        $stmt->execute([$username]);
        $customerRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customerRow) {
            $customerId = $customerRow['Customer Id'];

            // Default sorting order
            $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'DESC';

            // Fetch orders for the customer sorted by date in the specified order
            $orderStmt = $connect->prepare('SELECT * FROM `order` WHERE  `Customer Id` = ? ORDER BY `Order_date` ' . $orderBy);
            $orderStmt->execute([$customerId]);
            $orderRows = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($orderRows) {
                echo "<table border='1'>";
                echo "<tr><th>Order ID</th><th>Order Date</th><th>Total Amount</th><th>Status</th><th>Action</th></tr>";
                foreach ($orderRows as $orderRow) {
                    $statusClass = ($orderRow['Status'] == 'waiting for processing') ? 'waiting for processing' : 'shipped';
                    
                    echo "<tr class='$statusClass'>";
                    // Hyperlink the order ID to view order details
                    echo "<td><a href='OrderDetails.php?id=" . $orderRow['Order Id'] . "' target='_blank'>" . $orderRow['Order Id'] . "</a></td>";
                    echo "<td>" . $orderRow['Order_date'] . "</td>";
                    echo "<td>" . $orderRow['Total_amount'] . "</td>";
                    echo "<td>" . $orderRow['Status'] . "</td>";
                    // Form for updating order date
                    echo "<td>";
                    echo "<form method='POST' action=''>";
                    echo "<input type='hidden' name='order_id' value='" . $orderRow['Order Id'] . "'>";
                    echo "<input type='date' name='new_order_date'>";
                    echo "<input type='submit' value='Update'>";
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
        } else {
            echo "Customer ID not found.";
        }
    } else {
        echo "Username not set in the session.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

include("footer.html");
?>
</body>

</html>
