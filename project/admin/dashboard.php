<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../login.php");
    exit();
}
include '../includes/db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="../image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class=" min-h-screen">
    
    <div class="container mx-auto p-6">
        <h2 class="text-3xl font-bold mb-6 text-center">Welcome, Admin</h2>
        <h3 class="text-2xl font-semibold mb-4 text-center">User List</h3>
        <div class="overflow-x-auto mb-6">
            <table class="table w-full bg-[#1d2020]">
                <thead>
                    <tr>
                        <th>Serial No.</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT user_id, username, email, role FROM Users ORDER BY user_id ASC");
                    $serial_no = 1; // Initialize serial number
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$serial_no}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['role']}</td>
                                <td>
                                    <a href='delete_user_account.php?user_id={$row['user_id']}' class='text-red-500 hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring focus:ring-red-300  btn btn-outline btn-error btn-xs'>Delete User</a>
                                </td>
                              </tr>";
                        $serial_no++; // Increment serial number
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h3 class="text-2xl font-semibold mb-4 text-center">All Uploaded Materials</h3>
        <div class="overflow-x-auto mb-6">
            <table class="table w-full bg-[#1d2020]">
                <thead>
                    <tr>
                        <th>Material ID</th>
                        <th>Course Name</th>
                        <th>Material Name</th>
                        <th>Uploaded By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT cm.material_id, cm.course_name, cm.material_name, u.username, cm.file_path 
                                         FROM CourseMaterials cm 
                                         JOIN Teacher t ON cm.uploaded_by_teacher = t.teacher_id 
                                         JOIN Users u ON t.teacher_id = u.user_id 
                                         ORDER BY cm.material_id ASC");

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['material_id']}</td>
                                <td>{$row['course_name']}</td>
                                <td>{$row['material_name']}</td>
                                <td>{$row['username']}</td>
                                <td>
                                    <a href='{$row['file_path']}' target='_blank' class='btn btn-outline btn-info btn-xs'>View</a> | 
                                    <a href='delete_material.php?material_id={$row['material_id']}' class='text-red-500 hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring focus:ring-red-300  btn btn-outline btn-error btn-xs'>Delete</a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h3 class="text-2xl font-semibold mb-4 text-center">Top Contributors</h3>
        <div class="overflow-x-auto mb-6">
            <table class="table w-full bg-[#1d2020]">
                <thead>
                    <tr>
                        <th>Teacher ID</th>
                        <th>Username</th>
                        <th>Uploaded Materials</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT teacher_id, username, uploads FROM TopContributors ORDER BY uploads DESC LIMIT 10");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['teacher_id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['uploads']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h3 class="text-2xl font-semibold mb-4 text-center">Top Readers</h3>
        <div class="overflow-x-auto">
            <table class="table bg-[#1d2020] w-full">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Username</th>
                        <th>Accessed Materials</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT student_id, username, accesses FROM TopReaders ORDER BY accesses DESC LIMIT 10");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>
                                <td>{$row['student_id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['accesses']}</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="flex justify-center">
        <a href="../logout.php" class="mr-10 text-blue-500 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300  btn btn-outline btn-success ">Log Out</a> 
    </div>
    <footer class="text-center mt-2">
        <p>&copy; 2024 CSECU Course Material Management System</p>
    </footer>
</body>
</html>
