<?php

include("dbconfig.in.php");

// Assuming $connect is your PDO connection

if (isset($_GET['id'])) {
    $Order_id = $_GET['id'];

    // Assuming your table name is `order` and it's not a reserved keyword
    // Also assuming you have proper column names for product id and order id
    $sql = "DELETE FROM `order` WHERE `Order Id` = :Order_id"; // Using named placeholders

    // Prepare the SQL statement
    $statement = $connect->prepare($sql);

    if (!$statement) {
        echo "Error preparing statement: " . $connect->errorInfo()[2];
        exit;
    }

    // Bind the order id parameter
    $statement->bindParam(':Order_id', $Order_id, PDO::PARAM_INT);

    // Execute the statement
    if ($statement->execute()) {
        // Check if any rows were affected
        if ($statement->rowCount() > 0) {
            echo "Product deleted successfully";
            echo "<script>window.location.href='View_Item.php'</script>";

            exit; // Exit after redirection
        } else {
            echo "No rows were deleted. Order may not exist.";
        }
    } else {
        echo "Error deleting product: " . $statement->errorInfo()[2];
    }

    // Close the statement
    $statement->closeCursor();
}
?>
