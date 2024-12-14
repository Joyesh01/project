<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';
include '../includes/functions.php';

$materials = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure at least one course is selected
    if (!isset($_POST['courses']) || empty($_POST['courses'])) {
        echo "<p class='text-red-500'>Please select at least one course.</p>";
        exit();
    }

    $selected_courses = $_POST['courses'];
    $placeholders = str_repeat('?,', count($selected_courses) - 1) . '?';

    // Fetch materials for the selected courses
    $stmt = $pdo->prepare("
        SELECT material_id, course_name, material_name, file_path 
        FROM CourseMaterials 
        WHERE course_name IN ($placeholders)
    ");
    $stmt->execute($selected_courses);
    $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($materials)) {
        echo "<p class='text-red-500'>No materials available for the selected courses.</p>";
        exit();
    }

    // Record each material access
    $student_id = $_SESSION['user_id'];
    foreach ($materials as $material) {
        $material_id = $material['material_id'];
        $insert_stmt = $pdo->prepare("
            INSERT INTO AccessRecords (student_id, material_id) 
            VALUES (?, ?)
        ");
        $insert_stmt->execute([$student_id, $material_id]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Materials</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDropdown() {
            document.getElementById('dropdown').classList.toggle('hidden');
        }
    </script>
</head>
<body class="bg-[#1d2020] min-h-screen mt-10">
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6 text-center">Access Materials</h2>

        <!-- Form to select courses -->
        <form method="POST" action="" class="bg-[#1d3030] p-6 rounded shadow-md w-full max-w-lg mx-auto">
            <label for="courses" class="block text-center text-lg font-medium mb-2">Select Course:</label>
            <div class="relative">
                <button type="button" onclick="toggleDropdown()" class="btn btn-primary w-full">Course Name</button>
                <div id="dropdown" class="absolute left-0 mt-2 w-full bg-white rounded-md shadow-lg hidden">
                    <select name="courses[]" id="courses" multiple required class="select select-bordered w-full text-center">
                        <?php
                        // Fetch distinct course names
                        $stmt = $pdo->query("SELECT DISTINCT course_name FROM CourseMaterials");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['course_name']}' class='text-center'>{$row['course_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">Get Materials</button>
        </form>

        <?php if (!empty($materials)): ?>
            <h3 class='text-2xl font-semibold mb-4 mt-10'>Available Materials</h3>
            <div class='overflow-x-auto mb-6'>
                <table class='table bg-[#1d2020] w-full'>
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Material Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materials as $material): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($material['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($material['material_name']); ?></td>
                                <td><a href='<?php echo htmlspecialchars($material['file_path']); ?>' target='_blank' class='btn btn-info btn-xs'>View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
        <?php endif; ?>
    </div>
    <div class="text-center mt-2">
                <a href="dashboard.php" class="btn btn-info btn-outline">Dashboard</a>
            </div>
    <footer class="text-center mt-2">
        <p>&copy; 2024 CSECU Course Material Management System</p>
    </footer>
</body>
</html>
