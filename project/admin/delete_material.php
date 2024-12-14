<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

// Check if material_id is provided
if (isset($_GET['material_id'])) {
    $material_id = $_GET['material_id'];

    // Fetch material details from the database
    $stmt = $pdo->prepare("SELECT course_name, material_name, file_path FROM CourseMaterials WHERE material_id = ?");
    $stmt->execute([$material_id]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    // If material doesn't exist, redirect back to the dashboard
    if (!$material) {
        echo "<p class='text-red-500'>No material found with ID {$material_id}.</p>";
        echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}

// Handle material deletion after confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {
        // Delete the material
        $stmt = $pdo->prepare("DELETE FROM CourseMaterials WHERE material_id = ?");
        $stmt->execute([$material_id]);

        echo "<p class='text-green-500'>Material <strong>{$material['material_name']}</strong> from course <strong>{$material['course_name']}</strong> has been deleted.</p>";
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
    <title>Confirm Delete Material</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1d2020] min-h-screen flex items-center justify-center">
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="text-2xl">Confirm Delete Material</h2>
            <p>Are you sure you want to delete the material <strong><?php echo htmlspecialchars($material['material_name']); ?></strong> from the course <strong><?php echo htmlspecialchars($material['course_name']); ?></strong>?</p>
            <p>File Path: <a href="<?php echo htmlspecialchars($material['file_path']); ?>" target="_blank" class="text-blue-500 hover:underline">View Material</a></p>
            <form method="POST" action="">
                <button type="submit" name="confirm_delete" class="btn btn-error btn-outline m-2">Yes, Delete</button>
                <button type="submit" name="cancel_delete" class="btn btn-info btn-outline m-2">No, Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
