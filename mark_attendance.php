<?php
include 'session_config.php';
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

// Assuming $_SESSION['user_id'] is the user_id from the users table, let's get the employee_id
$user_id = $_SESSION['user_id'];
$employee_query = mysqli_query($conn, "SELECT id FROM employees WHERE user_id = $user_id");
$employee = mysqli_fetch_assoc($employee_query);
$employee_id = $employee['id'];  // This is the employee's ID

$today = date('Y-m-d');

// Check if already marked
$check = mysqli_query($conn, "SELECT * FROM attendance WHERE employee_id=$employee_id AND date='$today'");

if (mysqli_num_rows($check) > 0) {
    $message = "You have already marked attendance for today.";
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $status = $_POST['status']; // usually "present"
        $insert = "INSERT INTO attendance (employee_id, date, status) VALUES ($employee_id, '$today', '$status')";

        if (mysqli_query($conn, $insert)) {
            $message = "Attendance marked successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="style_v2.css">

</head>
<body>
    <h2>Mark Attendance</h2>

    <?php if (isset($message)): ?>
        <p style="color:green;"><?php echo $message; ?></p>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="status" value="present">
            <p>Mark yourself present for <strong><?php echo $today; ?></strong></p>
            <button type="submit">Mark Present</button>
        </form>
    <?php endif; ?>

    <p><a href="dashboard_employee.php">⬅ Back to Dashboard</a></p>
</body>
</html>
