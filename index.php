<?php
session_start();
require 'creds.php';
mysqli_set_charset($conn, 'utf8mb4');

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $url = 'index.php'): never
{
    header("Location: $url");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_store') {
        $location = trim(strip_tags($_POST['location'] ?? ''));
        if ($location === '') {
            $_SESSION['message'] = ['error', 'Please enter the store name or address.'];
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO stores (location) VALUES (?)');
            mysqli_stmt_bind_param($stmt, 's', $location);
            $_SESSION['message'] = mysqli_stmt_execute($stmt)
                ? ['success', 'Store added successfully!']
                : ['error', 'Could not add the store.'];
            mysqli_stmt_close($stmt);
        }
        redirect();
    }

    if ($action === 'add_review' || $action === 'edit_review') {
        $name = trim(strip_tags($_POST['name'] ?? ''));
        $review = trim(strip_tags($_POST['review'] ?? ''));
        $storeId = (int) ($_POST['storeId'] ?? 0);

        if ($name === '' || $review === '' || $storeId <= 0) {
            $_SESSION['message'] = ['error', 'Please enter your name, review, and select a store.'];
            redirect();
        }

        if ($action === 'edit_review') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = mysqli_prepare($conn, 'UPDATE entries SET name = ?, review = ?, storeId = ? WHERE id = ?');
            mysqli_stmt_bind_param($stmt, 'ssii', $name, $review, $storeId, $id);
            $successMessage = 'Review updated successfully!';
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO entries (name, review, storeId, date_entered) VALUES (?, ?, ?, NOW())');
            mysqli_stmt_bind_param($stmt, 'ssi', $name, $review, $storeId);
            $successMessage = 'Review published successfully!';
        }

        $_SESSION['message'] = mysqli_stmt_execute($stmt)
            ? ['success', $successMessage]
            : ['error', 'Could not save the review.'];
        mysqli_stmt_close($stmt);
        redirect();
    }

    if ($action === 'delete_review') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = mysqli_prepare($conn, 'DELETE FROM entries WHERE id = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        $_SESSION['message'] = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1
            ? ['success', 'Review deleted successfully!']
            : ['error', 'Could not delete the review.'];
        mysqli_stmt_close($stmt);
        redirect();
    }
}

$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

$stores = [];
$storesResult = mysqli_query($conn, 'SELECT id, location FROM stores ORDER BY location');
while ($storesResult && $store = mysqli_fetch_assoc($storesResult)) {
    $stores[] = $store;
}

$filterStoreId = (int) ($_GET['store'] ?? 0);
$editId = (int) ($_GET['edit'] ?? 0);
$editingReview = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT id, name, review, storeId FROM entries WHERE id = ?');
    mysqli_stmt_bind_param($stmt, 'i', $editId);
    mysqli_stmt_execute($stmt);
    $editingReview = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
}

$query = 'SELECT e.id, e.name, e.review, e.date_entered, s.id AS storeId, s.location
          FROM entries e
          JOIN stores s ON s.id = e.storeId';
$params = [];
if ($filterStoreId > 0) {
    $query .= ' WHERE e.storeId = ?';
    $params[] = $filterStoreId;
}
$query .= ' ORDER BY e.date_entered DESC';
$stmt = mysqli_prepare($conn, $query);
if ($filterStoreId > 0) {
    mysqli_stmt_bind_param($stmt, 'i', $filterStoreId);
}
mysqli_stmt_execute($stmt);
$reviewsResult = mysqli_stmt_get_result($stmt);
$totalReviews = mysqli_num_rows($reviewsResult);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Reviews</title>
    <style>
        :root { --navy:#14213d; --blue:#2563eb; --bg:#f4f7fb; --card:#fff; --text:#172033; --muted:#64748b; --border:#e2e8f0; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:Inter,Segoe UI,Arial,sans-serif; color:var(--text); background:var(--bg); }
        header { background:linear-gradient(120deg,var(--navy),#1d4ed8); color:white; padding:32px 24px 56px; }
        .container { width:min(1100px,calc(100% - 32px)); margin:0 auto; }
        h1 { margin:0 0 8px; font-size:clamp(28px,5vw,42px); }
        header p { margin:0; color:#dbeafe; }
        .dashboard { display:grid; grid-template-columns:360px 1fr; gap:22px; margin-top:-30px; }
        .card { background:var(--card); border:1px solid var(--border); border-radius:16px; box-shadow:0 8px 26px rgba(15,23,42,.07); }
        .panel { padding:22px; margin-bottom:18px; }
        h2 { margin:0 0 16px; font-size:20px; }
        h3 { margin:0; font-size:18px; }
        label { display:block; margin:12px 0 6px; font-weight:650; font-size:14px; }
        input, textarea, select { width:100%; padding:11px 12px; border:1px solid #cbd5e1; border-radius:9px; font:inherit; background:#fff; }
        input:focus, textarea:focus, select:focus { outline:3px solid #bfdbfe; border-color:var(--blue); }
        textarea { resize:vertical; min-height:100px; }
        .button { display:inline-block; border:0; border-radius:9px; padding:11px 15px; margin-top:14px; cursor:pointer; font-weight:700; color:white; background:var(--blue); text-decoration:none; }
        .button.secondary { background:#64748b; }
        .button.danger { margin-top:0; background:#dc2626; }
        .button.small { margin-top:0; padding:8px 11px; font-size:13px; }
        .toolbar { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:18px 22px; border-bottom:1px solid var(--border); }
        .toolbar form { display:flex; gap:8px; align-items:center; }
        .toolbar select { width:auto; min-width:190px; padding:9px; }
        .reviews { padding:8px 22px 22px; }
        .review { padding:20px 0; border-bottom:1px solid var(--border); }
        .review:last-child { border-bottom:0; }
        .review-top { display:flex; justify-content:space-between; gap:14px; align-items:flex-start; }
        .store-badge { display:inline-block; color:#1d4ed8; background:#eff6ff; border-radius:999px; padding:5px 9px; font-size:12px; margin-bottom:10px; }
        .date { color:var(--muted); font-size:13px; }
        .review p { line-height:1.6; white-space:pre-wrap; }
        .actions { display:flex; gap:8px; }
        .message { padding:13px 16px; border-radius:10px; margin:0 0 18px; font-weight:650; }
        .message.success { color:#166534; background:#dcfce7; }
        .message.error { color:#991b1b; background:#fee2e2; }
        .empty { text-align:center; padding:45px 10px; color:var(--muted); }
        .cancel { margin-left:8px; color:var(--muted); }
        .editing { outline:3px solid #93c5fd; outline-offset:3px; }
        .editing-message { margin-bottom:16px; padding:10px 12px; border-radius:8px; color:#1e40af; background:#eff6ff; font-size:14px; }
        @media (max-width:780px) { .dashboard { grid-template-columns:1fr; } header { padding-bottom:48px; } .toolbar,.review-top { align-items:stretch; flex-direction:column; } .toolbar form { flex-direction:column; } .toolbar select { width:100%; } }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>⭐ Store Reviews</h1>
        <p>Add stores and manage all reviews in one place.</p>
    </div>
</header>

<main class="container dashboard">
    <aside>
        <section class="card panel">
            <h2><?= $editingReview ? 'Edit review' : 'New review' ?></h2>
            <?php if ($editingReview): ?><div class="editing-message">Update the fields below, then select <strong>Save changes</strong>.</div><?php endif; ?>
            <form method="post" id="review-form" class="<?= $editingReview ? 'editing' : '' ?>">
                <input type="hidden" name="action" value="<?= $editingReview ? 'edit_review' : 'add_review' ?>">
                <?php if ($editingReview): ?><input type="hidden" name="id" value="<?= (int) $editingReview['id'] ?>"><?php endif; ?>
                <label for="name">Your name</label>
                <input id="name" name="name" maxlength="100" required value="<?= h($editingReview['name'] ?? '') ?>">
                <label for="review">Review</label>
                <textarea id="review" name="review" required><?= h($editingReview['review'] ?? '') ?></textarea>
                <label for="storeId">Store</label>
                <select id="storeId" name="storeId" required>
                    <option value="">Select a store</option>
                    <?php foreach ($stores as $store): ?>
                        <option value="<?= (int) $store['id'] ?>" <?= (int) ($editingReview['storeId'] ?? 0) === (int) $store['id'] ? 'selected' : '' ?>><?= h($store['location']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="button" type="submit"><?= $editingReview ? 'Save changes' : 'Publish review' ?></button>
                <?php if ($editingReview): ?><a class="cancel" href="index.php">Cancel</a><?php endif; ?>
            </form>
        </section>

        <section class="card panel">
            <h2>Add store</h2>
            <form method="post">
                <input type="hidden" name="action" value="add_store">
                <label for="location">Name or address</label>
                <input id="location" name="location" maxlength="30" required placeholder="Ex.: London Mall">
                <button class="button secondary" type="submit">Add store</button>
            </form>
        </section>
    </aside>

    <section>
        <?php if ($message): ?><div class="message <?= $message[0] ?>"><?= h($message[1]) ?></div><?php endif; ?>
        <div class="card">
            <div class="toolbar">
                <div><strong><?= $totalReviews ?></strong> review(s)</div>
                <form method="get">
                    <select name="store" aria-label="Filter by store" onchange="this.form.submit()">
                        <option value="0">All stores</option>
                        <?php foreach ($stores as $store): ?>
                            <option value="<?= (int) $store['id'] ?>" <?= $filterStoreId === (int) $store['id'] ? 'selected' : '' ?>><?= h($store['location']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
            <div class="reviews">
                <?php if ($totalReviews === 0): ?>
                    <div class="empty">No reviews found.</div>
                <?php endif; ?>
                <?php while ($item = mysqli_fetch_assoc($reviewsResult)): ?>
                    <article class="review">
                        <div class="review-top">
                            <div>
                                <span class="store-badge"><?= h($item['location']) ?></span>
                                <h3><?= h($item['name']) ?></h3>
                                <span class="date"><?= date('m/d/Y H:i', strtotime($item['date_entered'])) ?></span>
                            </div>
                            <div class="actions">
                                <a class="button small secondary" href="index.php?edit=<?= (int) $item['id'] ?><?= $filterStoreId ? '&store=' . $filterStoreId : '' ?>">Edit</a>
                                <form method="post" onsubmit="return confirm('Delete this review?');">
                                    <input type="hidden" name="action" value="delete_review">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button class="button small danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </div>
                        <p><?= nl2br(h($item['review'])) ?></p>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
</main>
<?php if ($editingReview): ?>
<script>
    window.addEventListener('load', function () {
        const form = document.getElementById('review-form');
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        document.getElementById('name').focus({ preventScroll: true });
    });
</script>
<?php endif; ?>
</body>
</html>
<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
