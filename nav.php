<?php
@ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <title>Navigation</title>
</head>

<body>
    <nav>
        <ul>
            <?php if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'customer' && $_SESSION['role'] != 'employee')): ?>
                <li>
                    <form method="post" action="search.php">
                        <input type="search" name="search">
                    </form>
                </li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'customer'): ?>
                <li>
                    <form method="post" action="search.php">
                        <input type="search" name="search">
                    </form>
                </li>
                <li><a href="<?php echo file_exists('View_Order_Cus.php') ? 'View_Order_Cus.php' : '#'; ?>">View Order</a>
                </li>
                <li><a href="<?php echo file_exists('View_Item.php') ? 'View_Item.php' : '#'; ?>">View Item</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'employee'): ?>
                <li>
                    <form method="post" action="searchEm.php">
                        <input type="search" name="search">
                    </form>
                </li>
                <li><a href="<?php echo file_exists('Add_item.php') ? 'Add_item.php' : '#'; ?>">Add Item</a></li>
                <li><a href="<?php echo file_exists('View_Order_Emp.php') ? 'View_Order_Emp.php' : '#'; ?>">View Orders</a>
                </li>
                <li><a href="<?php echo file_exists('View_Item.php') ? 'View_Item.php' : '#'; ?>">View Item</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>

</html>