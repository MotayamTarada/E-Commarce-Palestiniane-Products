<?php session_start(); ?>
<?php require 'dbconfig.in.php';?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="navbar">
        <!-- <img src="logo.png"> -->
        <a href="login.php">Login</a>
        <a href="Register.php">Register</a>
    </div>

    <?php



    if (isset($_POST['login'])) {
        $errMsg = '';

        // Get data from FORM
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username == '') {
            $errMsg = 'Enter your username';
        } elseif ($password == '') {
            $errMsg = 'Enter your password';
        }

        if ($errMsg == '') {
            try {
                $stmt = $connect->prepare('SELECT `Customer Id`, `Password` FROM customer WHERE `Name` = ? AND `Password` = ?');
                $stmt->bindValue(1, $username);
                $stmt->bindValue(2, $password);

                $stmt->execute();
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $connect->prepare('SELECT `ID`, `Password` FROM employee WHERE `Name` = ? AND `Password` = ?');
                $stmt->bindValue(1, $username);
                $stmt->bindValue(2, $password);

                $stmt->execute();
                $employeeData = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($userData) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'customer';
                    $_SESSION['customerID'] = $userData['Customer Id'];

                    echo "<script>window.location.href='Home.php?id=" . $_SESSION['customerID'] . "'</script>";


                } elseif ($employeeData) {
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = 'employee';
                    $_SESSION['ID'] = $employeeData['ID'];
                    echo "<script>window.location.href='Home.php?id=" . $_SESSION['ID'] . "'</script>";


                } else {
                    $errMsg = 'Invalid username or password';
                }

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
    ?>
    <form method="post" action="login.php">
        <fieldset>
            <legend> LOGIN </legend>
            <label for="username">Username:</label>
            <input type="text" name="username" class="form-control" id="username" required>
            <br>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" id="password" required>
            <br>
            <br>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </fieldset>
    </form>
    <?php include "footer.html" ?>
</body>

</html>