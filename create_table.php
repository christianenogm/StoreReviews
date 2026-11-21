<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a table</title>
</head>
<body>
    <?php
    require ("creds.php");
    if($conn) {
        // Create the query
        $query = 'CREATE TABLE  entries (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            review TEXT NOT NULL,
            date_entered DATETIME NOT NULL
        ) CHARACTER SET utf8' ;

        // Execute the query
        if (mysqli_query($conn, $query)) {
            print '<p>The table has been created!</p>';
        } else {
            print '<p style="color: red;"> Error creating the table:<br>' . mysqli_error($conn) . '.</p><p> The query being run was: ' . $query . '</p>';
        }
        // Close the connection
        mysqli_close($conn);
    } else { // In case of error
        print '<p style="color: red;">Error connecting to the database: ' . mysqli_connect_error() . '</p>';
    }
    ?>
</body>
</html>