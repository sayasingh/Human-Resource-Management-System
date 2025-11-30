<?php
include 'session_config.php';
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$query = "SELECT a.id, a.date, a.status, e.name 
          FROM attendance a 
          JOIN employees e ON a.employee_id = e.id
          ORDER BY a.date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Attendance Records</title>
    <link rel="stylesheet" href="style_v2.css">

</head>
<body>
    <h2>Attendance Records</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Employee</th><th>Date</th><th>Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo ucfirst($row['status']); ?></td>
        </tr>
        <?php } ?>
    </table>

    <p><a href="dashboard_admin.php">⬅ Back to Dashboard</a></p>
</body>
</html>
