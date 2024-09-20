<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="masseg.css"> 
    <title>Order Confirmation</title>
</head>

<body>
<?php
include("header.html");
include("nav.php");
include("dbconfig.in.php");

try {
    if (isset($_GET['id'])) {
        $orderId = $_GET['id'];

        // Prepare and execute the query to fetch order details
        $stmt = $connect->prepare('SELECT * FROM `order` WHERE `Order Id` = ?');
        $stmt->execute([$orderId]);
        $orderDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($orderDetails) {
            // Prepare and execute the query to fetch customer details
            $customerStmt = $connect->prepare('SELECT c.E_mail, c.Name, c.DateOfBirth, c.Telephone, c.Address FROM customer c WHERE c.`Customer Id` = ?');
            $customerStmt->execute([$orderDetails['Customer Id']]);
            $customerDetails = $customerStmt->fetch(PDO::FETCH_ASSOC);

            // Display order details
            echo "<div class='message-box'>";
            echo "<h2>Order Confirmation</h2>";
            echo "<p>Thank you for your purchase! Your order details are as follows:</p>";
            echo "<ul>";
            echo "<li>Order ID: " . $orderDetails['Order Id'] . "</li>";
            echo "<li>Customer ID: " . $orderDetails['Customer Id'] . "</li>";
            echo "<li>Product ID: " . $orderDetails['Product Id'] . "</li>";
            echo "<li>Quantity: " . $orderDetails['Quantity'] . "</li>";
            echo "<li>Total Amount: $" . $orderDetails['Total_amount'] . "</li>";
            echo "<li>Order Date: " . $orderDetails['Order_date'] . "</li>";
            echo "<li>Status: " . $orderDetails['Status'] . "</li>";
            echo "</ul>";

            // Display customer details
            echo "<h3>Customer Details:</h3>";
            echo "<ul>";
            if ($customerDetails) {
                echo "<li>Email: " . $customerDetails['E_mail'] . "</li>";
                echo "<li>Name: " . $customerDetails['Name'] . "</li>";
                echo "<li>Date of Birth: " . $customerDetails['DateOfBirth'] . "</li>";
                echo "<li>Telephone: " . $customerDetails['Telephone'] . "</li>";
                echo "<li>Address: " . $customerDetails['Address'] . "</li>";
            } else {
                echo "<li>No customer details found.</li>";
            }
            echo "</ul>";

            echo "</div>";
        } else {
            echo "<p>No order found with the provided ID.</p>";
        }
    } else {
        // If the order ID is not provided, redirect the user to the homepage or another appropriate page
        header("Location: index.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

include("footer.html");
?>

</body>

</html>
