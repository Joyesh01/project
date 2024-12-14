<?php
include 'includes/db_connection.php';
include 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = validateInput($_POST['email']);
    $password = validateInput($_POST['password']);
    $role = validateInput($_POST['role']);

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];

        if ($role === 'Admin') {
            redirect('admin/dashboard.php');
        } elseif ($role === 'Teacher') {
            redirect('teacher/dashboard.php');
        } elseif ($role === 'Student') {
            redirect('student/dashboard.php');
        }
    } else {
        echo "<p>Invalid credentials!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            <h2 class="card-title">Login</h2>
            <form method="POST" action="" class="bg-[#183D3D] p-6 rounded shadow-md w-full max-w-sm">
                <input type="email" name="email" placeholder="Email" class="input input-bordered w-full mb-4" required>
                <select name="role" class="select select-bordered w-full mb-4" required>
                    <option disabled selected>Select Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                </select>
                <input type="password" name="password" placeholder="Password" class="input input-bordered w-full mb-4" required>

                <div class="button-group justify-row">
                    <button type="submit" class="ml-10 text-blue-500 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring focus:ring-green-300  btn btn-outline btn-success ">Login</button>
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
