<?php
 
$env_file = __DIR__ . '/../.env';
 
if (file_exists($env_file)) {
    $env = parse_ini_file($env_file);
    
    $db_host = $env['DB_HOST'] ?? 'localhost';
    $db_user = $env['DB_USER'] ?? 'root';
    $db_pass = $env['DB_PASS'] ?? '';
    $db_name = $env['DB_NAME'] ?? 'parc_auto';
    $db_charset = $env['DB_CHARSET'] ?? 'utf8mb4';
} else {
    
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'parc_auto';
    $db_charset = 'utf8mb4';
}
 
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
if (!$conn) {
    die("Eroare conexiune: " . mysqli_connect_error());
}
mysqli_set_charset($conn, $db_charset);
 
?>