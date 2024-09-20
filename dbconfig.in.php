<?php 

// DB credentials.
define('DB_HOST','localhost'); // Host name
define('DB_USER','web1210299_dbuser'); // db user name
define('DB_PASS','mot.1692003'); // db user password name
define('DB_NAME','web1210299_db'); // db name
// Establish database connection.
try
{
     $connect = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS);
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}
?>


?>

