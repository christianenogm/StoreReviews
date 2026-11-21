<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a Review</title>
</head>
<body>
    <h1>Edit a Review</h1>
<?php
require("creds.php");
mysqli_set_charset($conn, 'utf8');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['review']) && !empty($_POST['id'])) {
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $review = mysqli_real_escape_string($conn, trim($_POST['review']));
        $id = (int) $_POST['id'];

        $query = "UPDATE entries SET name='$name', review='$review' WHERE id={$_POST['id']}";
        $r = mysqli_query($conn, $query);

        if (mysqli_affected_rows($conn) == 1) {
            print "<p>The review has been updated!</p>";
        } else {
            print "<p style='color:red;'>No changes were made or error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        print "<p style='color:red;'>Please fill all fields.</p>";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM entries WHERE id=$id");

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        print "<p style='color:red;'>Review not found.</p>";
        exit;
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    print "<p style='color:red;'>No review selected.</p>";
    exit;
}
if (isset($row)): ?>
<form action="edit_review.php" method="post">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <p>Enter Your Name: <input type="text" name="name" size="40" maxlength="100" value="<?= htmlspecialchars($row['name']) ?>"></p>
    <p>Enter Your Review: <textarea name="review" cols="40" rows="5"><?= htmlspecialchars($row['review']) ?></textarea></p>
    <input type="submit" name="submit" value="Update This Review!">
</form>
<?php 
endif; 
mysqli_close($conn);
?>
</body>
</html>
