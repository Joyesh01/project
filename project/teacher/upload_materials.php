<?php
session_start();
if ($_SESSION['role'] !== 'Teacher') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';
include '../includes/functions.php';

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = validateInput($_POST['course_name']);
    $material_name = validateInput($_POST['material_name']);
    $file_path = validateInput($_POST['file_path']);
    $teacher_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO CourseMaterials (course_name, material_name, file_path, uploaded_by_teacher) 
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$course_name, $material_name, $file_path, $teacher_id]);

    $success_message = "Material uploaded successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Materials</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#1d2020] min-h-screen flex flex-col items-center justify-center">
    <?php if ($success_message): ?>
        <div class="bg-green-500 text-white p-4 rounded mb-4 text-center w-full max-w-lg">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>
    <div class="card w-96 bg-base-100 shadow-xl">
        <div class="card-body text-center">
            <h2 class="text-2xl">Upload Materials</h2>
            <form method="POST" action="" class="bg-[#112c2c] p-6 rounded shadow-md w-full max-w-sm">
                <input type="text" name="course_name" placeholder="Course Name" class="input input-bordered w-full mb-4" required>
                <input type="text" name="material_name" placeholder="Material Name" class="input input-bordered w-full mb-4" required>
                <input type="text" name="file_path" placeholder="File URL/Path" class="input input-bordered w-full mb-4" required>
                <button type="submit" class="btn btn-info btn-outline w-full">Upload</button>
            </form>
            <a href="dashboard.php" class="btn btn-success btn-outline ml-6 mr-6 mt-2">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
