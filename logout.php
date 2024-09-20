<?php
// Start the session
session_start();

// Check if a session is active before attempting to destroy it
if (isset($_SESSION)) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect the user to the login page or any other appropriate page
    echo "<script>window.location.href='login.php'</script>";

} else {
    // No active session to destroy, redirect the user to an appropriate page
    echo "<script>window.location.href='login.php'</script>";
}
?>
