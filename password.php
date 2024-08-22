<?php
$password = 'admin123'; // Replace this with the desired password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed Password: " . $hashed_password;
?>
