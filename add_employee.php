<?php
include 'session_config.php';
include 'db.php';

// Only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate form fields
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $department = htmlspecialchars(trim($_POST['department']));
    $designation = htmlspecialchars(trim($_POST['designation']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = trim($_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($department) || empty($designation) || empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if username exists
        $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
        $check_email = mysqli_query($conn, "SELECT id FROM employees WHERE email = '$email'");

        if (mysqli_num_rows($check_user) > 0) {
            $error = "Username already exists!";
        } elseif (mysqli_num_rows($check_email) > 0) {
            $error = "Email already exists!";
        } else {
            // Add user login
            $user_sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', 'employee')";
            if (mysqli_query($conn, $user_sql)) {
                $user_id = mysqli_insert_id($conn);

                // Add employee
                $emp_sql = "INSERT INTO employees (name, email, phone, department, designation, user_id)
                            VALUES ('$name', '$email', '$phone', '$department', '$designation', $user_id)";
                if (mysqli_query($conn, $emp_sql)) {
                    $success = "✅ Employee and login account created successfully!";
                } else {
                    $error = "❌ Employee creation failed: " . mysqli_error($conn);
                    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
                }
            } else {
                $error = "❌ User creation failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <link rel="stylesheet" href="style_v2.css">
    <style>
        .message.success {
            color: green;
            /* font-weight: bold; */
            margin-bottom: 15px;
        }
        .message.error {
            color: red;
            /* font-weight: bold; */
            margin-bottom: 15px;
        }
        #formErrors p {
            margin: 2px 0;
            list-style: none;
        }
    </style>
</head>
<body>
    <form method="POST" id="employeeForm">
        <h2>Add New Employee</h2>
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Phone:</label>
        <input type="text" name="phone" required>
        
        <label>Department:</label>
        <input type="text" name="department" required>
        
        <label>Designation:</label>
        <input type="text" name="designation" required>
        
        <hr>
        
        <h3>🔐 Login Credentials</h3>
        
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit">Create Employee</button>
    </form>
    
    <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>
    <div id="formErrors" class="message error" style="display:none;"></div>

    <p><a href="dashboard_admin.php">⬅ Back to Dashboard</a></p>

    <script>
        document.getElementById("employeeForm").addEventListener("submit", function(event) {
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const phone = document.querySelector('input[name="phone"]').value.trim();
            const department = document.querySelector('input[name="department"]').value.trim();
            const designation = document.querySelector('input[name="designation"]').value.trim();
            const username = document.querySelector('input[name="username"]').value.trim();
            const password = document.querySelector('input[name="password"]').value;

            let errors = [];

            if (!name || !email || !phone || !department || !designation || !username || !password) {
                errors.push("All fields are required.");
            }

            // const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/; 
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push("Please enter a valid email address.");
            }

            if (!/^\d+$/.test(phone)) {
                errors.push("Phone number must contain only numbers");
            } 
            else if (phone.length < 7 || phone.length > 15) {
                errors.push("Phone number should be between 7 and 15 digits");
            }

            if (username.includes(" ")) {
                errors.push("Username should not contain spaces.");
            }

            if (password.length < 6) {
                errors.push("Password should be at least 6 characters.");
            }

            if (errors.length > 0) {
                event.preventDefault(); // stop form submission
                const errorDiv = document.getElementById("formErrors");
                errorDiv.innerHTML = errors.map(e => `<p>• ${e}</p>`).join("");
                errorDiv.style.display = "block";
            }
        });
    </script>
</body>
</html>
