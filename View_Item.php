<?php
session_start(); 
include("header.html");
include("nav.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="View_Item.css">
    <title>View_Item</title>
    <?php

    include("dbconfig.in.php");

    $result = $connect->query("SELECT `Product Id`, `Name`, `Remark`, `category`, `Price`, `image` FROM product");
    $rows = $result->fetchAll();
    ?>
</head>

<body>
    <?php
    foreach ($rows as $row) {
        echo "<div id='img_div'>";
        echo "<img src='images/" . $row['image'] . "' >";
        echo "<p>" . $row['Name'] . "</p>";
        echo "<p>Category: " . $row['category'] . "</p>";
        echo "<p>Remark: " . $row['Remark'] . "</p>";
        echo "<p>Price: " . $row['Price'] . "</p>";
        echo "<p class='logo'><a href='Add_Order.php?id=" . $row['Product Id'] . "'>Select Item</a></p>";
        echo "</div>";
    }
    ?>
</body>
<?php
include("footer.html");
?>

</html> 