<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

if (isset($_GET['material_id'])) {
    $material_id = $_GET['material_id'];
    $teacher_id = $_SESSION['user_id'];

    // Check if the material belongs to the logged-in teacher
    $stmt = $pdo->prepare("SELECT * FROM CourseMaterials WHERE material_id = ? AND uploaded_by_teacher = ?");
    $stmt->execute([$material_id, $teacher_id]);
    $material = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($material) {
        if (isset($_POST['confirm_delete'])) {
            // Delete the material
            $stmt = $pdo->prepare("DELETE FROM CourseMaterials WHERE material_id = ?");
            $stmt->execute([$material_id]);

            echo "<p class='text-green-500'>Material deleted successfully.</p>";
            echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
        } else {
            // Show confirmation form
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Delete Material</title>
                <link rel="icon" href="../image/icon.jpg">
                <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
            <body class="bg-[#1d2020] min-h-screen flex items-center justify-center">
                <div class="card w-96 bg-base-100 shadow-xl">
                    <div class="card-body text-center">
                        <h2 class="text-2xl">Confirm Deletion</h2>
                        <p>Are you sure you want to delete this material?</p>
                        <form method="POST" action="">
                            <button type="submit" name="confirm_delete" class="btn btn-error btn-outline m-2">Yes, Delete</button>
                            <a href="dashboard.php" class="btn btn-info btn-outline m-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </body>
            </html>
            <?php
        }
    } else {
        echo "<p class='text-red-500'>You do not have permission to delete this material.</p>";
        echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
    }
} else {
    echo "<p class='text-red-500'>Invalid request.</p>";
    echo "<a href='dashboard.php' class='btn btn-primary'>Back to Dashboard</a>";
}
?>
