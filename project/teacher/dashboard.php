<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';
$teacher_id = $_SESSION['user_id']; 
$stmt = $pdo->prepare("SELECT username FROM Users WHERE user_id = ?"); 
$stmt->execute([$teacher_id]); 
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_name = $teacher['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mt-10 mb-8 text-center">Welcome, <?php echo htmlspecialchars($teacher_name); ?></h2>

        <nav class="mb-6">
            <ul class="flex justify-center space-x-4">
                <li><a href="upload_materials.php" class="btn btn-info btn-outline">Upload Materials</a></li>
                <li><a href="delete_account.php" class="btn btn-error btn-outline">Delete Account</a></li>
                <li><a href="../logout.php" class="mr-10 text-blue-500 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300  btn btn-outline btn-success ">Log Out</a> </li>
            </ul>
        </nav>

        <h3 class="text-2xl font-semibold mb-4">Your Uploaded Materials</h3>
        <div class="overflow-x-auto mb-6">
            <table class="table bg-[#1d2020] w-full">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Material Name</th>
                        <th>File Path</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $teacher_id = $_SESSION['user_id'];
                    $stmt = $pdo->prepare("
                        SELECT material_id, course_name, material_name, file_path 
                        FROM CourseMaterials 
                        WHERE uploaded_by_teacher = ?
                        ORDER BY material_id DESC
                    ");
                    $stmt->execute([$teacher_id]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['course_name']}</td>
                                <td>{$row['material_name']}</td>
                                <td><a href='{$row['file_path']}' target='_blank' class='btn btn-outline btn-info btn-xs'>View</a></td>
                                <td><a href='delete_material.php?material_id={$row['material_id']}' class='btn btn-error btn-outline btn-xs'>Delete</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer class="text-center mt-2">
        <p>&copy; 2024 CSECU Course Material Management System</p>
    </footer>
</body>
</html>
