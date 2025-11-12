<?php
session_start();

// Hapus semua data session
session_unset();
session_destroy();

// Redirect ke halaman login dengan pesan logout sukses
header("Location: login.php?logout=success");
exit();
?>
