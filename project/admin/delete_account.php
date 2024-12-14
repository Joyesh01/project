<?php
session_start();
if ($_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = validateInput($_POST['user_id']);

    $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->execute([$user_id]);

    echo "<p>User account deleted successfully!</p>";
}
?>
<?php include '../templates/header.php'; ?>
<h2>Delete Account</h2>
<form method="POST" action="">
    <label for="user_id">Enter User ID to Delete:</label>
    <input type="number" name="user_id" id="user_id" required>
    <button type="submit">Delete</button>
</form>
<a href="dashboard.php">Back to Dashboard</a>
<?php include '../templates/footer.php'; ?>
