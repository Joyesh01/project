<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';

// Fetch the student's username
$student_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username FROM Users WHERE user_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
$student_name = $student['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="mt-10 min-h-screen">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6 text-center">Welcome, <?php echo htmlspecialchars($student_name); ?></h2>

        <nav class="mb-6">
            <ul class="flex justify-center space-x-4">
                <li><a href="access_materials.php" class="btn btn-info btn-outline">Access Materials</a></li>
                <li><a href="delete_account.php" class="btn btn-error btn-outline">Delete Account</a></li>
                <li><a href="../logout.php" class="hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 btn btn-outline btn-success">Logout</a></li>
            </ul>
        </nav>

        <h3 class="text-2xl font-semibold mb-4">Your Accessed Materials History</h3>
        <div class="overflow-x-auto mb-6">
            <table class="table bg-[#1d2020] w-full">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Material Name</th>
                        <th>Accessed On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->prepare("
                        SELECT cm.course_name, cm.material_name, ar.access_time 
                        FROM AccessRecords ar 
                        JOIN CourseMaterials cm ON ar.material_id = cm.material_id 
                        WHERE ar.student_id = ?
                        ORDER BY ar.access_time DESC
                    ");
                    $stmt->execute([$student_id]);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['course_name']}</td>
                                <td>{$row['material_name']}</td>
                                <td>{$row['access_time']}</td>
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
