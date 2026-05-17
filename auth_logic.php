<?php
include 'config/db.php'; 

if (isset($_POST['register'])) {
    $user = $_POST['username'];
    $email = $_POST['email'];
    // Criptăm parola înainte de salvare
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $sql = "INSERT INTO utilizatori (username, email, parola) VALUES ('$user', '$email', '$pass')";
    if (mysqli_query($conn, $sql)) {
        // Dacă a mers, îl trimitem la login
        header("Location: login.php?status=cont_creat");
    } else {
        echo "Eroare la înregistrare: " . mysqli_error($conn);
    }
}

if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM utilizatori WHERE username = '$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row && password_verify($pass, $row['parola'])) {
        session_start();
        $_SESSION['username'] = $row['username'];
        header("Location: dashboard.php?status=conectat");
    } else {
        echo "Utilizator sau parolă incorectă!";
    }
}
?>