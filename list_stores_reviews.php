<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Stores and Reviews</title>
</head>
<body>
    <h1>Stores and Reviews</h1>
    <?php
    require("creds.php");
    mysqli_set_charset($conn, 'utf8');

    // find all stores
    $storesQuery = "SELECT * FROM stores ORDER BY location";
    if ($storesResult = mysqli_query($conn, $storesQuery)) {
        while ($store = mysqli_fetch_assoc($storesResult)) {
            echo "<h2>" . htmlspecialchars($store['location']) . "</h2>";

            // find reviews for each store
            $storeId = (int)$store['id'];
            $reviewsQuery = "SELECT * FROM entries WHERE storeId = $storeId ORDER BY date_entered DESC";
            if ($reviewsResult = mysqli_query($conn, $reviewsQuery)) {
                if (mysqli_num_rows($reviewsResult) > 0) {
                    while ($review = mysqli_fetch_assoc($reviewsResult)) {
                        echo "<p><strong>" . htmlspecialchars($review['name']) . "</strong>: " .
                             htmlspecialchars($review['review']) . "<br />" .
                             "<small>" . $review['date_entered'] . "</small><br />" .
                             "<a href=\"edit_review.php?id={$review['id']}\">Edit</a> | " .
                             "<a href=\"delete_review.php?id={$review['id']}\">Delete</a></p><hr>";
                    }
                } else {
                    echo "<p>No reviews for this store.</p><hr>";
                }
                mysqli_free_result($reviewsResult);
            } else {
                echo "<p style='color:red;'>Error retrieving reviews: " . mysqli_error($conn) . "</p>";
            }
        }
        mysqli_free_result($storesResult);
    } else {
        echo "<p style='color:red;'>Error retrieving stores: " . mysqli_error($conn) . "</p>";
    }
    mysqli_close($conn);
    ?>
</body>
</html>
