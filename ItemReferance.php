<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="itemReferance.css"> <!-- Link to your CSS file -->
    <title>Product Details</title>
</head>
<body class="all">
    <?php include "header.html"; ?>
    <br>
    <?php include "nav.php"; ?>
    <?php
    include "dbconfig.in.php";
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $connect->prepare("SELECT Name, Remark, category, Price, image FROM product WHERE `Product Id` = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (count($rows) > 0) {
            ?>
            <div class="conta">
                <?php foreach ($rows as $row) { ?>
                    <div class="product-details-container">
                        <div class="product-details">
                            <h1>Product Details</h1>
                            <p><strong>Name:</strong>
                                <?php echo $row['Name']; ?>
                            </p>
                            <p><strong>Remark:</strong>
                                <?php echo $row['Remark']; ?>
                            </p>
                            <p><strong>Category:</strong>
                                <?php echo $row['category']; ?>
                            </p>
                            <p><strong>Price:</strong>
                                <?php echo $row['Price']; ?>
                            </p>
                        </div>
                        <img class="product-image" src="images/<?php echo $row['image']; ?>" alt="Product Image">
                    </div>
                <?php } ?>
            </div>
            <?php
        } else {
            echo "No product found with the provided ID.";
        }
    } else {
        echo "Invalid ID provided.";
    }
    ?>
    <?php include "footer.html"; ?>
</body>

</html>