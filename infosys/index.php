<?php
session_start();
include('db/dbcon.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['token'] = $token;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            $update = $con->prepare("UPDATE users SET token = ? WHERE id = ?");
            $update->bind_param("si", $token, $id);
            $update->execute();
            $update->close();

            if ($role == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        } else {
            $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Invalid password.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
    } else {
        $message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        Invalid username.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
    }

    $stmt->close();
    $con->close();
}
?>

<script>
    window.history.pushState(null, '', window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, '', window.location.href);
    };
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Login - Information System</title>
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
            <h4>Sign In</h4>
        </div>
        <div class="card-body">
            <?php echo $message; ?>
            <form action="index.php" method="POST">
                <div class="form-group mb-3">
                    <label for="username">Username:</label>
                    <input type="text" id="username" class="form-control" name="username" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password:</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Login</button>
             <br>
                    <a href="register.php" class="btn btn-link" >Register</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
