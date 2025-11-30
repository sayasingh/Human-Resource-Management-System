<?php

include 'session_config.php';

//  troubleshooting session
// echo "Session role: " . ($_SESSION['role'] ?? 'none');

include 'db.php';

// Session validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Approve / Reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = strtolower($_GET['action']);

    $status_map = [
        'approved' => 'Approved',
        'rejected' => 'Rejected'
    ];

    if (array_key_exists($action, $status_map)) {
        $new_status = $status_map[$action];
        $update = mysqli_query($conn, "UPDATE leave_requests SET status='$new_status' WHERE id=$id");

        // Only redirect if update is successful
        if ($update) {
            header("Location: leave_requests.php");
            exit();
        } else {
            $error = "Failed to update status: " . mysqli_error($conn);
        }
    }
}

// Fetch leave requests and employee names
$result = mysqli_query($conn,  "SELECT leave_requests.*, employees.name 
                                FROM leave_requests 
                                JOIN employees ON leave_requests.employee_id = employees.id 
                                ORDER BY leave_requests.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="style_v2.css">
</head>
<body>
    <h2>Leave Requests</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Employee</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Requested At</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['reason']); ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <?php if ($row['status'] === 'Pending') { ?>
                    <a href="?action=approved&id=<?php echo $row['id']; ?>">Approve</a> |
                    <a href="?action=rejected&id=<?php echo $row['id']; ?>">Reject</a>
                <?php } else {
                    echo "-";
                } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <p><a href="dashboard_admin.php">⬅ Back to Dashboard</a></p>
</body>
</html>
