<?php
$username = 'reviews_system';
$password = 'foobar';
$host = 'localhost';
$database = 'reviews';

/*if($conn = mysqli_connect($host, $username, $password, $database)) {
    print '<p>Connection established!</p>';
    // Close the connection
    mysqli_close($conn);
} else {
    print '<p style="color: red;">Error connecting to the database.</p>';
}*/

try{
   $conn =  mysqli_connect($host, $username, $password, $database, 3306);
    print '<p>Connection established!</p>';
} catch(Exception $e) {
     print "<p>Error connecting to the database: " . mysqli_connect_error() . "</p>";
}

?>
