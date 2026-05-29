<?php
session_start();
session_destroy();
echo "Logout reușit! Redirecționez...";
header("Location: index.php");
exit();
?>