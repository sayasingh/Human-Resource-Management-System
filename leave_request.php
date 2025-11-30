<!-- 
 
<
session_start();
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    $query = "INSERT INTO leave_requests (employee_id, reason) VALUES ('$employee_id', '$reason')";

    if (mysqli_query($conn, $query)) {
        $success = "Leave request submitted!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Leave</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h2>Request Leave</h2>
    <form method="POST" action="">
        <label>Reason:</label><br>
        <textarea name="reason" rows="4" cols="50" required></textarea><br><br>

        <button type="submit">Submit Request</button>
    </form>

    <php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <p><a href="dashboard_employee.php">⬅ Back to Dashboard</a></p>
</body>
</html> -->


<?php
include 'session_config.php';
// echo "Logged-in User ID: " . $_SESSION['user_id'];
include 'db.php';

// Redirect if not employee
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

// Initialize messages
$success = "";
$error = "";

// Get employee_id from employees table using user_id from session
$user_id = $_SESSION['user_id'];
$emp_query = mysqli_query($conn, "SELECT id FROM employees WHERE user_id = $user_id");

if ($emp_row = mysqli_fetch_assoc($emp_query)) {
    $employee_id = $emp_row['id'];
} else {
    $error = "Employee profile not found.";
}

// Handle leave form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($employee_id)) {
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($conn, $_POST['to_date']);

    if (!$reason || !$from_date || !$to_date) {
        $error = "All fields are required.";
    } else {
        $query = "INSERT INTO leave_requests (employee_id, reason, from_date, to_date) 
                  VALUES ('$employee_id', '$reason', '$from_date', '$to_date')";

        if (mysqli_query($conn, $query)) {
            $success = "✅ Leave request submitted!";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Leave</title>
    <link rel="stylesheet" href="style_v2.css">
    <style>
        
        textarea, input[type="date"], button {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
            margin-bottom: 15px;
        }
        .message {
            font-weight: bold;
            margin-top: 15px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style> 
</head>
<body>
    <h2>Request Leave</h2>

    <?php if ($error): ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="reason">Reason:</label>
        <textarea name="reason" rows="4" required></textarea>

        <label for="from_date">From Date:</label>
        <input type="date" name="from_date" required>

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date" required>

        <button type="submit">Submit Request</button>
    </form>

    <p><a href="dashboard_employee.php">⬅ Back to Dashboard</a></p>
</body>
</html>
