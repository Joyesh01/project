<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

// Check if user_id is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT username FROM Users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user doesn't exist, redirect back to dashboard
    if (!$user) {
        echo "<p>No user found with ID {$user_id}.</p>";
        echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}

// Handle account deletion after confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Delete the user account
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);

        echo "<p class='text-green-500'>User <strong>{$user['username']}</strong> with ID <strong>{$user_id}</strong> has been deleted.</p>";
        echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
        exit();
    } elseif (isset($_POST['cancel_delete'])) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete User Account</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1d2020] min-h-screen flex items-center justify-center">
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="text-center text-2xl">Confirm Delete User Account</h2>
            <p>Are you sure you want to delete the account of <strong><?php echo htmlspecialchars($user['username']); ?></strong> with User ID: <strong><?php echo htmlspecialchars($user_id); ?></strong>?</p>
            <form method="POST" action="">
                <button type="submit" name="confirm_delete" class="btn btn-outline btn-error m-2">Yes, Delete</button>
                <button type="submit" name="cancel_delete" class="btn btn-info btn-outline m-2">No, Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>

