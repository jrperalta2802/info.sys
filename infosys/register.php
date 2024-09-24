<?php
session_start();
include('db/dbcon.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $con->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);

    if ($stmt->execute()) {
        $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        Registration successful.
                    </div>';
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Error: ' . $stmt->error . '
                    </div>';
    }

    $stmt->close();
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">

     <!-- Font Awesome 4.7.0 -->
     <link rel="stylesheet" href="includes/css/font-awesome.min.css"/>
    <title>Register - Information System</title>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            color: black;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 80%;
            position: center;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-link {
            color: #007bff;
            text-decoration: none;
        }
        .btn-link:hover {
            color: #0056b3;
        }
        .form-control {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header text-center">
            <h4>Sign Up</h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form action="register.php" method="POST">
                <div class="form-group mb-3">
                    <label for="username">Username:</label>
                    <input type="text" id="username" class="form-control" name="username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email:</label>
                    <input type="email" id="email" class="form-control" name="email" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password:</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                <div class="form-group mb-3">
                    <label for="role">Role:</label>
                    <select id="role" class="form-control" name="role">
                        <option value="user">User</option>
                        <option value="admin" disabled>Admin</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <br>
                    <a href="index.php" class="btn btn-link">Login</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>
