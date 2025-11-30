<?php
include 'session_config.php';
include 'db.php';

// Only allow access to employees
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'employee') {
    header("Location: index.php");
    exit();
}

// Get the user_id of the logged-in employee
$user_id = $_SESSION['user_id'];

// Fetch the employee ID linked to this user
$emp_res = mysqli_query($conn, "SELECT id, name FROM employees WHERE user_id = $user_id");
$emp_data = mysqli_fetch_assoc($emp_res);

if (!$emp_data) {
    die("Employee profile not found.");
}

$employee_id = $emp_data['id'];
$employee_name = $emp_data['name'];

// Get last leave request status
$leave_res = mysqli_query($conn, "SELECT status FROM leave_requests WHERE employee_id = $employee_id ORDER BY id DESC LIMIT 1");
$leave = mysqli_fetch_assoc($leave_res);
$leave_status = $leave ? $leave['status'] : 'No requests';

// Get last attendance date
$attend_res = mysqli_query($conn, "SELECT date FROM attendance WHERE employee_id = $employee_id ORDER BY date DESC LIMIT 1");
$last_attendance = mysqli_fetch_assoc($attend_res);
$last_attendance_date = $last_attendance ? $last_attendance['date'] : 'Not marked yet';

// Get total approved leaves
$leave_total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM leave_requests WHERE employee_id = $employee_id AND status = 'Approved'");
$leave_total = mysqli_fetch_assoc($leave_total_res)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
      <h2>HRMS</h2>
      <ul>
        <li><a href="dashboard_employee.php">Dashboard</a></li>
        <li><a href="leave_request.php">Request Leave</a></li>
        <li><a href="mark_attendance.php">Mark Attendance</a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div class="main">
      <h1>Welcome, <?php echo htmlspecialchars($employee_name); ?></h1>
      <p>Here's your activity overview:</p>

      <div class="card-grid">
        <div class="card-box">
          <h3>Leave Status</h3>
          <p><?php echo ucfirst($leave_status); ?></p>
        </div>

        <div class="card-box">
          <h3>Last Attendance</h3>
          <p><?php echo $last_attendance_date; ?></p>
        </div>

        <div class="card-box">
          <h3>Total Leaves Approved</h3>
          <p><?php echo $leave_total; ?></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
