<?php
session_start(); // Start the session

include("header.html");
include("nav.php");

// Include necessary files
include("dbconfig.in.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile.css">
    <title>About Us</title>
</head>
<body>
    <?php
    
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'customer') {
        $customerId = $_SESSION['customerID'];
        $stmt = $connect->prepare('SELECT * FROM customer WHERE `Customer Id` = ?');
        $stmt->execute([$customerId]);
        $customerRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if($customerRow) {
            // Display customer information
            echo "<h2>Customer Information</h2>";
            echo "<p>Name: " . $customerRow['Name'] . "</p>";
            echo "<p>Email: " . $customerRow['E_mail'] . "</p>";
            echo "<p>Address: " . $customerRow['Address'] . "</p>";
            // Add more fields as needed
        } else {
            echo "Customer information not found.";
        }
    } elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'employee') {
        $employeeId = $_SESSION['ID'];
        $stmt = $connect->prepare('SELECT * FROM employee WHERE `ID` = ?');
        $stmt->execute([$employeeId]);
        $employeeRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if($employeeRow) {
            // Display employee information
            echo "<h2>Employee Information</h2>";
            echo "<p>ID: " . $employeeRow['ID'] . "</p>";
            echo "<p>Name: " . $employeeRow['Name'] . "</p>";
            // Add more fields as needed
        } else {
            echo "Employee information not found.";
        }
    } else {
        echo "Please log in to view your information.";
    }
    ?>
</body>
</html>
<?php include("footer.html"); ?>
