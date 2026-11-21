<?php
require("creds.php");
mysqli_set_charset($conn, 'utf8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];

        // Execute delete query
        $query = "DELETE FROM entries WHERE id={$_POST['id']} LIMIT 1";
        $r = mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) == 1) {
            print "<p>The review has been deleted!</p>";
        } else {
            print "<p style='color:red;'>Could not delete the review: " . mysqli_error($conn) . "</p>";
        }
    } else {
        print "<p style='color:red;'>No review selected to delete.</p>";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($_GET['id'])) {
        $id = (int) $_GET['id'];
        // Show confirmation form
        print "<h1>Confirm Delete</h1>";
        print "<p>Are you sure you want to delete review #$id?</p>";
        print '<form method="post" action="delete_review.php">';
        print "<input type='hidden' name='id' value='$id'>";
        print '<input type="submit" value="Yes, Delete">';
        print '</form>';
        print '<p><a href="read_reviews.php">Cancel</a></p>';
    } else {
        print "<p style='color:red;'>No review selected to delete.</p>";
    }
}

mysqli_close($conn);
?>
