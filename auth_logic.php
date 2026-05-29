<?php
session_start();
include 'config/db.php';

if (isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    
    if (strlen($user) < 3) {
        header("Location: register.php?error=Username+minim+3+caractere");
        exit();
    }
    
    if (strlen($password) < 6) {
        header("Location: register.php?error=Parola+minim+6+caractere");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: register.php?error=Email+invalid");
        exit();
    }
    
    // verificare daca username/email exista deja
    $check_sql = "SELECT id FROM utilizatori WHERE username = '$user' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: register.php?error=Username+sau+email+deja+exista");
        exit();
    }
    
    // criptare parola
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    
    
    $sql = "INSERT INTO utilizatori (username, email, parola) VALUES ('$user', '$email', '$pass_hash')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?status=cont_creat");
        exit();
    } else {
        header("Location: register.php?error=Eroare+baza+de+date");
        exit();
    }
}

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    if (empty($user) || empty($password)) {
        header("Location: login.php?error=Completeaza+toate+campurile");
        exit();
    }
    
    //select pentru autentificare
    $sql = "SELECT id, username, parola FROM utilizatori WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    if ($row && password_verify($password, $row['parola'])) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];
        header("Location: dashboard.php?status=conectat");
        exit();
    } else {
        header("Location: login.php?error=Utilizator+sau+parola+incorecta");
        exit();
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>