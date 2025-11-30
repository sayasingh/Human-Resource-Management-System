<?php
include 'session_config.php';
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


// For dashboard cards
// Total Employees
$emp_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM employees");
$emp_total = mysqli_fetch_assoc($emp_res)['total'];

// Pending Leaves
$leave_res = mysqli_query($conn, "SELECT COUNT(*) as pending FROM leave_requests WHERE status = 'Pending'");
$leave_pending = mysqli_fetch_assoc($leave_res)['pending'];

// Today’s Attendance
$today = date('Y-m-d');
$attend_res = mysqli_query($conn, "SELECT COUNT(*) as count FROM attendance WHERE date = '$today'");
$attend_today = mysqli_fetch_assoc($attend_res)['count'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=
  , initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">

</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
        <h2>HRMS Admin</h2>
        <!-- <h2>Welcome, Admin!</h2> -->
        <ul>
          <li><a href="dashboard_admin.php">Dashboard</a></li>
          <li><a href="add_employee.php">Add Employee</a></li>
          <li><a href="view_employees.php">View Employees</a></li>
          <li><a href="leave_requests.php">Leave Requests</a></li>
          <li><a href="attendance.php">Attendance</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>

      <!-- Main Content -->
      <div class="main">
          <h1>Welcome, Admin</h1>
          <p>Here’s a quick overview of your HRMS system.</p>

          <!--dashboard cards-->
          <div class="card-grid">
            <div class="card-box">
                <h3>Total Employees</h3>
                <!-- <p>42</p> -->
                <p><?php echo $emp_total; ?></p>
            </div>

            <div class="card-box">
                <h3>Pending Leaves</h3>
                <!-- <p>5</p> -->
                <p><?php echo $leave_pending; ?></p>
            </div>

            <div class="card-box">
                <h3>Today's Attendance</h3>
                <!-- <p>37</p> -->
                <p><?php echo $attend_today; ?></p>
            </div>

            <!-- <div class="card-box">
                <h3>Approved Leaves</h3>
                <p>8</p>
            </div> -->
        </div>

      </div>
    </div>
</body>
</html>
