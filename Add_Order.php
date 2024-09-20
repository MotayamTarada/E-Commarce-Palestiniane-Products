<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add_Order</title>
    <link rel="stylesheet" href="Add_Order.css"> <!-- Link to your external CSS file -->
</head>

<body>
    <?php
    // Check if the user is not logged in, redirect them to the login page
    if (!isset($_SESSION['username'])) {
        // Redirect the user to the login page with the correct redirect parameter
        $redirect = isset($_SERVER['PHP_SELF']) ? basename($_SERVER['PHP_SELF']) : 'Add_Order.php';
        echo "<script>window.location.href='login.php?redirect=$redirect'</script>";

        exit();
    }

    // Include necessary files
    include("header.html");
    include("nav.php");
    include("dbconfig.in.php");

    try {
        $username = $_SESSION['username']; // Assuming you have stored the username in the session
        $stmt = $connect->prepare('SELECT `Customer Id` FROM customer WHERE `Name` = ?');
        $stmt->execute([$username]);
        $customerRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customerRow) {
            $customerId = $customerRow['Customer Id'];

            if (isset($_GET['id'])) {
                $productId = intval($_GET['id']); // Assuming 'id' is an integer
    
                // Check if the product already exists in the order for the customer
                $checkStmt = $connect->prepare('SELECT * FROM `order` WHERE `Product Id` = ? AND `Customer Id` = ?');
                $checkStmt->execute([$productId, $customerId]);
                $existingProduct = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if (!$existingProduct) {
                    // If the product doesn't exist in the order, insert it with quantity 1
                    $insertStmt = $connect->prepare('INSERT INTO `order` (`Product Id`, `Customer Id`, `Quantity`, `Total_amount`, `Order_date`, `Status`) VALUES (?, ?, 1, 1, NOW(), "waiting for processing")');
                    $insertStmt->execute([$productId, $customerId]);

                    // Redirect back to the same page to refresh the order display
                    echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "'</script>";


                    exit();
                }
            }

            // Recalculate the total amount
            if (isset($_POST['recalculate'])) {
                try {
                    // Loop through the submitted quantities and update them in the database
                    foreach ($_POST['quantity'] as $productId => $quantity) {
                        // Ensure the quantity is a positive integer
                        $quantity = intval($quantity);
                        if ($quantity >= 0) {
                            // Update the quantity in the `order` table for the given product and customer
                            $updateStmt = $connect->prepare('UPDATE `order` SET `Quantity` = ? WHERE `Product Id` = ? AND `Customer Id` = ?');
                            $updateStmt->execute([$quantity, $productId, $customerId]);

                            // Fetch the product price from the database
                            $priceStmt = $connect->prepare('SELECT Price FROM product WHERE `Product Id` = ?');
                            $priceStmt->execute([$productId]);
                            $productPrice = $priceStmt->fetchColumn();

                            // Recalculate the total amount based on the updated quantity and price
                            $totalAmount = $quantity * $productPrice;

                            // Update the total amount in the `order` table
                            $updateTotalStmt = $connect->prepare('UPDATE `order` SET `Total_amount` = ? WHERE `Product Id` = ? AND `Customer Id` = ?');
                            $updateTotalStmt->execute([$totalAmount, $productId, $customerId]);
                        }
                    }

                    // Redirect back to the same page to refresh the order display
                    echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "'</script>";
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

            $orderStmt = $connect->prepare("SELECT o.`Order Id`, p.`Product Id`, c.Address, p.Price, o.Quantity, (p.Price * o.Quantity) AS Total_amount
        FROM product p
        JOIN `order` o ON p.`Product Id` = o.`Product Id`
        JOIN customer c ON o.`Customer Id` = c.`Customer Id`
        WHERE o.`Customer Id` = ?");

            $orderStmt->execute([$customerId]);
            $orderRows = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($orderRows) {
                echo "<table border='1'>";
                echo "<tr><th>Product ID</th><th>Address</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>";
                foreach ($orderRows as $orderRow) {
                    echo "<tr>";
                    echo "<td>" . $orderRow['Product Id'] . "</td>";
                    echo "<td>" . $orderRow['Address'] . "</td>";
                    echo "<td>" . $orderRow['Price'] . "</td>";
                    echo "<td><form method='post'><input type='number' name='quantity[" . $orderRow['Product Id'] . "]' value='" . $orderRow['Quantity'] . "'><input type='submit' name='recalculate' value='Recalculate'></form></td>";
                    // Add the remove link to remove the item from the order
                    echo "<td><a href='Remove_item.php?id=" . $orderRow['Order Id'] . "'>Remove</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "<a href='Form_Exit.php?id=" . $orderRow['Order Id'] . "'>Confirm</a>";
            } else {
                echo "No items in the order";
            }
        } else {
            echo "Customer ID not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    include("footer.html");
    ?>

</body>

</html>