<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Search</title>
    <link rel="stylesheet" type="text/css" href="search.css">
</head>

<body>
    <?php
    
    include("dbconfig.in.php");
    include("header.html");
    include("nav.php");

    // Function to generate HTML for the product table
    function generateProductTable($stmt, $shortlistedProductIds)
    {
        echo '<div class="table-container">';
        echo '<form method="post" action="search.php" class="contact-form">';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th><input type="submit" name="shortlist_button" class="shortlist-button" value="Shortlist"></th>';
        echo '<th><a href="?sort=reference">Reference Number</a></th>';
        echo '<th><a href="?sort=price">Price</a></th>';
        echo '<th><a href="?sort=category">Category</a></th>';
        echo '<th><a href="?sort=quantity">Quantity</a></th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_id = isset($row['Product Id']) ? $row['Product Id'] : '';
            $product_price = isset($row['Price']) ? $row['Price'] : '';
            $product_category = isset($row['category']) ? $row['category'] : '';
            $product_quantity = isset($row['Quantity']) ? $row['Quantity'] : '';

            $category_class = '';
            switch ($product_category) {
                case 'new arrival':
                    $category_class = 'new-arrival';
                    break;
                case 'on sale':
                    $category_class = 'on-sale';
                    break;
                case 'featured':
                    $category_class = 'featured';
                    break;
                case 'high demand':
                    $category_class = 'high-demand';
                    break;
                default:
                    $category_class = 'normal';
                    break;
            }

            echo "<tr class=\"$category_class\">";
            echo "<td><input type='checkbox' name='shortlist[]' value='$product_id'></td>";
            echo "<td><a href='ItemReferance.php?id=$product_id'>$product_id</a></td>";
            echo "<td>$product_price</td>";
            echo "<td>$product_category</td>";
            echo "<td>$product_quantity</td>";
            echo "</tr>";
        }

        echo '</tbody>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
    }

    // Check if the shortlist button is clicked
    if (isset($_POST['shortlist_button'])) {
        $shortlistedProductIds = isset($_POST['shortlist']) ? $_POST['shortlist'] : [];

        // Store shortlisted items in a session variable
        $_SESSION['shortlisted_items'] = $shortlistedProductIds;

        if (!empty($shortlistedProductIds)) {
            $placeholders = rtrim(str_repeat('?, ', count($shortlistedProductIds)), ', ');

            $shortlistedQuery = "SELECT * FROM product WHERE `Product Id` IN ($placeholders)";
            $shortlistedStmt = $connect->prepare($shortlistedQuery);
            $shortlistedStmt->execute($shortlistedProductIds);
            echo '<div class="shortlisted-details">';
            echo '<h2>Shortlisted Items Details</h2>';
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Product ID</th>';
            echo '<th>Price</th>';
            echo '<th>Category</th>';
            echo '<th>Quantity</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($shortlistedRow = $shortlistedStmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $shortlistedRow['Product Id'] . '</td>';
                echo '<td>' . $shortlistedRow['Price'] . '</td>';
                echo '<td>' . $shortlistedRow['category'] . '</td>';
                echo '<td>' . $shortlistedRow['Quantity'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
    }

    // Process sorting if the sort parameter is set
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    $sql = 'SELECT * FROM product WHERE 1';

    switch ($sort) {
        case 'reference':
            $sql .= ' ORDER BY `Product Id` ASC';
            break;
        case 'price':
            $sql .= ' ORDER BY `Price` ASC';
            break;
        case 'category':
            $sql .= ' ORDER BY `category` ASC';
            break;
        case 'quantity':
            $sql .= ' ORDER BY `Quantity` ASC';
            break;
        default:
            // Default sorting here
            break;
    }

    $stmt = $connect->query($sql);
    // Display the initial table
    generateProductTable($stmt, []);

    ?>

    <div class="contact-section">
        <form method="post" action="">
            <label for="search_name">Search Name:</label>
            <input type="text" name="search_name" id="search_name">

            <label for="min_price">Min Price:</label>
            <input type="number" name="min_price" id="min_price">

            <label for="max_price">Max Price:</label>
            <input type="number" name="max_price" id="max_price">

            <input type="submit" name="search" value="Search">
        </form>
    </div>

    <?php include("footer.html"); ?>
</body>

</html>