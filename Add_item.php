<?php
session_start();
?>
<?php
include("header.html");
include("nav.php");
include("Add_Item.html") ;
include("dbconfig.in.php");
include("footer.html");
$msg = "";

if (isset($_POST["add"])) {

    $name = $_POST["name"];
    $category = $_POST["category"];
    $brief = $_POST["des"];
    $price = $_POST["price"];
    $size = $_POST["size"];
    $remark = $_POST["remark"];
    $quantity = $_POST["quantity"];

    // Check if 'image' key is set in the $_FILES array
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        // Move the uploaded file to the destination folder
        $img_name = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $img_name);

    } else {
        // Handle the case when no image is uploaded or there's an issue
        $img_name = null; // or provide a default image
    }
    
    try {
        $stmt = $connect->prepare('INSERT INTO product(`Name`, `category`, `Brief Description`, `Size`, `Price`, `Remark`, `Quantity`, `image`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bindParam(1, $name);
        $stmt->bindParam(2, $category);
        $stmt->bindParam(3, $brief);
        $stmt->bindParam(4, $size);
        $stmt->bindParam(5, $price);
        $stmt->bindParam(6, $remark);
        $stmt->bindParam(7, $quantity);
        $stmt->bindParam(8, $img_name);

        $stmt->execute();
        
        echo "<script>window.location.href='View_Item.php'</script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

