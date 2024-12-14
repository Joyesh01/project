<?php
include 'includes/db_connection.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = validateInput($_POST['username']);
    $email = validateInput($_POST['email']);
    $role = validateInput($_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO Users (username, email, role, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $role, $password]);

    if ($role === 'Admin') {
        $pdo->query("INSERT INTO Admin (admin_id) VALUES (LAST_INSERT_ID())");
    } elseif ($role === 'Teacher') {
        $pdo->query("INSERT INTO Teacher (teacher_id) VALUES (LAST_INSERT_ID())");
    } elseif ($role === 'Student') {
        $pdo->query("INSERT INTO Student (student_id) VALUES (LAST_INSERT_ID())");
    }

    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" href="image/icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen mt-2">
    <div class="card w-96 bg-[#112c2c] shadow-xl">
        <div class="card-body">
            <h2 class="card-title">Sign Up</h2>
            <form method="POST" action="" class="bg-[#183D3D] p-6 rounded shadow-md w-full max-w-sm">
                <input type="text" name="username" placeholder="Username" class="input input-bordered w-full mb-4" required>
                <input type="email" name="email" placeholder="Email" class="input input-bordered w-full mb-4" required>
                <select name="role" class="select select-bordered w-full mb-4" required>
                    <option disabled selected>Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                </select>
                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full mb-4" required>
                <div class="button-group justify-row">
                    <button type="submit" class="ml-10 text-blue-500 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300  btn btn-outline btn-success ">Sign Up</button>
                    <a href="index.php" class="mr-10 text-blue-500 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300  btn btn-outline btn-success ">Home</a> 
                </div>
            </form>
        </div>
    </div>
    <footer class="text-center mt-2">
        <p>&copy; 2024 CSECU Course Material Management System</p>
    </footer>
</body>
</html>
