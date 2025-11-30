<?php
echo password_hash("admin123", PASSWORD_DEFAULT);

// Copy the generated hash and paste it into your SQL manually:

// INSERT INTO users (username, password, role) 
// VALUES ('admin', '$2y$10$.....', 'admin');
?>

