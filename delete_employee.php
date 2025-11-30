<?php

include 'session_config.php';
include 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


$id = $_GET['id'];

// First, get the user_id of the employee
$employee_res = mysqli_query($conn, "SELECT user_id FROM employees WHERE id=$id");
$employee = mysqli_fetch_assoc($employee_res);

if ($employee) {
    $user_id = $employee['user_id'];

    // Delete the employee record
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");

    // Delete the corresponding user record
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
}

header("Location: view_employees.php");
?>

