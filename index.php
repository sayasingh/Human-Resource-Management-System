<?php
include 'session_config.php';
include 'db.php';

// Initialize error
$error = "";

// Show error from session (if any)
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Clear it after displaying
}

// Handle login POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_employee.php");
            }
            exit(); // very important!
        } else {
            $_SESSION['login_error'] = "Invalid password.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['login_error'] = "User not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - HRMS</title>
    <link rel="stylesheet" href="style_v2.css">

</head>
    <body>
        <h2>HRMS Login</h2>
        <form method="POST" action="" id="loginForm" >
            <label>Username:</label><br>
            <input type="text" name="username" required><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <button type="submit">Login</button>
        </form>


        <?php if ($error): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        
</script>

    </body>
</html>
