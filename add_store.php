<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Store</title>
</head>
<body>
    <h1>Add a Store</h1>
    <?php
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate the input
        $problem  = FALSE;

       if(!empty($_POST['location'])) {
            $location = trim(strip_tags($_POST['location']));
        } else {            
            print '<p style="color: red;">Please submit a location.</p>';
            $problem = TRUE;
        }

        if(!$problem) {
            // Connect to the reviews database
            require ("creds.php");
            // Define the query
            $query = "INSERT INTO stores (id, location) VALUES (0, '$location')";
            // Execute the query
            if(mysqli_query($conn, $query)) {
                print '<p>The location has been added!</p>';  
            } else {
                print '<p style="color: red;">Could not add the location because:<br>' . mysqli_error($conn) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
            // Close the connection to the database
            mysqli_close($conn);
        } // No problem
    } // End of form submission IF
    // Display the form
    ?>
    <form action="add_store.php" method="post">
        <p>Enter Your Location Store: <textarea name="location" cols="40" rows="5"></textarea></p>
        <input type="submit" name="submit" value="Post This Location!">
</body>
</html>