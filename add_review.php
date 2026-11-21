<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Review</title>
</head>
<body>
    <h1>Add a Review</h1>
    <?php
    //Connect to the reviews database
    require ("creds.php");
    //Set the character set
    mysqli_set_charset($conn, 'utf8');
    // Find all stores for the dropdown
    $stores_result = mysqli_query($conn, "SELECT id, location FROM stores");

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate the input
        $problem  = FALSE;
       if(!empty($_POST['name']) && !empty($_POST['review'])  && !empty($_POST['storeId'])) {
            $name = mysqli_real_escape_string($conn, trim(strip_tags($_POST['name'])));
            $review = mysqli_real_escape_string($conn, trim(strip_tags($_POST['review'])));
            $storeId = (int) $_POST['storeId'];
        } else {            
            print '<p style="color: red;">Please submit both a name and a review, and select a store..</p>';
            $problem = TRUE;
        }
        if(!$problem) {
            // Define the query
            $query = "INSERT INTO entries (id, name, review, storeId, date_entered) VALUES (0, '$name', '$review', '$storeId', NOW())";
            // Execute the query
            if(mysqli_query($conn, $query)) {
                print '<p>The review has been added!</p>';  
            } else {
                print '<p style="color: red;">Could not add the review because:<br>' . mysqli_error($conn) . '.</p><p>The query being run was: ' . $query . '</p>';
            }
        } // No problem
        mysqli_close($conn); // Close the connection to the database
    } // End of form submission IF
    // Display the form
    ?>
    <form action="add_review.php" method="post">
        <p>Enter Your Name: <input type="text" name="name" size="40" maxsize="100"></p>
        <p>Enter Your Review: <textarea name="review" cols="40" rows="5"></textarea></p>
        <p>Select Store:
            <select name="storeId">
                <option value=""> Select a store </option>
                <?php while ($store = mysqli_fetch_assoc($stores_result)) { ?>
                    <option value="<?php echo $store['id']; ?>">
                        <?php echo htmlspecialchars($store['location']); ?>
                    </option>
                <?php } ?>
            </select>
        </p>       
        <input type="submit" name="submit" value="Post This Review!">
    </form>
</body>
</html>