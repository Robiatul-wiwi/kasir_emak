<?php
// hash.php
$password_baru = "12345"; // ganti dengan password yang ingin dibuat
$hash = password_hash($password_baru, PASSWORD_DEFAULT);

echo "Hash password: " . $hash;
?>
