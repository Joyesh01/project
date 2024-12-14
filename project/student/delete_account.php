<?php
session_start();
if ($_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    $pdo->prepare("DELETE FROM Users WHERE user_id = ?")->execute([$user_id]);

    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1d2020] min-h-screen flex items-center justify-center">
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="text-2xl">Delete Account</h2>
            <p>Are you sure you want to delete your account? This action is irreversible.</p>
            <form method="POST" action="">
                <button type="submit" class="btn btn-outline btn-error m-2">Yes, Delete</button>
                <a href="dashboard.php" class="btn btn-info btn-outline m-2">Cancel</a>
            </form>
            
        </div>
    </div>
</body>
</html>

