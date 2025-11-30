<?php
include 'session_config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

include 'db.php';

$id = $_GET['id'];

// Fetch the employee and user details
$result = mysqli_query($conn, "SELECT e.*, u.username, u.password FROM employees e JOIN users u ON e.user_id = u.id WHERE e.id = $id");
$employee = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    
    // Update the employees table
    $update_employee = "UPDATE employees SET name='$name', email='$email', phone='$phone', department='$department', designation='$designation' WHERE id=$id";
    mysqli_query($conn, $update_employee);

    // Update the users table 
    $update_user = "UPDATE users SET username='$name' WHERE id=" . $employee['user_id'];
    mysqli_query($conn, $update_user);

    // Redirect to employee list page
    header("Location: view_employees.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <link rel="stylesheet" href="style_v2.css">
</head>
<body>
    <h2>Edit Employee</h2>
    <form method="POST" action="">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?php echo $employee['name']; ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?php echo $employee['email']; ?>" required><br><br>

        <label>Phone:</label><br>
        <input type="text" name="phone" value="<?php echo $employee['phone']; ?>" required><br><br>

        <label>Department:</label><br>
        <input type="text" name="department" value="<?php echo $employee['department']; ?>" required><br><br>

        <label>Designation:</label><br>
        <input type="text" name="designation" value="<?php echo $employee['designation']; ?>" required><br><br>

        <button type="submit">Update</button>
    </form>

    <p><a href="view_employees.php">⬅ Back to Employee List</a></p>
</body>
</html>
