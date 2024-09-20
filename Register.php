<?php
session_start();

require 'dbconfig.in.php';

if (isset($_POST['register'])) {
    $errMsg = '';

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password']; // New field for password confirmation
    $dateOfBirth = $_POST['date'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    $number = $_POST['number'];
    $date =  $_POST['date'];   
    $name =  $_POST['name'];   
    $bank = $_POST['bank_issued']; // Corrected input name

   
     if (strlen($password) < 8 || strlen($password) > 12) {
        $errMsg = 'Password must be between 8 and 12 characters';
    } if ($username == '') {
        $errMsg = 'Enter username';
    } if ($dateOfBirth == '') {
        $errMsg = 'Enter a date of birth';
    } if ($tel == '') {
        $errMsg = 'Enter a telephone number';
    }

    if ($errMsg == '') {
        try {
            $stmt = $connect->prepare('INSERT INTO customer (E_mail, Name, DateOfBirth, Telephone, Password, Address) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $username);
            $stmt->bindValue(3, $dateOfBirth);
            $stmt->bindValue(4, $tel);
            $stmt->bindValue(5, $password);
            $stmt->bindValue(6, $address);
            $stmt->execute();

            // Fetch the customer ID after insertion
            $customerId = $connect->lastInsertId();
            $stmt = $connect->prepare('INSERT INTO credit_card (`number`, expiration_date, `name`, bank_issued, `Customer Id`) VALUES (?, ?, ?, ?, ?)');
            $stmt->bindValue(1, $number);
            $stmt->bindValue(2, $date);
            $stmt->bindValue(3, $name);
            $stmt->bindValue(4, $bank);
            $stmt->bindValue(5, $customerId);
            $stmt->execute();
            
            header('Location: login.php?action=joined');
            exit;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'joined') {
    $errMsg = 'Registration successful. Now you can <a href="login.php">login</a>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="Register.css">
</head>
<body>
<main>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div id='error_msg'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>
    <div class="navbar">
        <a href="login.php">Login</a>
        <a href="Register.php">Register</a>
    </div>
    <div class="container">
        <form method="post" action="Register.php">
            <fieldset class="fieldset-right">
                <legend>Registration</legend>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="textInput" id="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="textInput" id="email" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" class="textInput" id="address" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="textInput" id="password" required>
                </div>
                <div class="form-group">
                    <label for="date">Date Of Birth:</label>
                    <input type="date" name="date" class="textInput" id="date" required>
                </div>
                <div class="form-group">
                    <label for="tel">Telephone:</label>
                    <input type="text" name="tel" class="textInput" id="tel" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Register" name="register" class="Register">
                </div>
            </fieldset>
            <fieldset class="fieldset-left">
                <legend>Credit Card</legend>
                <div class="form-group">
                    <label for="number">Number:</label>
                    <input type="text" name="number" class="textInput" id="number" required>
                </div>
                <div class="form-group">
                    <label for="date">Expiration Date:</label>
                    <input type="date" name="date" class="textInput" id="date" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" class="textInput" id="name" required>
                </div>
                <div class="form-group">
                    <label for="bank_issued">Bank Issued:</label>
                    <input type="text" name="bank_issued" class="textInput" id="bank_issued" required>
                </div>
            </fieldset>
        </form>
    </div>
    <?php include "footer.html"?>
</main>
</body>
</html>
