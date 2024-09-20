
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="masseg.css"> <!-- Link to your external CSS file -->

    <title>Order Confirmation</title>
</head>
<body>
    <div class="message-box">
        <?php
            // Check if the order ID is provided in the URL
            if(isset($_GET['id'])) {
                $orderId = $_GET['id']; // Retrieve the order ID from the URL

                // Your code to store the order in the database and set its status
                // Display a message to the user with the order ID
                echo "<p>Thank you for your purchase! Your order ID is: <a href='OrderDetails.php?id=$orderId' target='_blank'>$orderId</a>.</p>";
            } else {
                // If the order ID is not provided, redirect the user to the homepage or another appropriate page
                header("Location: index.php");
                exit();
            }

            
        ?>
    </div>
</body>
</html>
